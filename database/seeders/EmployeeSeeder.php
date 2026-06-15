<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $sample = [
            [
                'employee_number' => 'EMP-001',
                'first_name' => 'Juan',
                'middle_name' => 'D',
                'last_name' => 'Dela Cruz',
                'suffix' => null,
                'sex' => 'Male',
                'civil_status' => 'Single',
                'birthdate' => '1990-04-12',
                'mobile' => '09171234567',
                'email' => 'juan.delacruz@example.com',
                'position' => 'Administrative Officer',
                'department' => 'Admin',
                'section' => 'Records',
                'employment_type' => 'Permanent',
                'date_hired' => '2018-06-01',
                'monthly_salary' => 25000,
                'sss' => '34-1234567-8',
                'tin' => '123-456-789',
                'present_address' => ['address'=>'123 Main St','barangay'=>'Central','city'=>'San Juan','province'=>'Metro Manila','zip'=>'1500'],
            ],
            [
                'employee_number' => 'EMP-002',
                'first_name' => 'Maria',
                'middle_name' => 'S',
                'last_name' => 'Santos',
                'sex' => 'Female',
                'civil_status' => 'Married',
                'birthdate' => '1985-11-02',
                'mobile' => '09181234567',
                'email' => 'maria.santos@example.com',
                'position' => 'Planning Officer',
                'department' => 'Planning',
                'section' => 'Programs',
                'employment_type' => 'Permanent',
                'date_hired' => '2016-09-15',
                'monthly_salary' => 32000,
                'sss' => '34-7654321-0',
                'tin' => '987-654-321',
                'present_address' => ['address'=>'45 Riverside','barangay'=>'River','city'=>'San Juan','province'=>'Metro Manila','zip'=>'1500'],
            ],
        ];

        foreach ($sample as $s) {
            Employee::updateOrCreate(
                ['employee_number' => $s['employee_number']],
                $s
            );
        }
    }
}
