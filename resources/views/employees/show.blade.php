@extends('layouts.app')

@section('page-name', 'Employee Details')

@push('styles')
<style>
    .app-main:has(.employee-profile) { justify-content: flex-start; padding-left: 18px; padding-right: 18px; }
    .employee-profile { width: 100%; max-width: none; }
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
    .attendance-calendar-card { grid-column: 1 / -1; order: 10; }
    .calendar-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .calendar-month { color: var(--text); font-size: 18px; font-weight: 800; }
    .calendar-nav { display: inline-flex; align-items: center; gap: 6px; }
    .calendar-nav a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border: 1px solid var(--border); border-radius: 8px; background: #fff; color: var(--text); text-decoration: none; }
    .calendar-nav a:hover { border-color: #9db5d5; background: #f8fbff; }
    .calendar-nav svg { width: 15px; height: 15px; }
    .calendar-legend { display: flex; flex-wrap: wrap; gap: 8px 14px; margin-bottom: 16px; color: var(--muted); font-size: 11px; font-weight: 700; }
    .calendar-legend-item { display: inline-flex; align-items: center; gap: 6px; }
    .calendar-swatch { width: 10px; height: 10px; border-radius: 3px; border: 1px solid transparent; }
    .calendar-swatch--present { background: #dff5e8; border-color: #a8dfbb; }
    .calendar-swatch--absent { background: #fee2e2; border-color: #f4aaaa; }
    .calendar-swatch--rest { background: #eef1f5; border-color: #d2d8e1; }
    .calendar-swatch--leave { background: #dbeafe; border-color: #a8c7f2; }
    .calendar-swatch--attention { background: #ffedd5; border-color: #fdba74; }
    .calendar-swatch--not-recorded { background: #fff; border-color: var(--border); }
    .calendar-weekdays, .calendar-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 6px; }
    .calendar-weekday { padding: 6px 4px; color: var(--muted); font-size: 10px; font-weight: 800; letter-spacing: .05em; text-align: center; text-transform: uppercase; }
    .calendar-blank { min-height: 82px; }
    .calendar-day { min-height: 82px; border: 1px solid var(--border); border-radius: 9px; overflow: hidden; }
    .calendar-day summary { display: flex; flex-direction: column; justify-content: space-between; min-height: 82px; padding: 9px; cursor: pointer; list-style: none; }
    .calendar-day summary::-webkit-details-marker { display: none; }
    .calendar-day summary:hover { filter: brightness(.98); }
    .calendar-day--present { background: #effbf3; border-color: #b7e5c7; }
    .calendar-day--absent { background: #fff2f2; border-color: #f4b4b4; }
    .calendar-day--rest_day { background: #f2f4f7; border-color: #d7dde5; }
    .calendar-day--leave { background: #eff6ff; border-color: #b8d2f5; }
    .calendar-day--not_recorded { background: #fff; }
    .calendar-day--attention { background: #fff7ed; border-color: #fdba74; }
    .calendar-day-number { color: var(--text); font-size: 14px; font-weight: 800; }
    .calendar-day-status { align-self: flex-start; padding: 3px 5px; border-radius: 5px; color: var(--muted); font-size: 9px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .calendar-day--present .calendar-day-status { color: #18794e; background: #dff5e8; }
    .calendar-day--absent .calendar-day-status { color: #b42318; background: #fee2e2; }
    .calendar-day--leave .calendar-day-status { color: #245ea8; background: #dbeafe; }
    .calendar-day--rest_day .calendar-day-status { color: #687386; background: #e2e6ec; }
    .calendar-day--attention .calendar-day-status { color: #b45309; background: #ffedd5; }
    .calendar-day-detail { padding: 8px 9px; border-top: 1px solid rgba(18, 32, 51, .1); color: var(--muted); font-size: 11px; line-height: 1.5; }
    .calendar-day-detail strong { color: var(--text); }
    .calendar-day-flags { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px; }
    .calendar-day-flag { padding: 2px 4px; border-radius: 4px; background: #fff4d6; color: #946200; font-size: 8px; font-weight: 800; }
    .calendar-day-flag--ot { background: #e8efff; color: #2858a8; }
    .calendar-edit-form { display: grid; gap: 10px; }
    .calendar-edit-form label { color: var(--muted); font-size: 9px; font-weight: 800; letter-spacing: .03em; text-transform: uppercase; }
    .calendar-edit-form input, .calendar-edit-form select, .calendar-edit-form textarea { width: 100%; min-height: 30px; padding: 5px 6px; border: 1px solid var(--border); border-radius: 5px; background: #fff; color: var(--text); font: inherit; font-size: 11px; }
    .calendar-edit-form textarea { min-height: 44px; resize: vertical; }
    .calendar-edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
    .calendar-save-button { min-height: 32px; border: 0; border-radius: 6px; background: var(--accent); color: #fff; font-size: 11px; font-weight: 800; cursor: pointer; }
    .calendar-edit-trigger { width: 100%; min-height: 30px; margin-top: 6px; border: 1px solid #c7d9f7; border-radius: 6px; background: #f3f7ff; color: var(--accent); font-size: 10px; font-weight: 800; cursor: pointer; }
    .calendar-edit-trigger:hover { background: #e8f0ff; }
    .calendar-editor-backdrop { position: fixed; inset: 0; z-index: 40; display: grid; place-items: center; padding: 18px; background: rgba(18, 32, 51, .42); }
    .calendar-editor-backdrop[hidden] { display: none; }
    .calendar-editor { width: min(460px, 100%); max-height: calc(100vh - 36px); overflow-y: auto; padding: 22px; border: 1px solid var(--border); border-radius: 14px; background: #fff; box-shadow: 0 24px 70px rgba(18, 32, 51, .22); }
    .calendar-editor-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .calendar-editor-title { margin: 0; color: var(--text); font-size: 19px; }
    .calendar-editor-date { margin: 4px 0 0; color: var(--muted); font-size: 12px; }
    .calendar-editor-close { width: 32px; height: 32px; border: 1px solid var(--border); border-radius: 8px; background: #fff; color: var(--text); cursor: pointer; }
    .calendar-editor .calendar-edit-form label { font-size: 10px; }
    .calendar-editor .calendar-edit-form input, .calendar-editor .calendar-edit-form select, .calendar-editor .calendar-edit-form textarea { min-height: 38px; padding: 8px 9px; border-radius: 7px; font-size: 13px; }
    .calendar-editor .calendar-edit-form textarea { min-height: 70px; }
    .calendar-editor .calendar-save-button { min-height: 40px; font-size: 13px; }
    .calendar-summary { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
    .calendar-count { padding: 6px 9px; border-radius: 7px; background: #f5f8fc; color: var(--muted); font-size: 11px; font-weight: 700; }
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
        .calendar-grid, .calendar-weekdays { gap: 3px; }
        .calendar-day, .calendar-day summary { min-height: 62px; }
        .calendar-day summary { padding: 6px; }
        .calendar-day-status { font-size: 8px; }
        .calendar-day-detail { font-size: 10px; }
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
                <p class="profile-id">ID No.: {{ $employee->employee_number ?: 'Not assigned' }}</p>
            </div>
        </div>
        <div class="profile-actions">
            <a href="{{ route('attendance.index', ['employee_id' => $employee->id, 'month' => now()->format('Y-m')]) }}" class="profile-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 9h18M8 13h3M8 17h5"/></svg>
                View DTR
            </a>
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

        <section class="profile-card attendance-calendar-card">
            <div class="card-heading">
                <div>
                    <h2 class="card-title">Attendance Calendar</h2>
                    <p class="card-subtitle">Daily attendance for {{ $employee->full_name }}</p>
                </div>
                <span class="card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 9h18M8 13h3M8 17h5"/></svg></span>
            </div>

            <div class="calendar-toolbar">
                <div class="calendar-month">{{ $calendarMonth->format('F Y') }}</div>
                <div class="calendar-nav" aria-label="Change attendance month">
                    <a href="{{ route('employees.show', ['employee' => $employee, 'month' => $calendarMonth->copy()->subMonth()->format('Y-m')]) }}" aria-label="Previous month"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg></a>
                    <a href="{{ route('employees.show', ['employee' => $employee, 'month' => now()->format('Y-m')]) }}" aria-label="Current month"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 9h18"/></svg></a>
                    <a href="{{ route('employees.show', ['employee' => $employee, 'month' => $calendarMonth->copy()->addMonth()->format('Y-m')]) }}" aria-label="Next month"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></a>
                </div>
            </div>

            <div class="calendar-legend" aria-label="Attendance status legend">
                <span class="calendar-legend-item"><i class="calendar-swatch calendar-swatch--present"></i>Present</span>
                <span class="calendar-legend-item"><i class="calendar-swatch calendar-swatch--absent"></i>Absent</span>
                <span class="calendar-legend-item"><i class="calendar-swatch calendar-swatch--rest"></i>Rest day</span>
                <span class="calendar-legend-item"><i class="calendar-swatch calendar-swatch--leave"></i>Leave</span>
                <span class="calendar-legend-item"><i class="calendar-swatch calendar-swatch--attention"></i>Late / Undertime</span>
                <span class="calendar-legend-item"><i class="calendar-swatch calendar-swatch--not-recorded"></i>No record</span>
            </div>

            <div class="calendar-weekdays" aria-hidden="true">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $weekday)
                    <div class="calendar-weekday">{{ $weekday }}</div>
                @endforeach
            </div>
            <div class="calendar-grid">
                @for($blank = 0; $blank < $calendarMonth->dayOfWeek; $blank++)
                    <div class="calendar-blank" aria-hidden="true"></div>
                @endfor
                @foreach($calendarDays as $day)
                    @php($statusLabel = ['present' => 'Present', 'absent' => 'Absent', 'rest_day' => 'Rest day', 'leave' => 'Leave', 'not_recorded' => 'No record'][$day->status] ?? ucfirst(str_replace('_', ' ', $day->status)))
                    <details class="calendar-day calendar-day--{{ $day->status }} @if($day->entry && ($day->entry->late_minutes > 0 || $day->entry->undertime_minutes > 0))calendar-day--attention @endif" @if($canManage) data-calendar-edit data-date="{{ $day->date->format('Y-m-d') }}" data-status="{{ $day->entry?->status ?: $day->status }}" data-time-in="{{ $day->entry?->time_in ? substr($day->entry->time_in, 0, 5) : '' }}" data-time-out="{{ $day->entry?->time_out ? substr($day->entry->time_out, 0, 5) : '' }}" data-late-minutes="{{ $day->entry?->late_minutes ?? 0 }}" data-overtime-minutes="{{ $day->entry?->overtime_minutes ?? 0 }}" data-undertime-minutes="{{ $day->entry?->undertime_minutes ?? 0 }}" data-remarks="{{ $day->entry?->remarks ?? '' }}" @endif>
                        <summary>
                            <span class="calendar-day-number">{{ $day->date->day }}</span>
                            <span class="calendar-day-status">{{ $statusLabel }}</span>
                            @if($day->entry && ($day->entry->late_minutes > 0 || $day->entry->overtime_minutes > 0))
                                <span class="calendar-day-flags">
                                    @if($day->entry->late_minutes > 0)<i class="calendar-day-flag">Late</i>@endif
                                    @if($day->entry->overtime_minutes > 0)<i class="calendar-day-flag calendar-day-flag--ot">OT</i>@endif
                                </span>
                            @endif
                        </summary>
                        @if($canManage)
                            <button type="button" class="calendar-edit-trigger">Edit attendance</button>
                        @else
                            <div class="calendar-day-detail">
                                @if($day->entry)
                                    <strong>{{ $day->entry->time_in ? substr($day->entry->time_in, 0, 5) : '-' }} - {{ $day->entry->time_out ? substr($day->entry->time_out, 0, 5) : '-' }}</strong><br>
                                    Late: {{ $day->entry->late_minutes }} min | OT: {{ $day->entry->overtime_minutes }} min
                                    @if($day->entry->remarks)<br>{{ $day->entry->remarks }}@endif
                                @elseif($day->leave)
                                    <strong>{{ $day->leave->leave_category ? $day->leave->leave_category . ' / ' : '' }}{{ $day->leave->leave_type }}</strong><br>{{ $day->leave->days }} day(s)
                                @elseif($day->status === 'rest_day')
                                    Scheduled rest day
                                @else
                                    No attendance record
                                @endif
                            </div>
                        @endif
                    </details>
                @endforeach
            </div>

            <div class="calendar-summary" aria-label="Monthly attendance totals">
                <span class="calendar-count">Present: {{ $calendarCounts->get('present', 0) }}</span>
                <span class="calendar-count">Absent: {{ $calendarCounts->get('absent', 0) }}</span>
                <span class="calendar-count">Leave: {{ $calendarCounts->get('leave', 0) }}</span>
                <span class="calendar-count">Rest days: {{ $calendarCounts->get('rest_day', 0) }}</span>
            </div>
            @if($canManage)
                <div class="calendar-editor-backdrop" data-calendar-editor-backdrop hidden>
                    <div class="calendar-editor" role="dialog" aria-modal="true" aria-labelledby="calendar-editor-title">
                        <div class="calendar-editor-head"><div><h3 class="calendar-editor-title" id="calendar-editor-title">Edit attendance</h3><p class="calendar-editor-date" data-calendar-editor-date></p></div><button type="button" class="calendar-editor-close" data-calendar-editor-close aria-label="Close editor">&times;</button></div>
                        <form method="POST" action="{{ route('attendance.entries.store') }}" class="calendar-edit-form" data-calendar-editor-form>
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="work_date" data-calendar-editor-input="date">
                            <label>Status</label>
                            <select name="status" data-calendar-editor-input="status">
                                <option value="present">Present</option><option value="absent">Absent</option><option value="leave">Leave</option><option value="rest_day">Rest day</option>
                            </select>
                            <div class="calendar-edit-grid">
                                <div><label>Time in</label><input type="time" name="time_in" data-calendar-editor-input="time-in"></div>
                                <div><label>Time out</label><input type="time" name="time_out" data-calendar-editor-input="time-out"></div>
                                <div><label>Late minutes</label><input type="number" name="late_minutes" min="0" max="1440" data-calendar-editor-input="late"></div>
                                <div><label>OT minutes</label><input type="number" name="overtime_minutes" min="0" max="1440" data-calendar-editor-input="overtime"></div>
                                <div><label>Undertime</label><input type="number" name="undertime_minutes" min="0" max="1440" data-calendar-editor-input="undertime"></div>
                            </div>
                            <label>Remarks</label><textarea name="remarks" placeholder="Optional note" data-calendar-editor-input="remarks"></textarea>
                            <button type="submit" class="calendar-save-button">Save day</button>
                        </form>
                    </div>
                </div>
            @endif
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
                <div class="detail-item"><div class="detail-label">ID No.</div><div class="detail-value">{{ $employee->employee_number ?: 'Not assigned' }}</div></div>
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

@if($canManage)
@push('scripts')
<script>
    (() => {
        const backdrop = document.querySelector('[data-calendar-editor-backdrop]');
        const form = document.querySelector('[data-calendar-editor-form]');
        if (!backdrop || !form) return;

        const input = (name) => form.querySelector(`[data-calendar-editor-input="${name}"]`);
        const close = () => { backdrop.hidden = true; document.body.style.overflow = ''; };
        const open = (day) => {
            input('date').value = day.dataset.date;
            input('status').value = day.dataset.status === 'not_recorded' ? 'present' : day.dataset.status;
            input('time-in').value = day.dataset.timeIn;
            input('time-out').value = day.dataset.timeOut;
            input('late').value = day.dataset.lateMinutes;
            input('overtime').value = day.dataset.overtimeMinutes;
            input('undertime').value = day.dataset.undertimeMinutes;
            input('remarks').value = day.dataset.remarks;
            backdrop.querySelector('[data-calendar-editor-date]').textContent = new Date(`${day.dataset.date}T00:00:00`).toLocaleDateString(undefined, { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
            backdrop.hidden = false;
            document.body.style.overflow = 'hidden';
            input('status').focus();
        };

        document.querySelectorAll('[data-calendar-edit]').forEach((day) => {
            day.addEventListener('click', (event) => {
                if (event.target.closest('summary') || event.target.closest('[data-calendar-edit-trigger]') || event.target.closest('.calendar-edit-trigger')) {
                    event.preventDefault();
                    day.open = false;
                    open(day);
                }
            });
        });
        backdrop.querySelector('[data-calendar-editor-close]')?.addEventListener('click', close);
        backdrop.addEventListener('click', (event) => { if (event.target === backdrop) close(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !backdrop.hidden) close(); });
    })();
</script>
@endpush
@endif
