<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRecord extends Model
{
    protected $fillable = ['employee_id', 'leave_category', 'leave_type', 'start_date', 'end_date', 'days', 'status', 'reason'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'days' => 'decimal:2'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
