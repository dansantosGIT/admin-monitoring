<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('incident_reports') && ! Schema::hasColumn('incident_reports', 'reported_by_name')) {
            Schema::table('incident_reports', function (Blueprint $table) {
                $table->string('reported_by_name', 100)->nullable()->after('reported_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('incident_reports') && Schema::hasColumn('incident_reports', 'reported_by_name')) {
            Schema::table('incident_reports', function (Blueprint $table) {
                $table->dropColumn('reported_by_name');
            });
        }
    }
};
