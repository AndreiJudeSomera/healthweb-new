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
        Schema::create('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->enum('patient_type', ['new', 'old'])->nullable();
            $table->unsignedBigInteger('record_id')->nullable()->index('patients_record_id_foreign');
            $table->timestamp('created_at')->useCurrentOnUpdate()->nullable()->default('now()');
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->default('now()');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
