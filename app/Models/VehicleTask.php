<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTask extends Model
{
    protected $fillable = [
        'vehicle_id', 'task_option_id', 'task_name', 'frequency',
        'responsible_person_id', 'responsible_name', 'status', 'sort_order',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function taskOption()
    {
        return $this->belongsTo(TaskOption::class);
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(Employee::class, 'responsible_person_id');
    }

    public function months()
    {
        return $this->hasMany(VehicleTaskMonth::class);
    }
}