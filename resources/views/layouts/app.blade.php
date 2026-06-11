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

            .app-sidebar {
                width: var(--sidebar-width);
                min-width: var(--sidebar-width);
                background: #ffffff;
                border-right: 1px solid var(--border);
                padding: 18px 16px;
                display:flex;
                flex-direction:column;
                gap:14px;
            }

            .app-main-column {
                flex: 1;
                display: flex;
                flex-direction: column;
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
                display:flex;align-items:center;gap:8px;font-weight:600;
            }

            .nav a.active {
                color: var(--accent);
                background: var(--accent-soft);
            }

            .app-main {
                flex: 1;
                width: 100%;
                padding: 18px 24px;
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
        </style>
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
    </body>
</html>
