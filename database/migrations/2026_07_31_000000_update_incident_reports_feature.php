<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('incident_reports')) {
            return;
        }

        Schema::table('incident_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('incident_reports', 'incident_code')) {
                $table->string('incident_code')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('incident_reports', 'employee_id')) {
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete()->after('incident_code');
            }

            if (! Schema::hasColumn('incident_reports', 'department')) {
                $table->string('department')->nullable()->after('employee_id');
            }

            if (! Schema::hasColumn('incident_reports', 'incident_type')) {
                $table->enum('incident_type', ['equipment_damage', 'equipment_loss', 'vehicle_incident', 'other'])->nullable()->after('department');
            }

            if (! Schema::hasColumn('incident_reports', 'item_name')) {
                $table->string('item_name')->nullable()->after('incident_type');
            }

            if (! Schema::hasColumn('incident_reports', 'property_serial_no')) {
                $table->string('property_serial_no')->nullable()->after('item_name');
            }

            if (! Schema::hasColumn('incident_reports', 'location')) {
                $table->string('location')->nullable()->after('description');
            }

            if (! Schema::hasColumn('incident_reports', 'date_of_incident')) {
                $table->date('date_of_incident')->nullable()->after('location');
            }

            if (! Schema::hasColumn('incident_reports', 'severity')) {
                $table->enum('severity', ['minor', 'major', 'critical'])->nullable()->after('date_of_incident');
            }

            if (! Schema::hasColumn('incident_reports', 'estimated_cost')) {
                $table->decimal('estimated_cost', 12, 2)->nullable()->after('severity');
            }

            if (! Schema::hasColumn('incident_reports', 'status')) {
                $table->enum('status', ['pending', 'under_investigation', 'resolved', 'closed'])->default('pending')->after('estimated_cost');
            }

            if (! Schema::hasColumn('incident_reports', 'action_taken')) {
                $table->text('action_taken')->nullable()->after('status');
            }

            if (! Schema::hasColumn('incident_reports', 'reported_by')) {
                $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete()->after('action_taken');
            }

            if (! Schema::hasColumn('incident_reports', 'remarks')) {
                $table->text('remarks')->nullable()->after('reported_by');
            }

            if (! Schema::hasColumn('incident_reports', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        if (Schema::hasColumn('incident_reports', 'status') && DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE incident_reports MODIFY status ENUM('pending', 'under_investigation', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
        }

        if (Schema::hasColumn('incident_reports', 'submitted_by') && Schema::hasColumn('incident_reports', 'reported_by')) {
            DB::table('incident_reports')
                ->whereNull('reported_by')
                ->update(['reported_by' => DB::raw('submitted_by')]);
        }

        if (Schema::hasColumn('incident_reports', 'report_number') && Schema::hasColumn('incident_reports', 'incident_code')) {
            DB::table('incident_reports')
                ->whereNull('incident_code')
                ->update(['incident_code' => DB::raw('report_number')]);
        }

        if (Schema::hasColumn('incident_reports', 'incident_date') && Schema::hasColumn('incident_reports', 'date_of_incident')) {
            DB::table('incident_reports')
                ->whereNull('date_of_incident')
                ->update(['date_of_incident' => DB::raw('incident_date')]);
        }

        if (Schema::hasColumn('incident_reports', 'status')) {
            DB::table('incident_reports')
                ->update([
                    'status' => DB::raw("CASE LOWER(status)
                        WHEN 'draft' THEN 'pending'
                        WHEN 'submitted' THEN 'pending'
                        WHEN 'under review' THEN 'under_investigation'
                        WHEN 'closed' THEN 'closed'
                        WHEN 'rejected' THEN 'closed'
                        ELSE status
                    END")
                ]);
        }

        if (! Schema::hasTable('incident_attachments')) {
            Schema::create('incident_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('incident_report_id')->constrained('incident_reports')->cascadeOnDelete();
                $table->string('file_path');
                $table->string('original_filename');
                $table->timestamp('uploaded_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('incident_attachments')) {
            Schema::dropIfExists('incident_attachments');
        }
    }
};