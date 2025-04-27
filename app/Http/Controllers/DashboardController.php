<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check if user has already seen the transition page in this session
        if ($request->session()->has('dashboard_visited')) {
            return $this->redirectToDashboard();
        }

        // Mark that the user has seen the transition page
        $request->session()->put('dashboard_visited', true);

        // Show the transition dashboard page
        return view('dashboard');
    }

    public function redirectToDashboard()
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

        $todayDate = today();
        $todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $todayDate)
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->get();

        $upcomingAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->where('appointment_date', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->take(10)  // Show more upcoming appointments
            ->get();

        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');

        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->count();

        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();

        // Explicitly query the day of week using lowercase
        $today = strtolower(now()->format('l')); // This gives us 'monday', 'tuesday', etc.

        $todaySchedule = Schedule::where('doctor_id', $doctor->id)
            ->where('day', $today)
            ->first();

        // Debug the schedule query
        if (!$todaySchedule) {
            // Try to find any schedule for this doctor
            $anySchedule = Schedule::where('doctor_id', $doctor->id)->first();
            // If there's any schedule but not for today, it's likely a day mismatch
            if ($anySchedule) {
                // Create a default schedule for today to prevent null values
                $todaySchedule = new Schedule();
                $todaySchedule->start_time = '09:00';
                $todaySchedule->end_time = '17:00';
            }
        }

        // Get all schedules for this doctor
        $weeklySchedules = Schedule::where('doctor_id', $doctor->id)
            ->orderByRaw("CASE
                WHEN day = 'monday' THEN 1
                WHEN day = 'tuesday' THEN 2
                WHEN day = 'wednesday' THEN 3
                WHEN day = 'thursday' THEN 4
                WHEN day = 'friday' THEN 5
                WHEN day = 'saturday' THEN 6
                WHEN day = 'sunday' THEN 7
                ELSE 8 END")
            ->get();

        return view('dashboard.doctor', compact(
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'completedAppointments',
            'totalAppointments',
            'todaySchedule',
            'weeklySchedules',
            'todayDate'
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
