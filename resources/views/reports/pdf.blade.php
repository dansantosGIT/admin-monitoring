<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #122033; }
            h1 { font-size: 22px; margin-bottom: 8px; }
            p { margin: 0 0 12px; }
            table { width: 100%; border-collapse: collapse; margin-top: 18px; }
            td { border: 1px solid #d9e2ef; padding: 10px 12px; vertical-align: top; }
            .label { width: 30%; font-weight: 700; background: #f5f7fb; }
        </style>
    </head>
    <body>
        <h1>{{ $report->title }}</h1>
        <p>Generated report export.</p>

        <table>
            <tr><td class="label">Description</td><td>{{ $report->description ?: 'No description provided' }}</td></tr>
            <tr><td class="label">Type</td><td>{{ ucfirst($report->type) }}</td></tr>
            <tr><td class="label">Status</td><td>{{ ucfirst($report->status) }}</td></tr>
            <tr><td class="label">Created</td><td>{{ optional($report->created_at)->format('M d, Y H:i') }}</td></tr>
        </table>
    </body>
</html>
