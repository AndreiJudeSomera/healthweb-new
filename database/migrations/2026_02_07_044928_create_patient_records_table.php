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
        Schema::create('patient_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pid')->default('')->unique('patient_records_pid_number_unique');
            $table->enum('patient_type', ['new', 'old'])->nullable()->default('new');
            $table->text('last_name');
            $table->text('first_name');
            $table->text('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->text('nationality')->nullable();
            $table->text('contact_number');
            $table->text('address')->nullable();
            $table->text('guardian_name')->nullable();
            $table->text('guardian_relation')->nullable();
            $table->text('guardian_contact')->nullable();
            $table->text('allergy')->nullable();
            $table->string('alcohol')->nullable();
            $table->integer('years_of_smoking')->nullable();
            $table->text('illicit_drug_use')->nullable();
            $table->boolean('hypertension')->default(false);
            $table->boolean('asthma')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('cancer')->default(false);
            $table->boolean('thyroid')->default(false);
            $table->text('others')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_records');
    }
};
