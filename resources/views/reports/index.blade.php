@extends('layouts.app')

@section('title', 'Incident Reports')
@section('page-name', 'Incident Reports')

@push('styles')
<style>
    .incident-index {
        width: 100%;
        display: grid;
        gap: 18px;
        color: #122033;
    }

    .hero-card,
    .panel-card,
    .stat-card {
        background: #ffffff;
        border: 1px solid #dde7f2;
        border-radius: 20px;
        box-shadow: 0 18px 48px rgba(18, 32, 51, 0.08);
    }

    .hero-card {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
    }

    .eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #0f62fe;
    }

    .hero-title {
        font-size: 26px;
        font-weight: 800;
        margin: 6px 0 8px;
    }

    .hero-sub {
        color: #607086;
        max-width: 760px;
        line-height: 1.6;
        font-size: 14px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .btn-primary {
        background: #0f62fe;
        color: #fff;
    }

    .btn-secondary {
        background: #fff;
        border-color: #dce5ef;
        color: #122033;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .stat-card {
        padding: 16px;
    }

    .stat-label {
        font-size: 12px;
        color: #607086;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .stat-value {
        margin-top: 8px;
        font-size: 28px;
        font-weight: 800;
        color: #122033;
    }

    .stat-note {
        margin-top: 4px;
        font-size: 13px;
        color: #607086;
    }

    .panel-card {
        padding: 16px;
    }

    .panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .panel-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .panel-sub {
        margin-top: 4px;
        font-size: 13px;
        color: #607086;
    }

    .toolbar {
        display: grid;
        grid-template-columns: 1.5fr repeat(3, minmax(140px, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .input,
    .select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 10px 12px;
        font: inherit;
        background: #fff;
        color: #122033;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 960px;
    }

    .table th,
    .table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf2f7;
        text-align: left;
        vertical-align: middle;
        font-size: 13px;
    }

    .table th {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 11px;
        color: #607086;
        font-weight: 800;
        background: #f8fbff;
    }

    .row-link {
        text-decoration: none;
        color: inherit;
    }

    .employee-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .employee-name {
        font-weight: 700;
    }

    .employee-sub,
    .muted {
        color: #607086;
        font-size: 12px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .badge--minor { background: #dff5e8; color: #166534; }
    .badge--major { background: #fff0c9; color: #854d0e; }
    .badge--critical { background: #fee2e2; color: #991b1b; }
    .badge--pending { background: #fee2e2; color: #991b1b; }
    .badge--under_investigation { background: #fff0c9; color: #854d0e; }
    .badge--resolved { background: #dff5e8; color: #166534; }
    .badge--closed { background: #e5e7eb; color: #374151; }

    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid #dce5ef;
        background: #fff;
        color: #122033;
        text-decoration: none;
    }

    .empty-state {
        padding: 32px 18px;
        text-align: center;
        color: #607086;
    }

    .pagination-wrap {
        margin-top: 14px;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        color: #607086;
        font-size: 13px;
    }

    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .toolbar {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 720px) {
        .hero-card {
            flex-direction: column;
        }

        .hero-actions {
            justify-content: flex-start;
        }

        .stats-grid,
        .toolbar {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $severityClasses = [
        'minor' => 'badge--minor',
        'major' => 'badge--major',
        'critical' => 'badge--critical',
    ];

    $statusClasses = [
        'pending' => 'badge--pending',
        'under_investigation' => 'badge--under_investigation',
        'resolved' => 'badge--resolved',
        'closed' => 'badge--closed',
    ];

    $typeLabels = [
        'equipment_damage' => 'Equipment Damage',
        'equipment_loss' => 'Equipment Loss',
        'vehicle_incident' => 'Vehicle Incident',
        'other' => 'Other',
    ];
@endphp

<div class="incident-index">
    <section class="hero-card">
        <div>
            <div class="eyebrow">Incident Management</div>
            <div class="hero-title">Incident Reports</div>
            <div class="hero-sub">Track property, equipment, and vehicle incidents with filters, review states, and attachment-ready case records.</div>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Reports Home</a>
            <a class="btn btn-primary" href="{{ route('reports.create') }}">+ New Report</a>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $reports->total() }}</div>
            <div class="stat-note">All incident reports</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $reports->where('status', 'pending')->count() }}</div>
            <div class="stat-note">Awaiting action</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Resolved</div>
            <div class="stat-value">{{ $reports->where('status', 'resolved')->count() }}</div>
            <div class="stat-note">Closed out cases</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Critical</div>
            <div class="stat-value">{{ $reports->where('severity', 'critical')->count() }}</div>
            <div class="stat-note">Priority cases</div>
        </article>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Incident Log</h2>
                <div class="panel-sub">Filter by status, severity, incident type, or search by code, employee, or location.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.index') }}" class="toolbar">
            <input class="input" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search incident code, employee, department, or location">
            <select class="select" name="status">
                <option value="">All Statuses</option>
                @foreach (['pending' => 'Pending', 'under_investigation' => 'Under Investigation', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="select" name="severity">
                <option value="">All Severity</option>
                @foreach (['minor' => 'Minor', 'major' => 'Major', 'critical' => 'Critical'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['severity'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="select" name="incident_type">
                <option value="">All Types</option>
                @foreach ($typeLabels as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['incident_type'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Apply Filters</button>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td><a class="row-link" href="{{ route('reports.show', $report) }}">{{ $report->incident_code }}</a></td>
                            <td>{{ optional($report->date_of_incident)->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <div class="employee-cell">
                                    <span class="employee-name">{{ $report->employee_name }}</span>
                                    <span class="employee-sub">{{ $report->department ?: 'No department' }}</span>
                                </div>
                            </td>
                            <td>{{ $typeLabels[$report->incident_type] ?? ucwords(str_replace('_', ' ', $report->incident_type)) }}</td>
                            <td>{{ $report->item_name }}</td>
                            <td><span class="badge {{ $severityClasses[$report->severity] ?? 'badge--minor' }}">{{ ucfirst($report->severity) }}</span></td>
                            <td><span class="badge {{ $statusClasses[$report->status] ?? 'badge--pending' }}">{{ ucwords(str_replace('_', ' ', $report->status)) }}</span></td>
                            <td>
                                <div class="actions">
                                    <a class="icon-btn" href="{{ route('reports.show', $report) }}" title="View">↗</a>
                                    <a class="icon-btn" href="{{ route('reports.edit', $report) }}" title="Edit">✎</a>
                                    <a class="icon-btn" href="{{ route('reports.export-pdf', $report) }}" title="PDF">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    No incident reports found. Create the first report to start tracking incidents.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <div>Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} records</div>
            <div>{{ $reports->links() }}</div>
        </div>
    </section>
</div>
@endsection
