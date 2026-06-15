<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'maiden_name')) {
                $table->string('maiden_name')->nullable()->after('suffix');
            }
            if (!Schema::hasColumn('employees', 'sex')) {
                $table->string('sex')->nullable()->after('maiden_name');
            }
            if (!Schema::hasColumn('employees', 'civil_status')) {
                $table->string('civil_status')->nullable()->after('sex');
            }
            if (!Schema::hasColumn('employees', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('civil_status');
            }
            if (!Schema::hasColumn('employees', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable()->after('birthdate');
            }

            if (!Schema::hasColumn('employees', 'nationality')) {
                $table->string('nationality')->nullable()->after('place_of_birth');
            }
            if (!Schema::hasColumn('employees', 'religion')) {
                $table->string('religion')->nullable()->after('nationality');
            }

            if (!Schema::hasColumn('employees', 'present_address')) {
                $table->json('present_address')->nullable()->after('religion');
            }
            if (!Schema::hasColumn('employees', 'permanent_address')) {
                $table->json('permanent_address')->nullable()->after('present_address');
            }

            if (!Schema::hasColumn('employees', 'mobile')) {
                $table->string('mobile')->nullable()->after('permanent_address');
            }
            if (!Schema::hasColumn('employees', 'phone')) {
                $table->string('phone')->nullable()->after('mobile');
            }
            if (!Schema::hasColumn('employees', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('employees', 'sss')) {
                $table->string('sss')->nullable()->after('email');
            }
            if (!Schema::hasColumn('employees', 'gsis')) {
                $table->string('gsis')->nullable()->after('sss');
            }
            if (!Schema::hasColumn('employees', 'philhealth')) {
                $table->string('philhealth')->nullable()->after('gsis');
            }
            if (!Schema::hasColumn('employees', 'pagibig')) {
                $table->string('pagibig')->nullable()->after('philhealth');
            }
            if (!Schema::hasColumn('employees', 'tin')) {
                $table->string('tin')->nullable()->after('pagibig');
            }

            if (!Schema::hasColumn('employees', 'position')) {
                $table->string('position')->nullable()->after('tin');
            }
            if (!Schema::hasColumn('employees', 'department')) {
                $table->string('department')->nullable()->after('position');
            }
            if (!Schema::hasColumn('employees', 'section')) {
                $table->string('section')->nullable()->after('department');
            }

            if (!Schema::hasColumn('employees', 'monthly_salary')) {
                $table->decimal('monthly_salary', 12, 2)->nullable()->after('section');
            }
            if (!Schema::hasColumn('employees', 'salary_grade')) {
                $table->string('salary_grade')->nullable()->after('monthly_salary');
            }
            if (!Schema::hasColumn('employees', 'supervisor_id')) {
                $table->unsignedBigInteger('supervisor_id')->nullable()->after('salary_grade');
            }

            if (!Schema::hasColumn('employees', 'spouse')) {
                $table->json('spouse')->nullable()->after('supervisor_id');
            }
            if (!Schema::hasColumn('employees', 'parents')) {
                $table->json('parents')->nullable()->after('spouse');
            }
            if (!Schema::hasColumn('employees', 'children')) {
                $table->json('children')->nullable()->after('parents');
            }
            if (!Schema::hasColumn('employees', 'education')) {
                $table->json('education')->nullable()->after('children');
            }
            if (!Schema::hasColumn('employees', 'eligibilities')) {
                $table->json('eligibilities')->nullable()->after('education');
            }
            if (!Schema::hasColumn('employees', 'work_experience')) {
                $table->json('work_experience')->nullable()->after('eligibilities');
            }
            if (!Schema::hasColumn('employees', 'trainings')) {
                $table->json('trainings')->nullable()->after('work_experience');
            }

            if (!Schema::hasColumn('employees', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('trainings');
            }
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $cols = [
                'maiden_name','sex','civil_status','birthdate','place_of_birth','nationality','religion',
                'present_address','permanent_address','mobile','phone','email',
                'sss','gsis','philhealth','pagibig','tin',
                'position','department','section','monthly_salary','salary_grade','supervisor_id',
                'spouse','parents','children','education','eligibilities','work_experience','trainings','photo_path'
            ];
            foreach ($cols as $c) {
                if (Schema::hasColumn('employees', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
