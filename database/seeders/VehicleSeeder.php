<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['call_sign' => 'Alpha 1', 'vehicle_type' => 'Ambulance', 'vehicle_model' => 'Hiace', 'brand' => 'Toyota', 'plate_number' => 'SNI 2701', 'team' => 'Team Alpha', 'status' => 'active'],
            ['call_sign' => 'Bravo 1', 'vehicle_type' => 'Rescue Truck', 'vehicle_model' => 'Ranger', 'brand' => 'Ford', 'plate_number' => 'SNI 2702', 'team' => 'Team Bravo', 'status' => 'idle'],
            ['call_sign' => 'Charlie 1', 'vehicle_type' => 'Fire Truck', 'vehicle_model' => 'FVR', 'brand' => 'Isuzu', 'plate_number' => 'SNI 2703', 'team' => 'Team Charlie', 'status' => 'offline'],
        ] as $vehicle) {
            Vehicle::updateOrCreate(['plate_number' => $vehicle['plate_number']], $vehicle);
        }
    }
}