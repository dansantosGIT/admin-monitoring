@props(['vehicle'])

<span class="vehicle-status-pill vehicle-status-pill--{{ $vehicle->status_class }}">
    {{ $vehicle->status_label }}
</span>