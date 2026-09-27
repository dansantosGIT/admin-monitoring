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
            if (! Schema::hasColumn('incident_reports', 'team')) {
                $table->string('team')->nullable()->after('department');
            }

            if (! Schema::hasColumn('incident_reports', 'department_other')) {
                $table->string('department_other')->nullable()->after('team');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('incident_reports')) {
            return;
        }

        Schema::table('incident_reports', function (Blueprint $table) {
            if (Schema::hasColumn('incident_reports', 'department_other')) {
                $table->dropColumn('department_other');
            }

            if (Schema::hasColumn('incident_reports', 'team')) {
                $table->dropColumn('team');
            }
        });
    }
};
