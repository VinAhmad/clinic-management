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
                'name' => 'Admin Klinik',
                'email' => 'admin@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'gender' => 'male',
                'address' => 'Jl. Admin No.1, Jakarta',
                'specialization' => null, // Admin tidak punya spesialisasi
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Doctor (1 dokter)
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'dr.budi@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '081234567891',
                'gender' => 'male',
                'address' => 'Jl. Dokter No.10, Jakarta',
                'specialization' => 'Cardiology', // Spesialisasi dokter
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Patients (3 pasien manual)
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
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
