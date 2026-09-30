<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vehicles') && ! Schema::hasColumn('vehicles', 'vehicle_type_other')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->string('vehicle_type_other')->nullable()->after('vehicle_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vehicles') && Schema::hasColumn('vehicles', 'vehicle_type_other')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('vehicle_type_other');
            });
        }
    }
};