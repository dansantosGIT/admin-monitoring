<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monitoring_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('total_lates')->default(0);
            $table->unsignedSmallInteger('total_absences')->default(0);
            $table->unsignedSmallInteger('total_violations')->default(0);
            $table->enum('remarks_status', [
                'Satisfactory Compliance',
                'With Attendance Concern',
                'Under Evaluation',
                'Needs Administrative Review',
                'With Recorded Violation',
                'Attendance Improved',
                'Pending Verification',
                'Compliant with Duty Requirements',
                'Under Supervisory Observation'
            ])->default('Pending Verification');
            $table->text('notes')->nullable();
            $table->foreignId('verified_by_officer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by_head_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['employee_id','year','month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('monitoring_records');
    }
};
