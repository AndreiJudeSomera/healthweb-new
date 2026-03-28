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
            $table->bigIncrements('Consultation_ID');
            $table->unsignedBigInteger('PatientRecord_ID')->index();
            $table->text('ConsultationDate')->nullable();
            $table->text('WT')->nullable();
            $table->text('BP')->nullable();
            $table->text('CR')->nullable();
            $table->text('RR')->nullable();
            $table->text('Temperature')->nullable();
            $table->text('SP02')->nullable();
            $table->text('History_PhysicalExam')->nullable();
            $table->text('Diagnosis')->nullable();
            $table->text('Treatment')->nullable();
            $table->timestamps();
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
