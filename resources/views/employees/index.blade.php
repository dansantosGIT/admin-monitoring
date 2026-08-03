@extends('layouts.app')

@section('title', 'Employees')
@section('page-name', 'Employees')

@push('styles')
<style>
    .employee-index {
        width: 100%;
        display: grid;
        gap: 18px;
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
        gap: 10px;
        flex-wrap: wrap;
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
        grid-template-columns: 1.5fr auto;
        gap: 10px;
        margin-bottom: 14px;
    }

    .search-box,
    .filter-select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 12px 14px;
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

    .employee-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dbe7ff;
        color: #1d4ed8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        flex-shrink: 0;
    }

    .employee-copy {
        display: grid;
        gap: 2px;
    }

    .employee-name {
        font-weight: 700;
    }

    .employee-sub {
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

    .badge--active { background: #dff5e8; color: #166534; }
    .badge--inactive { background: #fff0c9; color: #854d0e; }
    .badge--separated { background: #fee2e2; color: #991b1b; }

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
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .hero-card {
            flex-direction: column;
        }

        .hero-actions {
            justify-content: flex-start;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="employee-index">
    <section class="hero-card">
        <div>
            <div class="eyebrow">Personnel Records</div>
            <div class="hero-title">Employees</div>
            <div class="hero-sub">Manage employee profiles, employment types, and personal records in a clean audit-ready layout.</div>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Incident Reports</a>
            <a class="btn btn-primary" href="{{ route('employees.create') }}">+ Add Employee</a>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $employees->total() }}</div>
            <div class="stat-note">Registered employees</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Job Order</div>
            <div class="stat-value">{{ $employees->where('employment_type', 'JO')->count() }}</div>
            <div class="stat-note">Temporary workforce</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Permanent</div>
            <div class="stat-value">{{ $employees->where('employment_type', 'Permanent')->count() }}</div>
            <div class="stat-note">Regular staff</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Active</div>
            <div class="stat-value">{{ $employees->where('status', 'Active')->count() }}</div>
            <div class="stat-note">Currently active</div>
        </article>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Employee Directory</h2>
                <div class="panel-sub">Search by name, position, department, or employment type.</div>
            </div>
        </div>

        <input id="employee-search" class="search-box" type="search" placeholder="Search employees..." aria-label="Search employees">

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Employment</th>
                        <th>Date Hired</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr class="employee-row">
                            <td>{{ $emp->id }}</td>
                            <td>
                                <div class="employee-cell">
                                    <div class="employee-avatar">{{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}</div>
                                    <div class="employee-copy">
                                        <div class="employee-name">{{ $emp->last_name }}, {{ $emp->first_name }} {{ $emp->middle_name }}</div>
                                        <div class="employee-sub">{{ $emp->employee_number ?: 'No employee number' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $emp->position ?: '—' }}</td>
                            <td>{{ $emp->department ?: '—' }}</td>
                            <td>{{ $emp->employment_type === 'JO' ? 'Job Order' : ($emp->employment_type ?: '—') }}</td>
                            <td>{{ optional($emp->date_hired)->format('M d, Y') ?: '—' }}</td>
                            <td>
                                @php $status = strtolower($emp->status ?? 'active'); @endphp
                                <span class="badge {{ $status === 'active' ? 'badge--active' : ($status === 'inactive' ? 'badge--inactive' : 'badge--separated') }}">{{ $emp->status }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('employees.show', $emp) }}" class="icon-btn" title="View">↗</a>
                                    <a href="{{ route('employees.edit', $emp) }}" class="icon-btn" title="Edit">✎</a>
                                    <form method="POST" action="{{ route('employees.destroy', $emp) }}" onsubmit="return confirm('Delete this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn" title="Delete">⌫</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">No employees found. Create the first employee profile to begin.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <div>Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} records</div>
            <div>{{ $employees->links() }}</div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const employeeSearch = document.getElementById('employee-search');
    const employeeRows = Array.from(document.querySelectorAll('.employee-row'));

    employeeSearch?.addEventListener('input', () => {
        const query = employeeSearch.value.trim().toLowerCase();

        employeeRows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
