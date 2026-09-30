<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DtrEntry extends Model
{
    protected $fillable = [
        'employee_id', 'dtr_period_id', 'work_date', 'time_in', 'time_out', 'status',
        'late_minutes', 'undertime_minutes', 'overtime_minutes', 'remarks',
    ];

    protected $casts = ['work_date' => 'date'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(DtrPeriod::class, 'dtr_period_id');
    }
}
