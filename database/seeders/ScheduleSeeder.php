<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        for ($doctor_id = 2; $doctor_id <= 4; $doctor_id++) {
            foreach ($days as $day) {
                Schedule::create([
                    'doctor_id' => $doctor_id,
                    'day' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '14:00:00',
                    'is_available' => true,
                ]);
            }
        }
    }
}
