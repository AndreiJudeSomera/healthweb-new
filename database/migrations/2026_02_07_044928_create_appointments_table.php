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
            $table->bigIncrements('Appointment_ID');
            $table->unsignedBigInteger('User_ID')->index('appointments_user_id_foreign');
            $table->binary('name');
            $table->binary('email');
            $table->binary('ContactNumber')->nullable();
            $table->enum('AppointmentType', ['Consultation', 'Follow-up', 'Prescription', 'Medical Certificate', 'Referral Letter', 'Other']);
            $table->date('AppointmentDate');
            $table->time('AppointmentTime');
            $table->enum('Status', ['Pending', 'Approved', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();
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
