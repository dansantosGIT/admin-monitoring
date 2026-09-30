@extends('layouts.app')

@section('page-name', 'Employee Details')

@push('styles')
<style>
    .employee-profile { width: 100%; max-width: 1180px; }
    .employee-breadcrumb { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px; color: var(--muted); font-size: 13px; text-decoration: none; }
    .employee-breadcrumb:hover { color: var(--text); }
    .employee-breadcrumb svg { width: 15px; height: 15px; }
    .profile-header, .profile-summary, .profile-card { background: var(--panel); border: 1px solid var(--border); box-shadow: 0 12px 30px rgba(18, 32, 51, 0.06); }
    .profile-header { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 22px 24px; border-radius: 14px 14px 0 0; }
    .profile-heading { display: flex; align-items: center; gap: 16px; min-width: 0; }
    .profile-avatar { width: 64px; height: 64px; flex: 0 0 64px; display: grid; place-items: center; overflow: hidden; border-radius: 14px; background: #dbe7ff; color: #174ea6; font-size: 20px; font-weight: 800; }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .profile-kicker { margin: 0 0 4px; color: var(--muted); font-size: 12px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .profile-name { margin: 0; color: var(--text); font-size: clamp(21px, 2.2vw, 29px); line-height: 1.15; }
    .profile-id { margin: 5px 0 0; color: var(--muted); font-size: 13px; }
    .profile-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
    .profile-button { display: inline-flex; align-items: center; justify-content: center; gap: 7px; min-height: 38px; padding: 8px 13px; border: 1px solid var(--border); border-radius: 8px; background: #fff; color: var(--text); font-size: 13px; font-weight: 700; text-decoration: none; cursor: pointer; }
    .profile-button:hover { border-color: #9db5d5; background: #f8fbff; }
    .profile-button svg { width: 15px; height: 15px; }
    .profile-button--danger { color: #c62828; border-color: #ffd3d3; }
    .profile-button--danger:hover { background: #fff5f5; border-color: #f4a3a3; }
    .profile-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; padding: 0 24px; border-top: 0; border-radius: 0 0 14px 14px; }
    .summary-item { min-width: 0; padding: 16px 18px 17px 0; border-right: 1px solid var(--border); }
    .summary-item:not(:first-child) { padding-left: 18px; }
    .summary-item:last-child { border-right: 0; }
    .summary-label, .detail-label { color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
    .summary-value { margin-top: 5px; overflow: hidden; color: var(--text); font-size: 14px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 9px; border-radius: 999px; background: #e5f7ee; color: #18794e; font-size: 12px; font-weight: 800; }
    .status-badge::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ''; }
    .profile-tabs { display: flex; gap: 22px; overflow-x: auto; margin: 22px 0 18px; border-bottom: 1px solid var(--border); }
    .profile-tab { padding: 0 0 11px; border: 0; border-bottom: 2px solid transparent; background: transparent; color: var(--muted); font-size: 13px; font-weight: 700; white-space: nowrap; }
    .profile-tab.is-active { border-bottom-color: var(--accent); color: var(--accent); }
    .profile-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; align-items: start; }
    .profile-card { border-radius: 12px; padding: 20px; }
    .profile-card--wide { grid-column: 1 / -1; }
    .card-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .card-title { margin: 0; color: var(--text); font-size: 15px; }
    .card-subtitle { margin: 4px 0 0; color: var(--muted); font-size: 12px; }
    .card-icon { color: var(--accent); }
    .card-icon svg { width: 18px; height: 18px; }
    .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 24px; }
    .detail-item { min-width: 0; padding: 12px 0; border-top: 1px solid #edf1f6; }
    .detail-item:nth-child(-n+2) { padding-top: 0; border-top: 0; }
    .detail-value { margin-top: 5px; overflow-wrap: anywhere; color: var(--text); font-size: 14px; line-height: 1.45; }
    .schedule-value { font-variant-numeric: tabular-nums; font-weight: 700; }
    .empty-panel { padding: 22px; border: 1px dashed var(--border); border-radius: 9px; background: #f8fbff; color: var(--muted); font-size: 13px; }
    @media (max-width: 760px) {
        .profile-header { align-items: flex-start; flex-direction: column; padding: 18px; }
        .profile-actions { width: 100%; justify-content: flex-start; }
        .profile-button { flex: 1; }
        .profile-summary { grid-template-columns: repeat(2, 1fr); padding: 0 18px; }
        .summary-item:nth-child(2) { border-right: 0; }
        .summary-item:nth-child(3), .summary-item:nth-child(4) { border-top: 1px solid var(--border); }
        .summary-item:nth-child(3) { padding-left: 0; }
        .profile-grid { grid-template-columns: 1fr; }
        .profile-card--wide { grid-column: auto; }
    }
    @media (max-width: 460px) {
        .profile-heading { align-items: flex-start; }
        .profile-avatar { width: 52px; height: 52px; flex-basis: 52px; border-radius: 11px; }
        .profile-summary { padding: 0 14px; }
        .profile-card { padding: 16px; }
        .detail-grid { grid-template-columns: 1fr; }
        .detail-item:nth-child(2) { padding-top: 12px; border-top: 1px solid #edf1f6; }
    }
</style>
@endpush

@section('content')
<div class="employee-profile">
    <a href="{{ route('employees.index') }}" class="employee-breadcrumb">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        <span>Employee Directory</span>
    </a>

    <section class="profile-header">
        <div class="profile-heading">
            <div class="profile-avatar">
                @if($employee->photo_path)
                    <img src="{{ asset('storage/'.$employee->photo_path) }}" alt="{{ $employee->full_name }}">
                @else
                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                @endif
            </div>
            <div>
                <p class="profile-kicker">Employee Profile</p>
                <h1 class="profile-name">{{ $employee->full_name }}</h1>
                <p class="profile-id">{{ $employee->employee_number ?: 'Employee number not assigned' }}</p>
            </div>
        </div>
        <div class="profile-actions">
            <a href="{{ route('employees.edit', $employee) }}" class="profile-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                Edit Profile
            </a>
            <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Delete this employee?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-button profile-button--danger" aria-label="Delete employee">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </section>

    <section class="profile-summary" aria-label="Employee summary">
        <div class="summary-item"><div class="summary-label">Job Title</div><div class="summary-value">{{ $employee->position ?: '-' }}</div></div>
        <div class="summary-item"><div class="summary-label">Department</div><div class="summary-value">{{ $employee->department ?: '-' }}</div></div>
        <div class="summary-item"><div class="summary-label">Employment Type</div><div class="summary-value">{{ $employee->employment_type === 'JO' ? 'Job Order' : ($employee->employment_type ?: '-') }}</div></div>
        <div class="summary-item"><div class="summary-label">Status</div><div class="summary-value"><span class="status-badge">{{ $employee->status ?: 'Active' }}</span></div></div>
    </section>

    <nav class="profile-tabs" aria-label="Employee detail sections">
        <span class="profile-tab is-active">General</span>
    </nav>

    <div class="profile-grid">
        <section class="profile-card">
            <div class="card-heading"><div><h2 class="card-title">Personal Information</h2><p class="card-subtitle">Basic identity details</p></div><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span></div>
            <div class="detail-grid">
                <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value">{{ $employee->full_name }}</div></div>
                <div class="detail-item"><div class="detail-label">Birthdate</div><div class="detail-value">{{ optional($employee->birthdate)->format('F j, Y') ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Sex</div><div class="detail-value">{{ $employee->sex ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Civil Status</div><div class="detail-value">{{ $employee->civil_status ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Place of Birth</div><div class="detail-value">{{ $employee->place_of_birth ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Nationality</div><div class="detail-value">{{ $employee->nationality ?: '-' }}</div></div>
            </div>
        </section>

        <section class="profile-card">
            <div class="card-heading"><div><h2 class="card-title">Contact Information</h2><p class="card-subtitle">Ways to reach this employee</p></div><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"/><path d="m22 6-10 7L2 6"/></svg></span></div>
            <div class="detail-grid">
                <div class="detail-item"><div class="detail-label">Mobile</div><div class="detail-value">{{ $employee->mobile ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value">{{ $employee->email ?: '-' }}</div></div>
                <div class="detail-item" style="grid-column:1/-1"><div class="detail-label">Present Address</div><div class="detail-value">{{ trim(implode(', ', array_filter([data_get($employee->present_address, 'address'), data_get($employee->present_address, 'barangay'), data_get($employee->present_address, 'city'), data_get($employee->present_address, 'province'), data_get($employee->present_address, 'zip')]))) ?: '-' }}</div></div>
            </div>
        </section>

        <section class="profile-card profile-card--wide">
            <div class="card-heading"><div><h2 class="card-title">Employment Details</h2><p class="card-subtitle">Role, schedule, and compensation</p></div><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></span></div>
            <div class="detail-grid">
                <div class="detail-item"><div class="detail-label">Position</div><div class="detail-value">{{ $employee->position ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Department</div><div class="detail-value">{{ $employee->department ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Date Hired</div><div class="detail-value">{{ optional($employee->date_hired)->format('F j, Y') ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Employment Type</div><div class="detail-value">{{ $employee->employment_type === 'JO' ? 'Job Order' : ($employee->employment_type ?: '-') }}</div></div>
                <div class="detail-item"><div class="detail-label">Shift</div><div class="detail-value">{{ $employee->shift_type ? ucfirst($employee->shift_type) . ' Shift' : 'Not assigned' }}</div></div>
                <div class="detail-item"><div class="detail-label">Work Schedule</div><div class="detail-value schedule-value">{{ $employee->shift_start && $employee->shift_end ? substr($employee->shift_start, 0, 5) . ' - ' . substr($employee->shift_end, 0, 5) : 'Not assigned' }}</div></div>
                <div class="detail-item"><div class="detail-label">Monthly Salary</div><div class="detail-value">{{ $employee->monthly_salary ? number_format((float) $employee->monthly_salary, 2) : '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Salary Grade</div><div class="detail-value">{{ $employee->salary_grade ?: '-' }}</div></div>
            </div>
        </section>

        <section class="profile-card">
            <div class="card-heading"><div><h2 class="card-title">Government IDs</h2><p class="card-subtitle">Recorded identification numbers</p></div><span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg></span></div>
            <div class="detail-grid">
                <div class="detail-item"><div class="detail-label">SSS</div><div class="detail-value">{{ $employee->sss ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">GSIS</div><div class="detail-value">{{ $employee->gsis ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">PhilHealth</div><div class="detail-value">{{ $employee->philhealth ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">Pag-IBIG</div><div class="detail-value">{{ $employee->pagibig ?: '-' }}</div></div>
                <div class="detail-item"><div class="detail-label">TIN</div><div class="detail-value">{{ $employee->tin ?: '-' }}</div></div>
            </div>
        </section>

        <section class="profile-card">
            <div class="card-heading"><div><h2 class="card-title">Additional Notes</h2><p class="card-subtitle">Internal employee record notes</p></div></div>
            @if($employee->remarks)
                <div class="detail-value">{{ $employee->remarks }}</div>
            @else
                <div class="empty-panel">No notes have been added for this employee.</div>
            @endif
        </section>
    </div>
</div>
@endsection
