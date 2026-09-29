@extends('layouts.app')

@section('title', $vehicle->exists ? 'Edit Vehicle' : 'Add Vehicle')
@section('page-name', $vehicle->exists ? 'Edit Vehicle' : 'Add Vehicle')

@section('content')
<div class="ui-shell vehicle-form-page">
    <section class="ui-card">
        <div class="ui-card-head"><div><h1 class="ui-title">{{ $vehicle->exists ? 'Edit Vehicle' : 'Add Vehicle' }}</h1><p class="ui-subtitle">Manually encode the rescue vehicle details.</p></div><a class="ui-button ui-button--secondary" href="{{ $vehicle->exists ? route('reports.vehicle-monitoring.show', $vehicle) : route('reports.vehicle-monitoring') }}">Cancel</a></div>
        @if($errors->any())<div class="form-alert form-alert--error">Please correct the highlighted fields.</div>@endif
        <form method="POST" action="{{ $vehicle->exists ? route('reports.vehicle-monitoring.update', $vehicle) : route('reports.vehicle-monitoring.store') }}">
            @csrf @if($vehicle->exists) @method('PUT') @endif
            <div class="vehicle-form-grid">
                <label>Call Sign*<input class="ui-input" name="call_sign" value="{{ old('call_sign', $vehicle->call_sign) }}" required>@error('call_sign')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label>Vehicle Type*<select class="ui-select" name="vehicle_type" required><option value="">Select type</option>@foreach($vehicleTypes as $type)<option value="{{ $type }}" @selected(old('vehicle_type', $vehicle->vehicle_type) === $type)>{{ $type }}</option>@endforeach</select>@error('vehicle_type')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label>Vehicle Model<input class="ui-input" name="vehicle_model" value="{{ old('vehicle_model', $vehicle->vehicle_model) }}"></label>
                <label>Brand<input class="ui-input" name="brand" value="{{ old('brand', $vehicle->brand) }}"></label>
                <label>Plate Number*<input class="ui-input" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required>@error('plate_number')<small class="field-error">{{ $message }}</small>@enderror</label>
                <label>Year<input class="ui-input" name="year" type="number" value="{{ old('year', $vehicle->year) }}"></label>
                <label>Team<input class="ui-input" name="team" value="{{ old('team', $vehicle->team) }}" placeholder="Alpha, Bravo, Charlie..."></label>
                <label>Status*<select class="ui-select" name="status" required>@foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status', $vehicle->status ?: 'active') === $status)>{{ $status === 'offline' ? 'Offline / Under Repair' : ucfirst($status) }}</option>@endforeach</select></label>
                <label class="vehicle-form-wide">Google Drive Link<input class="ui-input" name="drive_link" type="url" value="{{ old('drive_link', $vehicle->drive_link) }}" placeholder="https://drive.google.com/..."></label>
                <label class="vehicle-form-wide">Remarks<textarea class="ui-textarea" name="remarks" rows="4">{{ old('remarks', $vehicle->remarks) }}</textarea></label>
            </div>
            <div class="vehicle-form-actions"><button class="ui-button ui-button--primary" type="submit">{{ $vehicle->exists ? 'Save Changes' : 'Add Vehicle' }}</button></div>
        </form>
    </section>
</div>
@endsection

@push('styles')
<style>
    .vehicle-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.vehicle-form-grid label{display:grid;gap:7px;font-size:12px;font-weight:800}.vehicle-form-wide{grid-column:1/-1}.vehicle-form-actions{display:flex;justify-content:flex-end;margin-top:18px}.form-alert{border-radius:10px;padding:10px 12px;margin-bottom:14px;font-size:13px}.form-alert--error{background:#fff1f2;color:#991b1b;border:1px solid #fecdd3}.field-error{color:#b91c1c;font-weight:600}@media(max-width:680px){.vehicle-form-grid{grid-template-columns:1fr}.vehicle-form-wide{grid-column:auto}}
</style>
@endpush