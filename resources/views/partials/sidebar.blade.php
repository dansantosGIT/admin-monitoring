<aside class="app-sidebar" aria-label="Main sidebar">
    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6h10M4 12h16M10 18h10"/></svg>
    </button>

    <a href="{{ route('dashboard') }}" class="brand">
        <span class="brand-mark">
            <img src="{{ asset('images/CDRRMD-Logo.png') }}" alt="CDRRMD" style="width:40px;height:40px;object-fit:cover;border-radius:50%;border:0;box-shadow:none;">
        </span>
        <span class="brand-copy sidebar-brand-copy">
            <strong>{{ config('app.name', 'CDRRMD') }}</strong>
            <span>Personnel monitoring</span>
        </span>
    </a>

    <nav class="nav" aria-label="Primary navigation">
        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM13 3v6h8V3h-8zM3 21h8v-6H3v6z"/></svg>
            <span class="sidebar-label">Dashboard</span>
        </a>
        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
            <span class="sidebar-label">Reports</span>
        </a>
        <a href="{{ route('employees.index') ?? '#' }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="sidebar-label">Employees</span>
        </a>
        <a href="{{ route('attendance.index') ?? '#' }}" class="{{ request()->is('attendance*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span class="sidebar-label">Attendance</span>
        </a>
        <a href="{{ route('incidents.index') ?? '#' }}" class="{{ request()->is('incidents*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            <span class="sidebar-label">Incident Reports</span>
        </a>
        @if(auth()->check() && (auth()->user()->role ?? '') === 'super-admin')
            <a href="{{ route('accounts.index') }}" class="{{ request()->is('accounts*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="sidebar-label">Accounts</span>
            </a>
        @endif
    </nav>

    <div style="margin-top:auto">
        <div class="sidebar-user" style="display:flex;align-items:center;gap:10px">
            <img src="{{ asset('images/CDRRMD-Logo.png') }}" alt="user" style="width:36px;height:36px;border-radius:50%">
            <div class="sidebar-user-copy">
                @if(auth()->check())
                    <div style="font-weight:700">{{ auth()->user()->name }}</div>
                    <div style="font-size:12px;color:var(--muted)">{{ auth()->user()->position ?? auth()->user()->role ?? 'Member' }}</div>
                @else
                    <div style="font-weight:700">Nawar Anwar</div>
                    <div style="font-size:12px;color:var(--muted)">LDRRM Officer II</div>
                @endif
            </div>
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}" style="margin-top:12px">
                @csrf
                <button type="submit" style="display:block;width:100%;text-align:left;padding:10px 12px;border:none;background:none;color:var(--muted);font-weight:600;cursor:pointer">
                    Logout
                </button>
            </form>
        @endauth
    </div>
</aside>

@push('scripts')
<script>
    (function () {
        const button = document.getElementById('sidebarToggle');
        const body = document.body;

        if (!button) {
            return;
        }

        const sync = () => {
            const collapsed = body.classList.contains('sidebar-collapsed');
            button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            try {
                localStorage.setItem('sidebar-collapsed', collapsed ? '1' : '0');
            } catch (error) {
                // ignore storage access issues
            }
        };

        button.addEventListener('click', () => {
            body.classList.toggle('sidebar-collapsed');
            sync();
        });

        sync();
    })();
</script>
@endpush
