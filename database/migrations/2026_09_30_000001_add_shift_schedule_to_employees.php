<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('shift_type', 20)->nullable()->after('employment_type');
            $table->time('shift_start')->nullable()->after('shift_type');
            $table->time('shift_end')->nullable()->after('shift_start');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['shift_type', 'shift_start', 'shift_end']);
        });
    }
};