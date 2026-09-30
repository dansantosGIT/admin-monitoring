@extends('layouts.app')

@section('title', 'Edit Report')
@section('page-name', 'Edit Report')

{{-- Broken before: edit mode still required and trusted factual fields that should be locked after save. Fixed here with disabled locked fields, inline validation for mutable fields, and a clearer admin layout. --}}

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
        align-items: start;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-self: start;
    }

    .field-label-row {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 10px;
    }

    .field-label-row label {
        flex: 1 1 auto;
    }

    .field-label-row .lock-badge {
        margin-left: auto;
    }

    .field-label-row .field-help {
        flex: 1 0 100%;
        margin-top: -2px;
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

    .input:disabled,
    .select:disabled,
    .textarea:disabled {
        background: #f3f6fa;
        color: #607086;
        cursor: not-allowed;
        border-style: dashed;
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

    .field-help {
        font-size: 12px;
        color: #607086;
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

    .preview-grid,
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }

    .preview-item,
    .gallery-item {
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

    .preview-item img,
    .gallery-item img {
        width: 100%;
        height: 76px;
        object-fit: cover;
        border-radius: 10px;
    }

    @media (max-width: 860px) {
        .grid-2 { grid-template-columns: 1fr; }
        .actions { justify-content: stretch; }
        .actions .btn { width: 100%; }
        .field-label-row { align-items: flex-start; flex-direction: column; }
    }
</style>
@endpush

@section('content')
@php
    $currentAttachments = $report->attachments ?? collect();
@endphp

<div class="incident-form-shell">
    <div class="panel-card">
        <div class="panel-head">
            <div>
                <h1 class="panel-title">Edit Report</h1>
                <div class="panel-sub">Update incident details, review status, or append new attachments.</div>
                <div class="ui-required-note">* Required field</div>
            </div>
            <a class="btn btn-secondary" href="{{ route('reports.show', $report) }}">Back</a>
        </div>

        <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data" data-incident-form novalidate>
            @csrf
            @method('PUT')

            <div class="grid-2">
                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Incident Info</h2>
                    <div class="grid-2">
                        <div class="field">
                            <div class="field-label-row">
                                <label for="date_of_incident">Date of Incident <span class="ui-required">*</span></label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <input class="input" id="date_of_incident" name="date_of_incident" type="date" value="{{ old('date_of_incident', optional($report->date_of_incident)->format('Y-m-d')) }}" disabled title="Locked after saving">
                            <div class="field-error" id="date_of_incident-error"></div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="incident_type">Incident Type <span class="ui-required">*</span></label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <select class="select" id="incident_type" name="incident_type" disabled title="Locked after saving">
                                <option value="">Select type</option>
                                @foreach ($incidentTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('incident_type', $report->incident_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="incident_type-error"></div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="severity">Severity <span class="ui-required">*</span></label>
                            </div>
                            <select class="select" id="severity" name="severity" required aria-required="true" aria-describedby="severity-error">
                                <option value="">Select severity</option>
                                @foreach ($severityLevels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('severity', $report->severity) === $value)>{{ $label }}</option>
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
                                    <option value="{{ $value }}" @selected(old('status', $report->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="status-error">@error('status'){{ $message }}@enderror</div>
                        </div>
                        <div class="field field-span-2">
                            <div class="field-label-row">
                                <label for="location">Location <span class="ui-required">*</span></label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <input class="input" id="location" name="location" type="text" value="{{ old('location', $report->location) }}" placeholder="Where did the incident happen?" disabled title="Locked after saving">
                            <div class="field-error" id="location-error"></div>
                        </div>
                    </div>
                </section>

                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Employee</h2>
                    <div class="grid-2">
                        <div class="field">
                            <div class="field-label-row">
                                <label for="employee_id">Employee <span class="ui-required">*</span></label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <select class="select" id="employee_id" name="employee_id" disabled title="Locked after saving">
                                <option value="">Select employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected((string) old('employee_id', $report->employee_id) === (string) $employee->id)>{{ $employee->full_name }}{{ $employee->employee_number ? ' • ' . $employee->employee_number : '' }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="employee_id-error"></div>
                        </div>

                        <div class="field field-span-2">
                            <div class="field-label-row">
                                <label for="department">Department <span class="ui-required">*</span></label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <select class="select" id="department" name="department" disabled title="Locked after saving">
                                @foreach ($departmentOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('department', $report->department) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" id="department-error"></div>
                        </div>
                        @if (old('department', $report->department) === 'OPERATIONS')
                            <div class="field field-span-2">
                                <div class="field-label-row">
                                    <label for="team">Team <span class="ui-required">*</span></label>
                                    <span class="lock-badge" title="Locked after saving">
                                        <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        Locked after saving
                                    </span>
                                </div>
                                <select class="select" id="team" name="team" disabled title="Locked after saving">
                                    @foreach ($teamOptions as $value => $label)
                                        <option value="{{ $value }}" @selected(old('team', $report->team) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error" id="team-error"></div>
                            </div>
                        @endif

                        @if (old('department', $report->department) === 'OTHERS')
                            <div class="field field-span-2">
                                <div class="field-label-row">
                                    <label for="department_other">Specify Office/Unit <span class="ui-required">*</span></label>
                                    <span class="lock-badge" title="Locked after saving">
                                        <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        Locked after saving
                                    </span>
                                </div>
                                <input class="input" id="department_other" name="department_other" type="text" value="{{ old('department_other', $report->department_other) }}" disabled title="Locked after saving">
                                <div class="field-error" id="department_other-error"></div>
                            </div>
                        @endif
                        <div class="field">
                            <div class="field-label-row">
                                <label for="reported_by">Reported By</label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <div class="input identity-display" id="reported_by" role="textbox" aria-readonly="true" title="Locked after saving"><x-masked-email :email="optional($report->reportedBy)->email ?? auth()->user()->email ?? auth()->user()->name ?? 'Current user'" /></div>
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
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <input class="input" id="item_name" name="item_name" type="text" value="{{ old('item_name', $report->item_name) }}" disabled title="Locked after saving">
                            <div class="field-error" id="item_name-error"></div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="property_number">Property Number</label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <input class="input" id="property_number" name="property_number" type="text" value="{{ old('property_number', $report->property_number) }}" disabled title="Locked after saving">
                            <div class="field-error" id="property_number-error"></div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="serial_number">Serial Number</label>
                                <span class="lock-badge" title="Locked after saving">
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    Locked after saving
                                </span>
                            </div>
                            <input class="input" id="serial_number" name="serial_number" type="text" value="{{ old('serial_number', $report->serial_number) }}" disabled title="Locked after saving">
                            <div class="field-error" id="serial_number-error"></div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="estimated_cost">Estimated Cost</label>
                            </div>
                            <input class="input" id="estimated_cost" name="estimated_cost" type="text" inputmode="decimal" autocomplete="off" placeholder="0.00" value="{{ old('estimated_cost', $report->estimated_cost) }}" aria-describedby="estimated_cost-error">
                            <div class="field-help">Numbers only, up to 2 decimal places.</div>
                            <div class="field-error" id="estimated_cost-error">@error('estimated_cost'){{ $message }}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="field-label-row">
                                <label for="attachments">Add Attachments</label>
                            </div>
                            <input class="input" id="attachments" name="attachments[]" type="file" accept="image/jpeg,image/png,application/pdf" multiple>
                            <div class="field-error" id="attachments-error">@error('attachments'){{ $message }}@enderror</div>
                        </div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <div class="field-label-row">
                            <label for="description">Description <span class="ui-required">*</span></label>
                            <span class="lock-badge" title="Locked after saving">
                                <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4.5 7V5.5a3.5 3.5 0 1 1 7 0V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="3" y="7" width="10" height="7" rx="1.5" stroke="currentColor" stroke-width="1.5"/><path d="M8 9.25v2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                Locked after saving
                            </span>
                        </div>
                        <textarea class="textarea" id="description" name="description" disabled title="Locked after saving">{{ old('description', $report->description) }}</textarea>
                        <div class="field-error" id="description-error"></div>
                    </div>
                </section>

                <section class="panel-card" style="box-shadow:none; border-color:#edf2f7;">
                    <h2 class="section-title">Status & Action</h2>
                    <div class="field">
                        <div class="field-label-row">
                            <label for="action_taken">Action Taken</label>
                        </div>
                        <textarea class="textarea" id="action_taken" name="action_taken" aria-describedby="action_taken-error">{{ old('action_taken', $report->action_taken) }}</textarea>
                        <div class="field-error" id="action_taken-error">@error('action_taken'){{ $message }}@enderror</div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <div class="field-label-row">
                            <label for="remarks">Remarks</label>
                        </div>
                        <textarea class="textarea" id="remarks" name="remarks" aria-describedby="remarks-error">{{ old('remarks', $report->remarks) }}</textarea>
                        <div class="field-error" id="remarks-error">@error('remarks'){{ $message }}@enderror</div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <label>Current Attachments</label>
                        <div class="gallery-grid">
                            @forelse ($currentAttachments as $attachment)
                                <a class="gallery-item" href="/storage/{{ $attachment->file_path }}" target="_blank" rel="noopener">
                                    @if (str_contains(strtolower($attachment->original_filename), '.pdf'))
                                        <strong>PDF</strong>
                                    @else
                                        <img src="/storage/{{ $attachment->file_path }}" alt="{{ $attachment->original_filename }}">
                                    @endif
                                    <span>{{ $attachment->original_filename }}</span>
                                </a>
                            @empty
                                <div class="gallery-item">No attachments yet</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="field" style="margin-top:12px;">
                        <label>New Attachment Preview</label>
                        <div class="preview-grid" id="attachmentPreview">
                            <div class="preview-item">No files selected</div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="actions" style="margin-top:16px;">
                <a class="btn btn-secondary" href="{{ route('reports.show', $report) }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Update Report</button>
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
        const moneyInput = document.getElementById('estimated_cost');
        const requiredFields = ['date_of_incident', 'incident_type', 'severity', 'status', 'employee_id', 'department', 'location', 'item_name', 'description'];
        const capitalizedFields = ['department', 'location', 'item_name', 'property_serial_no', 'description', 'action_taken', 'remarks'];

        const titleCase = (value) => value
            .toLowerCase()
            .replace(/\b([a-z])/g, (match) => match.toUpperCase());

        const errorNode = (field) => document.getElementById(`${field}-error`);

        const setError = (field, message) => {
            const node = errorNode(field);
            const input = document.getElementById(field);

            if (node) {
                node.textContent = message || '';
            }

            if (input) {
                input.setAttribute('aria-invalid', message ? 'true' : 'false');
            }
        };

        const clearError = (field) => setError(field, '');

        const validateRequired = (field) => {
            const input = document.getElementById(field);

            if (!input || input.disabled) {
                return true;
            }

            const value = input.value.trim();
            const isSelect = input.tagName === 'SELECT';
            const valid = isSelect ? value !== '' : value.length > 0;

            if (!valid) {
                setError(field, 'This field is required.');
                return false;
            }

            clearError(field);
            return true;
        };

        const sanitizeMoney = (value) => {
            const stripped = value.replace(/[^\d.]/g, '');
            const [whole = '', fraction = ''] = stripped.split('.');
            const cleanedFraction = fraction.slice(0, 2);

            return stripped.includes('.') ? `${whole}.${cleanedFraction}` : whole;
        };

        capitalizedFields.forEach((field) => {
            const input = document.getElementById(field);

            if (!input) {
                return;
            }

            input.addEventListener('input', () => {
                const selectionStart = input.selectionStart;
                const selectionEnd = input.selectionEnd;
                const transformed = titleCase(input.value);

                if (input.value !== transformed) {
                    input.value = transformed;
                    if (selectionStart !== null && selectionEnd !== null) {
                        input.setSelectionRange(selectionStart, selectionEnd);
                    }
                }

                if (input.value.trim()) {
                    clearError(field);
                }
            });
        });

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

        if (moneyInput) {
            const moneyField = 'estimated_cost';

            moneyInput.addEventListener('keydown', (event) => {
                const controlKeys = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];

                if (event.ctrlKey || event.metaKey || controlKeys.includes(event.key)) {
                    return;
                }

                if (!/^[\d.]$/.test(event.key)) {
                    event.preventDefault();
                    setError(moneyField, 'Numbers and a single decimal point only.');
                    return;
                }

                if (event.key === '.' && moneyInput.value.includes('.')) {
                    event.preventDefault();
                    setError(moneyField, 'Only one decimal point is allowed.');
                }
            });

            moneyInput.addEventListener('paste', (event) => {
                const pasted = (event.clipboardData || window.clipboardData).getData('text');

                if (!/^\d*(\.\d{1,2})?$/.test(pasted.trim())) {
                    event.preventDefault();
                    setError(moneyField, 'Numbers and up to 2 decimal places only.');
                }
            });

            moneyInput.addEventListener('input', () => {
                const sanitized = sanitizeMoney(moneyInput.value);

                if (moneyInput.value !== sanitized) {
                    moneyInput.value = sanitized;
                    setError(moneyField, 'Numbers and up to 2 decimal places only.');
                    return;
                }

                clearError(moneyField);
            });
        }

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

        form?.addEventListener('submit', (event) => {
            let firstInvalidField = null;

            requiredFields.forEach((field) => {
                const input = document.getElementById(field);

                if (!input || input.disabled) {
                    return;
                }

                if (!validateRequired(field) && !firstInvalidField) {
                    firstInvalidField = input;
                }
            });

            if (moneyInput && moneyInput.value.trim() && !/^\d+(\.\d{1,2})?$/.test(moneyInput.value.trim())) {
                setError('estimated_cost', 'Numbers and up to 2 decimal places only.');
                if (!firstInvalidField) {
                    firstInvalidField = moneyInput;
                }
            }

            if (firstInvalidField) {
                event.preventDefault();
                firstInvalidField.focus();
            }
        });
    })();
</script>
@endpush
