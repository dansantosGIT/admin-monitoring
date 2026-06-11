<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->date('incident_date');
            $table->text('description');
            $table->enum('status', ['Draft','Submitted','Under Review','Closed','Rejected'])->default('Submitted');
            $table->text('remarks')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incident_reports');
    }
};
