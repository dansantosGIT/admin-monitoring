<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.test',
            'password' => Hash::make('password'),
            'status' => 'pending',
            'role' => 'member',
        ]);

        // Create a super-admin for testing if not exists
        User::firstOrCreate([
            'email' => 'admin@example.test'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'status' => 'approved',
            'role' => 'super-admin',
        ]);
    }
}
