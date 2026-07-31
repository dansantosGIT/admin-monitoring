<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: DejaVu Sans, Arial, sans-serif;
                font-size: 12px;
                color: #122033;
                margin: 0;
                padding: 24px;
            }

            h1 {
                font-size: 22px;
                margin: 0 0 8px;
            }

            .subhead {
                color: #607086;
                margin: 0 0 16px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 14px;
            }

            td {
                border: 1px solid #d9e2ef;
                padding: 10px 12px;
                vertical-align: top;
            }

            .label {
                width: 28%;
                font-weight: 700;
                background: #f5f7fb;
            }

            .section {
                margin-top: 18px;
                font-size: 14px;
                font-weight: 700;
            }
        </style>
    </head>
    <body>
        <h1>{{ $report->incident_code }}</h1>
        <p class="subhead">Incident Report Export</p>

        <table>
            <tr><td class="label">Date of Incident</td><td>{{ optional($report->date_of_incident)->format('M d, Y') }}</td></tr>
            <tr><td class="label">Employee</td><td>{{ $report->employee_name }}</td></tr>
            <tr><td class="label">Department</td><td>{{ $report->department }}</td></tr>
            <tr><td class="label">Incident Type</td><td>{{ ucwords(str_replace('_', ' ', $report->incident_type)) }}</td></tr>
            <tr><td class="label">Item Name</td><td>{{ $report->item_name }}</td></tr>
            <tr><td class="label">Property / Serial No.</td><td>{{ $report->property_serial_no ?: 'N/A' }}</td></tr>
            <tr><td class="label">Location</td><td>{{ $report->location }}</td></tr>
            <tr><td class="label">Severity</td><td>{{ ucfirst($report->severity) }}</td></tr>
            <tr><td class="label">Status</td><td>{{ ucwords(str_replace('_', ' ', $report->status)) }}</td></tr>
            <tr><td class="label">Estimated Cost</td><td>{{ $report->estimated_cost !== null ? number_format((float) $report->estimated_cost, 2) : 'N/A' }}</td></tr>
            <tr><td class="label">Action Taken</td><td>{{ $report->action_taken ?: 'N/A' }}</td></tr>
            <tr><td class="label">Remarks</td><td>{{ $report->remarks ?: 'N/A' }}</td></tr>
            <tr><td class="label">Reported By</td><td>{{ optional($report->reportedBy)->name ?? 'System' }}</td></tr>
        </table>

        <div class="section">Description</div>
        <table>
            <tr><td>{{ $report->description }}</td></tr>
        </table>
    </body>
</html>
