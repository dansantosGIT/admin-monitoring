<header class="app-header">
    <div class="app-header-inner">
        <div style="display:flex;align-items:center;gap:12px">
            <div style="display:flex;flex-direction:column">
                <div style="font-weight:700;font-size:16px;color:var(--text)">
                    @yield('page-name', 'Dashboard')
                </div>
                <div style="font-size:12px;color:var(--muted)">Admin Employee Monitoring</div>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:10px">
            <input type="search" placeholder="Search Employee..." style="padding:8px 12px;border:1px solid #e6eef7;border-radius:8px;width:260px">
            <select style="padding:8px 10px;border:1px solid #e6eef7;border-radius:8px">
                <option>June 2026</option>
            </select>
            <button aria-label="Notifications" style="padding:8px;border-radius:8px;border:1px solid #e6eef7;background:#fff">🔔</button>
        </div>
    </div>
</header>
