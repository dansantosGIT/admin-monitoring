<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vehicles') || Schema::hasColumn('vehicles', 'next_due_date')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            $table->date('next_due_date')->nullable()->after('last_updated_at');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('vehicles') && Schema::hasColumn('vehicles', 'next_due_date')) {
            Schema::table('vehicles', function (Blueprint $table) {
                $table->dropColumn('next_due_date');
            });
        }
    }
};
