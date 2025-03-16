<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        Payment::insert([
            [
                'appointment_id' => 1,
                'patient_id' => 3,
                'doctor_id' => 2,
                'amount' => 150000,
                'payment_method' => 'cash',
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'appointment_id' => 2,
                'patient_id' => 4,
                'doctor_id' => 2,
                'amount' => 200000,
                'payment_method' => 'transfer',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
