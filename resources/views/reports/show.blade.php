<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Admin Monitoring') }} - Report</title>
        <style>
            :root { --bg:#f5f7fb; --panel:#fff; --text:#122033; --muted:#5e6b7d; --accent:#0f62fe; --border:#d9e2ef; --shadow:0 18px 50px rgba(18,32,51,.08); }
            *{box-sizing:border-box} body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
            .shell{min-height:100vh;padding:32px;display:flex;justify-content:center}.card{width:min(900px,100%);background:var(--panel);border:1px solid var(--border);border-radius:24px;box-shadow:var(--shadow);padding:28px}
            h1{margin:0 0 8px;font-size:32px}.lead{margin:0 0 24px;color:var(--muted);line-height:1.6}
            .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.item{padding:16px;border:1px solid var(--border);border-radius:16px;background:#fbfdff}.label{display:block;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:8px}.value{font-size:18px;font-weight:700}.actions{margin-top:24px;display:flex;gap:12px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 16px;border-radius:999px;text-decoration:none;font-weight:700;border:1px solid transparent}.primary{background:var(--accent);color:#fff}.secondary{background:#fff;color:var(--text);border-color:var(--border)}
            @media (max-width:700px){.shell{padding:16px}.card{padding:20px}.grid{grid-template-columns:1fr}}
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="card">
                <h1>{{ $report->title }}</h1>
                <p class="lead">Detailed report record.</p>

                <div class="grid">
                    <div class="item"><span class="label">Description</span><div class="value">{{ $report->description ?: 'No description provided' }}</div></div>
                    <div class="item"><span class="label">Type</span><div class="value">{{ ucfirst($report->type) }}</div></div>
                    <div class="item"><span class="label">Status</span><div class="value">{{ ucfirst($report->status) }}</div></div>
                    <div class="item"><span class="label">Created</span><div class="value">{{ optional($report->created_at)->format('M d, Y H:i') }}</div></div>
                </div>

                <div class="actions">
                    <a class="btn primary" href="{{ route('reports.edit', $report) }}">Edit</a>
                    <a class="btn secondary" href="{{ route('reports.index') }}">Back to Reports</a>
                </div>
            </section>
        </main>
    </body>
</html>
