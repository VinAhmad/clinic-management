<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'gender' => 'male',
            'address' => 'Admin Office',
        ]);

        // Dokter
        User::create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '081298765432',
            'gender' => 'male',
            'address' => 'Clinic 1',
            'specialization' => 'Cardiology',
        ]);

        User::create([
            'name' => 'Dr. Jane Smith',
            'email' => 'doctor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '081256789012',
            'gender' => 'female',
            'address' => 'Clinic 2',
            'specialization' => 'Dermatology',
        ]);

        // Generate 10 Pasien
        User::factory(10)->create();
    }
}
