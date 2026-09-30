<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vehicles') || Schema::hasColumn('vehicles', 'photo_path')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('next_due_date');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('vehicles') && Schema::hasColumn('vehicles', 'photo_path')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('photo_path');
            });
        }
    }
};
