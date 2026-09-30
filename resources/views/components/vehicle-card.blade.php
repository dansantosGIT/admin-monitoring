@props(['vehicle', 'canManage' => false])

@once
    @push('styles')
    <style>
        .vehicle-monitoring-page .vehicle-row-actions > a:last-child{background:#e7f3f5;border-color:#9ac8cf;color:#155e63}.vehicle-monitoring-page .vehicle-row-actions > a:last-child:hover{background:#cfe8eb;border-color:#5da8b2}.vehicle-monitoring-page .vehicle-row[data-vehicle-status="active"] .vehicle-quick-status{border-color:#86efac;background:#f0fdf4;color:#166534}.vehicle-monitoring-page .vehicle-row[data-vehicle-status="idle"] .vehicle-quick-status{border-color:#fcd34d;background:#fffbeb;color:#854d0e}.vehicle-monitoring-page .vehicle-row[data-vehicle-status="offline"] .vehicle-quick-status{border-color:#fca5a5;background:#fff1f2;color:#991b1b}
    </style>
    @endpush
@endonce

@once
    @push('styles')
    <style>
        .vehicle-monitoring-page .vehicle-overflow-menu button[data-delete-url],.vehicle-monitoring-page .vehicle-overflow-menu button.delete-action{color:#b91c1c}.vehicle-monitoring-page .vehicle-overflow-menu button[data-delete-url]:hover,.vehicle-monitoring-page .vehicle-overflow-menu button.delete-action:hover{background:#fff1f2;color:#991b1b}.vehicle-monitoring-page .vehicle-overflow-menu button[data-quick-status="active"]{color:#166534}.vehicle-monitoring-page .vehicle-overflow-menu button[data-quick-status="idle"]{color:#854d0e}.vehicle-monitoring-page .vehicle-overflow-menu button[data-quick-status="offline"]{color:#b91c1c}
    </style>
    @endpush
@endonce

<article class="vehicle-row" data-vehicle-id="{{ $vehicle->id }}" data-vehicle-status="{{ $vehicle->status }}">
    <a class="vehicle-row-main" href="{{ route('reports.vehicle-monitoring.show', $vehicle) }}">
        <span class="vehicle-thumbnail">@if($vehicle->photo_url)<img src="{{ $vehicle->photo_url }}" alt="{{ $vehicle->call_sign }} vehicle photo">@else<span aria-label="No vehicle photo">&#128663;</span>@endif</span>
        <span class="vehicle-status-dot vehicle-status-dot--{{ $vehicle->status_class }}" aria-hidden="true"></span>
        <div class="vehicle-row-identity">
            <span class="vehicle-card-label">Vehicle</span>
            <strong class="vehicle-card-call-sign">{{ strtoupper($vehicle->call_sign) }}</strong>
            <span class="vehicle-card-type">{{ $vehicle->vehicle_type }}{{ $vehicle->vehicle_model ? ' · ' . $vehicle->vehicle_model : '' }}</span>
        </div>
        <x-vehicle-status-pill :vehicle="$vehicle" />
        <div class="vehicle-row-detail"><small>Plate No.</small><strong>{{ $vehicle->plate_number }}</strong></div>
        <div class="vehicle-row-detail"><small>Team</small><strong>{{ $vehicle->team_label }}</strong></div>
        <div class="vehicle-row-detail vehicle-row-driver">
            <small>Driver / Responsible</small>
            @if($vehicle->driver)
                <span class="driver-badge"><span>{{ strtoupper(substr($vehicle->driver->first_name, 0, 1) . substr($vehicle->driver->last_name, 0, 1)) }}</span>{{ $vehicle->driver->full_name }}</span>
            @else
                <strong>Unassigned</strong>
            @endif
        </div>
        <div class="vehicle-row-detail vehicle-row-updated"><small>Last updated</small><strong>{{ $vehicle->last_updated_at?->diffForHumans() ?? 'Never' }}</strong></div>
        <div class="vehicle-maintenance {{ $vehicle->next_due_date && $vehicle->next_due_date->isPast() ? 'is-overdue' : '' }}">
            <small>Maintenance</small>
            <strong>{{ $vehicle->next_due_date ? ($vehicle->next_due_date->isPast() ? 'Service overdue' : 'Due ' . $vehicle->next_due_date->diffForHumans()) : 'Not scheduled' }}</strong>
        </div>
    </a>
    <div class="vehicle-row-actions">
        @if($canManage)
            <input type="checkbox" class="vehicle-select" data-vehicle-select value="{{ $vehicle->id }}" aria-label="Select {{ $vehicle->call_sign }}">
            <select class="vehicle-quick-status" data-status-url="{{ route('reports.vehicle-monitoring.status', $vehicle) }}" aria-label="Change {{ $vehicle->call_sign }} status">
                @foreach(['active' => 'Active', 'idle' => 'Idle', 'offline' => 'Offline'] as $status => $label)
                    <option value="{{ $status }}" @selected($vehicle->status === $status)>{{ $label }}</option>
                @endforeach
            </select>
        @endif
        <a class="ui-button ui-button--small ui-button--info" href="{{ route('reports.vehicle-monitoring.show', $vehicle) }}">View</a>
        @if($canManage)
            <a class="ui-button ui-button--small ui-button--secondary" href="{{ route('reports.vehicle-monitoring.edit', $vehicle) }}">Edit</a>
            <details class="vehicle-overflow">
                <summary aria-label="More actions for {{ $vehicle->call_sign }}">...</summary>
                <div class="vehicle-overflow-menu">
                    <button type="button" class="status-action status-action--active" data-quick-status-url="{{ route('reports.vehicle-monitoring.status', $vehicle) }}" data-quick-status="active">Mark Active</button>
                    <button type="button" class="status-action status-action--idle" data-quick-status-url="{{ route('reports.vehicle-monitoring.status', $vehicle) }}" data-quick-status="idle">Mark Idle</button>
                    <button type="button" class="status-action status-action--offline" data-quick-status-url="{{ route('reports.vehicle-monitoring.status', $vehicle) }}" data-quick-status="offline">Mark Offline</button>
                    <button type="button" data-duplicate-url="{{ route('reports.vehicle-monitoring.duplicate', $vehicle) }}">Duplicate</button>
                    <form method="POST" action="{{ route('reports.vehicle-monitoring.destroy', $vehicle) }}" onsubmit="return confirm('Delete this vehicle? Related task and service records will also be deleted.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="delete-action">Delete</button>
                    </form>
                </div>
            </details>
        @endif
    </div>
</article>