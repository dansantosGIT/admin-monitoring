<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\VehicleActivityLog;
use App\Models\Employee;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    private const TYPES = ['Ambulance', 'Rescue Truck', 'Fire Truck', 'Van', 'Motorcycle', 'Other'];

    public function index(Request $request)
    {
        $vehicles = $this->vehicleQuery($request)
            ->with(['driver', 'activityLogs' => fn ($query) => $query->limit(1)])
            ->get();

        $allVehicles = Vehicle::query()->get();
        $attentionCount = $allVehicles->filter(fn (Vehicle $vehicle) => $this->needsAttention($vehicle))->count();
        $activities = VehicleActivityLog::with('vehicle')->latest()->limit(6)->get();

        if ($request->expectsJson() || $request->boolean('ajax')) {
            return response()->json([
                'vehicles' => $vehicles->map(fn (Vehicle $vehicle) => $this->vehiclePayload($vehicle))->values(),
                'matching' => $vehicles->count(),
                'total' => $allVehicles->count(),
                'stats' => [
                    'active' => $allVehicles->where('status', 'active')->count(),
                    'idle' => $allVehicles->where('status', 'idle')->count(),
                    'offline' => $allVehicles->where('status', 'offline')->count(),
                    'attention' => $attentionCount,
                ],
            ]);
        }

        return view('reports.vehicle-monitoring', [
            'vehicles' => $vehicles,
            'stats' => [
                'total' => $allVehicles->count(),
                'active' => $allVehicles->where('status', 'active')->count(),
                'idle' => $allVehicles->where('status', 'idle')->count(),
                'offline' => $allVehicles->where('status', 'offline')->count(),
                'attention' => $attentionCount,
            ],
            'teams' => Vehicle::query()->whereNotNull('team')->where('team', '<>', '')->distinct()->orderBy('team')->pluck('team'),
            'filters' => $request->only(['search', 'team', 'status']),
            'vehicleTypes' => self::TYPES,
            'statuses' => ['active', 'idle', 'offline'],
            'canManage' => $this->canManage(),
            'activities' => $activities,
        ]);
    }

    public function quickStatus(Request $request, Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);
        $data = $request->validate(['status' => ['required', 'in:active,idle,offline']]);
        $previousStatus = $vehicle->status;
        $vehicle->update(['status' => $data['status'], 'last_updated_at' => now()]);
        $this->logActivity($vehicle, 'status_changed', sprintf('Vehicle status changed from %s to %s.', ucfirst($previousStatus), ucfirst($vehicle->status)));

        return response()->json(['vehicle' => $this->vehiclePayload($vehicle->fresh('driver'))]);
    }

    public function duplicate(Vehicle $vehicle)
    {
        Gate::authorize('create', Vehicle::class);
        $copy = $vehicle->replicate();
        $copy->call_sign = Str::limit($vehicle->call_sign . ' Copy', 100, '');
        $suffix = 1;
        do {
            $candidatePlate = Str::limit($vehicle->plate_number, 25, '') . '-C' . $suffix++;
        } while (Vehicle::where('plate_number', $candidatePlate)->exists());
        $copy->plate_number = $candidatePlate;
        if ($vehicle->photo_path && Storage::disk('public')->exists($vehicle->photo_path)) {
            $extension = pathinfo($vehicle->photo_path, PATHINFO_EXTENSION) ?: 'jpg';
            $copy->photo_path = 'vehicles/duplicates/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->copy($vehicle->photo_path, $copy->photo_path);
        }
        $copy->last_updated_at = now();
        $copy->save();
        $this->logActivity($copy, 'duplicated', 'Vehicle duplicated from ' . $vehicle->call_sign . '.');

        return response()->json(['vehicle' => $this->vehiclePayload($copy->fresh('driver'))]);
    }

    public function bulkStatus(Request $request)
    {
        abort_unless($this->canManage(), 403);
        $data = $request->validate([
            'vehicle_ids' => ['required', 'array', 'min:1'],
            'vehicle_ids.*' => ['integer', 'exists:vehicles,id'],
            'status' => ['required', 'in:active,idle,offline'],
        ]);

        $vehicles = Vehicle::whereIn('id', $data['vehicle_ids'])->get();
        foreach ($vehicles as $vehicle) {
            $previousStatus = $vehicle->status;
            $vehicle->update(['status' => $data['status'], 'last_updated_at' => now()]);
            $this->logActivity($vehicle, 'bulk_status_changed', sprintf('Vehicle status changed from %s to %s.', ucfirst($previousStatus), ucfirst($vehicle->status)));
        }

        return response()->json(['updated' => $vehicles->count()]);
    }

    public function exportCsv(Request $request)
    {
        abort_unless(auth()->check(), 403);
        $vehicles = $this->vehicleQuery($request)->with('driver')->get();

        return response()->streamDownload(function () use ($vehicles) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Call Sign', 'Status', 'Vehicle', 'Plate Number', 'Team', 'Driver', 'Last Updated', 'Next Maintenance Due']);
            foreach ($vehicles as $vehicle) {
                fputcsv($handle, [
                    $vehicle->call_sign,
                    $vehicle->status_label,
                    trim($vehicle->vehicle_type . ' ' . $vehicle->vehicle_model),
                    $vehicle->plate_number,
                    $vehicle->team_label,
                    $vehicle->driver?->full_name ?? 'Unassigned',
                    $vehicle->last_updated_at?->toDateTimeString() ?? 'Never',
                    $vehicle->next_due_date ? Carbon::parse($vehicle->next_due_date)->toDateString() : 'Not scheduled',
                ]);
            }
            fclose($handle);
        }, 'vehicle-monitoring.csv', ['Content-Type' => 'text/csv']);
    }

    public function create()
    {
        Gate::authorize('create', Vehicle::class);
        return view('reports.vehicle-form', $this->formOptions(new Vehicle()));
    }

    public function checkPlate(Request $request)
    {
        Gate::authorize('create', Vehicle::class);
        $plate = strtoupper(trim((string) $request->query('plate_number')));
        $vehicleId = $request->integer('vehicle_id');
        $exists = $plate !== '' && Vehicle::whereRaw('UPPER(plate_number) = ?', [$plate])
            ->when($vehicleId, fn ($query) => $query->where('id', '<>', $vehicleId))
            ->exists();

        return response()->json(['available' => ! $exists, 'message' => $exists ? 'This plate number is already assigned to another vehicle.' : null]);
    }

    public function store(StoreVehicleRequest $request)
    {
        $data = $request->validated();
        unset($data['new_team'], $data['remove_photo']);
        $photo = $request->file('photo');
        unset($data['photo']);
        if ($photo) {
            $data['photo_path'] = $photo->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($data);
        $vehicle->update(['last_updated_at' => now()]);
        $this->logActivity($vehicle, 'created', 'Vehicle record created.');
        return redirect()->route('reports.vehicle-monitoring.show', $vehicle)->with('success', 'Vehicle added successfully.')->with('clear_vehicle_draft', $request->input('draft_key'));
    }

    public function show(Vehicle $vehicle)
    {
        return view('reports.vehicle-sheet', [
            'vehicle' => $vehicle,
            'canManage' => $this->canManage(),
        ]);
    }

    public function edit(Vehicle $vehicle)
    {
        Gate::authorize('update', $vehicle);
        return view('reports.vehicle-form', $this->formOptions($vehicle));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $data = $request->validated();
        $removePhoto = $request->boolean('remove_photo');
        unset($data['new_team'], $data['remove_photo']);
        $photo = $request->file('photo');
        unset($data['photo']);
        if ($photo) {
            $previousPhoto = $vehicle->photo_path;
            $data['photo_path'] = $photo->store('vehicles', 'public');
            if ($previousPhoto) {
                Storage::disk('public')->delete($previousPhoto);
            }
        } elseif ($removePhoto && $vehicle->photo_path) {
            Storage::disk('public')->delete($vehicle->photo_path);
            $data['photo_path'] = null;
        }

        $vehicle->update($data);
        $vehicle->update(['last_updated_at' => now()]);
        $this->logActivity($vehicle, 'updated', 'Vehicle details updated.');
        return redirect()->route('reports.vehicle-monitoring.show', $vehicle)->with('success', 'Vehicle details updated.')->with('clear_vehicle_draft', $request->input('draft_key'));
    }

    public function destroy(Vehicle $vehicle)
    {
        Gate::authorize('delete', $vehicle);
        $this->logActivity($vehicle, 'deleted', 'Vehicle record deleted.');
        if ($vehicle->photo_path) {
            Storage::disk('public')->delete($vehicle->photo_path);
        }
        $vehicle->delete();
        return redirect()->route('reports.vehicle-monitoring')->with('success', 'Vehicle deleted.');
    }

    private function vehicleQuery(Request $request)
    {
        $sort = $request->input('sort', 'call_sign');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        $sortColumns = [
            'call_sign' => 'call_sign',
            'status' => 'status',
            'team' => 'team',
            'last_updated' => 'last_updated_at',
        ];

        $query = Vehicle::query()
            ->search($request->string('search')->toString())
            ->ofStatus($request->string('status')->toString())
            ->ofTeam($request->string('team')->toString());

        if ($request->boolean('attention')) {
            $query->where(function ($builder) {
                $builder->where('status', 'offline')->orWhereDate('next_due_date', '<=', today());
            });
        }

        return $query->orderBy($sortColumns[$sort] ?? 'call_sign', $direction);
    }

    private function vehiclePayload(Vehicle $vehicle): array
    {
        return [
            'id' => $vehicle->id,
            'photo_url' => $vehicle->photo_url,
            'call_sign' => strtoupper($vehicle->call_sign),
            'status' => $vehicle->status,
            'status_label' => $vehicle->status_label,
            'status_class' => $vehicle->status_class,
            'vehicle_type' => $vehicle->vehicle_type,
            'vehicle_model' => $vehicle->vehicle_model,
            'plate_number' => $vehicle->plate_number,
            'team' => $vehicle->team_label,
            'driver' => $vehicle->driver?->full_name,
            'driver_initials' => $vehicle->driver ? strtoupper(substr($vehicle->driver->first_name, 0, 1) . substr($vehicle->driver->last_name, 0, 1)) : null,
            'last_updated' => $vehicle->last_updated_at?->diffForHumans() ?? 'Never',
            'last_updated_iso' => $vehicle->last_updated_at?->toIso8601String(),
            'next_due_date' => $vehicle->next_due_date ? Carbon::parse($vehicle->next_due_date)->toDateString() : null,
            'maintenance_label' => $vehicle->next_due_date ? (Carbon::parse($vehicle->next_due_date)->isPast() ? 'Service overdue' : 'Service due ' . Carbon::parse($vehicle->next_due_date)->diffForHumans()) : 'Not scheduled',
            'needs_attention' => $this->needsAttention($vehicle),
        ];
    }

    private function needsAttention(Vehicle $vehicle): bool
    {
        return $vehicle->status === 'offline' || ($vehicle->next_due_date && Carbon::parse($vehicle->next_due_date)->isPast());
    }

    private function logActivity(Vehicle $vehicle, string $action, string $description): void
    {
        VehicleActivityLog::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }

    private function canManage(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }

    private function formOptions(Vehicle $vehicle): array
    {
        $teams = Vehicle::query()->whereNotNull('team')->where('team', '<>', '')->distinct()->orderBy('team')->pluck('team');
        $employees = Employee::query()->orderBy('last_name')->orderBy('first_name')->get();

        return compact('vehicle', 'teams', 'employees') + [
            'vehicleTypes' => self::TYPES,
            'statuses' => ['active', 'idle', 'offline'],
            'currentYear' => now()->year,
        ];
    }
}