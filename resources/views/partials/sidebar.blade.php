<aside class="app-sidebar" aria-label="Main sidebar">
    <div class="sidebar-header" id="sidebarHeader" role="button" tabindex="-1" aria-label="Sidebar header">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-mark">
                <img src="{{ asset('images/CDRRMD-Logo.png') }}" alt="CDRRMD" style="width:40px;height:40px;object-fit:cover;border-radius:50%;border:0;box-shadow:none;">
            </span>
            <span class="brand-copy sidebar-brand-copy">
                <strong>{{ config('app.name', 'CDRRMD') }}</strong>
                <span>Personnel monitoring</span>
            </span>
        </a>
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Collapse sidebar" aria-expanded="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6h10M4 12h16M10 18h10"/></svg>
        </button>
    </div>

    <nav class="nav" aria-label="Primary navigation">
        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM13 3v6h8V3h-8zM3 21h8v-6H3v6z"/></svg>
            <span class="sidebar-label">Dashboard</span>
        </a>
        @php($reportsSectionOpen = request()->is('reports*'))
        <div class="nav-group {{ $reportsSectionOpen ? 'is-open' : '' }}" data-reports-nav>
            <button type="button" class="nav-group-toggle {{ $reportsSectionOpen ? 'active' : '' }}" aria-expanded="{{ $reportsSectionOpen ? 'true' : 'false' }}" aria-controls="reportsSubmenu" data-reports-toggle>
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                <span class="sidebar-label">Reports</span>
                <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="nav-submenu" id="reportsSubmenu">
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') || request()->routeIs('reports.create') || request()->routeIs('reports.show') || request()->routeIs('reports.edit') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('reports.vehicle-monitoring') }}" class="{{ request()->routeIs('reports.vehicle-monitoring*') ? 'active' : '' }}">Vehicle Monitoring</a>
            </div>
        </div>
        <a href="{{ route('employees.index') ?? '#' }}" class="{{ request()->is('employees*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="sidebar-label">Employees</span>
        </a>
        <a href="{{ route('attendance.index') ?? '#' }}" class="{{ request()->is('attendance*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span class="sidebar-label">Attendance</span>
        </a>
        @if(auth()->check() && (auth()->user()->role ?? '') === 'super-admin')
            <a href="{{ route('accounts.index') }}" class="{{ request()->is('accounts*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="sidebar-label">Accounts</span>
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user" title="Signed-in account">
            <img class="sidebar-user-avatar" src="{{ asset('images/CDRRMD-Logo.png') }}" alt="{{ auth()->user()->name ?? 'User' }}">
            <div class="sidebar-user-copy">
                @if(auth()->check())
                    <div class="sidebar-user-email"><x-masked-email :email="auth()->user()->email" /></div>
                    <div style="font-size:12px;color:var(--muted)">{{ auth()->user()->role ?? 'Member' }}</div>
                @else
                    <div class="sidebar-user-email"><x-masked-email email="superadmin@sanjuan.gov.ph" /></div>
                    <div style="font-size:12px;color:var(--muted)">super-admin</div>
                @endif
            </div>
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout" aria-label="Log out">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                    <span class="sidebar-label">Logout</span>
                </button>
            </form>
        @endauth
    </div>
</aside>

@push('scripts')
<script>
    (function () {
        const reportsNav = document.querySelector('[data-reports-nav]');
        const reportsToggle = document.querySelector('[data-reports-toggle]');
        const button = document.getElementById('sidebarToggle');
        const header = document.getElementById('sidebarHeader');
        const sidebar = document.querySelector('.app-sidebar');
        const body = document.body;

        reportsToggle?.addEventListener('click', () => {
            const isOpen = reportsNav.classList.toggle('is-open');
            reportsToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        if (!button) {
            return;
        }

        const sync = () => {
            const collapsed = body.classList.contains('sidebar-collapsed');
            button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            button.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
            header.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Sidebar header');
            header.setAttribute('tabindex', collapsed ? '0' : '-1');
            try {
                localStorage.setItem('sidebar-collapsed', collapsed ? '1' : '0');
            } catch (error) {
                // ignore storage access issues
            }
        };

        button.addEventListener('click', () => {
            const willExpand = body.classList.contains('sidebar-collapsed');
            body.classList.toggle('sidebar-is-expanding', willExpand);
            body.classList.toggle('sidebar-collapsed');
            sync();
            window.setTimeout(() => body.classList.remove('sidebar-is-expanding'), 320);
        });

        header.addEventListener('click', (event) => {
            if (!body.classList.contains('sidebar-collapsed') || event.target.closest('#sidebarToggle')) {
                return;
            }
            if (event.target.closest('.brand')) {
                event.preventDefault();
            }
            body.classList.remove('sidebar-collapsed');
            body.classList.add('sidebar-is-expanding');
            sync();
            window.setTimeout(() => body.classList.remove('sidebar-is-expanding'), 320);
        });

        header.addEventListener('keydown', (event) => {
            if (body.classList.contains('sidebar-collapsed') && (event.key === 'Enter' || event.key === ' ')) {
                event.preventDefault();
                body.classList.remove('sidebar-collapsed');
                body.classList.add('sidebar-is-expanding');
                sync();
                window.setTimeout(() => body.classList.remove('sidebar-is-expanding'), 320);
            }
        });

        sidebar.addEventListener('click', (event) => {
            if (!body.classList.contains('sidebar-collapsed') || event.target.closest('a, button, input, select, form')) {
                return;
            }
            body.classList.remove('sidebar-collapsed');
            body.classList.add('sidebar-is-expanding');
            sync();
            window.setTimeout(() => body.classList.remove('sidebar-is-expanding'), 320);
        });

        sync();
    })();
</script>
@endpush
