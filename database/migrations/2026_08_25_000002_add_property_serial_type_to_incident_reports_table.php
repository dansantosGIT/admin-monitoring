<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('incident_reports')) {
            return;
        }

        Schema::table('incident_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('incident_reports', 'property_serial_type')) {
                $table->string('property_serial_type')->nullable()->after('property_serial_no');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('incident_reports')) {
            return;
        }

        Schema::table('incident_reports', function (Blueprint $table) {
            if (Schema::hasColumn('incident_reports', 'property_serial_type')) {
                $table->dropColumn('property_serial_type');
            }
        });
    }
};
