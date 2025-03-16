<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'diagnosis',
        'prescription',
        'treatment_plan',
        'next_appointment'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id')->where('role', 'patient');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->where('role', 'doctor');
    }
}
