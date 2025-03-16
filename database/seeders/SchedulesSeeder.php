<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class SchedulesSeeder extends Seeder
{
    public function run(): void
    {
        Schedule::insert([
            [
                'doctor_id' => 2,
                'day' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'doctor_id' => 2,
                'day' => 'Wednesday',
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
