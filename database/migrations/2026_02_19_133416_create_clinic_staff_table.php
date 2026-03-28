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
        Schema::create('clinic_staff', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->text('Lname');
            $table->text('Fname');
            $table->text('Mname')->nullable();
            $table->text('ContactNumber')->nullable();
            $table->text('Address')->nullable();
            $table->date('DateofBirth')->nullable();
            $table->integer('Age')->nullable();
            $table->enum('Gender', ['Male', 'Female']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_staff');
    }
};
