<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vehicles')) {
            return;
        }

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('call_sign');
            $table->string('vehicle_model');
            $table->string('brand');
            $table->string('plate_number')->unique();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('team')->nullable();
            $table->text('drive_link')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('status')->default('active');
            $table->string('last_known_location')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};