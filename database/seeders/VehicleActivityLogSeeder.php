<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class VehicleActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        if (VehicleActivityLog::query()->exists()) {
            return;
        }

        $userId = User::query()->value('id');
        $vehicles = Vehicle::query()->orderBy('id')->take(3)->get();

        foreach ($vehicles as $index => $vehicle) {
            VehicleActivityLog::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => $userId,
                'action' => $index === 1 ? 'service_logged' : 'status_changed',
                'description' => $index === 1
                    ? 'Routine service logged.'
                    : 'Vehicle marked ' . ucfirst($vehicle->status) . '.',
                'created_at' => now()->subHours(($index + 1) * 3),
                'updated_at' => now()->subHours(($index + 1) * 3),
            ]);
        }
    }
}
