<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Vehicle> */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'call_sign' => fake()->unique()->randomElement(['Alpha 1', 'Bravo 1', 'Charlie 1', 'Delta 1']),
            'vehicle_type' => fake()->randomElement(['Ambulance', 'Rescue Truck', 'Fire Truck', 'Van', 'Motorcycle', 'Other']),
            'vehicle_model' => 'Hiace',
            'brand' => 'Toyota',
            'plate_number' => fake()->unique()->bothify('??? ####'),
            'team' => 'Team Alpha',
            'status' => fake()->randomElement(['active', 'idle', 'offline']),
        ];
    }
}