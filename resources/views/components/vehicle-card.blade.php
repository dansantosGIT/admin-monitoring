@props(['vehicle'])

<a class="vehicle-card" href="{{ route('reports.vehicle-monitoring.show', $vehicle) }}">
    <div class="vehicle-card-top">
        <div>
            <span class="vehicle-card-label">Vehicle</span>
            <strong class="vehicle-card-call-sign">{{ strtoupper($vehicle->call_sign) }}</strong>
        </div>
        <x-vehicle-status-pill :vehicle="$vehicle" />
    </div>
    <div class="vehicle-card-type">{{ $vehicle->vehicle_type }}{{ $vehicle->vehicle_model ? ' · ' . $vehicle->vehicle_model : '' }}</div>
    <div class="vehicle-card-details">
        <span><small>Plate No.</small>{{ $vehicle->plate_number }}</span>
        <span><small>Team</small>{{ $vehicle->team ?: 'Unassigned' }}</span>
    </div>
</a>