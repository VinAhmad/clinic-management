<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class SchedulesSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $doctorIds = [2, 3, 4]; // Based on your UserSeeder

        foreach ($doctorIds as $doctorId) {
            foreach ($days as $day) {
                Schedule::create([
                    'doctor_id' => $doctorId,
                    'day' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
