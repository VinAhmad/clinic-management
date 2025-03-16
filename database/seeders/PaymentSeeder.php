<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Payment::create([
                'appointment_id' => $i,
                'patient_id' => rand(5, 14),
                'amount' => fake()->randomFloat(2, 50000, 200000),
                'payment_date' => now(),
                'status' => fake()->randomElement(['paid', 'pending']),
            ]);
        }
    }
}
