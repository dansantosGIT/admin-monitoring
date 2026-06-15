<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_number', 'first_name', 'last_name', 'middle_name', 'suffix', 'maiden_name',
        'sex', 'civil_status', 'birthdate', 'place_of_birth', 'nationality', 'religion',
        'present_address', 'permanent_address', 'mobile', 'phone', 'email',
        'sss', 'gsis', 'philhealth', 'pagibig', 'tin',
        'position', 'department', 'section', 'employment_type', 'date_hired', 'monthly_salary', 'salary_grade', 'supervisor_id',
        'spouse', 'parents', 'children', 'education', 'eligibilities', 'work_experience', 'trainings',
        'photo_path', 'remarks', 'status'
    ];

    protected $casts = [
        'present_address' => 'array',
        'permanent_address' => 'array',
        'spouse' => 'array',
        'parents' => 'array',
        'children' => 'array',
        'education' => 'array',
        'eligibilities' => 'array',
        'work_experience' => 'array',
        'trainings' => 'array',
        'date_hired' => 'date',
        'birthdate' => 'date',
    ];
}
