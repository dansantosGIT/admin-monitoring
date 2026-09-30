@extends('layouts.app')

@section('page-name', 'Attendance')

@push('styles')
<style>
    .app-main:has(.attendance-page) { justify-content: flex-start; padding-left: 18px; padding-right: 18px; }
    .attendance-page { width: 100%; max-width: none; }
    .attendance-hero, .attendance-panel, .attendance-stat { background: var(--panel); border: 1px solid var(--border); box-shadow: 0 12px 30px rgba(18, 32, 51, .06); }
    .attendance-hero { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; padding: 24px; border-radius: 14px; }
    .attendance-eyebrow { margin: 0 0 6px; color: var(--accent); font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .attendance-title { margin: 0; color: var(--text); font-size: clamp(24px, 3vw, 34px); }
    .attendance-subtitle { max-width: 680px; margin: 8px 0 0; color: var(--muted); font-size: 13px; line-height: 1.5; }
    .attendance-hero-meta { display: flex; gap: 8px; flex-wrap: wrap; }
    .attendance-chip { padding: 7px 10px; border: 1px solid var(--border); border-radius: 8px; background: #fff; color: var(--muted); font-size: 12px; font-weight: 700; }
    .attendance-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 14px 0; }
    .attendance-stat { padding: 16px; border-radius: 11px; }
    .attendance-stat-label, .attendance-label { color: var(--muted); font-size: 11px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .attendance-stat-value { margin-top: 7px; color: var(--text); font-size: 25px; font-weight: 800; }
    .attendance-stat-note, .attendance-muted { color: var(--muted); font-size: 12px; }
    .attendance-panel { border-radius: 12px; overflow: hidden; }
    .attendance-toolbar, .attendance-form-panel { display: flex; align-items: flex-end; gap: 10px; flex-wrap: wrap; padding: 16px; border-bottom: 1px solid var(--border); background: #fbfdff; }
    .attendance-field { display: grid; gap: 5px; min-width: 150px; }
    .attendance-field--search { flex: 1 1 250px; }
    .attendance-input, .attendance-select { width: 100%; min-height: 38px; padding: 8px 10px; border: 1px solid #d9e3ef; border-radius: 8px; background: #fff; color: var(--text); font: inherit; font-size: 13px; }
    .attendance-button { min-height: 38px; padding: 8px 13px; border: 1px solid #0f62fe; border-radius: 8px; background: #0f62fe; color: #fff; font-size: 13px; font-weight: 800; cursor: pointer; }
    .attendance-button--muted { border-color: var(--border); background: #fff; color: var(--text); text-decoration: none; }
    .attendance-tabs { display: flex; gap: 20px; overflow-x: auto; padding: 0 16px; border-bottom: 1px solid var(--border); }
    .attendance-tab { padding: 14px 0 11px; border-bottom: 2px solid transparent; color: var(--muted); font-size: 13px; font-weight: 800; text-decoration: none; white-space: nowrap; }
    .attendance-tab.is-active { border-bottom-color: var(--accent); color: var(--accent); }
    .attendance-table-wrap { overflow-x: auto; }
    .attendance-table { width: 100%; min-width: 1040px; border-collapse: collapse; }
    .attendance-table th, .attendance-table td { padding: 12px 14px; border-bottom: 1px solid #edf1f6; text-align: left; vertical-align: middle; font-size: 13px; }
    .attendance-table th { background: #fbfdff; color: var(--muted); font-size: 10px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
    .monthly-grid-wrap { overflow-x: auto; }
    .monthly-grid { width: 100%; min-width: 1540px; border-collapse: collapse; }
    .monthly-grid th, .monthly-grid td { padding: 8px 6px; border-bottom: 1px solid #edf1f6; text-align: center; vertical-align: middle; }
    .monthly-grid th { position: sticky; top: 0; z-index: 1; background: #fbfdff; color: var(--muted); font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .monthly-grid th:first-child, .monthly-grid td:first-child { position: sticky; left: 0; z-index: 2; background: #fff; text-align: left; }
    .monthly-grid th:first-child { z-index: 3; background: #fbfdff; }
    .monthly-grid th:first-child, .monthly-grid td:first-child { width: 260px; min-width: 260px; box-shadow: 5px 0 10px rgba(18, 32, 51, .04); }
    .monthly-grid th:not(:first-child) { min-width: 42px; }
    .monthly-grid th small { display: block; margin-top: 3px; font-size: 8px; font-weight: 700; text-transform: none; }
    .monthly-grid .monthly-weekend { background: #f5f7fa; }
    .monthly-employee { display: grid; gap: 4px; min-width: 236px; padding: 10px 12px; margin: 0; border-radius: 9px; }
    .monthly-employee strong { color: var(--text); font-size: 13px; line-height: 1.3; }
    .monthly-employee span { color: var(--muted); font-size: 11px; }
    .monthly-employee .attendance-code { font-size: 11px; }
    .monthly-employee-meta { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .monthly-day { display: inline-flex; flex-direction: column; align-items: center; justify-content: center; width: 34px; min-height: 36px; padding: 4px 2px; border: 1px solid transparent; border-radius: 6px; background: transparent; color: var(--muted); font: inherit; cursor: pointer; }
    .monthly-day:hover, .monthly-day:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }
    .monthly-day--present { background: #effbf3; border-color: #b7e5c7; color: #18794e; }
    .monthly-day--absent { background: #fff2f2; border-color: #f4b4b4; color: #b42318; }
    .monthly-day--leave { background: #eff6ff; border-color: #b8d2f5; color: #245ea8; }
    .monthly-day--rest_day { background: #f2f4f7; border-color: #d7dde5; color: #687386; }
    .monthly-day--attention { background: #fff7ed; border-color: #fdba74; color: #b45309; }
    .monthly-day-code { font-size: 11px; font-weight: 900; }
    .monthly-day-flag { min-height: 8px; margin-top: 2px; font-size: 7px; font-weight: 900; letter-spacing: .02em; }
    .attendance-employee { display: grid; gap: 3px; min-width: 180px; }
    .attendance-employee strong { color: var(--text); }
    .attendance-employee[data-attendance-employee] { padding: 6px; margin: -6px; border-radius: 8px; cursor: pointer; }
    .attendance-employee[data-attendance-employee]:hover, .attendance-employee[data-attendance-employee]:focus-visible { background: #eef5ff; outline: none; }
    .attendance-code { color: var(--accent); font-size: 11px; font-weight: 800; }
    .attendance-inline-form { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .attendance-inline-form .attendance-input, .attendance-inline-form .attendance-select { width: auto; min-width: 105px; }
    .attendance-inline-form .attendance-button { min-height: 34px; padding: 7px 10px; }
    .schedule-days { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; width: 100%; }
    .schedule-day-option { display: inline-flex; align-items: center; gap: 3px; padding: 4px 5px; border: 1px solid var(--border); border-radius: 5px; background: #fff; color: var(--muted); font-size: 10px; font-weight: 700; cursor: pointer; }
    .schedule-day-option:has(input:checked) { border-color: #9db5d5; background: #eef5ff; color: var(--accent); }
    .schedule-day-option input { margin: 0; accent-color: var(--accent); }
    .attendance-badge { display: inline-flex; padding: 5px 8px; border-radius: 999px; font-size: 10px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .attendance-badge--ready { background: #e5f7ee; color: #18794e; }
    .attendance-badge--needs { background: #fff4d6; color: #946200; }
    .attendance-badge--pending { background: #e8efff; color: #2858a8; }
    .attendance-empty { padding: 40px 18px; text-align: center; color: var(--muted); }
    .attendance-form-panel { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); }
    .attendance-summary-number { color: var(--text); font-weight: 800; }
    .attendance-drawer-backdrop { position: fixed; inset: 0; z-index: 30; display: flex; justify-content: flex-end; background: rgba(18, 32, 51, .38); }
    .attendance-drawer-backdrop[hidden] { display: none; }
    .attendance-drawer { width: min(560px, 100%); height: 100%; overflow-y: auto; padding: 24px; background: #f7faff; box-shadow: -14px 0 36px rgba(18, 32, 51, .18); }
    .attendance-drawer-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
    .attendance-drawer-kicker { margin: 0 0 4px; color: var(--accent); font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .attendance-drawer-title { margin: 0; color: var(--text); font-size: 22px; }
    .attendance-drawer-meta { margin: 5px 0 0; color: var(--muted); font-size: 12px; line-height: 1.5; }
    .attendance-drawer-close { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border: 1px solid var(--border); border-radius: 8px; background: #fff; color: var(--text); cursor: pointer; }
    .attendance-drawer-close svg { width: 16px; height: 16px; }
    .drawer-calendar { padding: 16px; border: 1px solid var(--border); border-radius: 12px; background: #fff; }
    .drawer-calendar[hidden] { display: none; }
    .drawer-calendar-title { margin: 0 0 14px; color: var(--text); font-size: 16px; }
    .drawer-weekdays, .drawer-days { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 5px; }
    .drawer-weekday { padding: 4px 0; color: var(--muted); font-size: 9px; font-weight: 800; text-align: center; text-transform: uppercase; }
    .drawer-blank { min-height: 48px; }
    .drawer-day { min-height: 48px; padding: 5px; border: 1px solid var(--border); border-radius: 7px; font: inherit; font-size: 11px; text-align: left; cursor: pointer; }
    .drawer-day:hover, .drawer-day:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }
    .drawer-day--present { background: #effbf3; border-color: #b7e5c7; color: #18794e; }
    .drawer-day--absent { background: #fff2f2; border-color: #f4b4b4; color: #b42318; }
    .drawer-day--rest_day { background: #f2f4f7; border-color: #d7dde5; color: #687386; }
    .drawer-day--leave { background: #eff6ff; border-color: #b8d2f5; color: #245ea8; }
    .drawer-day--not_recorded { background: #fff; color: var(--muted); }
    .drawer-day--attention { background: #fff7ed; border-color: #fdba74; color: #b45309; }
    .drawer-day-number { display: block; color: var(--text); font-weight: 800; }
    .drawer-day-status { display: block; margin-top: 4px; font-size: 8px; font-weight: 800; text-transform: uppercase; }
    .drawer-day-detail { margin-top: 3px; color: var(--muted); font-size: 9px; }
    .drawer-counts { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 14px; }
    .drawer-count { padding: 6px 8px; border-radius: 7px; background: #eef3f9; color: var(--muted); font-size: 11px; font-weight: 700; }
    .drawer-editor { margin-top: 14px; padding: 16px; border: 1px solid var(--border); border-radius: 12px; background: #fff; }
    .drawer-editor[hidden] { display: none; }
    .drawer-editor-title { margin: 0 0 12px; color: var(--text); font-size: 15px; }
    .drawer-editor-form { display: grid; gap: 9px; }
    .drawer-editor-form label { color: var(--muted); font-size: 10px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .drawer-editor-form input, .drawer-editor-form select, .drawer-editor-form textarea { width: 100%; min-height: 36px; padding: 7px 8px; border: 1px solid var(--border); border-radius: 7px; background: #fff; color: var(--text); font: inherit; font-size: 12px; }
    .drawer-editor-form textarea { min-height: 60px; resize: vertical; }
    .drawer-editor-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .drawer-editor-save { min-height: 38px; border: 0; border-radius: 7px; background: #0f62fe; color: #fff; font-size: 12px; font-weight: 800; cursor: pointer; }
    @media (max-width: 820px) { .attendance-hero { align-items: flex-start; flex-direction: column; } .attendance-stats { grid-template-columns: repeat(2, 1fr); } .attendance-form-panel { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 520px) { .attendance-stats, .attendance-form-panel { grid-template-columns: 1fr; } .attendance-hero { padding: 18px; } }
</style>
@endpush

@section('content')
<div class="attendance-page">
    <section class="attendance-hero">
        <div><p class="attendance-eyebrow">Personnel Operations</p><h1 class="attendance-title">Attendance</h1><p class="attendance-subtitle">Manage daily time records, schedules, leave records, and monthly summaries from the employee directory.</p></div>
        <div class="attendance-hero-meta"><span class="attendance-chip">{{ $periodStart->format('F Y') }}</span><span class="attendance-chip">{{ $isNewMonth ? 'Active employees' : 'Historical period' }}</span></div>
    </section>
    @if(session('success'))<div style="margin-top:14px;padding:11px 14px;border:1px solid #b7e5c7;border-radius:9px;background:#effbf3;color:#18794e;font-size:13px">{{ session('success') }}</div>@endif
    <section class="attendance-stats">
        <article class="attendance-stat"><div class="attendance-stat-label">Employees in view</div><div class="attendance-stat-value">{{ $employees->count() }}</div><div class="attendance-stat-note">Loaded from Employee Directory</div></article>
        <article class="attendance-stat"><div class="attendance-stat-label">Needs schedule</div><div class="attendance-stat-value">{{ $schedules->where('status', 'needs_schedule')->count() + $employees->filter(fn ($employee) => ! $schedules->has($employee->id))->count() }}</div><div class="attendance-stat-note">Requires assignment</div></article>
        <article class="attendance-stat"><div class="attendance-stat-label">Present entries</div><div class="attendance-stat-value">{{ $summary->sum('present') }}</div><div class="attendance-stat-note">For {{ $periodStart->format('F Y') }}</div></article>
        <article class="attendance-stat"><div class="attendance-stat-label">Approved leave</div><div class="attendance-stat-value">{{ number_format((float) $leaveRecords->where('status', 'approved')->sum('days'), 1) }}</div><div class="attendance-stat-note">Leave days in period</div></article>
    </section>

    <section class="attendance-panel">
        <form method="GET" action="{{ route('attendance.index') }}" class="attendance-toolbar">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="attendance-field attendance-field--search"><label class="attendance-label" for="attendance-search">Search Employee</label><input id="attendance-search" class="attendance-input" type="search" name="search" value="{{ $search }}" placeholder="Name or ID number..." autocomplete="off" data-auto-submit-search></div>
            <div class="attendance-field"><label class="attendance-label" for="attendance-month">Period</label><input id="attendance-month" class="attendance-input" type="month" name="month" value="{{ $month }}"></div>
            <div class="attendance-field" style="flex:1 1 180px"><label class="attendance-label" for="attendance-department">Department / Office</label><select id="attendance-department" class="attendance-select" name="department" data-auto-submit-filter><option value="">All departments</option>@foreach($departments as $option)<option value="{{ $option }}" {{ $department === $option ? 'selected' : '' }}>{{ $option }}</option>@endforeach</select></div>
            <button type="submit" class="attendance-button">Apply filters</button><a class="attendance-button attendance-button--muted" href="{{ route('attendance.index', ['month' => $month, 'tab' => $tab]) }}">Clear</a>
        </form>
        <nav class="attendance-tabs" aria-label="Attendance sections">
            @foreach(['dtr' => 'Daily Time Record', 'schedules' => 'Schedules', 'leaves' => 'Leaves', 'summary' => 'Monthly Summary'] as $key => $label)
                <a class="attendance-tab {{ $tab === $key ? 'is-active' : '' }}" href="{{ route('attendance.index', ['tab' => $key, 'month' => $month, 'search' => $search, 'department' => $department]) }}">{{ $label }}</a>
            @endforeach
        </nav>

        @if($tab === 'dtr')
            @php($monthDays = $calendarData->first()['days'] ?? [])
            <div class="monthly-grid-wrap">
                <table class="monthly-grid">
                    <thead>
                        <tr>
                            <th>Employee / Schedule</th>
                            @foreach($monthDays as $day)
                                @php($columnDate = \Illuminate\Support\Carbon::parse($day['date']))
                                <th class="{{ $columnDate->isWeekend() ? 'monthly-weekend' : '' }}" title="{{ $columnDate->format('F j, Y') }}">{{ $day['day'] }}<small>{{ $columnDate->format('D') }}</small></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($employees as $employee)
                        @php($employeeCalendar = $calendarData->get($employee->id))
                        @php($schedule = $schedules->get($employee->id))
                        <tr>
                            <td>
                                <div class="monthly-employee attendance-employee" data-attendance-employee="{{ $employee->id }}" tabindex="0" role="button" aria-label="View attendance for {{ $employee->full_name }}">
                                    <strong>{{ $employee->last_name }}, {{ $employee->first_name }}{{ $employee->middle_name ? ' '.substr($employee->middle_name, 0, 1).'.' : '' }}</strong>
                                    <span class="attendance-code">{{ $employee->employee_number ?: 'No ID number' }}</span>
                                    <span class="monthly-employee-meta">{{ $employee->department ?: 'Department not assigned' }} · {{ $employee->position ?: 'Position not assigned' }}</span>
                                    <span>{{ $schedule && $schedule->shift_start && $schedule->shift_end ? ucfirst($schedule->shift_type ?: 'Assigned').' · '.substr($schedule->shift_start, 0, 5).' - '.substr($schedule->shift_end, 0, 5) : 'Needs schedule' }}</span>
                                </div>
                            </td>
                            @foreach($employeeCalendar['days'] ?? [] as $dayIndex => $day)
                                @php($attention = $day['late_minutes'] > 0 || $day['undertime_minutes'] > 0)
                                @php($statusCode = ['present' => 'P', 'absent' => 'A', 'leave' => 'L', 'rest_day' => 'R', 'not_recorded' => '-'][$day['status']] ?? '-')
                                <td class="{{ \Illuminate\Support\Carbon::parse($day['date'])->isWeekend() ? 'monthly-weekend' : '' }}">
                                    <button type="button" class="monthly-day monthly-day--{{ $day['status'] }} {{ $attention ? 'monthly-day--attention' : '' }}" data-attendance-employee="{{ $employee->id }}" data-drawer-day-index="{{ $dayIndex }}" title="{{ $day['date'] }} - {{ $statusCode }}">
                                        <span class="monthly-day-code">{{ $statusCode }}</span>
                                        <span class="monthly-day-flag">{{ $day['late_minutes'] > 0 ? 'LATE' : ($day['undertime_minutes'] > 0 ? 'UT' : ($day['overtime_minutes'] > 0 ? 'OT' : '')) }}</span>
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($monthDays) + 1 }}"><div class="attendance-empty">No directory employees match this period and filter.</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @elseif($tab === 'schedules')
            <div class="attendance-table-wrap"><table class="attendance-table"><thead><tr><th>Employee</th><th>Department / Section</th><th>Current Schedule</th><th>Update Schedule</th></tr></thead><tbody>
            @forelse($employees as $employee)
                @php($schedule = $schedules->get($employee->id))
                <tr><td><div class="attendance-employee"><strong>{{ $employee->last_name }}, {{ $employee->first_name }}</strong><span class="attendance-code">{{ $employee->employee_number ?: 'No ID number' }}</span></div></td><td>{{ $employee->department ?: '-' }}<div class="attendance-muted">{{ $employee->section ?: 'Section not assigned' }}</div></td><td>@if($schedule && $schedule->shift_start && $schedule->shift_end)<span class="attendance-badge attendance-badge--ready">{{ ucfirst($schedule->shift_type ?: 'Assigned') }}</span> {{ substr($schedule->shift_start, 0, 5) }} - {{ substr($schedule->shift_end, 0, 5) }}@else<span class="attendance-badge attendance-badge--needs">Needs schedule</span>@endif</td><td>@if($canManage)<form method="POST" action="{{ route('attendance.schedules.store') }}" class="attendance-inline-form">@csrf<input type="hidden" name="employee_id" value="{{ $employee->id }}"><select class="attendance-select" name="shift_type"><option value="">Shift type</option><option value="morning" {{ $schedule?->shift_type === 'morning' ? 'selected' : '' }}>Morning</option><option value="mid" {{ $schedule?->shift_type === 'mid' ? 'selected' : '' }}>Mid</option><option value="night" {{ $schedule?->shift_type === 'night' ? 'selected' : '' }}>Night</option></select><input class="attendance-input" type="time" name="shift_start" value="{{ $schedule?->shift_start ? substr($schedule->shift_start, 0, 5) : '' }}"><input class="attendance-input" type="time" name="shift_end" value="{{ $schedule?->shift_end ? substr($schedule->shift_end, 0, 5) : '' }}"><input type="hidden" name="effective_from" value="{{ $periodStart->toDateString() }}"><div class="schedule-days">@php($selectedWorkingDays = $schedule?->working_days ?: ["mon", "tue", "wed", "thu", "fri"]) @foreach(["mon" => "Mon", "tue" => "Tue", "wed" => "Wed", "thu" => "Thu", "fri" => "Fri", "sat" => "Sat", "sun" => "Sun"] as $dayValue => $dayLabel)<label class="schedule-day-option"><input type="checkbox" name="working_days[]" value="{{ $dayValue }}" {{ in_array($dayValue, $selectedWorkingDays, true) ? "checked" : "" }}>{{ $dayLabel }}</label>@endforeach</div><button class="attendance-button" type="submit">Save</button></form>@else<span class="attendance-muted">Admin access required.</span>@endif</td></tr>
            @empty
                <tr><td colspan="4"><div class="attendance-empty">No employees found.</div></td></tr>
            @endforelse
            </tbody></table></div>
        @elseif($tab === 'leaves')
            @if($canManage)<form method="POST" action="{{ route('attendance.leaves.store') }}" class="attendance-form-panel">@csrf<div class="attendance-field"><label class="attendance-label">Employee</label><select class="attendance-select" name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->last_name }}, {{ $employee->first_name }}</option>@endforeach</select></div><div class="attendance-field"><label class="attendance-label">Leave category</label><select class="attendance-select" name="leave_category" required><option value="Regular">Regular Leave</option><option value="Special">Special Leave</option><option value="Other">Other Leave</option></select></div><div class="attendance-field"><label class="attendance-label">Leave type</label><select class="attendance-select" name="leave_type" required><option>Vacation Leave</option><option>Sick Leave</option><option>CTO</option><option>Wellness Leave</option><option>Other Leave</option></select></div><div class="attendance-field"><label class="attendance-label">Start / End</label><div style="display:flex;gap:6px"><input class="attendance-input" type="date" name="start_date" required><input class="attendance-input" type="date" name="end_date" required></div></div><div class="attendance-field"><label class="attendance-label">Days / Status</label><div style="display:flex;gap:6px"><input class="attendance-input" type="number" name="days" min="0.5" step="0.5" placeholder="Days" required><select class="attendance-select" name="status"><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select><button type="submit" class="attendance-button">Add leave</button></div></div></form>@endif
            <div class="attendance-table-wrap"><table class="attendance-table"><thead><tr><th>Employee</th><th>Category / Type</th><th>Dates</th><th>Days</th><th>Status</th><th>Leave credits</th></tr></thead><tbody>
            @forelse($leaveRecords as $leave)
                @php($credit = $credits->get($leave->employee_id))
                <tr><td><div class="attendance-employee"><strong>{{ $leave->employee?->last_name }}, {{ $leave->employee?->first_name }}</strong><span class="attendance-code">{{ $leave->employee?->employee_number ?: 'No ID number' }}</span></div></td><td>{{ $leave->leave_category ? $leave->leave_category . ' / ' : '' }}{{ $leave->leave_type }}</td><td>{{ $leave->start_date->format('M j, Y') }} - {{ $leave->end_date->format('M j, Y') }}</td><td>{{ number_format((float) $leave->days, 1) }}</td><td><span class="attendance-badge attendance-badge--{{ $leave->status === 'approved' ? 'ready' : ($leave->status === 'pending' ? 'pending' : 'needs') }}">{{ $leave->status }}</span></td><td>VL {{ $credit?->vacation_leave ?? '0.00' }} / SL {{ $credit?->sick_leave ?? '0.00' }} / SpL {{ $credit?->special_leave ?? '0.00' }}</td></tr>
            @empty
                <tr><td colspan="6"><div class="attendance-empty">No leave records overlap this month.</div></td></tr>
            @endforelse
            </tbody></table></div>
        @else
            <div class="attendance-table-wrap"><table class="attendance-table"><thead><tr><th>Employee</th><th>Schedule</th><th>Present</th><th>Absent</th><th>Leave entries</th><th>Late minutes</th><th>Approved leave days</th></tr></thead><tbody>
            @forelse($summary as $row)
                <tr><td><div class="attendance-employee"><strong>{{ $row['employee']->last_name }}, {{ $row['employee']->first_name }}</strong><span class="attendance-code">{{ $row['employee']->employee_number ?: 'No ID number' }}</span></div></td><td>@if($row['schedule'] && $row['schedule']->shift_start && $row['schedule']->shift_end){{ ucfirst($row['schedule']->shift_type ?: 'Assigned') }}<div class="attendance-muted">{{ substr($row['schedule']->shift_start, 0, 5) }} - {{ substr($row['schedule']->shift_end, 0, 5) }}</div>@else<span class="attendance-badge attendance-badge--needs">Needs schedule</span>@endif</td><td class="attendance-summary-number">{{ $row['present'] }}</td><td class="attendance-summary-number">{{ $row['absent'] }}</td><td class="attendance-summary-number">{{ $row['leave'] }}</td><td>{{ $row['late'] }}</td><td>{{ number_format((float) $row['leave_days'], 1) }}</td></tr>
            @empty
                <tr><td colspan="7"><div class="attendance-empty">No employees found.</div></td></tr>
            @endforelse
            </tbody></table></div>
        @endif
    </section>
</div>
@endsection

<div class="attendance-drawer-backdrop" data-attendance-backdrop hidden>
    <aside class="attendance-drawer" role="dialog" aria-modal="true" aria-labelledby="attendance-drawer-title">
        <div class="attendance-drawer-head">
            <div><p class="attendance-drawer-kicker">Employee Attendance</p><h2 class="attendance-drawer-title" id="attendance-drawer-title">Attendance</h2><p class="attendance-drawer-meta" data-attendance-meta></p></div>
            <button type="button" class="attendance-drawer-close" data-attendance-close aria-label="Close attendance details"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        </div>
        <div class="drawer-calendar"><h3 class="drawer-calendar-title">{{ $periodStart->format('F Y') }}</h3><div class="drawer-weekdays"><span class="drawer-weekday">Sun</span><span class="drawer-weekday">Mon</span><span class="drawer-weekday">Tue</span><span class="drawer-weekday">Wed</span><span class="drawer-weekday">Thu</span><span class="drawer-weekday">Fri</span><span class="drawer-weekday">Sat</span></div><div class="drawer-days" data-drawer-days></div><div class="drawer-counts" data-drawer-counts></div></div>
        @if($canManage)
            <div class="drawer-editor" data-drawer-editor hidden>
                <h3 class="drawer-editor-title">Edit selected day</h3>
                <form method="POST" action="{{ route('attendance.entries.store') }}" class="drawer-editor-form" data-drawer-editor-form>
                    @csrf
                    <input type="hidden" name="employee_id" data-drawer-input="employee">
                    <input type="hidden" name="work_date" data-drawer-input="date">
                    <label>Status</label>
                    <select name="status" data-drawer-input="status"><option value="present">Present</option><option value="absent">Absent</option><option value="leave">Leave</option><option value="rest_day">Rest day</option></select>
                    <div class="drawer-editor-grid"><div><label>Time in</label><input type="time" name="time_in" data-drawer-input="time-in"></div><div><label>Time out</label><input type="time" name="time_out" data-drawer-input="time-out"></div><div><label>Late minutes</label><input type="number" name="late_minutes" min="0" max="1440" data-drawer-input="late"></div><div><label>OT minutes</label><input type="number" name="overtime_minutes" min="0" max="1440" data-drawer-input="overtime"></div><div><label>Undertime</label><input type="number" name="undertime_minutes" min="0" max="1440" data-drawer-input="undertime"></div></div>
                    <label>Remarks</label><textarea name="remarks" data-drawer-input="remarks" placeholder="Optional note"></textarea>
                    <button type="submit" class="drawer-editor-save">Save selected day</button>
                </form>
            </div>
        @endif
    </aside>
</div>

@push('scripts')
<script>
    (() => {
        const attendanceData = @json($calendarData);
        const backdrop = document.querySelector('[data-attendance-backdrop]');
        const meta = document.querySelector('[data-attendance-meta]');
        const daysContainer = document.querySelector('[data-drawer-days]');
        const countsContainer = document.querySelector('[data-drawer-counts]');
        const drawerCalendar = document.querySelector('.drawer-calendar');
        const drawerEditor = document.querySelector('[data-drawer-editor]');
        const drawerForm = document.querySelector('[data-drawer-editor-form]');
        const statusLabels = { present: 'Present', absent: 'Absent', rest_day: 'Rest', leave: 'Leave', not_recorded: 'No record' };
        let activeEmployee = null;

        if (!backdrop || !daysContainer || !countsContainer) return;

        const searchInput = document.querySelector('[data-auto-submit-search]');
        const searchForm = searchInput?.closest('form');
        let searchTimer;
        searchInput?.addEventListener('input', () => {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(() => searchForm?.requestSubmit(), 350);
        });
        document.querySelector('[data-auto-submit-filter]')?.addEventListener('change', () => searchForm?.requestSubmit());

        const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[character]));
        const openDrawer = (employeeId) => {
            const employee = attendanceData[employeeId];
            if (!employee) return;
            activeEmployee = employee;
            if (drawerEditor) drawerEditor.hidden = true;
            if (drawerCalendar) drawerCalendar.hidden = false;
            document.getElementById('attendance-drawer-title').textContent = employee.name;
            meta.textContent = `${employee.employee_number} | ${employee.department} | ${employee.section} | ${employee.position}`;
            const firstDate = new Date(`${employee.days[0].date}T00:00:00`);
            const leadingBlanks = firstDate.getDay();
            daysContainer.innerHTML = `${'<span class="drawer-blank"></span>'.repeat(leadingBlanks)}${employee.days.map((day, index) => `<button type="button" class="drawer-day drawer-day--${escapeHtml(day.status)}${day.late_minutes > 0 || day.undertime_minutes > 0 ? ' drawer-day--attention' : ''}" data-drawer-day-index="${index}"><span class="drawer-day-number">${day.day}</span><span class="drawer-day-status">${statusLabels[day.status] || escapeHtml(day.status)}</span><span class="drawer-day-detail">${day.time_in || day.time_out ? `${escapeHtml(day.time_in || '-')} - ${escapeHtml(day.time_out || '-')}` : day.leave_type ? escapeHtml(day.leave_type) : ''}</span></button>`).join('')}`;
            countsContainer.innerHTML = Object.entries(employee.counts).map(([status, count]) => `<span class="drawer-count">${statusLabels[status] || status}: ${count}</span>`).join('');
            backdrop.hidden = false;
            document.body.style.overflow = 'hidden';
        };
        const openDirectDayEditor = (employeeId, dayIndex) => {
            const employee = attendanceData[employeeId];
            if (!employee || !drawerEditor) return;
            openDrawer(employeeId);
            if (drawerCalendar) drawerCalendar.hidden = true;
            openDayEditor(employee.days[Number(dayIndex)]);
        };
        const openDayEditor = (day) => {
            if (!drawerEditor || !drawerForm || !activeEmployee) return;
            const set = (name, value) => drawerForm.querySelector(`[data-drawer-input="${name}"]`).value = value ?? '';
            set('employee', activeEmployee.id);
            set('date', day.date);
            set('status', day.status === 'not_recorded' ? 'present' : day.status);
            set('time-in', day.time_in);
            set('time-out', day.time_out);
            set('late', day.late_minutes);
            set('overtime', day.overtime_minutes);
            set('undertime', day.undertime_minutes);
            set('remarks', day.remarks);
            drawerEditor.hidden = false;
            drawerEditor.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        };
        const closeDrawer = () => { backdrop.hidden = true; if (drawerCalendar) drawerCalendar.hidden = false; document.body.style.overflow = ''; };

        document.querySelectorAll('[data-attendance-employee]').forEach((employeeCell) => {
            const openEmployee = () => {
                const isUnrecordedDay = employeeCell.classList.contains('monthly-day--not_recorded');
                const dayIndex = employeeCell.dataset.drawerDayIndex;
                if (isUnrecordedDay && drawerEditor && dayIndex !== undefined) {
                    openDirectDayEditor(employeeCell.dataset.attendanceEmployee, dayIndex);
                    return;
                }
                openDrawer(employeeCell.dataset.attendanceEmployee);
            };
            employeeCell.addEventListener('click', openEmployee);
            employeeCell.addEventListener('keydown', (event) => { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openEmployee(); } });
        });
        daysContainer.addEventListener('click', (event) => {
            const dayButton = event.target.closest('[data-drawer-day-index]');
            if (dayButton && activeEmployee) openDayEditor(activeEmployee.days[Number(dayButton.dataset.drawerDayIndex)]);
        });
        document.querySelector('[data-attendance-close]')?.addEventListener('click', closeDrawer);
        backdrop.addEventListener('click', (event) => { if (event.target === backdrop) closeDrawer(); });
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !backdrop.hidden) closeDrawer(); });
    })();
</script>
@endpush





