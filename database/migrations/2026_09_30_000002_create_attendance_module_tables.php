<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->string('shift_type', 20)->nullable();
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->string('status', 20)->default('assigned');
            $table->timestamps();
            $table->index(['employee_id', 'effective_from']);
        });

        Schema::create('dtr_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('status', 20)->default('open');
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'period_start']);
        });

        Schema::create('dtr_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('dtr_period_id')->constrained('dtr_periods')->restrictOnDelete();
            $table->date('work_date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('status', 20)->default('present');
            $table->unsignedInteger('late_minutes')->default(0);
            $table->unsignedInteger('undertime_minutes')->default(0);
            $table->unsignedInteger('overtime_minutes')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'work_date']);
        });

        Schema::create('leave_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->string('leave_type', 40);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days', 5, 2);
            $table->string('status', 20)->default('pending');
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->index(['employee_id', 'start_date']);
        });

        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->unsignedSmallInteger('year');
            $table->decimal('vacation_leave', 6, 2)->default(0);
            $table->decimal('sick_leave', 6, 2)->default(0);
            $table->decimal('special_leave', 6, 2)->default(0);
            $table->timestamps();
            $table->unique(['employee_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_credits');
        Schema::dropIfExists('leave_records');
        Schema::dropIfExists('dtr_entries');
        Schema::dropIfExists('dtr_periods');
        Schema::dropIfExists('attendance_schedules');
    }
};
