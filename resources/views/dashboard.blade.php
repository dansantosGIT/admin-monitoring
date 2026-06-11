@extends('layouts.app')

@section('content')
<div class="dashboard-wrapper">

    {{-- TOP STATS ROW --}}
    <div class="stats-grid">

        <div class="stat-card">
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
                        @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $day)
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
@endpush
@endsection
