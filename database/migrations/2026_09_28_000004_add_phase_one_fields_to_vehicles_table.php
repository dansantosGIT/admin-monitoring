<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vehicles')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicles', 'vehicle_type')) {
                $table->string('vehicle_type')->default('Other')->after('call_sign');
            }

            if (! Schema::hasColumn('vehicles', 'remarks')) {
                $table->text('remarks')->nullable()->after('drive_link');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('vehicle_model')->nullable()->change();
            $table->string('brand')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('vehicles')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'vehicle_type')) {
                $table->dropColumn('vehicle_type');
            }

            if (Schema::hasColumn('vehicles', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};