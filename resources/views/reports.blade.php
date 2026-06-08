<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Admin Monitoring') }}</title>
        <style>
            :root {
                color-scheme: light;
                --bg: #f3f6fb;
                --panel: #ffffff;
                --text: #122033;
                --muted: #5e6b7d;
                --accent: #0f62fe;
                --accent-soft: #dbe7ff;
                --border: #d9e2ef;
                --shadow: 0 24px 60px rgba(18, 32, 51, 0.08);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
                background:
                    radial-gradient(circle at top left, rgba(15, 98, 254, 0.12), transparent 32%),
                    radial-gradient(circle at bottom right, rgba(15, 98, 254, 0.08), transparent 28%),
                    var(--bg);
                color: var(--text);
            }

            .shell {
                min-height: 100vh;
                padding: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .card {
                width: min(1120px, 100%);
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(217, 226, 239, 0.9);
                border-radius: 28px;
                box-shadow: var(--shadow);
                overflow: hidden;
            }

            .hero {
                padding: 32px 32px 24px;
                display: grid;
                gap: 20px;
                grid-template-columns: 1.4fr 0.8fr;
                border-bottom: 1px solid var(--border);
                background: linear-gradient(135deg, rgba(15, 98, 254, 0.08), rgba(15, 98, 254, 0.02));
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
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

            .stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }

            .stat {
                background: var(--panel);
                border: 1px solid var(--border);
                border-radius: 20px;
                padding: 18px;
            }

            .stat span {
                display: block;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--muted);
                margin-bottom: 10px;
            }

            .stat strong {
                font-size: 28px;
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
                min-width: 720px;
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

            .footer-note {
                margin-top: 18px;
                color: var(--muted);
                font-size: 14px;
            }

            @media (max-width: 820px) {
                .shell {
                    padding: 16px;
                }

                .hero {
                    grid-template-columns: 1fr;
                    padding: 24px;
                }

                .panel {
                    padding: 20px;
                }

                .stats {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="card">
                <div class="hero">
                    <div>
                        <div class="eyebrow">Personnel Monitoring Report</div>
                        <h1>Local admin monitoring dashboard</h1>
                        <p class="lead">A simple sample report page for personnel incident monitoring. It shows the name, position, type of incident, and date so the local site has a clear view right away.</p>
                    </div>

                    <div class="stats">
                        <div class="stat">
                            <span>Total Reports</span>
                            <strong>{{ count($reports) }}</strong>
                        </div>
                        <div class="stat">
                            <span>Open Status</span>
                            <strong>{{ count($reports) }}</strong>
                        </div>
                        <div class="stat">
                            <span>Site URL</span>
                            <strong>Local</strong>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Type of Incident</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td><strong>{{ $report['name'] }}</strong></td>
                                        <td>{{ $report['position'] }}</td>
                                        <td><span class="badge">{{ $report['incident_type'] }}</span></td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($report['date'])->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="footer-note">This is a starter sample. You can later replace the static rows with database records, filters, and export actions.</p>
                </div>
            </section>
        </main>
    </body>
</html>