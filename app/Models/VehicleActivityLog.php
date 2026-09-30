<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleActivityLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'action',
        'description',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
