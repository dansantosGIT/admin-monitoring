@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">

@section('page-name', 'Dashboard')

    {{-- TOP STATS ROW --}}
    <div class="stats-grid">

        <div class="stat-card" id="totalEmployeesCard" role="button" tabindex="0">
            <div class="stat-card__icon stat-card__icon--employees">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Total Employees</span>
                <span class="stat-card__badge">Total</span>
                <p class="stat-card__value">{{ $stats['total_employees'] ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Active Employees</span>
                <p class="stat-card__value stat-card__value--green">{{ $stats['active_employees'] ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--inactive">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                </svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Inactive Employees</span>
                <p class="stat-card__value stat-card__value--red">{{ $stats['inactive_employees'] ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--reports">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div class="stat-card__body">
                <span class="stat-card__label">Pending IRs</span>
                <p class="stat-card__value">{{ $stats['pending_irs'] ?? 0 }}</p>
            </div>
        </div>

    </div>
    {{-- END TOP STATS ROW --}}

    {{-- MAIN CONTENT AREA --}}
    <div class="main-content">

        {{-- LEFT COLUMN --}}
        <div class="left-col">

            {{-- ATTENDANCE OVERVIEW CHART --}}
            <div class="card">
                <div class="card__header">
                    <div>
                        <h2 class="card__title">Attendance Overview</h2>
                        <p class="card__subtitle">{{ \Carbon\Carbon::now()->format('F Y') }} &bull; All employees</p>
                    </div>
                    <div class="filter-group">
                        <select class="filter-select" id="attendanceFilter">
                            <option value="all">All</option>
                            <option value="permanent">Permanent</option>
                            <option value="job_order">Job Order</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-dot legend-dot--present"></span> Present</span>
                    <span class="legend-item"><span class="legend-dot legend-dot--absent"></span> Absent</span>
                    <span class="legend-item"><span class="legend-dot legend-dot--leave"></span> OB/Leave</span>
                </div>
            </div>
            {{-- END ATTENDANCE OVERVIEW --}}

            {{-- TODAY'S ATTENDANCE --}}
            <div class="card">
                <div class="card__header">
                    <div>
                        <h2 class="card__title">Today's Attendance</h2>
                        <p class="card__subtitle">{{ \Carbon\Carbon::today()->format('F d, Y') }}</p>
                    </div>
                    <a href="{{ \Illuminate\Support\Facades\Route::has('attendance.index') ? route('attendance.index') : route('reports.index') }}" class="btn btn--ghost">View all</a>
                </div>
                <div class="attendance-list">
                    @forelse($todayAttendance ?? [] as $record)
                    <div class="attendance-item">
                        <div class="employee-avatar">
                            @if($record->employee->photo)
                                <img src="{{ asset('storage/'.$record->employee->photo) }}" alt="{{ $record->employee->full_name }}">
                            @else
                                <div class="avatar-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="employee-info">
                            <p class="employee-name">{{ $record->employee->full_name }}</p>
                            <p class="employee-type">{{ ucfirst(str_replace('_', ' ', $record->employee->employment_type)) }}</p>
                        </div>
                        <div class="attendance-status">
                            @switch($record->status)
                                @case('present')
                                    <span class="badge badge--present">Present</span>
                                    @break
                                @case('absent')
                                    <span class="badge badge--absent">Absent</span>
                                    @break
                                @case('sick_leave')
                                    <span class="badge badge--leave">Sick Leave</span>
                                    @break
                                @case('official_business')
                                    <span class="badge badge--ob">Official Business</span>
                                    @break
                                @default
                                    <span class="badge badge--default">{{ ucfirst($record->status) }}</span>
                            @endswitch
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <p>No attendance records for today.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            {{-- END TODAY'S ATTENDANCE --}}

        </div>
        {{-- END LEFT COLUMN --}}

        {{-- RIGHT COLUMN --}}
        <div class="right-col">

            {{-- MINI CALENDAR --}}
            <div class="card">
                <div class="card__header">
                    <div>
                        <h2 class="card__title">Calendar</h2>
                        <p class="card__subtitle">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                    </div>
                </div>
                <div class="mini-calendar">
                    <div class="cal-days-header">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                            <span>{{ $day }}</span>
                        @endforeach
                    </div>
                    <div class="cal-days-grid">
                        @php
                            $now = \Carbon\Carbon::now();
                            $startOfMonth = $now->copy()->startOfMonth();
                            $endOfMonth = $now->copy()->endOfMonth();
                            $startPad = $startOfMonth->dayOfWeek;
                        @endphp
                        @for($i = 0; $i < $startPad; $i++)
                            <span></span>
                        @endfor
                        @for($day = 1; $day <= $endOfMonth->day; $day++)
                            @php $isToday = $day === (int)$now->format('j'); @endphp
                            <span class="cal-day{{ $isToday ? ' cal-day--today' : '' }}">{{ $day }}</span>
                        @endfor
                    </div>
                </div>
            </div>
            {{-- END MINI CALENDAR --}}

            {{-- RECENT VIOLATIONS --}}
            <div class="card">
                <div class="card__header">
                    <div>
                        <h2 class="card__title">Recent Violations</h2>
                        <p class="card__subtitle">This month</p>
                    </div>
                    <a href="{{ \Illuminate\Support\Facades\Route::has('violations.index') ? route('violations.index') : route('reports.index') }}" class="btn btn--ghost">View all</a>
                </div>
                <div class="violations-list">
                    @forelse($recentViolations ?? [] as $index => $violation)
                    <div class="violation-item">
                        <span class="violation-rank">{{ $index + 1 }}</span>
                        <div class="employee-info">
                            <p class="employee-name">{{ $violation->employee->full_name }}</p>
                            <p class="employee-type">{{ ucfirst(str_replace('_', ' ', $violation->employee->employment_type)) }}</p>
                        </div>
                        <span class="badge {{ $violation->severity === 'major' ? 'badge--major' : 'badge--minor' }}">
                            {{ ucfirst($violation->severity) }}
                        </span>
                    </div>
                    @empty
                    <div class="empty-state"><p>No violations this month.</p></div>
                    @endforelse
                </div>
            </div>
            {{-- END RECENT VIOLATIONS --}}

            {{-- STAFF BREAKDOWN --}}
            <div class="card">
                <div class="card__header">
                    <div>
                        <h2 class="card__title">Staff Breakdown</h2>
                        <p class="card__subtitle">By employment type</p>
                    </div>
                </div>
                <div class="breakdown-list">
                    <div class="breakdown-item">
                        <span class="breakdown-label breakdown-label--job-order">Job Order</span>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar breakdown-bar--red" style="width: {{ $stats['total_employees'] > 0 ? ($stats['job_order_count'] / $stats['total_employees']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="breakdown-count">{{ $stats['job_order_count'] ?? 0 }}</span>
                    </div>
                    <div class="breakdown-item">
                        <span class="breakdown-label breakdown-label--permanent">Permanent</span>
                        <div class="breakdown-bar-wrap">
                            <div class="breakdown-bar breakdown-bar--blue" style="width: {{ $stats['total_employees'] > 0 ? ($stats['permanent_count'] / $stats['total_employees']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="breakdown-count">{{ $stats['permanent_count'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="breakdown-summary">
                    <div class="breakdown-row">
                        <span>Present today</span>
                        <span class="breakdown-row__value breakdown-row__value--green">{{ $stats['present_today'] ?? 0 }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>On leave / OB</span>
                        <span class="breakdown-row__value breakdown-row__value--orange">{{ $stats['on_leave_today'] ?? 0 }}</span>
                    </div>
                    <div class="breakdown-row">
                        <span>Absent</span>
                        <span class="breakdown-row__value breakdown-row__value--red">{{ $stats['absent_today'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="card__footer">
                    <a href="{{ \Illuminate\Support\Facades\Route::has('reports.monthly') ? route('reports.monthly') : route('reports.index') }}" class="btn btn--primary btn--full">Export Monthly Report</a>
                </div>
            </div>
            {{-- END STAFF BREAKDOWN --}}

        </div>
        {{-- END RIGHT COLUMN --}}

    </div>
    {{-- END MAIN CONTENT AREA --}}

</div>

<!-- Employees preview panel (client-side only, opens when Total Employees clicked) -->
<div class="employees-preview-overlay" id="employeesPreview">
    <div class="employees-panel" role="dialog" aria-modal="true" aria-labelledby="employeesTitle">
        <div class="employees-panel__header">
            <div>
                <div id="employeesTitle" class="employees-panel__title">Registered Employees</div>
                <div style="font-size:12px;color:#6b7280;margin-top:4px;">Browse and filter registered employees</div>
            </div>
            <div class="employees-panel__controls">
                <input id="empSearch" type="search" placeholder="Search name…" style="padding:8px 10px;border:1px solid #e5e7eb;border-radius:8px;min-width:220px;">
                <select id="empSort" style="padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                    <option value="name_asc">Name A–Z</option>
                    <option value="name_desc">Name Z–A</option>
                    <option value="age_asc">Age ↑</option>
                    <option value="age_desc">Age ↓</option>
                    <option value="gender">Gender</option>
                    <option value="position">Position</option>
                    <option value="department">Department</option>
                </select>
                <select id="empDept" style="padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                    <option value="all">All Departments</option>
                    <option value="OPERATIONS">OPERATIONS</option>
                    <option value="CEDOC">CEDOC</option>
                    <option value="PLANNING">PLANNING</option>
                    <option value="ADMIN">ADMIN</option>
                </select>
                <select id="empType" style="padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                    <option value="all">All Types</option>
                    <option value="permanent">Permanent</option>
                    <option value="job_order">Job Order</option>
                </select>
                <button id="closeEmployees" class="btn" style="background:#fff;border:1px solid #e5e7eb;padding:8px;border-radius:8px;">Close</button>
            </div>
        </div>
        <div class="employees-panel__body">
            <div class="employees-preview-grid">
                <aside class="employees-preview-card" id="employeePreviewCard">
                    <div class="employee-preview-card__eyebrow">Selected profile</div>
                    <div class="employee-preview-card__name" id="employeePreviewName">Choose an employee</div>
                    <div class="employee-preview-card__sub">Monitoring preview • placeholder data until backend is connected</div>
                    <div class="employee-preview-grid">
                        <div class="employee-preview-item"><span>Employee No.</span><strong id="employeePreviewNo">—</strong></div>
                        <div class="employee-preview-item"><span>Section</span><strong id="employeePreviewSection">—</strong></div>
                        <div class="employee-preview-item"><span>Department</span><strong id="employeePreviewDept">—</strong></div>
                        <div class="employee-preview-item"><span>Age</span><strong id="employeePreviewAge">—</strong></div>
                        <div class="employee-preview-item"><span>Birthdate</span><strong id="employeePreviewBirth">—</strong></div>
                        <div class="employee-preview-item"><span>Employment</span><strong id="employeePreviewType">—</strong></div>
                    </div>
                    <div class="employee-preview-item employee-preview-item--full"><span>Status</span><strong id="employeePreviewStatus">—</strong></div>
                    <div class="employee-preview-note" id="employeePreviewNote">Select any registered employee row to preview their monitoring details.</div>
                </aside>
                <div style="overflow:auto;max-height:420px;">
                    <table class="employees-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Position / Section</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody id="employeesTbody">
                        <!-- rendered by JS -->
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="employees-panel__footer">
            <div id="employeesCount" style="color:#6b7280;font-size:13px;">0 employees</div>
            <div class="pagination" id="employeesPagination">
                <!-- page buttons -->
            </div>
        </div>
    </div>
</div>

{{-- Inline styles scoped to this dashboard --}}
<style>
/* ── Layout ─────────────────────────────────────────── */
.dashboard-wrapper {
    padding: 1.5rem;
    background: #f3f4f6;
    min-height: 100vh;
}

/* ── Stats grid ─────────────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 640px)  { .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: #fff;
    border-radius: 0.75rem;
    padding: 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.07);
}
.stat-card__icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-card__icon svg { width: 1.4rem; height: 1.4rem; }
.stat-card__icon--employees { background: #fce8e8; color: #c0392b; }
.stat-card__icon--active    { background: #e6f9f0; color: #27ae60; }
.stat-card__icon--inactive  { background: #fff3e0; color: #e67e22; }
.stat-card__icon--reports   { background: #eef2ff; color: #4f46e5; }

.stat-card__body { flex: 1; }
.stat-card__label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 0.25rem;
}
.stat-card__badge {
    float: right;
    font-size: 0.65rem;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 0.3rem;
    padding: 0.1rem 0.4rem;
}
.stat-card__value {
    font-size: 2rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
    margin: 0;
}
.stat-card__value--green { color: #16a34a; }
.stat-card__value--red   { color: #dc2626; }

/* ── Two-column main layout ─────────────────────────── */
.main-content {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 1024px) {
    .main-content { grid-template-columns: 1fr; }
}

/* ── Card base ──────────────────────────────────────── */
.card {
    background: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.07);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.card__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1.25rem 0.75rem;
}
.card__title   { font-size: 0.95rem; font-weight: 700; color: #111827; margin: 0; }
.card__subtitle{ font-size: 0.75rem; color: #9ca3af; margin: 0.1rem 0 0; }
.card__footer  { padding: 1rem 1.25rem; border-top: 1px solid #f3f4f6; }

/* ── Buttons ────────────────────────────────────────── */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.35rem 0.85rem;
    border-radius: 0.4rem;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s;
}
.btn--ghost   { background: #fee2e2; color: #b91c1c; }
.btn--ghost:hover { background: #fecaca; }
.btn--primary { background: #c0392b; color: #fff; }
.btn--primary:hover { background: #a93226; }
.btn--full    { width: 100%; }

/* ── Attendance chart ───────────────────────────────── */
.chart-container { padding: 0 1.25rem 0.5rem; height: 180px; }
.chart-legend {
    display: flex;
    gap: 1.25rem;
    padding: 0 1.25rem 1rem;
}
.legend-item { display: flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; color: #6b7280; }
.legend-dot  { width: 10px; height: 10px; border-radius: 2px; }
.legend-dot--present { background: #dc2626; }
.legend-dot--absent  { background: #d1d5db; }
.legend-dot--leave   { background: #fbbf24; }

.filter-select {
    font-size: 0.78rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.4rem;
    padding: 0.3rem 0.6rem;
    color: #374151;
    background: #fff;
    cursor: pointer;
}

/* ── Attendance list ────────────────────────────────── */
.attendance-list { padding: 0 1.25rem 1rem; }
.attendance-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.65rem 0;
    border-bottom: 1px solid #f9fafb;
}
.attendance-item:last-child { border-bottom: none; }

.employee-avatar { flex-shrink: 0; }
.employee-avatar img,
.avatar-placeholder {
    width: 2.25rem; height: 2.25rem;
    border-radius: 50%;
    object-fit: cover;
    background: #f3f4f6;
    display: flex; align-items: center; justify-content: center;
}
.avatar-placeholder svg { width: 1.1rem; height: 1.1rem; color: #9ca3af; }

.employee-info { flex: 1; }
.employee-name { font-size: 0.82rem; font-weight: 600; color: #111827; margin: 0; }
.employee-type { font-size: 0.72rem; color: #9ca3af; margin: 0; }

/* ── Badges ─────────────────────────────────────────── */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge--present  { background: #dcfce7; color: #166534; }
.badge--absent   { background: #fee2e2; color: #991b1b; }
.badge--leave    { background: #fef9c3; color: #854d0e; }
.badge--ob       { background: #dbeafe; color: #1e40af; }
.badge--default  { background: #f3f4f6; color: #374151; }
.badge--major    { background: #fee2e2; color: #991b1b; }
.badge--minor    { background: #fef9c3; color: #854d0e; }

/* ── Mini calendar ──────────────────────────────────── */
.mini-calendar { padding: 0 1.25rem 1rem; }
.cal-days-header {
    display: grid;
    grid-template-columns: repeat(7,1fr);
    text-align: center;
    font-size: 0.7rem;
    font-weight: 600;
    color: #9ca3af;
    margin-bottom: 0.4rem;
}
.cal-days-grid {
    display: grid;
    grid-template-columns: repeat(7,1fr);
    text-align: center;
    gap: 2px;
}
.cal-day {
    font-size: 0.75rem;
    color: #374151;
    padding: 0.3rem 0;
    border-radius: 50%;
    cursor: default;
}
.cal-day--today {
    background: #c0392b;
    color: #fff;
    font-weight: 700;
    border-radius: 50%;
}

/* ── Violations list ────────────────────────────────── */
.violations-list { padding: 0 1.25rem 1rem; }
.violation-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0;
    border-bottom: 1px solid #f9fafb;
}
.violation-item:last-child { border-bottom: none; }
.violation-rank {
    width: 1.6rem; height: 1.6rem;
    background: #f3f4f6;
    border-radius: 0.35rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: #374151;
    flex-shrink: 0;
}

/* ── Staff breakdown ────────────────────────────────── */
.breakdown-list { padding: 0 1.25rem 0.75rem; }
.breakdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}
.breakdown-label { font-size: 0.78rem; font-weight: 600; min-width: 80px; }
.breakdown-label--job-order { color: #c0392b; }
.breakdown-label--permanent { color: #1d4ed8; }
.breakdown-bar-wrap {
    flex: 1;
    background: #f3f4f6;
    border-radius: 999px;
    height: 8px;
}
.breakdown-bar {
    height: 100%;
    border-radius: 999px;
    min-width: 4px;
    transition: width 0.5s ease;
}
.breakdown-bar--red  { background: #c0392b; }
.breakdown-bar--blue { background: #1d4ed8; }
.breakdown-count { font-size: 0.8rem; font-weight: 700; color: #111827; min-width: 24px; text-align: right; }

.breakdown-summary { padding: 0.75rem 1.25rem; border-top: 1px solid #f3f4f6; }
.breakdown-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.3rem 0;
    font-size: 0.82rem;
    color: #374151;
}
.breakdown-row__value { font-weight: 700; font-size: 0.88rem; }
.breakdown-row__value--green  { color: #16a34a; }
.breakdown-row__value--orange { color: #d97706; }
.breakdown-row__value--red    { color: #dc2626; }

/* ── Empty state ────────────────────────────────────── */
.empty-state { padding: 1.5rem 0; text-align: center; color: #9ca3af; font-size: 0.82rem; }

/* ── Employees preview (mini panel) ───────────────────── */
.employees-preview-overlay {
    position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(2,6,23,0.4); z-index: 1000;
}
.employees-preview-overlay.open { display: flex; }
.employees-panel {
    width: min(940px, 95%); max-height: 80vh; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(2,6,23,0.2); overflow: hidden; display:flex; flex-direction:column;
}
.employees-panel__header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px; border-bottom:1px solid #f3f4f6; }
.employees-panel__title { font-weight:700; color:#111827; }
.employees-panel__controls { display:flex; gap:8px; align-items:center; }
.employees-panel__body { padding:12px; overflow:auto; }
.employees-table { width:100%; border-collapse:collapse; }
.employees-preview-grid { display:grid; grid-template-columns: 320px 1fr; gap:12px; align-items:start; }
@media (max-width: 980px) { .employees-preview-grid { grid-template-columns: 1fr; } }
.employees-preview-card { background:#fff7f7; border:1px solid #fde2e2; border-radius:12px; padding:12px; box-shadow: inset 0 0 0 1px rgba(192,57,43,0.04); }
.employee-preview-card__eyebrow { font-size:11px; text-transform:uppercase; letter-spacing:.12em; color:#c0392b; font-weight:700; }
.employee-preview-card__name { font-size:18px; font-weight:800; color:#111827; margin-top:4px; }
.employee-preview-card__sub { font-size:12px; color:#6b7280; margin-top:4px; margin-bottom:10px; }
.employee-preview-grid { display:grid; grid-template-columns: 1fr 1fr; gap:8px; }
.employee-preview-item { background:#fff; border:1px solid #f3f4f6; border-radius:10px; padding:8px; }
.employee-preview-item span { display:block; font-size:11px; color:#6b7280; text-transform:uppercase; letter-spacing:.08em; margin-bottom:3px; }
.employee-preview-item strong { font-size:13px; color:#111827; }
.employee-preview-item--full { margin-top:8px; }
.employee-preview-note { margin-top:10px; font-size:12px; color:#6b7280; }
.employees-table thead th { text-align:left; font-size:12px; color:#6b7280; padding:8px; border-bottom:1px solid #f3f4f6; }
.employees-table tbody tr { transition: background .12s, filter .12s; cursor: pointer; }
.employees-table tbody tr:hover { background:#fff7f7; filter:contrast(1.02); }
.employee-row { display:flex; gap:12px; align-items:center; padding:10px 8px; }
.employee-avatar-sm { width:36px; height:36px; border-radius:50%; background:#fce8e8; color:#c0392b; display:flex; align-items:center; justify-content:center; font-weight:700; }
.employees-panel__footer { padding:12px; border-top:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; gap:12px; }
.pagination { display:flex; gap:6px; }
.page-btn { padding:6px 9px; background:#fff; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; font-size:13px; }
.page-btn.active { background:#c0392b; color:#fff; border-color:#c0392b; }
.filter-pill { padding:6px 8px; border-radius:8px; border:1px solid #e5e7eb; background:#fff; cursor:pointer; font-size:13px; }
.stat-card[role="button"] { cursor:pointer; }
.stat-card[role="button"]:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,24,40,0.06); }

</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Attendance chart data passed from controller
    const chartLabels  = @json($attendanceChart['labels'] ?? []);
    const presentData  = @json($attendanceChart['present'] ?? []);
    const absentData   = @json($attendanceChart['absent'] ?? []);
    const leaveData    = @json($attendanceChart['leave'] ?? []);

    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Present',
                    data: presentData,
                    backgroundColor: '#dc2626',
                    borderRadius: 4,
                    barPercentage: 0.55,
                },
                {
                    label: 'Absent',
                    data: absentData,
                    backgroundColor: '#d1d5db',
                    borderRadius: 4,
                    barPercentage: 0.55,
                },
                {
                    label: 'OB/Leave',
                    data: leaveData,
                    backgroundColor: '#fbbf24',
                    borderRadius: 4,
                    barPercentage: 0.55,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { stacked: false, grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { stacked: false, grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 }, stepSize: 1 }, beginAtZero: true },
            },
        },
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Employees data: try server-provided list, otherwise fallback to sample
    const employeesFromServer = @json($employees ?? null);
    const sampleEmployees = [
        {id:1, employee_number:'EMP-2026-001', full_name:'Pedro, John F.', age:32, birthdate:'1993-05-30', gender:'Male', position:'Monitoring Officer', section:'Operations', department:'OPERATIONS', employment_type:'permanent', status:'Active', remarks:'Primary monitoring staff for daily field verification.'},
        {id:2, employee_number:'EMP-2026-002', full_name:'Reyes, Maria A.', age:29, birthdate:'1996-11-12', gender:'Female', position:'Admin Coordinator', section:'Admin', department:'ADMIN', employment_type:'permanent', status:'Active', remarks:'Handles staff documentation and reporting support.'},
        {id:3, employee_number:'EMP-2026-003', full_name:'Santos, Marvin D.', age:27, birthdate:'1998-02-18', gender:'Male', position:'Field Staff', section:'Operations', department:'OPERATIONS', employment_type:'job_order', status:'Active', remarks:'Field operations and attendance verification.'},
        {id:4, employee_number:'EMP-2026-004', full_name:'Bergado, Novel P.', age:35, birthdate:'1990-07-09', gender:'Male', position:'Planning Analyst', section:'Planning', department:'PLANNING', employment_type:'job_order', status:'Active', remarks:'Planning and monitoring review support.'},
        {id:5, employee_number:'EMP-2026-005', full_name:'Carlos, Kurt R.', age:31, birthdate:'1994-09-03', gender:'Male', position:'CEDOC Specialist', section:'CEDOC', department:'CEDOC', employment_type:'permanent', status:'Active', remarks:'Content and documentation oversight.'},
        {id:6, employee_number:'EMP-2026-006', full_name:'Domingo, Liza M.', age:26, birthdate:'1999-04-16', gender:'Female', position:'Operations Assistant', section:'Operations', department:'OPERATIONS', employment_type:'job_order', status:'Active', remarks:'Daily monitoring and attendance support.'},
        {id:7, employee_number:'EMP-2026-007', full_name:'Galvez, Mark L.', age:40, birthdate:'1985-12-22', gender:'Male', position:'Senior Supervisor', section:'Admin', department:'ADMIN', employment_type:'permanent', status:'Active', remarks:'Supervisory review and staff coordination.'},
        {id:8, employee_number:'EMP-2026-008', full_name:'Tampi, Ansarie P.', age:23, birthdate:'2002-06-28', gender:'Female', position:'Documentation Assistant', section:'CEDOC', department:'CEDOC', employment_type:'job_order', status:'Active', remarks:'Documentation and records assistance.'},
        {id:9, employee_number:'EMP-2026-009', full_name:'Reyes, Pedro J.', age:38, birthdate:'1987-08-04', gender:'Male', position:'Program Manager', section:'Admin', department:'ADMIN', employment_type:'permanent', status:'Active', remarks:'Program oversight and monitoring coordination.'},
        {id:10, employee_number:'EMP-2026-010', full_name:'Acot, Mauro T.', age:30, birthdate:'1995-01-11', gender:'Male', position:'Field Technician', section:'Operations', department:'OPERATIONS', employment_type:'job_order', status:'Active', remarks:'Field monitoring and attendance support.'},
    ];

    function normalizeEmployee(e){
        const fullName = e.full_name || [e.last_name, e.first_name, e.middle_name, e.suffix].filter(Boolean).join(', ').replace(/, ,/g, ', ');
        const birthdate = e.birthdate || e.birth_date || e.date_of_birth || 'N/A';
        const age = e.age ?? (birthdate !== 'N/A' ? new Date().getFullYear() - new Date(birthdate).getFullYear() : '—');
        return {
            ...e,
            id: e.id ?? e.employee_id ?? Math.random(),
            employee_number: e.employee_number || e.employee_no || 'EMP-PLACEHOLDER',
            full_name: fullName || 'Unknown Employee',
            age: age,
            birthdate,
            gender: e.gender || '—',
            position: e.position || e.role || 'Staff',
            section: e.section || '—',
            department: e.department || '—',
            employment_type: e.employment_type || e.employmentType || 'job_order',
            status: e.status || 'Active',
            remarks: e.remarks || 'Placeholder monitoring profile until the backend employee module is connected.'
        };
    }

    const employees = (employeesFromServer && Array.isArray(employeesFromServer) ? employeesFromServer : sampleEmployees).map(normalizeEmployee);

    // State
    let state = { page:1, perPage:5, sort:'name_asc', dept:'all', type:'all', q:'' };

    const tbody = document.getElementById('employeesTbody');
    const paginationEl = document.getElementById('employeesPagination');
    const countEl = document.getElementById('employeesCount');
    const previewName = document.getElementById('employeePreviewName');
    const previewNo = document.getElementById('employeePreviewNo');
    const previewSection = document.getElementById('employeePreviewSection');
    const previewDept = document.getElementById('employeePreviewDept');
    const previewAge = document.getElementById('employeePreviewAge');
    const previewBirth = document.getElementById('employeePreviewBirth');
    const previewType = document.getElementById('employeePreviewType');
    const previewStatus = document.getElementById('employeePreviewStatus');
    const previewNote = document.getElementById('employeePreviewNote');

    function applyFilters(list){
        return list.filter(e=>{
            if(state.dept !== 'all' && e.department !== state.dept) return false;
            if(state.type !== 'all'){
                if(state.type === 'permanent' && e.employment_type !== 'permanent') return false;
                if(state.type === 'job_order' && e.employment_type !== 'job_order') return false;
            }
            if(state.q){
                const q = state.q.toLowerCase();
                return e.full_name.toLowerCase().includes(q) || (e.position||'').toLowerCase().includes(q);
            }
            return true;
        });
    }

    function applySort(list){
        const s = state.sort;
        const copy = [...list];
        switch(s){
            case 'name_asc': return copy.sort((a,b)=>a.full_name.localeCompare(b.full_name));
            case 'name_desc': return copy.sort((a,b)=>b.full_name.localeCompare(a.full_name));
            case 'age_asc': return copy.sort((a,b)=>a.age - b.age);
            case 'age_desc': return copy.sort((a,b)=>b.age - a.age);
            case 'gender': return copy.sort((a,b)=>a.gender.localeCompare(b.gender));
            case 'position': return copy.sort((a,b)=> (a.position||'').localeCompare(b.position||''));
            case 'department': return copy.sort((a,b)=> (a.department||'').localeCompare(b.department||''));
            default: return copy;
        }
    }

    function updatePreview(employee){
        if(!employee){
            previewName.textContent = 'Choose an employee';
            previewNo.textContent = '—';
            previewSection.textContent = '—';
            previewDept.textContent = '—';
            previewAge.textContent = '—';
            previewBirth.textContent = '—';
            previewType.textContent = '—';
            previewStatus.textContent = '—';
            previewNote.textContent = 'Select any registered employee row to preview their monitoring details.';
            return;
        }
        previewName.textContent = employee.full_name;
        previewNo.textContent = employee.employee_number || '—';
        previewSection.textContent = employee.section || '—';
        previewDept.textContent = employee.department || '—';
        previewAge.textContent = `${employee.age ?? '—'} yrs`;
        previewBirth.textContent = employee.birthdate || '—';
        previewType.textContent = employee.employment_type === 'permanent' ? 'Permanent' : 'Job Order';
        previewStatus.textContent = employee.status || 'Active';
        previewNote.textContent = employee.remarks || 'Monitoring profile placeholder until the backend employee module is connected.';
    }

    function render(){
        const filtered = applyFilters(employees);
        const sorted = applySort(filtered);
        const total = sorted.length;
        const totalPages = Math.max(1, Math.ceil(total / state.perPage));
        if(state.page > totalPages) state.page = totalPages;

        const start = (state.page -1) * state.perPage;
        const pageItems = sorted.slice(start, start + state.perPage);

        // render rows
        tbody.innerHTML = '';
        for(const e of pageItems){
            const tr = document.createElement('tr');
            tr.className = 'employee-selectable';
            tr.dataset.employeeId = e.id;
            tr.innerHTML = `
                <td>
                    <div class="employee-row">
                        <div class="employee-avatar-sm">${(e.full_name.split(',')[0] || '').slice(0,2).toUpperCase()}</div>
                        <div>
                            <div style="font-weight:700;color:#111827;">${e.full_name}</div>
                            <div style="font-size:12px;color:#6b7280;">${e.position} · ${e.employment_type === 'permanent' ? 'Permanent' : 'Job Order'}</div>
                        </div>
                    </div>
                </td>
                <td>${e.age ?? '-'}</td>
                <td>${e.gender ?? '-'}</td>
                <td>${e.position} / ${e.section || '-'}</td>
                <td>${e.department || '-'}</td>
            `;
            tr.addEventListener('click', ()=> updatePreview(e));
            tbody.appendChild(tr);
        }

        if(pageItems.length){ updatePreview(pageItems[0]); }

        // count
        countEl.textContent = `${total} employee${total !== 1 ? 's' : ''}`;

        // pagination
        paginationEl.innerHTML = '';
        const prev = document.createElement('button'); prev.className='page-btn'; prev.textContent='‹'; prev.disabled = state.page === 1; prev.onclick = ()=>{ if(state.page>1){ state.page--; render(); }}; paginationEl.appendChild(prev);
        for(let p=1;p<=totalPages;p++){
            const btn = document.createElement('button'); btn.className='page-btn'+(p===state.page?' active':''); btn.textContent=p; btn.onclick = ()=>{ state.page = p; render(); };
            paginationEl.appendChild(btn);
        }
        const next = document.createElement('button'); next.className='page-btn'; next.textContent='›'; next.disabled = state.page === totalPages; next.onclick = ()=>{ if(state.page<totalPages){ state.page++; render(); }}; paginationEl.appendChild(next);
    }

    // controls
    document.getElementById('empSort').addEventListener('change', (e)=>{ state.sort = e.target.value; render(); });
    document.getElementById('empDept').addEventListener('change', (e)=>{ state.dept = e.target.value; state.page = 1; render(); });
    document.getElementById('empType').addEventListener('change', (e)=>{ state.type = e.target.value; state.page = 1; render(); });
    document.getElementById('empSearch').addEventListener('input', (e)=>{ state.q = e.target.value; state.page = 1; render(); });

    // open/close
    const overlay = document.getElementById('employeesPreview');
    const openBtn = document.getElementById('totalEmployeesCard');
    const closeBtn = document.getElementById('closeEmployees');
    openBtn && openBtn.addEventListener('click', ()=>{ overlay.classList.add('open'); state.page = 1; render(); });
    closeBtn && closeBtn.addEventListener('click', ()=>{ overlay.classList.remove('open'); });
    overlay.addEventListener('click', (e)=>{ if(e.target === overlay) overlay.classList.remove('open'); });

    // keyboard accessibility
    openBtn && openBtn.addEventListener('keydown', (e)=>{ if(e.key === 'Enter' || e.key === ' ') { overlay.classList.add('open'); state.page = 1; render(); } });

    // initial render not necessary until opened
});
</script>
@endpush
@endsection
