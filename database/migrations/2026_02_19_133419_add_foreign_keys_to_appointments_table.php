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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign(['attended_by'])->references(['id'])->on('doctors')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['patient_pid'], 'appointments_pid_foreign')->references(['pid'])->on('patient_records')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_attended_by_foreign');
            $table->dropForeign('appointments_pid_foreign');
        });
    }
};
