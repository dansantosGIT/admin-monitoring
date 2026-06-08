<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Admin Monitoring') }}</title>

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
                width: min(1280px, calc(100% - 32px));
                margin: 0 auto;
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
                border-radius: 14px;
                display: grid;
                place-items: center;
                background: linear-gradient(135deg, var(--accent), #3d8bfd);
                color: #fff;
                font-weight: 800;
                box-shadow: 0 12px 24px rgba(15, 98, 254, 0.28);
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
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .nav a {
                text-decoration: none;
                padding: 10px 14px;
                border-radius: 999px;
                color: var(--muted);
            }

            .nav a:hover,
            .nav a.active {
                color: var(--text);
                background: var(--accent-soft);
            }

            .app-main {
                flex: 1;
                width: 100%;
            }

            .app-footer {
                width: min(1280px, calc(100% - 32px));
                margin: 0 auto;
                padding: 18px 0 28px;
                color: var(--muted);
                font-size: 13px;
            }

            @media (max-width: 760px) {
                .app-header-inner {
                    width: min(1280px, calc(100% - 24px));
                    min-height: auto;
                    padding: 14px 0;
                    flex-direction: column;
                    align-items: flex-start;
                }

                .app-footer {
                    width: min(1280px, calc(100% - 24px));
                }
            }
        </style>
    </head>
    <body>
        <div class="app-shell">
            <header class="app-header">
                <div class="app-header-inner">
                    <a href="{{ url('/') }}" class="brand">
                        <span class="brand-mark">AM</span>
                        <span class="brand-copy">
                            <strong>{{ config('app.name', 'Admin Monitoring') }}</strong>
                            <span>Personnel monitoring dashboard</span>
                        </span>
                    </a>

                    <nav class="nav" aria-label="Primary navigation">
                        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">Reports</a>
                    </nav>
                </div>
            </header>

            <main class="app-main">
                @yield('content')
            </main>

            <footer class="app-footer">
                {{ config('app.name', 'Admin Monitoring') }} · Local development environment
            </footer>
        </div>
    </body>
</html>
