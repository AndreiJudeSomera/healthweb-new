<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_audit_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('audit_logs', function (Blueprint $table) {
      $table->id();

      // who did it
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

      // what happened
      $table->string('action', 80); // e.g. "appointment.confirm"
      $table->string('entity_type', 80)->nullable(); // e.g. "Appointment"
      $table->unsignedBigInteger('entity_id')->nullable();

      // where/extra
      $table->string('route')->nullable();
      $table->string('ip', 45)->nullable();
      $table->string('user_agent', 255)->nullable();

      // payloads (keep small)
      $table->json('meta')->nullable(); // anything extra (pid, status, etc)
      $table->json('changes')->nullable(); // optional: {field: {from,to}}

      $table->timestamps();

      $table->index(['action', 'created_at']);
      $table->index(['entity_type', 'entity_id']);
      $table->index(['user_id', 'created_at']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('audit_logs');
  }
};
