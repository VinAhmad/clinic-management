<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'gender',
        'address',
        'specialization',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopePatients($query)
    {
        return $query->where('role', 'patient');
    }

    public function scopeDoctors($query)
    {
        return $query->where('role', 'doctor');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }

    public function doctorAppoinments() {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function patientAppoinments() {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function doctorMedicalRecords() {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    public function patientMedicalRecords() {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'patient_id');
    }
}
