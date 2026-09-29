<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vehicle_task_months')) {
            return;
        }

        Schema::create('vehicle_task_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_task_id')->constrained('vehicle_tasks')->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->string('state');
            $table->timestamps();
            $table->unique(['vehicle_task_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_task_months');
    }
};