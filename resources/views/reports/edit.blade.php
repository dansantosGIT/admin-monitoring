<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Admin Monitoring') }} - Edit Report</title>
        <style>
            :root { --bg:#f5f7fb; --panel:#fff; --text:#122033; --muted:#5e6b7d; --accent:#0f62fe; --border:#d9e2ef; --shadow:0 18px 50px rgba(18,32,51,.08); }
            *{box-sizing:border-box} body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
            .shell{min-height:100vh;padding:32px;display:flex;justify-content:center}.card{width:min(880px,100%);background:var(--panel);border:1px solid var(--border);border-radius:24px;box-shadow:var(--shadow);padding:28px}
            h1{margin:0 0 8px;font-size:32px}.lead{margin:0 0 24px;color:var(--muted);line-height:1.6}
            label{display:block;font-weight:700;margin:0 0 8px} input,select,textarea{width:100%;padding:14px 16px;border:1px solid var(--border);border-radius:14px;font:inherit;margin:0 0 18px;background:#fff}
            textarea{min-height:140px;resize:vertical}.row{display:grid;grid-template-columns:1fr 1fr;gap:16px}.actions{display:flex;gap:12px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 16px;border-radius:999px;text-decoration:none;font-weight:700;border:1px solid transparent}.primary{background:var(--accent);color:#fff}.secondary{background:#fff;color:var(--text);border-color:var(--border)}
            @media (max-width:700px){.shell{padding:16px}.card{padding:20px}.row{grid-template-columns:1fr}}
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="card">
                <h1>Edit report</h1>
                <p class="lead">Update the selected monitoring report.</p>

                <form method="POST" action="{{ route('reports.update', $report) }}">
                    @csrf
                    @method('PUT')

                    <label for="title">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $report->title) }}" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description">{{ old('description', $report->description) }}</textarea>

                    <div class="row">
                        <div>
                            <label for="type">Type</label>
                            <select id="type" name="type" required>
                                @foreach (['system', 'performance', 'user', 'custom'] as $type)
                                    <option value="{{ $type }}" @selected(old('type', $report->type) === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                @foreach (['draft', 'published', 'archived'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $report->status) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="btn primary" type="submit">Update Report</button>
                        <a class="btn secondary" href="{{ route('reports.show', $report) }}">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
    </body>
</html>
