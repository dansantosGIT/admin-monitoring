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
        // Return the report data in a format suitable for Excel export
        $rows = [];
        
        if (is_array($this->report->data)) {
            foreach ($this->report->data as $key => $value) {
                $rows[] = [
                    'key' => $key,
                    'value' => is_array($value) ? json_encode($value) : $value,
                ];
            }
        }

        return collect($rows);
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
