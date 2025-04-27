<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            // Admin (hanya 1)
            [
                'name' => 'Admin',
                'email' => 'admin@clinic.com',
                'email_verified_at' => now(),
                'password' => bcrypt('Admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'gender' => 'male',
                'address' => 'Jl. Admin No.1, Jakarta',
                'specialization' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Doctor
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'dr.budi@clinic.com',
                'email_verified_at' => now(),
                'password' => bcrypt('Budi123'),
                'role' => 'doctor',
                'phone' => '081234567891',
                'gender' => 'male',
                'address' => 'Jl. Dokter No.10, Jakarta',
                'specialization' => 'Cardiology',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Siti Aminah',
                'email' => 'dr.siti@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '081234567895',
                'gender' => 'female',
                'address' => 'Jl. Dokter No.11, Jakarta',
                'specialization' => 'Dermatology',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Ahmad Fauzi',
                'email' => 'dr.ahmad@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '081234567896',
                'gender' => 'male',
                'address' => 'Jl. Dokter No.12, Jakarta',
                'specialization' => 'Neurology',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Patients
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('andi123'),
                'role' => 'patient',
                'phone' => '081234567892',
                'gender' => 'male',
                'address' => 'Jl. Pasien No.20, Jakarta',
                'specialization' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti.rahma@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => '081234567893',
                'gender' => 'female',
                'address' => 'Jl. Pasien No.21, Jakarta',
                'specialization' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Joko Susanto',
                'email' => 'joko.susanto@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => '081234567894',
                'gender' => 'male',
                'address' => 'Jl. Pasien No.22, Jakarta',
                'specialization' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
