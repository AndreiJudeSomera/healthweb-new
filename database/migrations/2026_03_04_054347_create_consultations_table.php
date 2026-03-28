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
        Schema::create('consultations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patient_pid')->default('')->index('consultations_patientrecord_id_index');
            $table->unsignedBigInteger('appointment_id')->nullable()->index('appointment_foreign');
            $table->date('consultation_date')->nullable();
            $table->enum('document_type', ['medical-certificate', 'referral-letter', 'prescription', 'consultation'])->nullable();
            $table->text('wt')->nullable();
            $table->text('bp')->nullable();
            $table->text('cr')->nullable();
            $table->text('rr')->nullable();
            $table->text('temperature')->nullable();
            $table->text('sp02')->nullable();
            $table->text('history_physical_exam')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->timestamp('created_at')->nullable()->default('now()');
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            $table->text('referral_to')->nullable();
            $table->text('referral_reason')->nullable();
            $table->text('prescription_meds')->nullable();
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
