<aside class="app-sidebar" aria-label="Main sidebar">
    <a href="{{ url('/') }}" class="brand">
        <span class="brand-mark">
            <img src="{{ asset('images/CDRRMD-Logo.png') }}" alt="CDRRMD" style="width:40px;height:40px;object-fit:cover;border-radius:50%;border:0;box-shadow:none;">
        </span>
        <span class="brand-copy">
            <strong>{{ config('app.name', 'CDRRMD') }}</strong>
            <span>Personnel monitoring</span>
        </span>
    </a>

    <nav class="nav" aria-label="Primary navigation">
        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">Reports</a>
        <a href="{{ route('employees.index') ?? '#' }}" class="{{ request()->is('employees*') ? 'active' : '' }}">Employees</a>
        <a href="{{ route('attendance.index') ?? '#' }}" class="{{ request()->is('attendance*') ? 'active' : '' }}">Attendance</a>
        <a href="{{ route('incidents.index') ?? '#' }}" class="{{ request()->is('incidents*') ? 'active' : '' }}">Incident Reports</a>
    </nav>

    <div style="margin-top:auto">
        <div style="display:flex;align-items:center;gap:10px">
            <img src="{{ asset('images/CDRRMD-Logo.png') }}" alt="user" style="width:36px;height:36px;border-radius:50%">
            <div>
                <div style="font-weight:700">Nawar Anwar</div>
                <div style="font-size:12px;color:var(--muted)">LDRRM Officer II</div>
            </div>
        </div>
    </div>
</aside>
