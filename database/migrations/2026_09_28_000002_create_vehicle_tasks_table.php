<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vehicle_tasks')) {
            return;
        }

        Schema::create('vehicle_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('task_option_id')->nullable()->constrained('task_options')->nullOnDelete();
            $table->string('task_name');
            $table->string('frequency')->nullable();
            $table->foreignId('responsible_person_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('responsible_name')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_tasks');
    }
};