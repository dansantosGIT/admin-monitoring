<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VehicleSheetExport implements FromArray, WithHeadings
{
    public function __construct(private Vehicle $vehicle) {}

    public function headings(): array
    {
        return ['No.', 'Task / Service', 'Frequency', 'Responsible Person', 'Status', 'Year', 'Month', 'State'];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->vehicle->tasks as $index => $task) {
            $months = $task->months->groupBy('year');
            if ($months->isEmpty()) {
                $rows[] = [$index + 1, $task->task_name, $task->frequency, $task->responsiblePerson?->full_name ?: $task->responsible_name, ucfirst($task->status), '', '', ''];
                continue;
            }

            foreach ($months as $year => $states) {
                foreach ($states as $month) {
                    $rows[] = [$index + 1, $task->task_name, $task->frequency, $task->responsiblePerson?->full_name ?: $task->responsible_name, ucfirst($task->status), $year, $month->month, ucfirst($month->state)];
                }
            }
        }

        return $rows;
    }
}