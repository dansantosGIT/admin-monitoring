<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Admin Monitoring') }} - Reports</title>
        <style>
            :root {
                --bg: #f5f7fb;
                --panel: #ffffff;
                --text: #122033;
                --muted: #5e6b7d;
                --accent: #0f62fe;
                --accent-soft: #dbe7ff;
                --border: #d9e2ef;
                --shadow: 0 18px 50px rgba(18, 32, 51, 0.08);
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
                background:
                    radial-gradient(circle at top left, rgba(15, 98, 254, 0.12), transparent 30%),
                    radial-gradient(circle at bottom right, rgba(15, 98, 254, 0.08), transparent 28%),
                    var(--bg);
                color: var(--text);
            }

            .shell {
                min-height: 100vh;
                padding: 32px;
                display: flex;
                justify-content: center;
            }

            .card {
                width: min(1200px, 100%);
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(217, 226, 239, 0.9);
                border-radius: 28px;
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .hero {
                padding: 32px;
                display: grid;
                gap: 20px;
                grid-template-columns: 1.2fr 0.8fr;
                border-bottom: 1px solid var(--border);
                background: linear-gradient(135deg, rgba(15, 98, 254, 0.08), rgba(15, 98, 254, 0.02));
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                padding: 8px 12px;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent);
                font-size: 12px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                width: fit-content;
            }

            h1 {
                margin: 14px 0 10px;
                font-size: clamp(28px, 4vw, 46px);
                line-height: 1.02;
            }

            .lead {
                margin: 0;
                color: var(--muted);
                max-width: 62ch;
                line-height: 1.6;
            }

            .actions {
                display: flex;
                justify-content: flex-end;
                align-items: flex-start;
                gap: 12px;
                flex-wrap: wrap;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 44px;
                padding: 0 16px;
                border-radius: 999px;
                text-decoration: none;
                font-weight: 700;
                border: 1px solid transparent;
            }

            .btn.primary {
                background: var(--accent);
                color: #fff;
            }

            .btn.secondary {
                background: #fff;
                color: var(--text);
                border-color: var(--border);
            }

            .panel {
                padding: 24px 32px 32px;
            }

            .table-wrap {
                overflow-x: auto;
                border: 1px solid var(--border);
                border-radius: 20px;
                background: var(--panel);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                min-width: 860px;
            }

            thead {
                background: linear-gradient(180deg, #f8fbff, #eef4ff);
            }

            th, td {
                padding: 16px 18px;
                text-align: left;
                border-bottom: 1px solid var(--border);
                vertical-align: top;
            }

            th {
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--muted);
            }

            tbody tr:hover {
                background: rgba(15, 98, 254, 0.04);
            }

            .badge {
                display: inline-flex;
                padding: 7px 10px;
                border-radius: 999px;
                background: #edf4ff;
                color: #1849a9;
                font-size: 13px;
                font-weight: 700;
            }

            .muted {
                color: var(--muted);
            }

            .empty {
                padding: 24px;
                text-align: center;
                color: var(--muted);
            }

            @media (max-width: 820px) {
                .shell { padding: 16px; }
                .hero { grid-template-columns: 1fr; padding: 24px; }
                .panel { padding: 20px; }
            }
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="card">
                <div class="hero">
                    <div>
                        <div class="eyebrow">Reports</div>
                        <h1>Monitoring reports</h1>
                        <p class="lead">This page lists the report records pulled from the database. You can create, inspect, update, or export each entry from here.</p>
                    </div>

                    <div class="actions">
                        <a class="btn secondary" href="{{ url('/dashboard') }}">Dashboard</a>
                        <a class="btn primary" href="{{ route('reports.create') }}">New Report</a>
                    </div>
                </div>

                <div class="panel">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    <tr>
                                        <td>
                                            <strong>{{ $report->title }}</strong><br>
                                            <span class="muted">{{ $report->description ?: 'No description provided' }}</span>
                                        </td>
                                        <td>{{ ucfirst($report->type) }}</td>
                                        <td><span class="badge">{{ ucfirst($report->status) }}</span></td>
                                        <td>{{ optional($report->created_at)->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('reports.show', $report) }}">View</a>
                                            &nbsp;|&nbsp;
                                            <a href="{{ route('reports.edit', $report) }}">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="empty">No reports found yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
