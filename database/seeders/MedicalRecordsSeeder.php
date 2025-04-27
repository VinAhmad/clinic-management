<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;

class MedicalRecordsSeeder extends Seeder
{
    public function run(): void
    {
        MedicalRecord::insert([
            [
                'appointment_id' => 1,
                'patient_id' => 5,  // Andi Wijaya (patient)
                'doctor_id' => 2,   // Dr. Budi Santoso (doctor)
                'diagnosis' => 'Infeksi saluran pernapasan ringan',
                'prescription' => 'Paracetamol, antibiotik',
                'treatment_plan' => 'Kontrol ulang dalam 1 minggu',
                'next_appointment' => now()->addDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'appointment_id' => 2,
                'patient_id' => 6,  // Siti Rahma (patient)
                'doctor_id' => 3,   // Dr. Siti Aminah (doctor)
                'diagnosis' => 'Diabetes tipe 2',
                'prescription' => 'Metformin, diet rendah gula',
                'treatment_plan' => 'Kontrol setiap 2 minggu',
                'next_appointment' => now()->addDays(14),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
