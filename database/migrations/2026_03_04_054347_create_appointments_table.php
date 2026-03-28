<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patient_pid')->index('appointments_user_id_foreign');
            $table->unsignedBigInteger('attended_by')->nullable()->index('appointments_attended_by_foreign');
            $table->enum('appointment_type', ['consultation', 'follow-up', 'prescription', 'medical-certificate', 'referral-letter', 'other'])->default('other');
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['pending', 'approved', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();

            $table->index(['appointment_date', 'appointment_time', 'status'], 'idx_appointments_date_time_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
