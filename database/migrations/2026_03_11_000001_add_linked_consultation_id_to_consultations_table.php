<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('consultations', function (Blueprint $table) {
            $table->unsignedBigInteger('linked_consultation_id')
                  ->nullable()
                  ->after('appointment_id');

            $table->foreign('linked_consultation_id')
                  ->references('id')
                  ->on('consultations')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['linked_consultation_id']);
            $table->dropColumn('linked_consultation_id');
        });
    }
};
