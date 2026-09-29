<?php

namespace App\Http\Controllers;

use App\Exports\VehicleSheetExport;
use App\Http\Requests\VehicleRequest;
use App\Http\Requests\VehicleTaskRequest;
use App\Models\Employee;
use App\Models\TaskOption;
use App\Models\Vehicle;
use App\Models\VehicleTask;
use App\Models\VehicleTaskMonth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class VehicleMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::with(['driver', 'tasks'])->orderBy('call_sign')->get();
        $search = trim((string) $request->input('search'));

        if ($search !== '') {
            $vehicles = $vehicles->filter(fn (Vehicle $vehicle) => str_contains(strtolower($vehicle->call_sign . ' ' . $vehicle->vehicle_model . ' ' . $vehicle->plate_number . ' ' . ($vehicle->team ?? '')), strtolower($search)))->values();
        }

        if ($request->filled('team')) {
            $vehicles = $vehicles->where('team', $request->input('team'))->values();
        }

        if ($request->filled('status')) {
            $vehicles = $vehicles->filter(fn (Vehicle $vehicle) => $vehicle->monitoring_status === $request->input('status'))->values();
        }

        $allVehicles = Vehicle::with('tasks')->get();
        $stats = [
            'total' => $allVehicles->count(),
            'active' => $allVehicles->filter(fn (Vehicle $vehicle) => $vehicle->monitoring_status === 'active')->count(),
            'idle' => $allVehicles->filter(fn (Vehicle $vehicle) => $vehicle->monitoring_status === 'idle')->count(),
            'offline' => $allVehicles->filter(fn (Vehicle $vehicle) => $vehicle->monitoring_status === 'offline')->count(),
        ];

        return view('reports.vehicle-monitoring', [
            'vehicles' => $vehicles,
            'stats' => $stats,
            'teams' => Vehicle::whereNotNull('team')->distinct()->orderBy('team')->pluck('team'),
            'filters' => $request->only(['search', 'team', 'status']),
            'canManage' => $this->canManage(),
        ]);
    }

    public function store(VehicleRequest $request)
    {
        $vehicle = DB::transaction(function () use ($request) {
            $vehicle = Vehicle::create(array_merge($request->validated(), ['last_updated_at' => now()]));
            $this->ensureTaskRows($vehicle);
            return $vehicle;
        });

        return redirect()->route('reports.vehicle-monitoring.show', $vehicle)->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['driver', 'tasks.taskOption', 'tasks.responsiblePerson', 'tasks.months']);

        if ($vehicle->tasks->isEmpty()) {
            $this->ensureTaskRows($vehicle);
            $vehicle->load(['tasks.taskOption', 'tasks.responsiblePerson', 'tasks.months']);
        }

        return view('reports.vehicle-sheet', [
            'vehicle' => $vehicle,
            'taskOptions' => TaskOption::orderBy('name')->get(),
            'employees' => Employee::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'year' => (int) request('year', now()->year),
            'canManage' => $this->canManage(),
            'months' => ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        ]);
    }

    public function update(VehicleRequest $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);
        $vehicle->update(array_merge($request->validated(), ['last_updated_at' => now()]));
        return back()->with('success', 'Vehicle details updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        Gate::authorize('delete', $vehicle);
        $vehicle->delete();
        return redirect()->route('reports.vehicle-monitoring')->with('success', 'Vehicle deleted.');
    }

    public function storeTask(VehicleTaskRequest $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);
        $task = $vehicle->tasks()->create($this->taskData($request->validated()));
        return response()->json(['task' => $task->load('months', 'responsiblePerson')]);
    }

    public function updateTask(VehicleTaskRequest $request, Vehicle $vehicle, VehicleTask $task)
    {
        Gate::authorize('update', $vehicle);
        abort_unless($task->vehicle_id === $vehicle->id, 404);
        $task->update($this->taskData($request->validated()));
        return response()->json(['task' => $task->fresh(['months', 'responsiblePerson'])]);
    }

    public function destroyTask(Vehicle $vehicle, VehicleTask $task)
    {
        Gate::authorize('update', $vehicle);
        abort_unless($task->vehicle_id === $vehicle->id, 404);
        $task->delete();
        return response()->json(['ok' => true]);
    }

    public function toggleMonth(Request $request, Vehicle $vehicle, VehicleTask $task)
    {
        Gate::authorize('update', $vehicle);
        abort_unless($task->vehicle_id === $vehicle->id, 404);
        $data = $request->validate(['year' => ['required', 'integer', 'min:2000', 'max:2200'], 'month' => ['required', 'integer', 'between:1,12']]);
        $month = VehicleTaskMonth::where(['vehicle_task_id' => $task->id, 'year' => $data['year'], 'month' => $data['month']])->first();

        if (! $month) {
            $month = VehicleTaskMonth::create(array_merge($data, ['vehicle_task_id' => $task->id, 'state' => 'scheduled']));
        } elseif ($month->state === 'scheduled') {
            $month->update(['state' => 'done']);
        } else {
            $month->delete();
            $month = null;
        }

        return response()->json(['month' => $month]);
    }

    public function storeTaskOption(Request $request)
    {
        $this->ensureManager();
        $data = $request->validate(['name' => ['required', 'string', 'max:150', 'unique:task_options,name']]);
        return response()->json(['option' => TaskOption::create($data)]);
    }

    public function updateTaskOption(Request $request, TaskOption $taskOption)
    {
        $this->ensureManager();
        $data = $request->validate(['name' => ['required', 'string', 'max:150', Rule::unique('task_options', 'name')->ignore($taskOption)]]);
        $taskOption->update($data);
        $taskOption->tasks()->update(['task_name' => $taskOption->name]);
        return response()->json(['option' => $taskOption]);
    }

    public function destroyTaskOption(TaskOption $taskOption)
    {
        $this->ensureManager();
        $taskOption->delete();
        return response()->json(['ok' => true]);
    }

    public function exportExcel(Vehicle $vehicle)
    {
        $vehicle->load(['tasks.responsiblePerson', 'tasks.months']);
        return Excel::download(new VehicleSheetExport($vehicle), $vehicle->call_sign . '-vehicle-sheet.xlsx');
    }

    public function exportPdf(Vehicle $vehicle)
    {
        $vehicle->load(['tasks.responsiblePerson', 'tasks.months']);
        return Pdf::loadView('reports.vehicle-sheet-pdf', ['vehicle' => $vehicle])->download($vehicle->call_sign . '-vehicle-sheet.pdf');
    }

    private function ensureTaskRows(Vehicle $vehicle): void
    {
        if ($vehicle->tasks()->exists()) {
            return;
        }

        for ($index = 0; $index < 11; $index++) {
            $vehicle->tasks()->create(['task_name' => '', 'status' => 'pending', 'sort_order' => $index]);
        }
    }

    private function taskData(array $data): array
    {
        if (! empty($data['task_option_id'])) {
            $data['task_name'] = TaskOption::findOrFail($data['task_option_id'])->name;
        }
        $data['sort_order'] ??= 0;
        return $data;
    }

    private function canManage(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }

    private function ensureManager(): void
    {
        abort_unless($this->canManage(), 403);
    }
}