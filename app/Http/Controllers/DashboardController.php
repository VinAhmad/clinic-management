<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'doctor') {
            return $this->doctorDashboard();
        } else {
            return $this->patientDashboard();
        }
    }

    private function adminDashboard()
    {
        $totalDoctors = User::where('role', 'doctor')->count();
        $totalPatients = User::where('role', 'patient')->count();
        $totalAppointments = Appointment::count();
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        $recentAppointments = Appointment::with(['doctor', 'patient'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $upcomingAppointments = Appointment::with(['doctor', 'patient'])
            ->where('appointment_date', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalDoctors',
            'totalPatients',
            'totalAppointments',
            'totalRevenue',
            'recentAppointments',
            'upcomingAppointments'
        ));
    }

    private function doctorDashboard()
    {
        $doctor = Auth::user();

        $todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->get();

        $upcomingAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('appointment_date', '>', today())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');

        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();

        $todaySchedule = Schedule::where('doctor_id', $doctor->id)
            ->where('day', strtolower(now()->format('l')))
            ->first();

        return view('dashboard.doctor', compact(
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'totalAppointments',
            'todaySchedule'
        ));
    }

    private function patientDashboard()
    {
        $patient = Auth::user();

        $upcomingAppointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->where('appointment_date', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->get();

        $pastAppointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->where(function($query) {
                $query->where('appointment_date', '<', now())
                    ->orWhere('status', 'completed');
            })
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();

        $pendingPayments = Payment::with('appointment')
            ->where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->get();

        return view('dashboard.patient', compact(
            'upcomingAppointments',
            'pastAppointments',
            'pendingPayments'
        ));
    }
}
