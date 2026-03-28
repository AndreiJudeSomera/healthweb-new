<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor; // if you have this model
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Create Doctor User (role = 2)
        $doctor = User::create([
            'username' => 'doctor1',
            'email' => 'doctor@example.test',
            'role' => 2,
            'password' => Hash::make('password'),
        ]);

        // 🔹 If you have a doctors table, create related record
        Doctor::create([
            'user_id' => $doctor->id,
            // add other fields if needed
        ]);

        // 🔹 OPTIONAL: create more users using factory
        User::factory(5)->create();
    }
}