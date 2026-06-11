<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $email = 'superadmin@sanjuan.gov.ph';
        $password = 'P@ssw0rd123';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $email,
                'password' => Hash::make($password),
            ]
        );

        $id = $user->id;
        $this->command->info("Superadmin account ready (id={$id}).");

        DB::table('roles')->insertOrIgnore([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('account_approvals')->updateOrInsert(
            ['user_id' => $id],
            [
                'status' => 'Approved',
                'reviewed_by' => $id,
                'reason' => 'Initial superadmin created',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->command->info('Seeder finished.');
    }
}
