@extends('layouts.app')

@section('title', $vehicle->call_sign)
@section('page-name', 'Vehicle Monitoring')

@section('content')
<div class="ui-shell vehicle-detail-page">
    <div class="vehicle-detail-actions"><a class="ui-button ui-button--secondary" href="{{ route('reports.vehicle-monitoring') }}">Back to Overview</a>@if($canManage)<div><a class="ui-button ui-button--secondary" href="{{ route('reports.vehicle-monitoring.edit', $vehicle) }}">Edit Vehicle</a><form class="inline-form" method="POST" action="{{ route('reports.vehicle-monitoring.destroy', $vehicle) }}" onsubmit="return confirm('Delete this vehicle?')">@csrf @method('DELETE')<button class="ui-button ui-button--danger" type="submit">Delete</button></form></div>@endif</div>
    @if(session('success'))<div class="form-alert form-alert--success">{{ session('success') }}</div>@endif
    <section class="vehicle-sheet-banner"><strong>SAN JUAN CITY DISASTER RISK REDUCTION AND MANAGEMENT DEPARTMENT</strong><em>Monitoring and Tracking Status for Rescue Vehicles</em><h1>{{ strtoupper($vehicle->vehicle_type . ' ' . $vehicle->call_sign) }}</h1></section>
    <section class="ui-card"><div class="vehicle-detail-status"><div><h2 class="ui-title">Vehicle details</h2><p class="ui-subtitle">Manually encoded information for this rescue vehicle.</p></div><x-vehicle-status-pill :vehicle="$vehicle" /></div><div class="vehicle-info-grid">
        @foreach([['VEHICLE TYPE',$vehicle->vehicle_type],['VEHICLE MODEL',$vehicle->vehicle_model ?: '—'],['BRAND',$vehicle->brand ?: '—'],['PLATE NUMBER',$vehicle->plate_number],['YEAR',$vehicle->year ?: '—'],['TEAM',$vehicle->team ?: 'Unassigned'],['GOOGLE DRIVE LINK',null],['REMARKS',$vehicle->remarks ?: '—']] as [$label,$value])<div class="vehicle-info-cell"><span>{{ $label }}</span><strong>@if($label === 'GOOGLE DRIVE LINK')@if($vehicle->drive_link)<a href="{{ $vehicle->drive_link }}" target="_blank" rel="noopener">LINK</a>@else—@endif @else{{ $value }}@endif</strong></div>@endforeach
    </div></section>
    <section class="ui-card task-placeholder-card"><h2 class="ui-title">Task / Service Log - coming next</h2><p class="ui-subtitle">The maintenance and service tracking table will be added in Phase 2.</p></section>
</div>
+@endsection
+
+@push('styles')
+<style>
+    .vehicle-detail-actions,.vehicle-detail-status{display:flex;justify-content:space-between;align-items:center;gap:12px}.vehicle-detail-actions>div{display:flex;gap:8px}.inline-form{display:inline}.ui-button--danger{color:#991b1b;border-color:#fecaca}.form-alert{border-radius:10px;padding:10px 12px;font-size:13px}.form-alert--success{background:#ecfdf5;color:#166534;border:1px solid #bbf7d0}.vehicle-sheet-banner{background:#9b4444;color:#fff;padding:22px 24px;border-radius:18px;display:grid;gap:6px}.vehicle-sheet-banner strong{font-size:18px;letter-spacing:.05em}.vehicle-sheet-banner em{font-size:14px}.vehicle-sheet-banner h1{margin:10px 0 0;font-size:26px;letter-spacing:.08em}.vehicle-info-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));border:1px solid #cbd5e1}.vehicle-info-cell{min-height:66px;border:1px solid #cbd5e1;display:grid;grid-template-rows:auto 1fr}.vehicle-info-cell span{background:#eef1f4;padding:8px;font-size:10px;font-weight:800;letter-spacing:.08em}.vehicle-info-cell strong{padding:10px;font-size:14px;font-weight:700}.vehicle-info-cell a{color:#0f62fe}.task-placeholder-card{min-height:180px;display:grid;align-content:center;justify-items:center;text-align:center}.task-placeholder-card .ui-subtitle{margin:0}@media(max-width:760px){.vehicle-detail-actions,.vehicle-detail-status{align-items:stretch;flex-direction:column}.vehicle-detail-actions>div{width:100%;flex-wrap:wrap}.vehicle-info-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:480px){.vehicle-info-grid{grid-template-columns:1fr}.vehicle-sheet-banner strong{font-size:14px}}
</style>
+@endpush
