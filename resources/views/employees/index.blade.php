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

    .filter-toolbar {
        display: grid;
        gap: 12px;
        margin: 14px 0 16px;
    }

    .directory-actions-wrap {
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #f3f4f6;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        padding: 12px;
    }

    .search-box {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 12px 14px;
        font: inherit;
        background: #fff;
        color: #122033;
    }

    .department-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .department-filter-btn {
        border-radius: 999px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease, transform 0.12s ease;
    }

    .department-filter-btn:hover {
        transform: translateY(-1px);
    }

    .department-filter-btn.is-active {
        background: #0f62fe;
        border-color: #0f62fe;
        color: #fff;
        box-shadow: 0 10px 24px rgba(15, 98, 254, 0.18);
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

    .action-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
        min-width: 0;
    }

    .action-group .btn {
        padding: 8px 12px;
        border-radius: 10px;
        min-height: 36px;
        white-space: nowrap;
        flex: 0 0 auto;
    }

    .action-group form {
        margin: 0;
    }

    .action-group .btn.danger {
        background: #ef4444;
    }

    .action-group .btn.secondary {
        background: #f3f4f6;
        color: #111827;
        border: 1px solid #e6e9ee;
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

    .directory-empty-row td {
        padding: 28px 18px;
        text-align: center;
        color: #607086;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .emp-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
        background: transparent;
        table-layout: fixed;
    }

    .emp-table th,
    .emp-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .emp-table thead th {
        background: #f8fbff;
        color: #607086;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 11px;
    }

    .emp-row:hover {
        background: #fbfdff;
    }

    .emp-photo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #eee;
        flex-shrink: 0;
    }

    .emp-name-cell {
        min-width: 0;
    }

    .emp-name-cell .employee-cell {
        min-width: 0;
    }

    .emp-name-cell .employee-copy {
        min-width: 0;
    }

    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-toolbar {
            gap: 10px;
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

        .department-filters {
            gap: 8px;
        }

        .department-filter-btn {
            width: 100%;
        }

        .directory-actions-wrap {
            padding: 10px;
        }

        .action-group {
            justify-content: flex-start;
        }

        .action-group .btn {
            width: 100%;
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
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Reports</a>
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
                <div class="panel-sub">Search by name, position, department, or employment type, then narrow the list by department.</div>
            </div>
        </div>

    @if(session('success'))
        <div style="padding:10px;background:#e6ffed;border:1px solid #b7f0c6;border-radius:6px;margin-bottom:12px">{{ session('success') }}</div>
    @endif

    @php
        $departments = ['All', 'Admin', 'Logistics', 'Operations', 'CEDOC', 'Planning'];
    @endphp

    <div class="filter-toolbar">
        <div>
            <input
                id="employee-search"
                type="search"
                class="search-box"
                placeholder="Search employees by name, position, or department"
                aria-label="Search employees"
            >
        </div>

        <div class="department-filters" role="tablist" aria-label="Department filters">
            @foreach($departments as $department)
                <button
                    type="button"
                    class="btn btn-secondary department-filter-btn {{ $loop->first ? 'is-active' : '' }}"
                    data-department-filter="{{ strtolower($department) }}"
                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                >
                    {{ $department }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="directory-actions-wrap">
        <div class="table-responsive">
        <table class="emp-table">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th style="width:56px"></th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Employment</th>
                    <th style="width:140px;text-align:right">Date Hired</th>
                    <th style="width:180px;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($employees->count())
                    @foreach($employees as $emp)
                    <tr
                        class="emp-row employee-row"
                        data-employee-row
                        data-department="{{ strtolower(trim($emp->department ?? '')) }}"
                        data-search="{{ strtolower(trim($emp->last_name.' '.$emp->first_name.' '.$emp->middle_name.' '.$emp->position.' '.$emp->department.' '.($emp->employment_type == 'JO' ? 'Job Order' : $emp->employment_type))) }}"
                    >
                        <td>{{ $emp->id }}</td>
                        <td>
                            @if($emp->photo_path)
                                <img src="{{ asset('storage/'.$emp->photo_path) }}" class="emp-photo" alt="photo">
                            @else
                                <div class="emp-photo" style="display:inline-flex;align-items:center;justify-content:center;background:#f3f4f6;color:var(--muted)">
                                    {{ strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="emp-name-cell">{{ $emp->last_name }}, {{ $emp->first_name }} {{ $emp->middle_name }}</td>
                        <td>{{ $emp->position }}</td>
                        <td>{{ $emp->department }}</td>
                        <td>{{ $emp->employment_type == 'JO' ? 'Job Order' : $emp->employment_type }}</td>
                        <td style="text-align:right">{{ optional($emp->date_hired)->format('F j, Y') }}</td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('employees.show', $emp) }}" class="btn secondary">View</a>
                                <a href="{{ route('employees.edit', $emp) }}" class="btn">Edit</a>
                                <form method="POST" action="{{ route('employees.destroy', $emp) }}" style="display:inline-block" onsubmit="return confirm('Delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr id="employee-empty-state" class="directory-empty-row" hidden>
                        <td colspan="8">
                            <strong>No employees found in this department.</strong>
                            <div style="margin-top:4px">Try a different department filter or clear the search.</div>
                        </td>
                    </tr>
                @else
                <tr><td colspan="8" style="padding:12px">No employees yet. Click <strong>Add Employee</strong> to create one.</td></tr>
                @endif
            </tbody>
        </table>
        </div>
    </div>

    <div class="pagination-wrap" style="margin-top:12px">{{ $employees->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('employee-search');
        const filterButtons = Array.from(document.querySelectorAll('[data-department-filter]'));
        const rows = Array.from(document.querySelectorAll('[data-employee-row]'));
        const emptyState = document.getElementById('employee-empty-state');
        const pagination = document.querySelector('.pagination-wrap');

        if (!searchInput || !filterButtons.length || !rows.length) {
            return;
        }

        let activeDepartment = 'all';

        const normalize = (value) => value.toString().trim().toLowerCase().replace(/\s+/g, ' ');

        const syncButtons = () => {
            filterButtons.forEach((button) => {
                const isActive = button.dataset.departmentFilter === activeDepartment;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const updateEmptyState = (visibleCount, hasSearchTerm) => {
            if (!emptyState) {
                return;
            }

            if (visibleCount > 0) {
                emptyState.hidden = true;
                return;
            }

            emptyState.hidden = false;

            const title = emptyState.querySelector('strong');
            const detail = emptyState.querySelector('div');

            if (title) {
                title.textContent = activeDepartment === 'all' && hasSearchTerm
                    ? 'No employees match your search.'
                    : activeDepartment === 'all'
                        ? 'No employees available.'
                        : 'No employees found in this department.';
            }

            if (detail) {
                detail.textContent = activeDepartment === 'all' && hasSearchTerm
                    ? 'Try a different keyword or clear the search.'
                    : 'Try a different department filter or clear the search.';
            }
        };

        const applyFilters = () => {
            const searchQuery = normalize(searchInput.value);
            let visibleCount = 0;

            rows.forEach((row) => {
                const rowDepartment = normalize(row.dataset.department || '');
                const rowSearchText = normalize(row.dataset.search || row.textContent || '');
                const matchesDepartment = activeDepartment === 'all' || rowDepartment === activeDepartment;
                const matchesSearch = !searchQuery || rowSearchText.includes(searchQuery);
                const isVisible = matchesDepartment && matchesSearch;

                row.hidden = !isVisible;

                if (isVisible) {
                    visibleCount += 1;
                }
            });

            if (pagination) {
                pagination.hidden = searchQuery.length > 0 || activeDepartment !== 'all';
            }

            updateEmptyState(visibleCount, searchQuery.length > 0);
        };

        filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeDepartment = button.dataset.departmentFilter || 'all';
                syncButtons();
                applyFilters();
            });
        });

        searchInput.addEventListener('input', applyFilters);

        syncButtons();
        applyFilters();
    });
</script>
@endpush
