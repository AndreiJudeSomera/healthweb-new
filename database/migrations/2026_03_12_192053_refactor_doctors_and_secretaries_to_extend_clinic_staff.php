<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- DOCTORS ---
        // Only remap + restructure if the old auto-increment 'id' column still exists
        if (Schema::hasColumn('doctors', 'id')) {
            // Remap appointments.attended_by from doctor.id → doctor.user_id
            $doctors = DB::table('doctors')->get(['id', 'user_id']);
            foreach ($doctors as $doctor) {
                DB::table('appointments')
                    ->where('attended_by', $doctor->id)
                    ->update(['attended_by' => $doctor->user_id]);
            }

            // Remove AUTO_INCREMENT so MySQL allows dropping the PK
            DB::statement('ALTER TABLE doctors MODIFY id BIGINT UNSIGNED NOT NULL');

            Schema::table('doctors', function (Blueprint $table) {
                $table->dropPrimary('primary');
                if (Schema::hasIndex('doctors', 'doctors_user_id_foreign')) {
                    $table->dropIndex('doctors_user_id_foreign');
                }
                $table->dropColumn('id');
            });

            Schema::table('doctors', function (Blueprint $table) {
                $table->primary('user_id');
            });
        }

        // Seed clinic_staff for any doctors that don't have a matching row yet
        $doctorUserIds = DB::table('doctors')->pluck('user_id');
        $existingStaff = DB::table('clinic_staff')->pluck('user_id')->flip();
        foreach ($doctorUserIds as $userId) {
            if (!$existingStaff->has($userId)) {
                $user = DB::table('users')->find($userId);
                $username = $user ? $user->username : 'Unknown';
                $parts = explode(' ', $username, 2);
                DB::table('clinic_staff')->insert([
                    'user_id'       => $userId,
                    'Fname'         => $parts[0] ?? $username,
                    'Lname'         => $parts[1] ?? '—',
                    'Mname'         => null,
                    'ContactNumber' => null,
                    'Address'       => null,
                    'DateofBirth'   => null,
                    'Age'           => null,
                    'Gender'        => 'Male',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // Add FK: doctors.user_id → clinic_staff.user_id (if not already there)
        $doctorsFk = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'doctors'
             AND CONSTRAINT_NAME = 'doctors_user_id_clinic_staff_foreign'"
        );
        if (empty($doctorsFk)) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->foreign('user_id', 'doctors_user_id_clinic_staff_foreign')
                      ->references('user_id')
                      ->on('clinic_staff')
                      ->onDelete('cascade');
            });
        }

        // --- SECRETARIES ---
        // Swap FK from users → clinic_staff (if not already swapped)
        $secOldFk = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'secretaries'
             AND CONSTRAINT_NAME = 'secretaries_user_id_foreign'"
        );
        if (!empty($secOldFk)) {
            Schema::table('secretaries', function (Blueprint $table) {
                $table->dropForeign('secretaries_user_id_foreign');
            });
        }

        $secNewFk = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'secretaries'
             AND CONSTRAINT_NAME = 'secretaries_user_id_clinic_staff_foreign'"
        );
        if (empty($secNewFk)) {
            // Seed clinic_staff for secretaries that don't have an entry
            $secUserIds = DB::table('secretaries')->pluck('user_id');
            $existingStaff = DB::table('clinic_staff')->pluck('user_id')->flip();
            foreach ($secUserIds as $userId) {
                if (!$existingStaff->has($userId)) {
                    $user = DB::table('users')->find($userId);
                    $username = $user ? $user->username : 'Unknown';
                    $parts = explode(' ', $username, 2);
                    DB::table('clinic_staff')->insert([
                        'user_id'       => $userId,
                        'Fname'         => $parts[0] ?? $username,
                        'Lname'         => $parts[1] ?? '—',
                        'Mname'         => null,
                        'ContactNumber' => null,
                        'Address'       => null,
                        'DateofBirth'   => null,
                        'Age'           => null,
                        'Gender'        => 'Male',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

            Schema::table('secretaries', function (Blueprint $table) {
                $table->foreign('user_id', 'secretaries_user_id_clinic_staff_foreign')
                      ->references('user_id')
                      ->on('clinic_staff')
                      ->onDelete('cascade');
            });
        }

        // --- APPOINTMENTS ---
        // Add FK: appointments.attended_by → doctors.user_id (if not already there)
        $apptFk = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'appointments'
             AND CONSTRAINT_NAME = 'appointments_attended_by_foreign'"
        );
        if (empty($apptFk)) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->foreign('attended_by', 'appointments_attended_by_foreign')
                      ->references('user_id')
                      ->on('doctors')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointments_attended_by_foreign');
        });

        Schema::table('secretaries', function (Blueprint $table) {
            $table->dropForeign('secretaries_user_id_clinic_staff_foreign');
            $table->foreign('user_id', 'secretaries_user_id_foreign')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign('doctors_user_id_clinic_staff_foreign');
            $table->dropPrimary('primary');
        });

        DB::statement('ALTER TABLE doctors ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');

        Schema::table('doctors', function (Blueprint $table) {
            $table->index('user_id', 'doctors_user_id_foreign');
        });

        // Remap appointments.attended_by back from doctor.user_id → doctor.id
        $doctors = DB::table('doctors')->get(['id', 'user_id']);
        foreach ($doctors as $doctor) {
            DB::table('appointments')
                ->where('attended_by', $doctor->user_id)
                ->update(['attended_by' => $doctor->id]);
        }
    }
};
