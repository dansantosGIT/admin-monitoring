<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $email = 'superadmin@sanjuan.gov.ph';

        $existing = DB::table('users')->where('email', $email)->first();
        if ($existing) {
            $id = $existing->id;
            $this->command->info("Superadmin already exists (id={$id}).");
        } else {
            $id = DB::table('users')->insertGetId([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => Hash::make('P@ssw0rd123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Created superadmin (id={$id}).");
        }

        DB::table('roles')->insertOrIgnore([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('account_approvals')->insertOrIgnore([
            'user_id' => $id,
            'status' => 'Approved',
            'reviewed_by' => $id,
            'reason' => 'Initial superadmin created',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Seeder finished.');
    }
}
