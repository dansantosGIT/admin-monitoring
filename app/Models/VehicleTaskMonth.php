<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTaskMonth extends Model
{
    protected $fillable = ['vehicle_task_id', 'year', 'month', 'state'];

    protected $casts = ['year' => 'integer', 'month' => 'integer'];

    public function task()
    {
        return $this->belongsTo(VehicleTask::class, 'vehicle_task_id');
    }
}