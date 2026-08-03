@extends('layouts.app')

@section('title', 'Add Employee')
@section('page-name', 'Create Employee')

@push('styles')
<style>
    :root {
        --red: #0f62fe;
        --red-dark: #0b4fd8;
        --red-light: #eaf2ff;
        --white: #ffffff;
        --panel: rgba(255,255,255,0.92);
        --gray-50: #F6F6F6;
        --gray-100: #EEEEEE;
        --gray-200: #DDDDDD;
        --gray-400: #999999;
        --gray-600: #555555;
        --gray-800: #222222;
        --green: #1A7A4A;
        --green-light: #E6F4ED;
        --amber: #A35C00;
        --amber-light: #FEF3E2;
        --blue: #1A4FA3;
        --blue-light: #E8EFFE;
        --font: 'Inter', Arial, sans-serif;
        --sidebar-w: 260px;
        --topbar-h: 72px;
        --shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
    }

    .main {
        width: 100%;
        padding-top: var(--topbar-h);
        flex: 1;
        background:
            radial-gradient(circle at top, rgba(192, 23, 43, 0.04), transparent 26%),
            linear-gradient(180deg, #f5f7fb 0%, #eef3f9 100%);
    }
    .content {
        padding: 22px 24px 32px;
        max-width: 1440px;
        width: 100%;
        margin: 0 auto;
    }

    /* PAGE HEADER */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 18px;
        padding: 18px 18px 16px;
        border: 1px solid rgba(192,23,43,0.10);
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,247,247,0.98) 100%);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }
    .page-header-left { display: flex; align-items: center; gap: 12px; }
    .page-copy { max-width: 760px; }
    .page-badges { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
    .required-note { margin-top: 10px; font-size: 12px; color: var(--gray-600); }
    .badge-chip {
        display:inline-flex; align-items:center; gap:6px;
        border:1px solid #f1d8dd; border-radius:999px;
        background: rgba(255,255,255,0.96); color: var(--gray-600);
        padding: 7px 10px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em;
        box-shadow: 0 8px 18px rgba(192,23,43,0.06);
    }
    .badge-chip strong { color: var(--red-dark); }
    .back-btn {
        width: 34px; height: 34px;
        border-radius: 8px;
        border: 1px solid var(--gray-100);
        background: var(--white);
        display: flex; align-items: center; justify-content: center;
        color: var(--gray-600);
        text-decoration: none;
        transition: background 0.15s;
    }
    .back-btn:hover { background: var(--gray-50); }
    .back-btn svg { width: 16px; height: 16px; }
    .page-title { font-size: 20px; font-weight: 800; color: var(--gray-800); letter-spacing: -0.02em; }
    .page-sub { font-size: 13px; color: var(--gray-500); margin-top: 4px; line-height: 1.45; }

    .btn-save {
        display: flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, var(--red) 0%, #d83a52 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: var(--font);
        box-shadow: 0 10px 18px rgba(192,23,43,0.18);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 14px 24px rgba(192,23,43,0.24); }
    .btn-save svg { width: 14px; height: 14px; }

    .btn-cancel {
        display: flex; align-items: center; gap: 6px;
        background: var(--white);
        color: var(--gray-800);
        border: 1px solid #e4e8ef;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: var(--font);
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, transform 0.15s;
    }
    .btn-cancel:hover { background: #f8fafc; border-color: #cfd8e3; transform: translateY(-1px); }

    /* LAYOUT */
    .form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 18px;
        align-items: start;
    }

    /* SECTION PANELS */
    .form-section {
        background: #ffffff;
        border: 1px solid #edf1f6;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 14px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }
    .form-section:last-child { margin-bottom: 0; }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid #edf1f6;
        background: linear-gradient(135deg, #fff 0%, #fffafc 100%);
    }
    .section-title { font-size: 14px; font-weight: 800; color: var(--gray-800); }
    .section-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
    .section-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--red-light);
        color: var(--red);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .section-icon svg { width: 15px; height: 15px; }
    .section-title { font-size: 13px; font-weight: 700; color: var(--gray-800); }
    .section-sub { font-size: 11px; color: var(--gray-400); margin-top: 1px; }

    .section-body { padding: 16px 18px; }

    /* FIELD GRID */
    .field-row { display: grid; gap: 10px; margin-bottom: 10px; }
    .field-row:last-child { margin-bottom: 0; }
    .col-3 { grid-template-columns: minmax(0, 1.15fr) repeat(2, minmax(0, 1fr)); }
    .col-2 { grid-template-columns: repeat(2, 1fr); }
    .col-1 { grid-template-columns: 1fr; }
    .col-2-1 { grid-template-columns: 2fr 1fr; }
    .col-1-2 { grid-template-columns: 1fr 2fr; }

    /* FIELD */
    .field { display: flex; flex-direction: column; gap: 5px; }
    .field-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .field-label .req { color: var(--red); margin-left: 2px; }

    .field-input,
    .field-select,
    .field-textarea {
        width: 100%;
        border: 1px solid #e3e8ef;
        border-radius: 10px;
        padding: 11px 13px;
        font-size: 14px;
        font-family: var(--font);
        color: var(--gray-800);
        background: linear-gradient(180deg, #fff 0%, #fbfdff 100%);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
        height: 42px;
    }
    .field-textarea { height: auto; min-height: 96px; resize: vertical; }
    .field-input::placeholder,
    .field-textarea::placeholder { color: var(--gray-400); font-size: 13px; opacity: 1; }
    .field-input:hover,
    .field-select:hover,
    .field-textarea:hover { border-color: #cfd8e5; }
    .field-input:focus,
    .field-select:focus,
    .field-textarea:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 4px rgba(192, 23, 43, 0.10);
        transform: translateY(-1px);
    }

    .field.has-error .field-input,
    .field.has-error .field-select,
    .field.has-error .field-textarea {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.08);
    }

    .field-error {
        min-height: 16px;
        font-size: 12px;
        line-height: 1.35;
        color: #dc2626;
    }

    /* ERROR BLOCK */
    .error-block {
        background: #FFF5F5;
        border: 1px solid #FBC7CC;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 18px;
        font-size: 13px;
        color: var(--red-dark);
    }
    .error-block ul { margin: 8px 0 0 16px; }

    /* SIDEBAR PANELS */
    .sidebar-panel {
        background: #ffffff;
        border: 1px solid #edf1f6;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 12px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }
    .sidebar-panel:last-child { margin-bottom: 0; }

    .photo-area { padding: 20px; text-align: center; }
    .photo-placeholder {
        width: 110px; height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff5f6 0%, #f4f7fb 100%);
        margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        color: var(--red);
        border: 2px dashed #f0cfd5;
        box-shadow: inset 0 0 0 1px rgba(192,23,43,0.05);
    }
    .photo-placeholder svg { width: 32px; height: 32px; }
    .photo-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        cursor: pointer;
        transition: background 0.15s;
    }
    .photo-label:hover { background: var(--gray-100); }
    .photo-label svg { width: 13px; height: 13px; }
    .photo-hint { font-size: 11px; color: var(--gray-400); margin-top: 8px; }

    .tips-header {
        padding: 12px 14px;
        border-bottom: 1px solid #edf1f6;
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-800);
        background: linear-gradient(135deg, #fff 0%, #fffafc 100%);
    }
    .tips-body { padding: 14px 16px; display: flex; flex-direction: column; gap: 8px; }
    .tip-item { display: flex; align-items: flex-start; gap: 8px; }
    .tip-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--red);
        flex-shrink: 0;
        margin-top: 5px;
    }
    .tip-text { font-size: 12px; color: var(--gray-600); line-height: 1.5; }

    .notice-banner {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 10px 12px;
        margin: 10px 0 12px;
        border-radius: 12px;
        border: 1px solid #d7e3ef;
        background: #f4f8fc;
        color: #44556b;
        font-size: 12px;
        line-height: 1.45;
    }
    .notice-banner svg {
        width: 16px;
        height: 16px;
        color: #58708c;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 4px;
    }

    /* STEP INDICATOR */
    .steps {
        display: flex;
        gap: 0;
        margin-bottom: 20px;
        background: var(--white);
        border: 1px solid var(--gray-100);
        border-radius: 10px;
        overflow: hidden;
    }
    .step {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 11px 14px;
        font-size: 12px;
        color: var(--gray-400);
        border-right: 1px solid var(--gray-100);
        font-weight: 500;
    }
    .step:last-child { border-right: none; }
    .step.active { color: var(--red); font-weight: 700; background: var(--red-light); }
    .step.done { color: var(--green); }
    .step-num {
        width: 20px; height: 20px;
        border-radius: 50%;
        background: var(--gray-100);
        color: var(--gray-400);
        font-size: 11px;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .step.active .step-num { background: var(--red); color: white; }
    .step.done .step-num { background: var(--green); color: white; }
</style>
@endpush

@section('content')
<main class="main">
<div class="content">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="page-header-left">
            <a href="{{ route('employees.index') }}" class="back-btn" aria-label="Back to employees">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
            <div class="page-copy">
                <div class="page-title">Create Employee Profile</div>
                <div class="page-sub">Capture the key PDS, employment, and contact details for a clean, audit-ready onboarding record.</div>
                <div class="page-badges">
                    <span class="badge-chip"><strong>HR</strong> onboarding</span>
                    <span class="badge-chip"><strong>PDS</strong> ready</span>
                    <span class="badge-chip"><strong>Modern</strong> dashboard style</span>
                </div>
                <div class="required-note">* Required field</div>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;">
            <a href="{{ route('employees.index') }}" class="btn-cancel">Cancel</a>
            <button form="employeeForm" type="submit" class="btn-save">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Save Employee
            </button>
        </div>
    </div>

    {{-- ERROR BLOCK --}}
    @if($errors->any())
    <div class="error-block">
        <strong>Please fix the following errors:</strong>
        <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="employeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data"
        data-location-form="present"
        data-old-province="{{ old('present_province') }}"
        data-old-city="{{ old('present_city') }}"
        data-old-barangay="{{ old('present_barangay') }}"
        data-old-zip="{{ old('present_zip') }}">
        @csrf
        <div class="form-layout">

            {{-- LEFT: MAIN FORM --}}
            <div>

                {{-- PERSONAL INFORMATION --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Personal Information</div>
                            <div class="section-sub">Basic personal details</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="field-row col-3">
                            <div class="field @error('last_name') has-error @enderror">
                                <label class="field-label">Last Name <span class="req">*</span></label>
                                <input class="field-input" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" data-mask="name" maxlength="150" autocomplete="family-name" required>
                                <div class="field-error">@error('last_name'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('first_name') has-error @enderror">
                                <label class="field-label">First Name <span class="req">*</span></label>
                                <input class="field-input" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Juan" data-mask="name" maxlength="150" autocomplete="given-name" required>
                                <div class="field-error">@error('first_name'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('middle_name') has-error @enderror">
                                <label class="field-label">Middle Name</label>
                                <input class="field-input" name="middle_name" value="{{ old('middle_name') }}" placeholder="e.g. Santos" data-mask="name" maxlength="50" autocomplete="additional-name">
                                <div class="field-error">@error('middle_name'){{ $message }}@enderror</div>
                            </div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field @error('suffix') has-error @enderror">
                                <label class="field-label">Suffix</label>
                                <input class="field-input" name="suffix" value="{{ old('suffix') }}" placeholder="e.g. Jr., Sr., III" data-mask="name" maxlength="20">
                                <div class="field-error">@error('suffix'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('maiden_name') has-error @enderror">
                                <label class="field-label">Maiden Name</label>
                                <input class="field-input" name="maiden_name" value="{{ old('maiden_name') }}" placeholder="e.g. Dela Cruz" data-mask="name" maxlength="150">
                                <div class="field-error">@error('maiden_name'){{ $message }}@enderror</div>
                            </div>
                            <div class="field">
                                <label class="field-label">Sex</label>
                                <select class="field-select" name="sex">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field @error('birthdate') has-error @enderror">
                                <label class="field-label">Birthdate</label>
                                <input class="field-input" type="date" name="birthdate" value="{{ old('birthdate') }}" max="{{ now()->toDateString() }}" data-mask="birthdate">
                                <div class="field-error">@error('birthdate'){{ $message }}@enderror</div>
                            </div>
                            <div class="field">
                                <label class="field-label">Place of Birth</label>
                                <input class="field-input" name="place_of_birth" value="{{ old('place_of_birth') }}" placeholder="City / Municipality">
                            </div>
                            <div class="field">
                                <label class="field-label">Civil Status</label>
                                <select class="field-select" name="civil_status">
                                    <option value="">Select</option>
                                    <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTACT & ADDRESS --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Contact & Address</div>
                            <div class="section-sub">Where we can reach you</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="field-row col-1">
                            <div class="field">
                                <label class="field-label">Present Address (House No. / Street)</label>
                                <input class="field-input" name="present_address" value="{{ old('present_address') }}" placeholder="House No., Street">
                            </div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field">
                                <label class="field-label">Province</label>
                                <select class="field-select" name="present_province" data-location="province">
                                    <option value="">Select province</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field-label">City / Municipality</label>
                                <select class="field-select" name="present_city" data-location="city" disabled>
                                    <option value="">Select city/municipality</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field-label">Barangay</label>
                                <select class="field-select" name="present_barangay" data-location="barangay" disabled>
                                    <option value="">Select barangay</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-row col-2">
                            <div class="field @error('present_zip') has-error @enderror">
                                <label class="field-label">ZIP Code</label>
                                <input class="field-input" name="present_zip" data-location="zip" data-mask="zip" value="{{ old('present_zip') }}" placeholder="e.g. 1234" inputmode="numeric" maxlength="4" autocomplete="postal-code">
                                <div class="field-error">@error('present_zip'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('mobile') has-error @enderror">
                                <label class="field-label">Mobile Number</label>
                                <input class="field-input" name="mobile" value="{{ old('mobile') }}" placeholder="e.g. 09XX XXX XXXX" data-mask="mobile" inputmode="numeric" maxlength="13" autocomplete="tel">
                                <div class="field-error">@error('mobile'){{ $message }}@enderror</div>
                            </div>
                        </div>
                        <div class="field-row col-1">
                            <div class="field">
                                <label class="field-label">Permanent Address <span style="font-weight:400;color:var(--gray-400)">(if different from present)</span></label>
                                <input class="field-input" name="permanent_address" value="{{ old('permanent_address') }}" placeholder="Leave blank if same as present address">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GOVERNMENT IDs --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Government IDs</div>
                            <div class="section-sub">Official identification numbers</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="notice-banner">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 11v5"/><path d="M12 7h.01"/><circle cx="12" cy="12" r="9"/></svg>
                            <div>This information is confidential and used for internal HR records only.</div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field @error('sss') has-error @enderror">
                                <label class="field-label">SSS No.</label>
                                <input class="field-input" name="sss" value="{{ old('sss') }}" placeholder="e.g. 34-1234567-8" data-mask="sss" inputmode="numeric" maxlength="12" autocomplete="off">
                                <div class="field-error">@error('sss'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('tin') has-error @enderror">
                                <label class="field-label">TIN</label>
                                <input class="field-input" name="tin" value="{{ old('tin') }}" placeholder="e.g. 123-456-789" data-mask="tin" inputmode="numeric" maxlength="11" autocomplete="off">
                                <div class="field-error">@error('tin'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('philhealth') has-error @enderror">
                                <label class="field-label">PhilHealth</label>
                                <input class="field-input" name="philhealth" value="{{ old('philhealth') }}" placeholder="e.g. 12-345678901-2" data-mask="philhealth" inputmode="numeric" maxlength="14" autocomplete="off">
                                <div class="field-error">@error('philhealth'){{ $message }}@enderror</div>
                            </div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field @error('pagibig') has-error @enderror">
                                <label class="field-label">Pag-IBIG</label>
                                <input class="field-input" name="pagibig" value="{{ old('pagibig') }}" placeholder="e.g. 1234-5678-9012" data-mask="pagibig" inputmode="numeric" maxlength="14" autocomplete="off">
                                <div class="field-error">@error('pagibig'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('gsis') has-error @enderror">
                                <label class="field-label">GSIS</label>
                                <input class="field-input" name="gsis" value="{{ old('gsis') }}" placeholder="e.g. 1234567890" data-mask="gsis" inputmode="numeric" maxlength="20" autocomplete="off">
                                <div class="field-error">@error('gsis'){{ $message }}@enderror</div>
                            </div>
                            <div class="field @error('email') has-error @enderror">
                                <label class="field-label">Email Address</label>
                                <input class="field-input" type="email" name="email" value="{{ old('email') }}" placeholder="e.g. juan@email.com" data-mask="email" inputmode="email" maxlength="200" autocomplete="email">
                                <div class="field-error">@error('email'){{ $message }}@enderror</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- EMPLOYMENT DETAILS --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Employment Details</div>
                            <div class="section-sub">Role and compensation information</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="field-row col-3">
                            <div class="field">
                                <label class="field-label">Employee No.</label>
                                <input class="field-input" name="employee_number" value="{{ old('employee_number') }}" placeholder="EMP-0001">
                            </div>
                            <div class="field">
                                <label class="field-label">Position / Title <span class="req">*</span></label>
                                <input class="field-input" name="position" value="{{ old('position') }}" placeholder="e.g. LDRRM Officer" required>
                            </div>
                            <div class="field">
                                <label class="field-label">Department</label>
                                <input class="field-input" name="department" value="{{ old('department') }}" placeholder="e.g. Operations">
                            </div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field">
                                <label class="field-label">Section</label>
                                <input class="field-input" name="section" value="{{ old('section') }}" placeholder="e.g. Admin">
                            </div>
                            <div class="field">
                                <label class="field-label">Date Hired <span class="req">*</span></label>
                                <input class="field-input" type="date" name="date_hired" value="{{ old('date_hired') }}" required>
                            </div>
                            <div class="field">
                                <label class="field-label">Employment Type <span class="req">*</span></label>
                                <select class="field-select" name="employment_type" required>
                                    <option value="">Select type</option>
                                    <option value="JO" {{ old('employment_type') == 'JO' ? 'selected' : '' }}>Job Order (JO)</option>
                                    <option value="Permanent" {{ old('employment_type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-row col-3">
                            <div class="field">
                                <label class="field-label">Salary Grade</label>
                                <input class="field-input" name="salary_grade" value="{{ old('salary_grade') }}" placeholder="e.g. SG-11">
                            </div>
                            <div class="field">
                                <label class="field-label">Monthly Salary</label>
                                <input class="field-input" name="monthly_salary" value="{{ old('monthly_salary') }}" placeholder="e.g. 25000">
                            </div>
                            <div class="field">
                                <label class="field-label">Supervisor ID</label>
                                <input class="field-input" name="supervisor_id" value="{{ old('supervisor_id') }}" placeholder="Supervisor reference">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FAMILY & EDUCATION --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Family & Education</div>
                            <div class="section-sub">Immediate family and educational background</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="field-row col-2">
                            <div class="field">
                                <label class="field-label">Spouse Name</label>
                                <input class="field-input" name="spouse_name" value="{{ old('spouse_name') }}" placeholder="Full name">
                            </div>
                            <div class="field">
                                <label class="field-label">Spouse Occupation</label>
                                <input class="field-input" name="spouse_occupation" value="{{ old('spouse_occupation') }}" placeholder="Occupation">
                            </div>
                        </div>
                        <div class="field-row col-2">
                            <div class="field">
                                <label class="field-label">Father's Name</label>
                                <input class="field-input" name="father_name" value="{{ old('father_name') }}" placeholder="Full name">
                            </div>
                            <div class="field">
                                <label class="field-label">Mother's Maiden Name</label>
                                <input class="field-input" name="mother_name" value="{{ old('mother_name') }}" placeholder="Full maiden name">
                            </div>
                        </div>
                        <div style="height:1px;background:var(--gray-100);margin:4px 0 12px;"></div>
                        <div class="field-row col-2">
                            <div class="field">
                                <label class="field-label">Elementary School</label>
                                <input class="field-input" name="elem_school" value="{{ old('elem_school') }}" placeholder="School name">
                            </div>
                            <div class="field">
                                <label class="field-label">High School</label>
                                <input class="field-input" name="hs_school" value="{{ old('hs_school') }}" placeholder="School name">
                            </div>
                        </div>
                        <div class="field-row col-2">
                            <div class="field">
                                <label class="field-label">College / Degree</label>
                                <input class="field-input" name="college_school" value="{{ old('college_school') }}" placeholder="School · Course">
                            </div>
                            <div class="field">
                                <label class="field-label">Graduate Studies</label>
                                <input class="field-input" name="grad_school" value="{{ old('grad_school') }}" placeholder="School · Program">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REMARKS --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Remarks</div>
                            <div class="section-sub">Optional notes or observations</div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="field">
                            <textarea class="field-textarea" name="remarks" placeholder="Add any remarks, notes, or observations about this employee…">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- FORM FOOTER --}}
                <div class="form-footer">
                    <a href="{{ route('employees.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Save Employee
                    </button>
                </div>

            </div>

            {{-- RIGHT: SIDEBAR --}}
            <aside>

                {{-- PHOTO UPLOAD --}}
                <div class="sidebar-panel">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                        <div>
                            <div class="section-title">Photo</div>
                            <div class="section-sub">Employee profile picture</div>
                        </div>
                    </div>
                    <div class="photo-area">
                        <div class="photo-placeholder">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <label class="photo-label" for="photoInput">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload Photo
                        </label>
                        <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;">
                        <div class="photo-hint">JPG or PNG · Max 2MB</div>
                    </div>
                </div>

                {{-- REQUIRED FIELDS TIPS --}}
                <div class="sidebar-panel">
                    <div class="tips-header">Required Fields</div>
                    <div class="tips-body">
                        <div class="tip-item">
                            <div class="tip-dot"></div>
                            <div class="tip-text">Last Name, First Name</div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-dot"></div>
                            <div class="tip-text">Position / Title</div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-dot"></div>
                            <div class="tip-text">Date Hired</div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-dot"></div>
                            <div class="tip-text">Employment Type (JO or Permanent)</div>
                        </div>
                    </div>
                </div>

                {{-- EMPLOYMENT TYPE NOTE --}}
                <div class="sidebar-panel">
                    <div class="tips-header">Employment Types</div>
                    <div class="tips-body">
                        <div class="tip-item">
                            <div class="tip-dot" style="background:var(--red)"></div>
                            <div class="tip-text"><strong>Job Order (JO)</strong> — Contract-based, monitored under JO records</div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-dot" style="background:var(--blue)"></div>
                            <div class="tip-text"><strong>Permanent</strong> — Regular employee under plantilla</div>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    </form>

</div>
</main>
@endsection

@push('scripts')
<script>
    // Preview uploaded photo
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            const placeholder = document.querySelector('.photo-placeholder');
            placeholder.style.background = 'none';
            placeholder.style.border = 'none';
            placeholder.innerHTML = `<img src="${ev.target.result}" style="width:110px;height:110px;border-radius:50%;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    });

    (function initEmployeeFieldMasks() {
        const form = document.getElementById('employeeForm');
        if (!form) return;

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const minBirthdate = new Date(today);
        minBirthdate.setFullYear(minBirthdate.getFullYear() - 18);

        const fieldConfigs = {
            first_name: { type: 'name', required: true, message: 'First name may contain letters, spaces, hyphens, and apostrophes only.' },
            last_name: { type: 'name', required: true, message: 'Last name may contain letters, spaces, hyphens, and apostrophes only.' },
            middle_name: { type: 'name', required: false, message: 'Middle name may contain letters, spaces, hyphens, and apostrophes only.' },
            suffix: { type: 'name', required: false, message: 'Suffix may contain letters, spaces, hyphens, and apostrophes only.' },
            maiden_name: { type: 'name', required: false, message: 'Maiden name may contain letters, spaces, hyphens, and apostrophes only.' },
            mobile: { type: 'mobile', required: false, message: 'Mobile number must be 11 digits starting with 09.' },
            sss: { type: 'sss', required: false, message: 'SSS number must follow the format XX-XXXXXXX-X.' },
            tin: { type: 'tin', required: false, message: 'TIN must follow the format XXX-XXX-XXX.' },
            philhealth: { type: 'philhealth', required: false, message: 'PhilHealth number must follow the format XX-XXXXXXXXX-X.' },
            pagibig: { type: 'pagibig', required: false, message: 'Pag-IBIG number must follow the format XXXX-XXXX-XXXX.' },
            gsis: { type: 'gsis', required: false, message: 'GSIS number may contain digits and hyphens only.' },
            email: { type: 'email', required: false, message: 'Please enter a valid email address.' },
            present_zip: { type: 'zip', required: false, message: 'ZIP code must be 4 digits.' },
            birthdate: { type: 'birthdate', required: false, message: 'Birthdate must be at least 18 years ago and cannot be in the future.' },
        };

        const inputs = Object.entries(fieldConfigs)
            .map(([name, config]) => ({ input: form.querySelector(`[name="${name}"]`), config }))
            .filter(({ input }) => Boolean(input));

        const stripDigits = (value) => String(value || '').replace(/\D+/g, '');
        const stripName = (value) => String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\pL\s'-]+/gu, '')
            .replace(/\s+/g, ' ')
            .trimStart();
        const stripGsis = (value) => String(value || '').replace(/[^\d-]+/g, '').replace(/-+/g, '-');

        const formatGrouped = (digits, groups) => {
            let cursor = 0;
            const parts = [];

            for (const size of groups) {
                const chunk = digits.slice(cursor, cursor + size);
                if (!chunk) break;
                parts.push(chunk);
                cursor += size;
            }

            return parts.join('-');
        };

        const formatMobile = (value) => {
            const digits = stripDigits(value).slice(0, 11);
            const parts = [digits.slice(0, 4), digits.slice(4, 7), digits.slice(7, 11)].filter(Boolean);
            return parts.join(' ');
        };

        const formatSss = (value) => formatGrouped(stripDigits(value).slice(0, 10), [2, 7, 1]);
        const formatTin = (value) => formatGrouped(stripDigits(value).slice(0, 9), [3, 3, 3]);
        const formatPhilhealth = (value) => formatGrouped(stripDigits(value).slice(0, 12), [2, 9, 1]);
        const formatPagibig = (value) => formatGrouped(stripDigits(value).slice(0, 12), [4, 4, 4]);
        const formatZip = (value) => stripDigits(value).slice(0, 4);
        const formatName = (value) => stripName(value).slice(0, 150);
        const formatEmail = (value) => String(value || '').trim();
        const formatBirthdate = (value) => String(value || '').trim();

        const getField = (input) => input.closest('.field');
        const getError = (input) => getField(input)?.querySelector('.field-error');

        const setError = (input, message) => {
            const field = getField(input);
            const error = getError(input);
            if (!field || !error) return;

            field.classList.toggle('has-error', Boolean(message));
            error.textContent = message || '';
            input.setAttribute('aria-invalid', message ? 'true' : 'false');
        };

        const validateValue = (input, config) => {
            const value = String(input.value || '');
            if (!value) {
                return config.required ? `${input.previousElementSibling?.textContent?.replace(/\s*\*\s*/, '').trim() || 'This field'} is required.` : '';
            }

            switch (config.type) {
                case 'name':
                    return /^[\pL\s'-]+$/u.test(value)
                        ? ''
                        : config.message;
                case 'mobile':
                    return /^09\d{9}$/.test(stripDigits(value)) ? '' : config.message;
                case 'sss':
                    return /^\d{2}-\d{7}-\d$/.test(value) ? '' : config.message;
                case 'tin':
                    return /^\d{3}-\d{3}-\d{3}$/.test(value) ? '' : config.message;
                case 'philhealth':
                    return /^\d{2}-\d{9}-\d$/.test(value) ? '' : config.message;
                case 'pagibig':
                    return /^\d{4}-\d{4}-\d{4}$/.test(value) ? '' : config.message;
                case 'gsis':
                    return /^(?=.*\d)[\d-]+$/.test(value) ? '' : config.message;
                case 'email':
                    return input.checkValidity() ? '' : config.message;
                case 'zip':
                    return /^\d{4}$/.test(value) ? '' : config.message;
                case 'birthdate': {
                    if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                        return config.message;
                    }

                    const [year, month, day] = value.split('-').map(Number);
                    const birthdate = new Date(year, month - 1, day);
                    birthdate.setHours(0, 0, 0, 0);

                    return birthdate <= minBirthdate ? '' : config.message;
                }
                default:
                    return '';
            }
        };

        const applyFormat = (input, config) => {
            switch (config.type) {
                case 'name':
                    input.value = formatName(input.value);
                    break;
                case 'mobile':
                    input.value = formatMobile(input.value);
                    break;
                case 'sss':
                    input.value = formatSss(input.value);
                    break;
                case 'tin':
                    input.value = formatTin(input.value);
                    break;
                case 'philhealth':
                    input.value = formatPhilhealth(input.value);
                    break;
                case 'pagibig':
                    input.value = formatPagibig(input.value);
                    break;
                case 'gsis':
                    input.value = stripGsis(input.value);
                    break;
                case 'zip':
                    input.value = formatZip(input.value);
                    break;
                case 'email':
                    input.value = formatEmail(input.value);
                    break;
                case 'birthdate':
                    input.value = formatBirthdate(input.value);
                    break;
            }
        };

        inputs.forEach(({ input, config }) => {
            applyFormat(input, config);

            const existingMessage = getError(input)?.textContent?.trim();
            if (existingMessage) {
                input.closest('.field')?.classList.add('has-error');
                input.setAttribute('aria-invalid', 'true');
            }

            const runValidation = () => {
                applyFormat(input, config);
                const message = validateValue(input, config);
                input.dataset.touched = 'true';
                setError(input, message);
            };

            input.addEventListener('input', runValidation);
            input.addEventListener('blur', runValidation);
        });

        form.addEventListener('submit', (event) => {
            let firstInvalid = null;

            inputs.forEach(({ input, config }) => {
                applyFormat(input, config);
                const message = validateValue(input, config);
                setError(input, message);

                if (message && !firstInvalid) {
                    firstInvalid = input;
                }
            });

            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.focus({ preventScroll: true });
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    })();

    (function initPhilippineLocationCascades() {
        const API_BASE = 'https://psgc.cloud/api/v2';
        const NCR_REGION_CODE = '1300000000';
        const forms = document.querySelectorAll('form[data-location-form="present"]');

        if (!forms.length) return;

        const normalize = (value) => String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/\b(city|municipality|province|district|of|the)\b/g, ' ')
            .replace(/[^a-z0-9]+/g, ' ')
            .trim()
            .replace(/\s+/g, ' ');

        const sanitizeCityName = (value) => String(value || '')
            .replace(/^city of\s+/i, '')
            .replace(/^municipality of\s+/i, '')
            .trim();

        const fetchJson = async (url) => {
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Location request failed');
            const payload = await response.json();
            return Array.isArray(payload?.data) ? payload.data : [];
        };

        let zipLookupPromise;
        const getZipLookup = async () => {
            if (!zipLookupPromise) {
                zipLookupPromise = fetch('/data/zipcodes-ph.json')
                    .then((response) => response.ok ? response.json() : {})
                    .then((zipMap) => {
                        const lookup = new Map();
                        Object.entries(zipMap || {}).forEach(([zip, rawValue]) => {
                            const values = Array.isArray(rawValue) ? rawValue : [rawValue];
                            values.forEach((name) => {
                                const normalized = normalize(name);
                                if (normalized && !lookup.has(normalized)) {
                                    lookup.set(normalized, zip);
                                }
                            });
                        });
                        return lookup;
                    })
                    .catch(() => new Map());
            }
            return zipLookupPromise;
        };

        const setLoading = (select, label) => {
            select.innerHTML = `<option value="">${label}</option>`;
            select.disabled = true;
        };

        const fillSelect = (select, items, placeholder, { keepEnabled = true } = {}) => {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.name;
                option.textContent = item.name;
                option.dataset.code = item.code;
                option.dataset.kind = item.kind || '';
                option.dataset.normalized = normalize(item.name);
                select.appendChild(option);
            });
            select.disabled = keepEnabled ? items.length === 0 : true;
        };

        const selectByName = (select, targetName) => {
            const target = normalize(targetName);
            if (!target) return false;
            const match = Array.from(select.options).find((option) => option.dataset.normalized === target);
            if (!match) return false;
            select.value = match.value;
            return true;
        };

        const cityFromApiRow = (row) => {
            const type = String(row?.type || '').toLowerCase();
            const isCityOrMunicipality = type.includes('city') || type.includes('municipality');
            return {
                code: row.code,
                name: row.name,
                kind: isCityOrMunicipality ? 'city' : 'other'
            };
        };

        const resolveZip = async (barangayName, cityName) => {
            const lookup = await getZipLookup();
            const candidates = [
                barangayName,
                cityName,
                sanitizeCityName(cityName),
                `${barangayName}, ${cityName}`,
                `${barangayName}, ${sanitizeCityName(cityName)}`
            ].map(normalize).filter(Boolean);

            for (const key of candidates) {
                if (lookup.has(key)) {
                    return lookup.get(key);
                }
            }
            return '';
        };

        forms.forEach(async (form) => {
            const provinceSelect = form.querySelector('select[name="present_province"]');
            const citySelect = form.querySelector('select[name="present_city"]');
            const barangaySelect = form.querySelector('select[name="present_barangay"]');
            const zipInput = form.querySelector('input[name="present_zip"]');

            if (!provinceSelect || !citySelect || !barangaySelect || !zipInput) return;

            const oldProvince = form.dataset.oldProvince || '';
            const oldCity = form.dataset.oldCity || '';
            const oldBarangay = form.dataset.oldBarangay || '';
            const oldZip = form.dataset.oldZip || '';

            setLoading(provinceSelect, 'Loading provinces...');
            setLoading(citySelect, 'Select city/municipality');
            setLoading(barangaySelect, 'Select barangay');

            let provinces = [];
            try {
                const rows = await fetchJson(`${API_BASE}/provinces`);
                provinces = rows
                    .map((row) => ({ code: row.code, name: row.name, kind: 'province' }))
                    .sort((a, b) => a.name.localeCompare(b.name));
                provinces.unshift({ code: NCR_REGION_CODE, name: 'National Capital Region (NCR)', kind: 'region' });
            } catch (error) {
                setLoading(provinceSelect, 'Unable to load provinces');
                return;
            }

            fillSelect(provinceSelect, provinces, 'Select province');
            selectByName(provinceSelect, oldProvince);

            const loadCities = async () => {
                const selectedProvince = provinceSelect.selectedOptions[0];
                const provinceCode = selectedProvince?.dataset.code || '';
                const provinceKind = selectedProvince?.dataset.kind || '';

                setLoading(citySelect, provinceCode ? 'Loading cities...' : 'Select city/municipality');
                setLoading(barangaySelect, 'Select barangay');

                if (!provinceCode) return;

                const endpoint = provinceKind === 'region'
                    ? `${API_BASE}/regions/${provinceCode}/cities-municipalities`
                    : `${API_BASE}/provinces/${provinceCode}/cities-municipalities`;

                let cities = [];
                try {
                    const rows = await fetchJson(endpoint);
                    cities = rows
                        .map(cityFromApiRow)
                        .filter((item) => item.kind === 'city')
                        .sort((a, b) => a.name.localeCompare(b.name));
                } catch (error) {
                    setLoading(citySelect, 'Unable to load cities');
                    return;
                }

                fillSelect(citySelect, cities, 'Select city/municipality');
                if (oldCity && !citySelect.dataset.initialized) {
                    selectByName(citySelect, oldCity);
                }
            };

            const loadBarangays = async () => {
                const selectedCity = citySelect.selectedOptions[0];
                const cityCode = selectedCity?.dataset.code || '';

                setLoading(barangaySelect, cityCode ? 'Loading barangays...' : 'Select barangay');

                if (!cityCode) {
                    if (!zipInput.value) zipInput.value = oldZip;
                    return;
                }

                let barangays = [];
                try {
                    const rows = await fetchJson(`${API_BASE}/cities-municipalities/${cityCode}/barangays`);
                    barangays = rows
                        .map((row) => ({ code: row.code, name: row.name, kind: 'barangay' }))
                        .sort((a, b) => a.name.localeCompare(b.name));
                } catch (error) {
                    setLoading(barangaySelect, 'Unable to load barangays');
                    return;
                }

                fillSelect(barangaySelect, barangays, 'Select barangay');
                if (oldBarangay && !barangaySelect.dataset.initialized) {
                    selectByName(barangaySelect, oldBarangay);
                }
            };

            const applyZip = async () => {
                const cityName = citySelect.value;
                const barangayName = barangaySelect.value;
                const zip = await resolveZip(barangayName, cityName);
                zipInput.value = zip || oldZip || '';
            };

            provinceSelect.addEventListener('change', async () => {
                citySelect.dataset.initialized = 'true';
                barangaySelect.dataset.initialized = 'true';
                zipInput.value = '';
                await loadCities();
            });

            citySelect.addEventListener('change', async () => {
                barangaySelect.dataset.initialized = 'true';
                zipInput.value = '';
                await loadBarangays();
                await applyZip();
            });

            barangaySelect.addEventListener('change', applyZip);

            await loadCities();
            citySelect.dataset.initialized = 'true';
            await loadBarangays();
            barangaySelect.dataset.initialized = 'true';
            await applyZip();
        });
    })();
</script>
@endpush