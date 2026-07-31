<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected Report $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the collection of data to be exported.
     */
    public function collection()
    {
        return collect([
            ['key' => 'Incident Code', 'value' => $this->report->incident_code],
            ['key' => 'Date of Incident', 'value' => optional($this->report->date_of_incident)->format('M d, Y')],
            ['key' => 'Employee', 'value' => $this->report->employee_name],
            ['key' => 'Department', 'value' => $this->report->department],
            ['key' => 'Incident Type', 'value' => ucwords(str_replace('_', ' ', $this->report->incident_type))],
            ['key' => 'Item Name', 'value' => $this->report->item_name],
            ['key' => 'Serial Number', 'value' => $this->report->property_serial_no ?: 'N/A'],
            ['key' => 'Location', 'value' => $this->report->location],
            ['key' => 'Severity', 'value' => ucfirst($this->report->severity)],
            ['key' => 'Status', 'value' => ucwords(str_replace('_', ' ', $this->report->status))],
            ['key' => 'Estimated Cost', 'value' => $this->report->estimated_cost !== null ? number_format((float) $this->report->estimated_cost, 2) : 'N/A'],
            ['key' => 'Action Taken', 'value' => $this->report->action_taken ?: 'N/A'],
            ['key' => 'Remarks', 'value' => $this->report->remarks ?: 'N/A'],
            ['key' => 'Reported By', 'value' => optional($this->report->reportedBy)->name ?: 'System'],
        ]);
    }

    /**
     * Provide headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Field',
            'Value',
        ];
    }

    /**
     * Map the data.
     */
    public function map($row): array
    {
        return [
            $row['key'],
            $row['value'],
        ];
    }
}
