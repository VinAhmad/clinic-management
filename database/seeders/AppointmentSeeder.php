<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Appointment::create([
                'doctor_id' => rand(2, 4),
                'patient_id' => rand(5, 14),
                'appointment_date' => now()->addDays(rand(1, 10)),
                'status' => fake()->randomElement(['scheduled', 'completed', 'canceled']),
                'notes' => fake()->sentence,
                'fee' => fake()->randomFloat(2, 50000, 200000),
            ]);
        }
    }
}
