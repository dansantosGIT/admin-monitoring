<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->boolean('is_absent')->default(false);
            $table->integer('late_minutes')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['employee_id','date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
};
