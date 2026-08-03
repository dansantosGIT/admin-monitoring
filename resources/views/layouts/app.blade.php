<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Admin Monitoring') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/CDRRMD-Logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/CDRRMD-Logo.png') }}">
        <meta name="msapplication-TileImage" content="{{ asset('images/CDRRMD-Logo.png') }}">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @stack('styles')

        <style>
            :root {
                color-scheme: light;
                --page-bg: #eef3f9;
                --panel: rgba(255, 255, 255, 0.92);
                --border: rgba(211, 221, 234, 0.92);
                --text: #122033;
                --muted: #607086;
                --accent: #0f62fe;
                --accent-soft: #dbe7ff;
                --shadow: 0 22px 60px rgba(18, 32, 51, 0.12);
                --sidebar-width: 260px;
                --sidebar-collapsed-width: 84px;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: Arial, Helvetica, sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(15, 98, 254, 0.10), transparent 30%),
                    radial-gradient(circle at bottom right, rgba(15, 98, 254, 0.08), transparent 26%),
                    var(--page-bg);
            }

            a {
                color: inherit;
            }

            .app-shell {
                min-height: 100vh;
                display: block;
            }

            .app-frame {
                display: flex;
                min-height: calc(100vh - 0px);
            }

            body.sidebar-collapsed .app-frame {
                --sidebar-width: var(--sidebar-collapsed-width);
            }

            .app-sidebar {
                width: var(--sidebar-width);
                min-width: var(--sidebar-width);
                background: #ffffff;
                border-right: 1px solid var(--border);
                padding: 18px 16px;
                display:flex;
                flex-direction:column;
                gap:14px;
                transition: width 0.18s ease, min-width 0.18s ease, padding 0.18s ease;
                position: relative;
            }

            .sidebar-toggle {
                position: absolute;
                top: 16px;
                right: 14px;
                width: 32px;
                height: 32px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #fff;
                color: var(--muted);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 8px 18px rgba(18, 32, 51, 0.06);
            }

            .sidebar-toggle svg {
                width: 16px;
                height: 16px;
            }

            .sidebar-brand-copy,
            .sidebar-label,
            .sidebar-user-copy {
                transition: opacity 0.15s ease, transform 0.15s ease, width 0.15s ease;
            }

            body.sidebar-collapsed .app-sidebar {
                padding-left: 10px;
                padding-right: 10px;
                align-items: center;
            }

            body.sidebar-collapsed .sidebar-brand-copy,
            body.sidebar-collapsed .sidebar-label,
            body.sidebar-collapsed .sidebar-user-copy {
                opacity: 0;
                width: 0;
                overflow: hidden;
                transform: translateX(-6px);
                white-space: nowrap;
                pointer-events: none;
            }

            body.sidebar-collapsed .brand,
            body.sidebar-collapsed .nav a,
            body.sidebar-collapsed .sidebar-user {
                justify-content: center;
            }

            body.sidebar-collapsed .nav a {
                padding-left: 0;
                padding-right: 0;
                gap: 0;
            }

            body.sidebar-collapsed .app-main-column {
                min-width: 0;
            }

            .app-main-column {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
            }

            .app-header {
                position: sticky;
                top: 0;
                z-index: 10;
                backdrop-filter: blur(14px);
                background: rgba(255, 255, 255, 0.82);
                border-bottom: 1px solid var(--border);
            }

            .app-header-inner {
                width: 100%;
                max-width: none; /* allow header to span full main column */
                margin: 0; /* align to left of main column (beside sidebar) */
                padding: 0 36px; /* extra right padding so controls sit closer to the main column edge */
                min-height: 72px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
            }

            .brand-mark {
                width: 40px;
                height: 40px;
                display: grid;
                place-items: center;
                background: transparent;
                box-shadow: none;
                padding: 0;
            }

            .brand-copy strong {
                display: block;
                font-size: 15px;
            }

            .brand-copy span {
                display: block;
                color: var(--muted);
                font-size: 12px;
                margin-top: 2px;
            }

            .nav {
                display: flex;
                flex-direction: column;
                gap: 6px;
                margin-top: 6px;
            }

            .nav a {
                text-decoration: none;
                padding: 10px 12px;
                border-radius: 8px;
                color: var(--muted);
                display:flex;align-items:center;gap:10px;font-weight:600;
                overflow: hidden;
            }

            .nav a svg {
                width: 18px;
                height: 18px;
                flex-shrink: 0;
            }

            .nav a.active {
                color: var(--accent);
                background: var(--accent-soft);
            }

            .app-main {
                flex: 1;
                width: 100%;
                padding: 18px 24px;
                display: flex;
                justify-content: center;
                min-width: 0;
            }

            .app-footer {
                width: min(1280px, calc(100% - 32px));
                margin: 0 auto;
                padding: 18px 0 28px;
                color: var(--muted);
                font-size: 13px;
            }

            @media (max-width: 1024px) {
                .app-sidebar { display: none; }
                .app-frame { display:block; }
                .app-header-inner { width: calc(100% - 32px); }
            }

            .ui-shell {
                width: 100%;
                max-width: 1180px;
                display: grid;
                gap: 18px;
            }

            .ui-card,
            .ui-panel,
            .ui-table-card {
                background: #fff;
                border: 1px solid #dde7f2;
                border-radius: 20px;
                box-shadow: 0 18px 48px rgba(18, 32, 51, 0.08);
            }

            .ui-card {
                padding: 18px;
            }

            .ui-section-card {
                background: #fff;
                border: 1px solid #edf2f7;
                border-radius: 18px;
                box-shadow: none;
                padding: 18px;
            }

            .ui-card-head,
            .ui-panel-head,
            .ui-table-head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 14px;
            }

            .ui-title {
                margin: 0;
                font-size: 20px;
                font-weight: 800;
                color: #122033;
            }

            .ui-subtitle {
                margin-top: 4px;
                font-size: 13px;
                color: var(--muted);
                line-height: 1.5;
            }

            .ui-toolbar {
                display: grid;
                grid-template-columns: 1.5fr repeat(3, minmax(140px, 1fr));
                gap: 10px;
                margin-bottom: 14px;
            }

            .ui-input,
            .ui-select,
            .ui-textarea {
                width: 100%;
                min-height: 44px;
                border: 1px solid #dce5ef;
                border-radius: 12px;
                padding: 12px 14px;
                font: inherit;
                background: #fff;
                color: #122033;
            }

            .ui-textarea {
                min-height: 120px;
                resize: vertical;
            }

            .ui-field {
                display: grid;
                gap: 8px;
            }

            .ui-field label {
                font-size: 13px;
                font-weight: 700;
                color: #122033;
            }

            .ui-required {
                color: #dc2626;
            }

            .ui-required-note {
                font-size: 12px;
                color: var(--muted);
                margin-bottom: 12px;
            }

            .ui-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 44px;
                padding: 0 16px;
                border-radius: 12px;
                text-decoration: none;
                font-weight: 700;
                border: 1px solid transparent;
                cursor: pointer;
            }

            .ui-btn--primary {
                background: #0f62fe;
                color: #fff;
            }

            .ui-btn--secondary {
                background: #fff;
                color: #122033;
                border-color: #dce5ef;
            }

            .ui-badge {
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

            .ui-badge--neutral { background: #e5e7eb; color: #374151; }
            .ui-badge--blue { background: #dbeafe; color: #1d4ed8; }
            .ui-badge--green { background: #dff5e8; color: #166534; }
            .ui-badge--yellow { background: #fff0c9; color: #854d0e; }
            .ui-badge--red { background: #fee2e2; color: #991b1b; }

            .ui-table-wrap {
                overflow-x: auto;
            }

            .ui-table {
                width: 100%;
                border-collapse: collapse;
                min-width: 960px;
            }

            .ui-table th,
            .ui-table td {
                padding: 14px 12px;
                border-bottom: 1px solid #edf2f7;
                text-align: left;
                vertical-align: middle;
                font-size: 13px;
            }

            .ui-table th {
                text-transform: uppercase;
                letter-spacing: 0.08em;
                font-size: 11px;
                color: #607086;
                font-weight: 800;
                background: #f8fbff;
            }

            .ui-pagination-wrap {
                margin-top: 14px;
                display: flex;
                justify-content: space-between;
                gap: 12px;
                align-items: center;
                flex-wrap: wrap;
                color: var(--muted);
                font-size: 13px;
            }

            @media (max-width: 1100px) {
                .ui-toolbar {
                    grid-template-columns: 1fr 1fr;
                }
            }

            @media (max-width: 720px) {
                .ui-toolbar {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <script>
            (function () {
                try {
                    const collapsed = localStorage.getItem('sidebar-collapsed') === '1';
                    document.body.classList.toggle('sidebar-collapsed', collapsed);
                } catch (error) {
                    // ignore storage access issues
                }
            })();
        </script>
    </head>
    <body>
        <div class="app-shell">
            <div class="app-frame">
                @include('partials.sidebar')

                <div class="app-main-column">
                    @include('partials.topbar')

                    <main class="app-main">
                        @yield('content')
                    </main>

                    <footer class="app-footer">
                        {{ config('app.name', 'Admin Monitoring') }} · Local development environment
                    </footer>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
