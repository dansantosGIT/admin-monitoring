@extends('layouts.app')

@section('page-name', 'Employees')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:12px">
        <div>
            <h2 style="margin:0">Employees</h2>
            <div style="color:var(--muted);font-size:13px">Manage employee records and Personal Data Sheets</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
            <input id="employee-search" type="search" placeholder="Search employees..." aria-label="Search employees" style="padding:10px 12px;border:1px solid #e6e9ee;border-radius:8px;width:320px;background:#fff;color:#111">
            <a href="{{ route('employees.create') }}" class="btn btn-primary" style="padding:10px 14px;border-radius:10px;font-weight:700">+ Add Employee</a>
        </div>
    </div>

    @if(session('success'))
        <div style="padding:10px;background:#e6ffed;border:1px solid #b7f0c6;border-radius:6px;margin-bottom:12px">{{ session('success') }}</div>
    @endif

    <style>
        .table-wrap{ background:linear-gradient(180deg,#ffffff 0%, #fbfdff 100%); padding:12px }
        .emp-table { width:100%; border-collapse:collapse; background:transparent }
        .emp-table thead th { background:#f8fafc; color:var(--muted); font-weight:700; padding:12px; text-align:left; border-bottom:1px solid #eef2f6 }
        .emp-table td { padding:12px; border-bottom:1px solid #f3f6f9; vertical-align:middle }
        .emp-row:hover { background:#fbfdff }
        .emp-photo { width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid #eee }

        .btn { display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border-radius:8px;border:none;background:#374151;color:white;text-decoration:none; cursor:pointer; transition:transform .12s ease,box-shadow .12s ease,opacity .12s ease }
        .btn:hover{ transform:translateY(-3px); box-shadow:0 10px 30px rgba(13,30,60,0.08); opacity:0.98 }
        .btn.secondary{ background:#f3f4f6; color:#111; border:1px solid #e6e9ee }
        .btn.danger{ background:#ef4444 }
        .btn-primary{ background:#0b6df0 }
        .action-group{ display:flex; gap:8px; justify-content:flex-end }

        .dept-filters { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px; align-items:center }
        .dept-filters-label { font-size:13px; color:var(--muted); font-weight:600; margin-right:4px }
        .dept-filter-btn { padding:8px 14px; border-radius:8px; border:1px solid #e6e9ee; background:#f3f4f6; color:#111; font-size:13px; font-weight:600; cursor:pointer; transition:transform .12s ease, box-shadow .12s ease, background .12s ease, color .12s ease, border-color .12s ease }
        .dept-filter-btn:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(13,30,60,0.06) }
        .dept-filter-btn.active { background:#0b6df0; color:#fff; border-color:#0b6df0 }
        .dept-filter-btn.active:hover { opacity:0.95 }
        @media (max-width:640px) {
            .dept-filters { gap:6px }
            .dept-filter-btn { padding:7px 10px; font-size:12px }
        }
    </style>

    <div class="dept-filters" role="group" aria-label="Filter by department">
        <span class="dept-filters-label">Department:</span>
        @foreach (['All', 'Admin', 'Logistics', 'Operations', 'CEDOC', 'Planning'] as $dept)
            <button type="button"
                class="dept-filter-btn{{ $dept === 'All' ? ' active' : '' }}"
                data-department="{{ $dept === 'All' ? '' : $dept }}"
                aria-pressed="{{ $dept === 'All' ? 'true' : 'false' }}">{{ $dept }}</button>
        @endforeach
    </div>

    <div class="table-wrap" style="overflow:auto;border-radius:8px;border:1px solid #f3f4f6">
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
                @forelse($employees as $emp)
                <tr class="emp-row" data-department="{{ $emp->department }}">
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
                    <td>{{ $emp->last_name }}, {{ $emp->first_name }} {{ $emp->middle_name }}</td>
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
                @empty
                <tr><td colspan="8" style="padding:12px">No employees yet. Click <strong>Add Employee</strong> to create one.</td></tr>
                @endforelse
                <tr id="emp-filter-empty" style="display:none">
                    <td colspan="8" style="padding:16px;text-align:center;color:var(--muted)">No employees found in this department.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top:12px">{{ $employees->links() }}</div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const input = document.getElementById('employee-search');
            const rows = Array.from(document.querySelectorAll('.emp-row'));
            const pager = document.querySelector('.pagination');
            const emptyRow = document.getElementById('emp-filter-empty');
            const deptButtons = Array.from(document.querySelectorAll('.dept-filter-btn'));
            let selectedDepartment = '';
            let debounce;

            function normalize(value){
                return (value || '').trim().toLowerCase();
            }

            function matchesDepartment(row){
                if(!selectedDepartment) return true;
                return normalize(row.dataset.department) === normalize(selectedDepartment);
            }

            function matchesSearch(row){
                const q = input ? input.value.trim().toLowerCase() : '';
                if(!q) return true;
                const cells = row.getElementsByTagName('td');
                const name = (cells[2] && cells[2].textContent || '').toLowerCase();
                const pos = (cells[3] && cells[3].textContent || '').toLowerCase();
                const dept = (cells[4] && cells[4].textContent || '').toLowerCase();
                const empType = (cells[5] && cells[5].textContent || '').toLowerCase();
                const haystack = name + ' ' + pos + ' ' + dept + ' ' + empType;
                return haystack.indexOf(q) !== -1;
            }

            function applyFilters(){
                let visibleCount = 0;
                rows.forEach(function(row){
                    const visible = matchesDepartment(row) && matchesSearch(row);
                    row.style.display = visible ? '' : 'none';
                    if(visible) visibleCount++;
                });

                const isFiltering = !!selectedDepartment || (input && input.value.trim());
                if(emptyRow){
                    emptyRow.style.display = rows.length > 0 && visibleCount === 0 ? '' : 'none';
                    if(visibleCount === 0 && selectedDepartment && input && input.value.trim()){
                        emptyRow.querySelector('td').textContent = 'No employees found in this department matching your search.';
                    } else if(visibleCount === 0 && selectedDepartment){
                        emptyRow.querySelector('td').textContent = 'No employees found in this department.';
                    } else if(visibleCount === 0 && input && input.value.trim()){
                        emptyRow.querySelector('td').textContent = 'No employees match your search.';
                    }
                }
                if(pager) pager.style.display = isFiltering ? 'none' : '';
            }

            deptButtons.forEach(function(btn){
                btn.addEventListener('click', function(){
                    selectedDepartment = btn.dataset.department || '';
                    deptButtons.forEach(function(b){
                        const isActive = b === btn;
                        b.classList.toggle('active', isActive);
                        b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });
                    applyFilters();
                });
            });

            if(input){
                input.addEventListener('input', function(){
                    clearTimeout(debounce);
                    debounce = setTimeout(applyFilters, 150);
                });
            }
        });
    </script>
</div>

@endsection
