<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            MedicalRecord::create([
                'appointment_id' => $i,
                'patient_id' => rand(5, 14),
                'doctor_id' => rand(2, 4),
                'diagnosis' => fake()->sentence,
                'prescription' => fake()->sentence,
                'treatment_plan' => fake()->optional()->sentence,
                'next_appointment' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            ]);
        }
    }
}
