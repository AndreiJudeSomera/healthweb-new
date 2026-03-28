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
        Schema::table('consultations', function (Blueprint $table) {
            $table->foreign(['appointment_id'], 'appointment_foreign')->references(['id'])->on('appointments')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['patient_pid'], 'consultations_patientrecord_id_foreign')->references(['pid'])->on('patient_records')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign('appointment_foreign');
            $table->dropForeign('consultations_patientrecord_id_foreign');
        });
    }
};
