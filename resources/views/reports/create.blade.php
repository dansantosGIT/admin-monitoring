@extends('layouts.app')

@section('title', 'Create Report')
@section('page-name', 'Create Report')

{{-- Fixed layout-shifting inline validation and restored the Department dropdown with conditional Team / Specify Office fields. --}}

@push('styles')
<style>
    .incident-form-shell {
        width: 100%;
        max-width: 1180px;
        display: grid;
        gap: 18px;
    }

    .panel-card {
        background: #fff;
        border: 1px solid #dde7f2;
        border-radius: 20px;
        box-shadow: 0 18px 48px rgba(18, 32, 51, 0.08);
        padding: 20px;
    }

    .panel-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        align-items: flex-start;
    }

    .panel-title {
        font-size: 20px;
        font-weight: 800;
        margin: 0;
    }

    .panel-sub {
        color: #607086;
        font-size: 13px;
        margin-top: 4px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .field {
        display: grid;
        gap: 8px;
    }

    .field-label-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
    }

    .field label {
        font-size: 13px;
        font-weight: 700;
        color: #122033;
    }

    .input,
    .select,
    .textarea {
        width: 100%;
        height: 44px;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 10px 14px;
        background: #fff;
        color: #122033;
        font: inherit;
        box-sizing: border-box;
    }

    .input[type="file"] {
        padding-top: 8px;
        padding-bottom: 8px;
    }

    .field-span-2 {
        grid-column: 1 / -1;
    }

    .textarea {
        min-height: 120px;
        resize: vertical;
    }

    .input:focus,
    .select:focus,
    .textarea:focus {
        outline: none;
        border-color: #0f62fe;
        box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.12);
    }

    .input[readonly] {
        background: #f6f8fb;
        color: #607086;
    }

    .identity-display {
        display: flex;
        align-items: center;
    }

    .reporter-identity-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .reporter-identity-grid > .field {
        align-content: start;
    }

    .reporter-identity-grid > .field > .field-label-row {
        min-height: 32px;
        align-items: center;
    }

    .field-error {
        min-height: 18px;
        font-size: 12px;
        color: #c53030;
        visibility: hidden;
    }

    .field-error:not(:empty) {
        visibility: visible;
    }

    .conditional-panel {
        display: none;
    }

    .conditional-panel.is-visible {
        display: grid;
        gap: 14px;
    }

    .conditional-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .lock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #607086;
        background: #eef3f8;
        border: 1px solid #dce5ef;
        border-radius: 999px;
        padding: 4px 8px;
        white-space: nowrap;
    }

    .lock-badge svg {
        width: 12px;
        height: 12px;
        flex: 0 0 auto;
    }

    .select option {
        color: #122033;
    }

    .section-title {
        font-size: 15px;
        font-weight: 800;
        margin: 0 0 10px;
    }

    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .btn-primary { background: #0f62fe; color: #fff; }
    .btn-secondary { background: #fff; color: #122033; border-color: #dce5ef; }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }

    .preview-item {
        position: relative;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 8px;
        background: #f8fbff;
        min-height: 110px;
        display: grid;
        align-content: center;
        gap: 6px;
        text-align: center;
        font-size: 12px;
        color: #607086;
    }

    .preview-remove {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 26px;
        height: 26px;
        border: 1px solid #dce5ef;
        border-radius: 50%;
        background: #fff;
        color: #c53030;
        font-size: 16px;
        line-height: 1;
        cursor: pointer;
    }

    .preview-remove:hover,
    .preview-remove:focus-visible {
        background: #fff5f5;
        border-color: #c53030;
    }

    .preview-item img {
        width: 100%;
        height: 76px;
        object-fit: cover;
        border-radius: 10px;
    }

    @media (max-width: 860px) {
        .grid-2 { grid-template-columns: 1fr; }
        .conditional-grid { grid-template-columns: 1fr; }
        .reporter-identity-grid { grid-template-columns: 1fr; }
        .actions { justify-content: stretch; }
        .actions .btn { width: 100%; }
        .field-label-row { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="incident-form-shell">
    <div class="panel-card">
        <div class="panel-head">
            <div>
                <h1 class="panel-title">Create Report</h1>
                <div class="panel-sub">Record a new incident with attachments, action notes, and the employee involved.</div>
                <div class="ui-required-note">* Required field</div>
            </div>
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" novalidate data-incident-form>
            @csrf

            <div class="grid-2">
                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Incident Info</h2>
                    <div class="grid-2">
                        <div class="field">
                            <div class="field-label-row">
                                <label for="date_of_incident">Date of Incident <span class="ui-required">*</span></label>
                            </div>
                            <input class="input" id="date_of_incident" name="date_of_incident" type="date" value="{{ old('date_of_incident') }}" required aria-required="true" aria-describedby="date_of_incident-error">
                            <div class="field-error" id="date_of_incident-error">@error('date_of_incident'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="incident_type">Incident Type <span class="ui-required">*</span></label>
                            </div>
                            <select class="select" id="incident_type" name="incident_type" required aria-required="true" aria-describedby="incident_type-error">
                                <option value="">Select type</option>
                                @foreach ($incidentTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('incident_type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="incident_type-error">@error('incident_type'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="severity">Severity <span class="ui-required">*</span></label>
                            </div>
                            <select class="select" id="severity" name="severity" required aria-required="true" aria-describedby="severity-error">
                                <option value="">Select severity</option>
                                @foreach ($severityLevels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('severity') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="severity-error">@error('severity'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="status">Status <span class="ui-required">*</span></label>
                            </div>
                            <select class="select" id="status" name="status" required aria-required="true" aria-describedby="status-error">
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'pending') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="status-error">@error('status'){{ $message }}@enderror</div>
                        </div>
                        <div class="field field-span-2">
                            <div class="field-label-row">
                                <label for="location">Location <span class="ui-required">*</span></label>
                            </div>
                            <input class="input" id="location" name="location" type="text" value="{{ old('location') }}" placeholder="Where did the incident happen?" required aria-required="true" aria-describedby="location-error">
                            <div class="field-error" id="location-error">@error('location'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </section>

                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Employee</h2>
                    <div class="grid-2">
                        <div class="field">
                            <div class="field-label-row">
                                <label for="employee_id">Employee <span class="ui-required">*</span></label>
                            </div>
                            <select class="select" id="employee_id" name="employee_id" required aria-required="true" aria-describedby="employee_id-error">
                                <option value="">Select employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->full_name }}{{ $employee->employee_number ? ' • ' . $employee->employee_number : '' }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="employee_id-error">@error('employee_id'){{ $message }}@enderror</div>
                        </div>

                        <div class="field field-span-2">
                            <div class="field-label-row">
                                <label for="department">Department <span class="ui-required">*</span></label>
                            </div>
                            <select class="select" id="department" name="department" required aria-required="true" aria-describedby="department-error">
                                <option value="">Select department</option>
                                @foreach ($departmentOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('department') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="department-error">@error('department'){{ $message }}@enderror</div>
                        </div>

                        <div class="field field-span-2 conditional-panel" id="departmentTeamPanel" hidden>
                            <div class="conditional-grid">
                                <div class="field">
                                    <div class="field-label-row">
                                        <label for="team">Team <span class="ui-required">*</span></label>
                                    </div>
                                    <select class="select" id="team" name="team" aria-describedby="team-error">
                                        <option value="">Select team</option>
                                        @foreach ($teamOptions as $value => $label)
                                            <option value="{{ $value }}" @selected(old('team') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-error" id="team-error">@error('team'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>

                        <div class="field field-span-2 conditional-panel" id="departmentOtherPanel" hidden>
                            <div class="field">
                                <div class="field-label-row">
                                    <label for="department_other">Specify Office/Unit <span class="ui-required">*</span></label>
                                </div>
                                <input class="input" id="department_other" name="department_other" type="text" value="{{ old('department_other') }}" placeholder="Which part of San Juan government?" aria-describedby="department_other-error">
                                <div class="field-error" id="department_other-error">@error('department_other'){{ $message }}@enderror</div>
                            </div>
                        </div>
                        <div class="field field-span-2 reporter-identity-grid">
                            <div class="field">
                                <div class="field-label-row">
                                    <label for="reported_by">Account Email</label>
                                    <span class="lock-badge" title="Auto-filled from the logged-in admin account">
                                        <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        Locked after saving
                                    </span>
                                </div>
                                <div class="input identity-display" id="reported_by" role="textbox" aria-readonly="true"><x-masked-email :email="auth()->user()->email ?? auth()->user()->name ?? 'Current user'" /></div>
                            </div>
                            <div class="field">
                                <div class="field-label-row"><label for="reported_by_name">Staff Name <span class="ui-required">*</span></label></div>
                                <input class="input" id="reported_by_name" name="reported_by_name" type="text" value="{{ old('reported_by_name') }}" placeholder="Enter your name" minlength="2" maxlength="100" required aria-required="true" aria-describedby="reported_by_name-error">
                                <div class="field-error" id="reported_by_name-error">@error('reported_by_name'){{ $message }}@enderror</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="grid-2" style="margin-top:14px;">
                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Item Details</h2>
                    <div class="grid-2">
                        <div class="field">
                            <div class="field-label-row">
                                <label for="item_name">Item Name <span class="ui-required">*</span></label>
                            </div>
                            <input class="input" id="item_name" name="item_name" type="text" value="{{ old('item_name') }}" required aria-required="true" aria-describedby="item_name-error">
                            <div class="field-error" id="item_name-error">@error('item_name'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="property_number">Property Number</label>
                            </div>
                            <input class="input" id="property_number" name="property_number" type="text" value="{{ old('property_number') }}" aria-describedby="property_number-error">
                            <div class="field-error" id="property_number-error">@error('property_number'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="serial_number">Serial Number</label>
                            </div>
                            <input class="input" id="serial_number" name="serial_number" type="text" value="{{ old('serial_number') }}" aria-describedby="serial_number-error">
                            <div class="field-error" id="serial_number-error">@error('serial_number'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="estimated_cost">Estimated Cost</label>
                            </div>
                            <input class="input" id="estimated_cost" name="estimated_cost" type="text" inputmode="decimal" autocomplete="off" placeholder="0.00" value="{{ old('estimated_cost') }}" aria-describedby="estimated_cost-error">
                            <div class="field-error" id="estimated_cost-error">@error('estimated_cost'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="attachments">Attachments</label>
                            </div>
                            <input class="input" id="attachments" name="attachments[]" type="file" accept="image/jpeg,image/png,application/pdf" multiple>
                            <div class="field-error" id="attachments-error">@error('attachments'){{ $message }}@enderror</div>
                        </div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <div class="field-label-row">
                            <label for="description">Description <span class="ui-required">*</span></label>
                        </div>
                        <textarea class="textarea" id="description" name="description" required aria-required="true" aria-describedby="description-error">{{ old('description') }}</textarea>
                        <div class="field-error" id="description-error">@error('description'){{ $message }}@enderror</div>
                    </div>
                </section>

                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Status & Action</h2>
                    <div class="field">
                        <div class="field-label-row">
                            <label for="action_taken">Action Taken</label>
                        </div>
                        <textarea class="textarea" id="action_taken" name="action_taken" aria-describedby="action_taken-error">{{ old('action_taken') }}</textarea>
                        <div class="field-error" id="action_taken-error">@error('action_taken'){{ $message }}@enderror</div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <div class="field-label-row">
                            <label for="remarks">Remarks</label>
                        </div>
                        <textarea class="textarea" id="remarks" name="remarks" aria-describedby="remarks-error">{{ old('remarks') }}</textarea>
                        <div class="field-error" id="remarks-error">@error('remarks'){{ $message }}@enderror</div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <label>Attachment Preview</label>
                        <div class="preview-grid" id="attachmentPreview">
                            <div class="preview-item">No files selected</div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="actions" style="margin-top:16px;">
                <a class="btn btn-secondary" href="{{ route('reports.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Save Report</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
                const form = document.querySelector('[data-incident-form]');
                const attachmentInput = document.getElementById('attachments');
                const preview = document.getElementById('attachmentPreview');
                const requiredFields = ['date_of_incident', 'incident_type', 'severity', 'status', 'employee_id', 'department', 'location', 'item_name', 'description', 'reported_by_name'];
                const capitalizedFields = ['location', 'item_name', 'property_number', 'serial_number', 'department_other', 'description', 'action_taken', 'remarks'];
                const department = document.getElementById('department');
                const team = document.getElementById('team');
                const departmentOther = document.getElementById('department_other');
                const teamPanel = document.getElementById('departmentTeamPanel');
                const otherPanel = document.getElementById('departmentOtherPanel');
                const estimatedCost = document.getElementById('estimated_cost');
                let selectedAttachments = [];

                const titleCase = (value) => value
                    .toLowerCase()
                    .replace(/\b([a-z])/g, (match) => match.toUpperCase());

                const setError = (field, message) => {
                    const errorElement = document.getElementById(`${field}-error`);
                    const input = document.getElementById(field);

                    if (errorElement) {
                        errorElement.textContent = message || '';
                    }

                    if (input) {
                        input.setAttribute('aria-invalid', message ? 'true' : 'false');
                    }
                };

                const clearError = (field) => setError(field, '');

                const showDepartmentPanels = () => {
                    const value = department ? department.value : '';
                    const isOperations = value === 'OPERATIONS';
                    const isOthers = value === 'OTHERS';

                    if (teamPanel) {
                        teamPanel.hidden = !isOperations;
                        teamPanel.classList.toggle('is-visible', isOperations);
                    }

                    if (otherPanel) {
                        otherPanel.hidden = !isOthers;
                        otherPanel.classList.toggle('is-visible', isOthers);
                    }

                    if (team) {
                        team.required = isOperations;
                        team.disabled = !isOperations;

                        if (!isOperations) {
                            team.value = '';
                            clearError('team');
                        }
                    }

                    if (departmentOther) {
                        departmentOther.required = isOthers;
                        departmentOther.disabled = !isOthers;

                        if (!isOthers) {
                            departmentOther.value = '';
                            clearError('department_other');
                        }
                    }
                };

                const validateRequired = (field) => {
                    const input = document.getElementById(field);

                    if (!input || input.disabled) {
                        return true;
                    }

                    if (!input.value.trim()) {
                        setError(field, 'This field is required.');
                        return false;
                    }

                    clearError(field);
                    return true;
                };

                const validateDepartment = () => {
                    let valid = true;

                    if (!department || !department.value.trim()) {
                        setError('department', 'This field is required.');
                        valid = false;
                    } else {
                        clearError('department');
                    }

                    if (department && department.value === 'OPERATIONS') {
                        if (!team || !team.value.trim()) {
                            setError('team', 'This field is required.');
                            valid = false;
                        } else {
                            clearError('team');
                        }
                    }

                    if (department && department.value === 'OTHERS') {
                        if (!departmentOther || !departmentOther.value.trim()) {
                            setError('department_other', 'This field is required.');
                            valid = false;
                        } else {
                            clearError('department_other');
                        }
                    }

                    return valid;
                };

                const normalizeCurrency = (value) => {
                    const stripped = value.replace(/[^\d.]/g, '');
                    const parts = stripped.split('.');
                    const whole = parts[0] || '';
                    const fraction = (parts[1] || '').slice(0, 2);

                    return parts.length > 1 ? `${whole}.${fraction}` : whole;
                };

                capitalizedFields.forEach((field) => {
                    const input = document.getElementById(field);

                    if (!input) {
                        return;
                    }

                    input.addEventListener('input', () => {
                        const start = input.selectionStart;
                        const end = input.selectionEnd;
                        const transformed = titleCase(input.value);

                        if (input.value !== transformed) {
                            input.value = transformed;
                            if (start !== null && end !== null) {
                                input.setSelectionRange(start, end);
                            }
                        }

                        if (input.value.trim()) {
                            clearError(field);
                        }
                    });
                });

                department?.addEventListener('change', () => {
                    showDepartmentPanels();
                    validateDepartment();
                });

                team?.addEventListener('change', () => clearError('team'));
                departmentOther?.addEventListener('input', () => clearError('department_other'));

                requiredFields.forEach((field) => {
                    const input = document.getElementById(field);

                    if (!input) {
                        return;
                    }

                    input.addEventListener('blur', () => validateRequired(field));
                    input.addEventListener('input', () => {
                        if (input.value.trim()) {
                            clearError(field);
                        }
                    });
                });

                estimatedCost?.addEventListener('keydown', (event) => {
                    const controlKeys = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];

                    if (event.ctrlKey || event.metaKey || controlKeys.includes(event.key)) {
                        return;
                    }

                    if (!/^[\d.]$/.test(event.key)) {
                        event.preventDefault();
                        setError('estimated_cost', 'Numbers and a single decimal point only.');
                        return;
                    }

                    if (event.key === '.' && estimatedCost.value.includes('.')) {
                        event.preventDefault();
                        setError('estimated_cost', 'Only one decimal point is allowed.');
                    }
                });

                estimatedCost?.addEventListener('paste', (event) => {
                    const pasted = (event.clipboardData || window.clipboardData).getData('text');

                    if (!/^\d*(\.\d{1,2})?$/.test(pasted.trim())) {
                        event.preventDefault();
                        setError('estimated_cost', 'Numbers and up to 2 decimal places only.');
                    }
                });

                estimatedCost?.addEventListener('input', () => {
                    const sanitized = normalizeCurrency(estimatedCost.value);

                    if (estimatedCost.value !== sanitized) {
                        estimatedCost.value = sanitized;
                        setError('estimated_cost', 'Numbers and up to 2 decimal places only.');
                        return;
                    }

                    clearError('estimated_cost');
                });

                const renderAttachmentPreview = () => {
                    preview.innerHTML = '';

                    if (!selectedAttachments.length) {
                        preview.innerHTML = '<div class="preview-item">No files selected</div>';
                        return;
                    }

                    selectedAttachments.forEach((file, index) => {
                        const item = document.createElement('div');
                        item.className = 'preview-item';

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'preview-remove';
                        removeButton.dataset.attachmentIndex = index;
                        removeButton.setAttribute('aria-label', `Remove ${file.name}`);
                        removeButton.title = 'Remove attachment';
                        removeButton.textContent = '\u00d7';
                        item.appendChild(removeButton);

                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.alt = file.name;
                            const reader = new FileReader();
                            reader.onload = (event) => { img.src = event.target.result; };
                            reader.readAsDataURL(file);
                            item.appendChild(img);
                        } else {
                            item.innerHTML = '<strong>PDF</strong><span>' + file.name + '</span>';
                        }

                        preview.appendChild(item);
                    });
                };

                attachmentInput?.addEventListener('change', () => {
                    selectedAttachments = Array.from(attachmentInput.files || []);
                    renderAttachmentPreview();
                });

                preview?.addEventListener('click', (event) => {
                    const removeButton = event.target.closest('[data-attachment-index]');

                    if (!removeButton) {
                        return;
                    }

                    selectedAttachments.splice(Number(removeButton.dataset.attachmentIndex), 1);

                    const dataTransfer = new DataTransfer();
                    selectedAttachments.forEach((file) => dataTransfer.items.add(file));
                    attachmentInput.files = dataTransfer.files;
                    renderAttachmentPreview();
                });

                showDepartmentPanels();

                form?.addEventListener('submit', (event) => {
                    let firstInvalidField = null;

                    showDepartmentPanels();

                    requiredFields.forEach((field) => {
                        const input = document.getElementById(field);

                        if (!input || input.disabled) {
                            return;
                        }

                        if (!validateRequired(field) && !firstInvalidField) {
                            firstInvalidField = input;
                        }
                    });

                    if (!validateDepartment() && !firstInvalidField) {
                        firstInvalidField = department || firstInvalidField;
                    }

                    if (estimatedCost && estimatedCost.value.trim() && !/^\d+(\.\d{1,2})?$/.test(estimatedCost.value.trim())) {
                        setError('estimated_cost', 'Numbers and up to 2 decimal places only.');
                        if (!firstInvalidField) {
                            firstInvalidField = estimatedCost;
                        }
                    }

                    if (firstInvalidField) {
                        event.preventDefault();
                        firstInvalidField.focus();
                    }
                });

                showDepartmentPanels();
            })();
        </script>
        @endpush

