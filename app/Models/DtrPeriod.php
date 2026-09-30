<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DtrPeriod extends Model
{
    protected $fillable = ['employee_id', 'period_start', 'period_end', 'status', 'locked_at'];

    protected $casts = ['period_start' => 'date', 'period_end' => 'date', 'locked_at' => 'datetime'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(DtrEntry::class);
    }
}
