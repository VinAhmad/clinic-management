<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentsSeeder extends Seeder
{
    public function run(): void
    {
        Appointment::insert([
            [
                'patient_id' => 5, // Andi Wijaya (patient)
                'doctor_id' => 2,  // Dr. Budi Santoso (doctor)
                'appointment_date' => now()->addDays(3),
                'status' => 'scheduled',
                'notes' => 'Checkup pertama',
                'fee' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => 6, // Siti Rahma (patient)
                'doctor_id' => 3,  // Dr. Siti Aminah (doctor)
                'appointment_date' => now()->addDays(7),
                'status' => 'scheduled',
                'notes' => 'Kontrol setelah operasi',
                'fee' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
