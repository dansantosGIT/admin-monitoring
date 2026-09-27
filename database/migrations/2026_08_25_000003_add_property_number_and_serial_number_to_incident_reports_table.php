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
            if (! Schema::hasColumn('incident_reports', 'property_number')) {
                $table->string('property_number')->nullable()->after('property_serial_no');
            }

            if (! Schema::hasColumn('incident_reports', 'serial_number')) {
                $table->string('serial_number')->nullable()->after('property_number');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('incident_reports')) {
            return;
        }

        Schema::table('incident_reports', function (Blueprint $table) {
            if (Schema::hasColumn('incident_reports', 'serial_number')) {
                $table->dropColumn('serial_number');
            }

            if (Schema::hasColumn('incident_reports', 'property_number')) {
                $table->dropColumn('property_number');
            }
        });
    }
};
