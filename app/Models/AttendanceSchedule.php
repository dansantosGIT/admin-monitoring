<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSchedule extends Model
{
    protected $fillable = [
        'employee_id', 'shift_type', 'shift_start', 'shift_end', 'working_days', 'effective_from', 'effective_to', 'status',
    ];

    protected $casts = [
        'working_days' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
