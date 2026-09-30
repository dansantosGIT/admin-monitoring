@extends('layouts.app')

@section('title', $vehicle->exists ? 'Edit Vehicle' : 'Add Vehicle')
@section('page-name', $vehicle->exists ? 'Edit Vehicle' : 'Add Vehicle')

@section('content')
<div class="ui-shell vehicle-form-page" data-vehicle-form data-editing="{{ $vehicle->exists ? '1' : '0' }}" data-vehicle-id="{{ $vehicle->id }}" data-original-photo-url="{{ $vehicle->photo_url ?? '' }}" data-check-plate-url="{{ route('reports.vehicle-monitoring.check-plate') }}">
    <div class="vehicle-form-heading"><div><div class="monitoring-eyebrow">Reports / Vehicle Monitoring</div><h1 class="ui-title">{{ $vehicle->exists ? 'Edit Vehicle' : 'Add Vehicle' }}</h1><p class="ui-subtitle">Capture the details your team needs to identify and assign this rescue vehicle.</p></div><x-vehicle-button variant="secondary" :href="$vehicle->exists ? route('reports.vehicle-monitoring.show', $vehicle) : route('reports.vehicle-monitoring')" data-cancel-link>← Back to overview</x-vehicle-button></div>
    @if(session('success'))<div class="form-alert form-alert--success" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="form-alert form-alert--error" role="alert">Please correct the highlighted fields.</div>@endif
    <div class="draft-recovery-banner" data-draft-banner hidden><span>We found unsaved changes from your last session. Restore draft?</span><small>Any previously selected photo will need to be re-added.</small><div><button type="button" class="draft-recovery-action" data-draft-restore>Restore</button><button type="button" class="draft-recovery-action draft-recovery-action--muted" data-draft-discard>Discard</button></div></div>
    <form method="POST" enctype="multipart/form-data" action="{{ $vehicle->exists ? route('reports.vehicle-monitoring.update', $vehicle) : route('reports.vehicle-monitoring.store') }}" data-vehicle-form-element>
        @csrf @if($vehicle->exists) @method('PUT') @endif<input type="hidden" name="draft_key" value="vehicle_draft_{{ $vehicle->exists ? 'edit_'.$vehicle->id : 'create' }}">
        <div class="vehicle-form-layout">
            <div class="vehicle-form-sections">
                <section class="ui-card form-section"><div class="form-section-heading"><div><span class="form-section-kicker">01</span><h2>Identification</h2></div><span class="required-note">* Required</span></div><div class="vehicle-fields-grid">
                    <label class="field-group">Call Sign*<input class="ui-input" name="call_sign" value="{{ old('call_sign', $vehicle->call_sign) }}" placeholder="e.g. Alpha 1" required aria-describedby="call-sign-error">@error('call_sign')<small id="call-sign-error" class="field-error">{{ $message }}</small>@else<small id="call-sign-error" class="field-error" data-client-error></small>@enderror</label>
                    <label class="field-group">Vehicle Type*<select class="ui-select" name="vehicle_type" required aria-describedby="vehicle-type-error"><option value="">Select vehicle type</option>@foreach($vehicleTypes as $type)<option value="{{ $type }}" @selected(old('vehicle_type', $vehicle->vehicle_type) === $type)>{{ $type }}</option>@endforeach</select>@error('vehicle_type')<small id="vehicle-type-error" class="field-error">{{ $message }}</small>@else<small id="vehicle-type-error" class="field-error" data-client-error></small>@enderror</label>
                    <label class="field-group" data-other-type-field @if(old('vehicle_type', $vehicle->vehicle_type) !== 'Other') hidden @endif>Specify vehicle type<input class="ui-input" name="vehicle_type_other" value="{{ old('vehicle_type_other', $vehicle->vehicle_type_other) }}" placeholder="e.g. Rescue boat" aria-describedby="vehicle-type-other-error">@error('vehicle_type_other')<small id="vehicle-type-other-error" class="field-error">{{ $message }}</small>@else<small id="vehicle-type-other-error" class="field-error" data-client-error></small>@enderror</label>
                    <label class="field-group">Vehicle Model<input class="ui-input" name="vehicle_model" value="{{ old('vehicle_model', $vehicle->vehicle_model) }}" placeholder="e.g. Hiace">@error('vehicle_model')<small class="field-error">{{ $message }}</small>@enderror</label>
                    <label class="field-group">Brand<input class="ui-input" name="brand" list="vehicle-brands" value="{{ old('brand', $vehicle->brand) }}" placeholder="e.g. Toyota"><datalist id="vehicle-brands"><option value="Toyota"><option value="Mitsubishi"><option value="Isuzu"><option value="Nissan"><option value="Ford"><option value="Honda"></datalist>@error('brand')<small class="field-error">{{ $message }}</small>@enderror</label>
                    <div class="field-group field-group--wide"><span>Vehicle Photo</span><input class="ui-input" id="vehiclePhoto" name="photo" type="file" accept="image/jpeg,image/png"><input type="hidden" name="remove_photo" value="0"><small class="field-help">JPG or PNG, maximum 5 MB. Photos are saved in a 4:3 frame.</small><div class="photo-actions"><button class="photo-action" type="button" data-adjust-photo @if(!$vehicle->photo_url) hidden @endif>Adjust photo</button><button class="photo-action photo-action--danger" type="button" data-remove-photo @if(!$vehicle->photo_url) hidden @endif>Remove photo</button></div><img id="vehiclePhotoPreview" class="vehicle-photo-preview" src="{{ $vehicle->photo_url ?? '' }}" alt="Vehicle photo preview" @if(!$vehicle->photo_url) hidden @endif><div class="crop-panel" data-crop-panel hidden><div class="crop-frame" data-crop-frame><img data-crop-image alt="Photo adjustment preview"></div><label class="crop-zoom">Zoom<input type="range" min="1" max="3" step="0.01" value="1" data-crop-zoom></label><div class="crop-actions"><button class="ui-button ui-button--secondary" type="button" data-crop-reset>Reset</button><button class="ui-button ui-button--secondary" type="button" data-crop-cancel>Cancel</button><button class="ui-button ui-button--primary" type="button" data-crop-apply>Apply crop</button></div></div>@error('photo')<small class="field-error">{{ $message }}</small>@enderror</div>
                </div></section>
                <section class="ui-card form-section"><div class="form-section-heading"><div><span class="form-section-kicker">02</span><h2>Registration</h2></div></div><div class="vehicle-fields-grid">
                    <label class="field-group">Plate Number*<input class="ui-input" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" placeholder="e.g. SNI 2701" autocomplete="off" required aria-describedby="plate-error"><small class="field-help">Letters are automatically capitalized.</small>@error('plate_number')<small id="plate-error" class="field-error">{{ $message }}</small>@else<small id="plate-error" class="field-error" data-client-error></small>@enderror</label>
                    <label class="field-group">Year<select class="ui-select" name="year"><option value="">Select year</option>@for($year = $currentYear; $year >= $currentYear - 30; $year--)<option value="{{ $year }}" @selected((string) old('year', $vehicle->year ?: $currentYear) === (string) $year)>{{ $year }}</option>@endfor</select>@error('year')<small class="field-error">{{ $message }}</small>@enderror</label>
                    <label class="field-group field-group--wide">Google Drive Link<input class="ui-input" name="drive_link" type="url" value="{{ old('drive_link', $vehicle->drive_link) }}" placeholder="https://drive.google.com/..."><small class="field-help">Link to vehicle documents/photos folder.</small>@error('drive_link')<small class="field-error">{{ $message }}</small>@enderror</label>
                </div></section>
                <section class="ui-card form-section"><div class="form-section-heading"><div><span class="form-section-kicker">03</span><h2>Assignment</h2></div></div><div class="vehicle-fields-grid">
                    <label class="field-group">Team*<select class="ui-select" name="team" required aria-describedby="team-error"><option value="">Select a team</option>@foreach($teams as $team)<option value="{{ $team }}" @selected(old('team', $vehicle->team) === $team)>{{ $team }}</option>@endforeach<option value="__new__">+ Add new team</option></select><input class="ui-input new-team-input" name="new_team" placeholder="Enter new team name" hidden>@error('team')<small id="team-error" class="field-error">{{ $message }}</small>@else<small id="team-error" class="field-error" data-client-error></small>@enderror</label>
                    <label class="field-group">Driver / Responsible Person<input class="ui-input" name="driver_search" list="vehicle-drivers" value="{{ old('driver_search', $vehicle->driver?->full_name) }}" placeholder="Search an employee or leave unassigned" autocomplete="off"><datalist id="vehicle-drivers"><option value="Unassigned">@foreach($employees as $employee)<option value="{{ $employee->full_name }}{{ $employee->employee_number ? ' · '.$employee->employee_number : '' }}" data-driver-id="{{ $employee->id }}">@endforeach</datalist><input type="hidden" name="driver_id" value="{{ old('driver_id', $vehicle->driver_id) }}">@error('driver_id')<small class="field-error">{{ $message }}</small>@enderror</label>
                    <label class="field-group">Status*<select class="ui-select" name="status" required>@foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status', $vehicle->status ?: 'active') === $status)>{{ $status === 'offline' ? 'Offline / Under Repair' : ucfirst($status) }}</option>@endforeach</select></label>
                </div></section>
                <section class="ui-card form-section"><div class="form-section-heading"><div><span class="form-section-kicker">04</span><h2>Notes</h2></div></div><label class="field-group">Remarks / Notes<textarea class="ui-textarea" name="remarks" rows="4" placeholder="Add context that will help the next person reviewing this vehicle.">{{ old('remarks', $vehicle->remarks) }}</textarea><small class="field-help">Use this for manual notes, special equipment, or follow-up details.</small>@error('remarks')<small class="field-error">{{ $message }}</small>@enderror</label></section>
                <div class="vehicle-form-actions"><div class="vehicle-form-footer-start"><x-vehicle-button variant="secondary" :href="$vehicle->exists ? route('reports.vehicle-monitoring.show', $vehicle) : route('reports.vehicle-monitoring')" data-cancel-link>Cancel</x-vehicle-button><small class="draft-note" data-draft-note hidden>Draft saved a few seconds ago</small><small class="draft-storage-warning" data-draft-storage-warning hidden>Draft saving is unavailable in this browser.</small></div><div class="vehicle-form-action-group"><x-vehicle-button variant="primary" type="submit">{{ $vehicle->exists ? 'Save Changes' : 'Save Vehicle' }}</x-vehicle-button></div></div>
            </div>
            <aside class="vehicle-preview-column"><div class="ui-card vehicle-live-preview"><div class="preview-heading"><div><span class="form-section-kicker">LIVE PREVIEW</span><h2>Overview card</h2></div><span class="vehicle-status-pill vehicle-status-pill--active" data-preview-status>Active</span></div><div class="preview-photo"><img data-preview-photo alt="Vehicle preview" @if(!$vehicle->photo_url) hidden @endif><span data-preview-placeholder aria-hidden="true">🚑</span></div><span class="preview-label">Vehicle</span><strong class="preview-call-sign" data-preview-call-sign>{{ $vehicle->call_sign ?: 'Call sign' }}</strong><span class="preview-type" data-preview-type>{{ trim(($vehicle->vehicle_type ?: 'Vehicle type').' '.($vehicle->vehicle_model ?: '')) }}</span><div class="preview-details"><span><small>Plate</small><strong data-preview-plate>{{ $vehicle->plate_number ?: 'Not set' }}</strong></span><span><small>Team</small><strong data-preview-team>{{ $vehicle->team ?: 'Unassigned' }}</strong></span></div></div></aside>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .vehicle-form-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:18px}.vehicle-form-layout{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(280px,.75fr);gap:18px;align-items:start}.vehicle-form-sections{display:grid;gap:14px}.form-section{padding:20px}.form-section-heading{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:18px}.form-section-heading h2,.vehicle-live-preview h2{margin:3px 0 0;font-size:18px}.form-section-kicker{color:#0f62fe;font-size:10px;font-weight:900;letter-spacing:.12em}.required-note,.field-help,.draft-note{color:#607086;font-size:11px;font-weight:600}.vehicle-fields-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.field-group{display:grid;gap:7px;font-size:12px;font-weight:800}.field-group--wide{grid-column:1/-1}.field-group[hidden]{display:none}.field-error{min-height:15px;color:#b91c1c;font-size:11px;font-weight:700}.field-error:empty{display:none}.form-alert{border-radius:10px;padding:10px 12px;margin-bottom:14px;font-size:13px}.form-alert--error{background:#fff1f2;color:#991b1b;border:1px solid #fecdd3}.form-alert--success{background:#ecfdf3;color:#166534;border:1px solid #bbf7d0}.vehicle-form-actions{display:flex;justify-content:space-between;align-items:center;gap:18px;border-top:1px solid #e5ebf3;padding-top:18px}.vehicle-form-action-group{display:flex;justify-content:flex-end;gap:9px}.new-team-input{margin-top:2px}.vehicle-live-preview{position:sticky;top:18px;padding:20px;background:linear-gradient(145deg,#fff 0%,#f6f9ff 100%)}.preview-heading{display:flex;justify-content:space-between;gap:10px;align-items:flex-start}.preview-photo{height:170px;display:grid;place-items:center;margin:20px 0 16px;border:1px solid #dce5ef;border-radius:12px;background:#f8fbff;color:#94a3b8;font-size:44px;overflow:hidden}.preview-photo img{width:100%;height:100%;object-fit:cover}.preview-photo img[hidden]{display:none}.preview-label,.preview-type{display:block;color:#607086;font-size:11px}.preview-label{text-transform:uppercase;letter-spacing:.1em;font-weight:800}.preview-call-sign{display:block;margin:4px 0;font-size:24px}.preview-details{display:grid;grid-template-columns:1fr 1fr;gap:12px;border-top:1px solid #e5ebf3;margin-top:18px;padding-top:14px}.preview-details span{display:grid;gap:4px}.preview-details small{color:#607086;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em}.preview-details strong{font-size:13px;overflow-wrap:anywhere}.draft-note{margin:7px 0 0;text-align:right}.vehicle-photo-preview{width:120px;height:90px;object-fit:cover;border-radius:10px;border:1px solid #cbd5e1;background:#f8fbff}.vehicle-photo-preview[hidden]{display:none}@media(max-width:850px){.vehicle-form-layout{grid-template-columns:1fr}.vehicle-preview-column{grid-row:1}.vehicle-live-preview{position:static}}@media(max-width:600px){.vehicle-form-heading{display:grid}.vehicle-fields-grid{grid-template-columns:1fr}.field-group--wide{grid-column:auto}.vehicle-form-actions{align-items:stretch;flex-wrap:wrap}.vehicle-form-action-group{flex:1 1 100%;justify-content:stretch}.vehicle-form-actions>.ui-button,.vehicle-form-action-group>.ui-button{flex:1}.vehicle-form-action-group{order:2}}
    .photo-actions{display:flex;gap:8px;flex-wrap:wrap}.photo-action{border:0;background:none;color:#0f62fe;padding:0;font:inherit;font-size:11px;font-weight:800;cursor:pointer;text-decoration:underline}.photo-action--danger{color:#b91c1c}.crop-panel{display:grid;gap:12px;margin-top:10px;padding:14px;border:1px solid #cbd8e8;border-radius:12px;background:#f8fbff}.crop-frame{position:relative;width:100%;aspect-ratio:4/3;overflow:hidden;border-radius:9px;background:#172033;touch-action:none;cursor:grab}.crop-frame:active{cursor:grabbing}.crop-frame img{position:absolute;max-width:none;user-select:none;pointer-events:none;transform-origin:0 0}.crop-zoom{display:grid;gap:6px;font-size:11px;font-weight:800}.crop-zoom input{accent-color:#0f62fe}.crop-actions{display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap}.crop-actions .ui-button{min-height:34px;padding:0 10px;font-size:12px}.preview-photo,.vehicle-photo-preview{aspect-ratio:4/3;height:auto}.preview-photo{min-height:0}.vehicle-photo-preview{width:120px}
</style>
@endpush

@php
    $draftBaseline = [
        'call_sign' => old('call_sign', $vehicle->call_sign),
        'vehicle_type' => old('vehicle_type', $vehicle->vehicle_type),
        'vehicle_type_other' => old('vehicle_type_other', $vehicle->vehicle_type_other),
        'vehicle_model' => old('vehicle_model', $vehicle->vehicle_model),
        'brand' => old('brand', $vehicle->brand),
        'plate_number' => old('plate_number', $vehicle->plate_number),
        'year' => old('year', $vehicle->year ?: $currentYear),
        'team' => old('team', $vehicle->team),
        'new_team' => old('new_team'),
        'driver_search' => old('driver_search', $vehicle->driver?->full_name),
        'driver_id' => old('driver_id', $vehicle->driver_id),
        'status' => old('status', $vehicle->status ?: 'active'),
        'drive_link' => old('drive_link', $vehicle->drive_link),
        'remarks' => old('remarks', $vehicle->remarks),
    ];
@endphp

@push('styles')
<style>
    .vehicle-form-page .ui-button{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 18px;border:1px solid transparent;border-radius:9px;text-decoration:none;font-size:13px;font-weight:800;line-height:1;cursor:pointer;transition:background .15s ease,border-color .15s ease,color .15s ease,box-shadow .15s ease}.vehicle-form-page .ui-button:focus-visible{outline:3px solid rgba(15,98,254,.25);outline-offset:2px}.vehicle-form-page .ui-button--primary{background:#0f62fe;border-color:#0f62fe;color:#fff}.vehicle-form-page .ui-button--primary:hover{background:#0b4dcc;border-color:#0b4dcc}.vehicle-form-page .ui-button--secondary{background:#fff;border-color:#cbd8e8;color:#3f4d5d}.vehicle-form-page .ui-button--secondary:hover{background:#f3f6fb;border-color:#94a3b8;color:#122033}.vehicle-form-page .crop-actions .ui-button{min-height:34px;padding:0 10px;font-size:12px}
</style>
@endpush

@push('styles')
<style>
    .draft-recovery-banner{display:grid;grid-template-columns:1fr auto;gap:4px 16px;align-items:center;margin-bottom:14px;padding:12px 14px;border:1px solid #bfdbfe;border-radius:10px;background:#eff6ff;color:#1e3a8a;font-size:13px}.draft-recovery-banner small{color:#475569;font-size:11px}.draft-recovery-banner>div{grid-row:1/3;grid-column:2;display:flex;gap:8px}.draft-recovery-action{border:1px solid #0f62fe;border-radius:7px;background:#0f62fe;color:#fff;padding:6px 10px;font:inherit;font-size:11px;font-weight:800;cursor:pointer}.draft-recovery-action--muted{background:#fff;color:#475569;border-color:#cbd5e1}.draft-recovery-action:focus-visible{outline:3px solid rgba(15,98,254,.25);outline-offset:2px}.vehicle-form-footer-start{display:flex;align-items:center;gap:12px;min-width:0}.draft-note,.draft-storage-warning{margin:0;color:#607086;font-size:11px;font-weight:600}.draft-storage-warning{color:#92400e}.vehicle-form-action-group{min-width:150px}@media(max-width:600px){.draft-recovery-banner{grid-template-columns:1fr}.draft-recovery-banner>div{grid-column:1;grid-row:auto}.vehicle-form-footer-start{align-items:flex-start;flex-direction:column;gap:6px}.vehicle-form-action-group{width:100%}}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('[data-vehicle-form]');
    const form = page?.querySelector('[data-vehicle-form-element]');
    if (!page || !form) return;
    const input = document.getElementById('vehiclePhoto');
    const preview = document.getElementById('vehiclePhotoPreview');
    const draftKey = `vehicle_draft_${page.dataset.editing === '1' ? `edit_${page.dataset.vehicleId}` : 'create'}`;
    const draftNote = page.querySelector('[data-draft-note]');
    const draftBanner = page.querySelector('[data-draft-banner]');
    const storageWarning = page.querySelector('[data-draft-storage-warning]');
    const baseline = @json($draftBaseline);
    let dirty = false;
    let draftTimer;
    const fields = ['call_sign', 'vehicle_type', 'vehicle_type_other', 'vehicle_model', 'brand', 'plate_number', 'year', 'team', 'new_team', 'driver_search', 'driver_id', 'status', 'drive_link', 'remarks'];
    const cropPanel = page.querySelector('[data-crop-panel]');
    const cropFrame = page.querySelector('[data-crop-frame]');
    const cropImage = page.querySelector('[data-crop-image]');
    const cropZoom = page.querySelector('[data-crop-zoom]');
    let cropSourceUrl = '';
    let pendingNewFile = null;
    let cropScale = 1;
    let cropOffset = {x: 0, y: 0};
    let cropStart = null;
    const setClientError = (name, message) => { const error = page.querySelector(`[name="${name}"]`)?.closest('.field-group')?.querySelector('[data-client-error]'); if (error) error.textContent = message || ''; };
    const updatePreview = () => { const value = name => form.elements[name]?.value || ''; page.querySelector('[data-preview-call-sign]').textContent = value('call_sign') || 'Call sign'; page.querySelector('[data-preview-type]').textContent = `${value('vehicle_type') || 'Vehicle type'}${value('vehicle_model') ? ` · ${value('vehicle_model')}` : ''}`; page.querySelector('[data-preview-plate]').textContent = value('plate_number') || 'Not set'; page.querySelector('[data-preview-team]').textContent = value('team') === '__new__' ? (value('new_team') || 'New team') : (value('team') || 'Unassigned'); const status = value('status'); const statusEl = page.querySelector('[data-preview-status]'); statusEl.textContent = status === 'offline' ? 'Offline / Under Repair' : status.charAt(0).toUpperCase() + status.slice(1); statusEl.className = `vehicle-status-pill vehicle-status-pill--${status || 'active'}`; };
    const showStorageWarning = () => { if (storageWarning) storageWarning.hidden = false; };
    const readDraft = () => { try { return JSON.parse(localStorage.getItem(draftKey) || 'null'); } catch (error) { try { localStorage.removeItem(draftKey); } catch (removeError) {} showStorageWarning(); return null; } };
    const collectDraft = () => { const draft = {}; fields.forEach(name => { if (form.elements[name]) draft[name] = form.elements[name].value; }); return draft; };
    const clearDraft = () => { try { localStorage.removeItem(draftKey); } catch (error) { showStorageWarning(); } };
    const saveDraft = () => { try { localStorage.setItem(draftKey, JSON.stringify(collectDraft())); if (draftNote) { draftNote.hidden = false; draftNote.textContent = 'Draft saved a few seconds ago'; } } catch (error) { showStorageWarning(); } };
    const draftDiffers = draft => fields.some(name => String(draft?.[name] ?? '') !== String(baseline?.[name] ?? ''));
    const restoreDraft = () => { const draft = readDraft(); if (!draft || !draftDiffers(draft)) { if (draft) clearDraft(); return; } draftBanner.hidden = false; };
    const applyDraft = draft => { fields.forEach(name => { if (form.elements[name] && draft[name] !== undefined) form.elements[name].value = draft[name]; }); toggleOtherType(); toggleNewTeam(); updatePreview(); draftBanner.hidden = true; dirty = true; saveDraft(); };
    const queueDraft = () => { dirty = true; updatePreview(); clearTimeout(draftTimer); draftTimer = setTimeout(saveDraft, 1200); };
    const toggleOtherType = () => { const field = page.querySelector('[data-other-type-field]'); field.hidden = form.elements.vehicle_type.value !== 'Other'; form.elements.vehicle_type_other.required = !field.hidden; };
    const toggleNewTeam = () => { const input = form.elements.new_team; const custom = form.elements.team.value === '__new__'; input.hidden = !custom; input.required = custom; if (custom) input.focus(); };
    const positionCropImage = () => { const frameWidth = cropFrame.clientWidth; const frameHeight = cropFrame.clientHeight; const baseScale = Math.max(frameWidth / cropImage.naturalWidth, frameHeight / cropImage.naturalHeight); const scale = baseScale * Number(cropZoom.value); const width = cropImage.naturalWidth * scale; const height = cropImage.naturalHeight * scale; const minX = Math.min(0, frameWidth - width); const minY = Math.min(0, frameHeight - height); cropOffset.x = Math.min(0, Math.max(minX, cropOffset.x)); cropOffset.y = Math.min(0, Math.max(minY, cropOffset.y)); cropImage.style.width = `${cropImage.naturalWidth * scale}px`; cropImage.style.height = `${cropImage.naturalHeight * scale}px`; cropImage.style.left = `${cropOffset.x}px`; cropImage.style.top = `${cropOffset.y}px`; cropScale = scale; };
    const openCropper = sourceUrl => { cropSourceUrl = sourceUrl; cropPanel.hidden = false; cropZoom.value = '1'; cropOffset = {x: 0, y: 0}; cropImage.onload = () => { const baseScale = Math.max(cropFrame.clientWidth / cropImage.naturalWidth, cropFrame.clientHeight / cropImage.naturalHeight); cropOffset = {x: (cropFrame.clientWidth - cropImage.naturalWidth * baseScale) / 2, y: (cropFrame.clientHeight - cropImage.naturalHeight * baseScale) / 2}; positionCropImage(); }; cropImage.src = sourceUrl; };
    const closeCropper = () => { cropPanel.hidden = true; cropStart = null; };
    const cancelCrop = () => { if (pendingNewFile) { input.value = ''; pendingNewFile = null; const originalUrl = page.dataset.originalPhotoUrl; if (originalUrl) { preview.src = originalUrl; preview.hidden = false; page.querySelector('[data-preview-photo]').src = originalUrl; page.querySelector('[data-preview-photo]').hidden = false; page.querySelector('[data-preview-placeholder]').hidden = true; page.querySelector('[data-adjust-photo]').hidden = false; page.querySelector('[data-remove-photo]').hidden = false; } else { clearPhoto(); } form.elements.remove_photo.value = '0'; } closeCropper(); };
    const applyCrop = () => { const canvas = document.createElement('canvas'); canvas.width = 1200; canvas.height = 900; const context = canvas.getContext('2d'); const sourceX = -cropOffset.x / cropScale; const sourceY = -cropOffset.y / cropScale; const sourceWidth = cropFrame.clientWidth / cropScale; const sourceHeight = cropFrame.clientHeight / cropScale; context.drawImage(cropImage, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, canvas.width, canvas.height); canvas.toBlob(blob => { if (!blob) return; const croppedFile = new File([blob], 'vehicle-cropped.jpg', {type: 'image/jpeg'}); const transfer = new DataTransfer(); transfer.items.add(croppedFile); input.files = transfer.files; pendingNewFile = null; const url = URL.createObjectURL(blob); preview.src = url; preview.hidden = false; page.querySelector('[data-preview-photo]').src = url; page.querySelector('[data-preview-photo]').hidden = false; page.querySelector('[data-preview-placeholder]').hidden = true; page.querySelector('[data-remove-photo]').hidden = false; form.elements.remove_photo.value = '0'; dirty = true; closeCropper(); }, 'image/jpeg', .9); };
    const clearPhoto = () => { input.value = ''; pendingNewFile = null; preview.removeAttribute('src'); preview.hidden = true; page.querySelector('[data-preview-photo]').removeAttribute('src'); page.querySelector('[data-preview-photo]').hidden = true; page.querySelector('[data-preview-placeholder]').hidden = false; page.querySelector('[data-remove-photo]').hidden = true; page.querySelector('[data-adjust-photo]').hidden = true; form.elements.remove_photo.value = '1'; dirty = true; };
    input?.addEventListener('change', () => {
        const file = input.files?.[0];
        if (!file) return;
        if (!['image/jpeg', 'image/png'].includes(file.type) || file.size > 5 * 1024 * 1024) {
            input.value = '';
            preview.hidden = true;
            return;
        }
        const sourceUrl = URL.createObjectURL(file);
        pendingNewFile = file;
        page.querySelector('[data-adjust-photo]').hidden = false;
        page.querySelector('[data-remove-photo]').hidden = false;
        form.elements.remove_photo.value = '0';
        openCropper(sourceUrl);
    });
    page.querySelector('[data-adjust-photo]')?.addEventListener('click', () => { const sourceUrl = cropSourceUrl || preview.currentSrc || preview.src; if (sourceUrl) openCropper(sourceUrl); });
    page.querySelector('[data-remove-photo]')?.addEventListener('click', () => { if (page.dataset.editing === '1' && !window.confirm("Remove this vehicle's photo?")) return; clearPhoto(); });
    page.querySelector('[data-crop-apply]')?.addEventListener('click', applyCrop);
    page.querySelector('[data-crop-cancel]')?.addEventListener('click', cancelCrop);
    page.querySelector('[data-crop-reset]')?.addEventListener('click', () => { cropZoom.value = '1'; cropOffset = {x: 0, y: 0}; positionCropImage(); });
    cropZoom?.addEventListener('input', positionCropImage);
    cropFrame?.addEventListener('pointerdown', event => { cropStart = {x: event.clientX, y: event.clientY, offsetX: cropOffset.x, offsetY: cropOffset.y}; cropFrame.setPointerCapture(event.pointerId); });
    cropFrame?.addEventListener('pointermove', event => { if (!cropStart) return; cropOffset.x = cropStart.offsetX + event.clientX - cropStart.x; cropOffset.y = cropStart.offsetY + event.clientY - cropStart.y; positionCropImage(); });
    cropFrame?.addEventListener('pointerup', () => { cropStart = null; });
    form.addEventListener('input', queueDraft);
    form.addEventListener('change', queueDraft);
    form.elements.vehicle_type.addEventListener('change', toggleOtherType);
    form.elements.team.addEventListener('change', toggleNewTeam);
    form.elements.driver_search.addEventListener('input', event => { const option = [...page.querySelectorAll('#vehicle-drivers option')].find(item => item.value === event.target.value); form.elements.driver_id.value = option?.dataset.driverId || ''; });
    form.elements.plate_number.addEventListener('input', event => { event.target.value = event.target.value.toUpperCase(); setClientError('plate_number', ''); });
    form.elements.plate_number.addEventListener('blur', async event => { const plate = event.target.value.trim(); if (!plate) return; const params = new URLSearchParams({plate_number: plate}); if (page.dataset.vehicleId) params.set('vehicle_id', page.dataset.vehicleId); const response = await fetch(`${page.dataset.checkPlateUrl}?${params}`); if (response.ok) { const result = await response.json(); setClientError('plate_number', result.available ? '' : result.message); } });
    draftBanner.querySelector('[data-draft-restore]')?.addEventListener('click', () => { const draft = readDraft(); if (draft) applyDraft(draft); else draftBanner.hidden = true; });
    draftBanner.querySelector('[data-draft-discard]')?.addEventListener('click', () => { clearDraft(); draftBanner.hidden = true; });
    page.querySelectorAll('[data-cancel-link]').forEach(link => link.addEventListener('click', event => { if (dirty && !window.confirm('Discard unsaved changes?')) { event.preventDefault(); return; } clearDraft(); }));
    window.addEventListener('beforeunload', event => { if (dirty) { event.preventDefault(); event.returnValue = ''; } });
    try { localStorage.setItem('__vehicle_draft_probe__', '1'); localStorage.removeItem('__vehicle_draft_probe__'); } catch (error) { showStorageWarning(); }
    toggleOtherType(); toggleNewTeam(); updatePreview(); restoreDraft();
});
</script>
@endpush