<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveCredit extends Model
{
    protected $fillable = ['employee_id', 'year', 'vacation_leave', 'sick_leave', 'special_leave'];

    protected $casts = [
        'year' => 'integer',
        'vacation_leave' => 'decimal:2',
        'sick_leave' => 'decimal:2',
        'special_leave' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
