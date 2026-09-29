<?php

namespace Database\Seeders;

use App\Models\TaskOption;
use Illuminate\Database\Seeder;

class TaskOptionSeeder extends Seeder
{
    public const OPTIONS = [
        'Aircon Maintenance', 'Aircon Repair', 'Air Filter Replacement', 'Battery Replacement',
        'Blinker Replacement', 'Body Repair', 'Brake Maintenance', 'Change Oil', 'Dents',
        'Lights Repair', 'Paint Chipped', 'Radio Repair', 'Siren Repair', 'Tire Change',
        'Tire Repair', 'Window Replacement',
    ];

    public function run(): void
    {
        foreach (self::OPTIONS as $name) {
            TaskOption::firstOrCreate(['name' => $name]);
        }
    }
}