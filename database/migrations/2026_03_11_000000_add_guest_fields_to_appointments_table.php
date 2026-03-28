<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop FK so we can make patient_pid nullable
            $table->dropForeign('appointments_pid_foreign');

            // Allow NULL for walk-in/guest appointments
            $table->string('patient_pid')->nullable()->change();

            // Re-add FK — nullable column still validates non-null values
            $table->foreign('patient_pid', 'appointments_pid_foreign')
                  ->references('pid')->on('patient_records')
                  ->onUpdate('cascade')->onDelete('cascade');

            // Guest patient details (used when patient_pid is null)
            $table->string('guest_name')->nullable()->after('patient_pid');
            $table->unsignedTinyInteger('guest_age')->nullable()->after('guest_name');
            $table->enum('guest_sex', ['male', 'female'])->nullable()->after('guest_age');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_pid_foreign');
            $table->dropColumn(['guest_name', 'guest_age', 'guest_sex']);
            $table->string('patient_pid')->nullable(false)->change();
            $table->foreign('patient_pid', 'appointments_pid_foreign')
                  ->references('pid')->on('patient_records')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
