@extends('layouts.app')

@section('title', 'Create Incident Report')
@section('page-name', 'Create Incident Report')

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
        padding: 18px;
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

    .field label {
        font-size: 13px;
        font-weight: 700;
        color: #122033;
    }

    .input,
    .select,
    .textarea {
        width: 100%;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 12px 14px;
        background: #fff;
        color: #122033;
        font: inherit;
    }

    .textarea {
        min-height: 120px;
        resize: vertical;
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

    .preview-item img {
        width: 100%;
        height: 76px;
        object-fit: cover;
        border-radius: 10px;
    }

    @media (max-width: 860px) {
        .grid-2 { grid-template-columns: 1fr; }
        .actions { justify-content: stretch; }
        .actions .btn { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="incident-form-shell">
    <div class="panel-card">
        <div class="panel-head">
            <div>
                <h1 class="panel-title">Create Incident Report</h1>
                <div class="panel-sub">Record a new incident with attachments, action notes, and the employee involved.</div>
            </div>
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid-2">
                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Incident Info</h2>
                    <div class="grid-2">
                        <div class="field">
                            <label for="date_of_incident">Date of Incident</label>
                            <input class="input" id="date_of_incident" name="date_of_incident" type="date" value="{{ old('date_of_incident') }}" required>
                        </div>
                        <div class="field">
                            <label for="incident_type">Incident Type</label>
                            <select class="select" id="incident_type" name="incident_type" required>
                                <option value="">Select type</option>
                                @foreach ($incidentTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('incident_type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="severity">Severity</label>
                            <select class="select" id="severity" name="severity" required>
                                <option value="">Select severity</option>
                                @foreach ($severityLevels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('severity') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="status">Status</label>
                            <select class="select" id="status" name="status" required>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'pending') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Employee & Location</h2>
                    <div class="grid-2">
                        <div class="field">
                            <label for="employee_id">Employee</label>
                            <select class="select" id="employee_id" name="employee_id" required>
                                <option value="">Select employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->full_name }}{{ $employee->employee_number ? ' • ' . $employee->employee_number : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label for="department">Department</label>
                            <input class="input" id="department" name="department" type="text" value="{{ old('department') }}" list="department-list" required>
                            <datalist id="department-list">
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="field">
                            <label for="location">Location</label>
                            <input class="input" id="location" name="location" type="text" value="{{ old('location') }}" required>
                        </div>
                        <div class="field">
                            <label for="reported_by">Reported By</label>
                            <input class="input" id="reported_by" type="text" value="{{ auth()->user()->name ?? 'Current user' }}" disabled>
                        </div>
                    </div>
                </section>
            </div>

            <div class="grid-2" style="margin-top:14px;">
                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Item Details</h2>
                    <div class="grid-2">
                        <div class="field">
                            <label for="item_name">Item Name</label>
                            <input class="input" id="item_name" name="item_name" type="text" value="{{ old('item_name') }}" required>
                        </div>
                        <div class="field">
                            <label for="property_serial_no">Property / Serial No.</label>
                            <input class="input" id="property_serial_no" name="property_serial_no" type="text" value="{{ old('property_serial_no') }}">
                        </div>
                        <div class="field">
                            <label for="estimated_cost">Estimated Cost</label>
                            <input class="input" id="estimated_cost" name="estimated_cost" type="number" min="0" step="0.01" value="{{ old('estimated_cost') }}">
                        </div>
                        <div class="field">
                            <label for="attachments">Attachments</label>
                            <input class="input" id="attachments" name="attachments[]" type="file" accept="image/jpeg,image/png,application/pdf" multiple>
                        </div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <label for="description">Description</label>
                        <textarea class="textarea" id="description" name="description" required>{{ old('description') }}</textarea>
                    </div>
                </section>

                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Status & Action</h2>
                    <div class="field">
                        <label for="action_taken">Action Taken</label>
                        <textarea class="textarea" id="action_taken" name="action_taken">{{ old('action_taken') }}</textarea>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <label for="remarks">Remarks</label>
                        <textarea class="textarea" id="remarks" name="remarks">{{ old('remarks') }}</textarea>
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
                <button class="btn btn-primary" type="submit">Save Incident Report</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const attachmentInput = document.getElementById('attachments');
    const preview = document.getElementById('attachmentPreview');

    attachmentInput?.addEventListener('change', () => {
        const files = Array.from(attachmentInput.files || []);
        preview.innerHTML = '';

        if (!files.length) {
            preview.innerHTML = '<div class="preview-item">No files selected</div>';
            return;
        }

        files.forEach((file) => {
            const item = document.createElement('div');
            item.className = 'preview-item';

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
    });
</script>
@endpush
