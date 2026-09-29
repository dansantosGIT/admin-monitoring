<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    private const TYPES = ['Ambulance', 'Rescue Truck', 'Fire Truck', 'Van', 'Motorcycle', 'Other'];

    public function index(Request $request)
    {
        $vehicles = Vehicle::query()
            ->search($request->string('search')->toString())
            ->ofStatus($request->string('status')->toString())
            ->ofTeam($request->string('team')->toString())
            ->orderBy('call_sign')
            ->get();

        $allVehicles = Vehicle::query()->get();

        return view('reports.vehicle-monitoring', [
            'vehicles' => $vehicles,
            'stats' => [
                'total' => $allVehicles->count(),
                'active' => $allVehicles->where('status', 'active')->count(),
                'idle' => $allVehicles->where('status', 'idle')->count(),
                'offline' => $allVehicles->where('status', 'offline')->count(),
            ],
            'teams' => Vehicle::query()->whereNotNull('team')->where('team', '<>', '')->distinct()->orderBy('team')->pluck('team'),
            'filters' => $request->only(['search', 'team', 'status']),
            'vehicleTypes' => self::TYPES,
            'statuses' => ['active', 'idle', 'offline'],
            'canManage' => $this->canManage(),
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Vehicle::class);
        return view('reports.vehicle-form', ['vehicle' => new Vehicle(), 'vehicleTypes' => self::TYPES, 'statuses' => ['active', 'idle', 'offline']]);
    }

    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());
        return redirect()->route('reports.vehicle-monitoring.show', $vehicle)->with('success', 'Vehicle added successfully.');
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
        return view('reports.vehicle-form', ['vehicle' => $vehicle, 'vehicleTypes' => self::TYPES, 'statuses' => ['active', 'idle', 'offline']]);
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return redirect()->route('reports.vehicle-monitoring.show', $vehicle)->with('success', 'Vehicle details updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        Gate::authorize('delete', $vehicle);
        $vehicle->delete();
        return redirect()->route('reports.vehicle-monitoring')->with('success', 'Vehicle deleted.');
    }

    private function canManage(): bool
    {
        return auth()->check() && in_array(auth()->user()->role ?? '', ['admin', 'super-admin'], true);
    }
}