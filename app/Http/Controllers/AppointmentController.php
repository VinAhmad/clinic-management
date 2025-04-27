<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Appointment::query();

        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }

        $appointments = $query->with(['doctor', 'patient'])
            ->orderBy('appointment_date')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $doctors = User::where('role', 'doctor')->get();
            // Add availability status to each doctor
            $doctors = $doctors->map(function($doctor) {
                $doctor->hasSchedule = Schedule::where('doctor_id', $doctor->id)
                    ->where('is_available', true)
                    ->exists();
                return $doctor;
            });
            
            $patients = User::where('role', 'patient')->get();

            return view('appointments.create', compact('doctors', 'patients'));
        } elseif ($user->role === 'patient') {
            $doctors = User::where('role', 'doctor')->get();
            // Add availability status to each doctor
            $doctors = $doctors->map(function($doctor) {
                $doctor->hasSchedule = Schedule::where('doctor_id', $doctor->id)
                    ->where('is_available', true)
                    ->exists();
                return $doctor;
            });

            return view('appointments.create', compact('doctors'));
        } else {
            $patients = User::where('role', 'patient')->get();

            return view('appointments.create', compact('patients'));
        }
    }

    public function getAvailableSlots(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date;

        $day = strtolower(Carbon::parse($date)->format('l'));

        $schedule = Schedule::where('doctor_id', $doctorId)
            ->where('day', $day)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            $hasAnySchedule = Schedule::where('doctor_id', $doctorId)
                ->where('is_available', true)
                ->exists();
                
            return response()->json([
                'slots' => [],
                'hasSchedule' => $hasAnySchedule,
                'message' => 'No schedule available for this day'
            ]);
        }

        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        $slots = [];
        $currentTime = $startTime->copy();

        while ($currentTime < $endTime) {
            $slotStart = $currentTime->format('H:i');
            $currentTime->addMinutes(30);
            $slotEnd = $currentTime->format('H:i');

            // Check if slot is already booked
            $isBooked = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $date)
                ->whereTime('appointment_date', '>=', $slotStart)
                ->whereTime('appointment_date', '<', $slotEnd)
                ->where('status', 'scheduled')
                ->exists();

            if (!$isBooked) {
                $slots[] = [
                    'start' => $slotStart,
                    'end' => $slotEnd,
                    'formatted' => "$slotStart - $slotEnd",
                ];
            }
        }

        return response()->json([
            'slots' => $slots,
            'hasSchedule' => true
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        if ($user->role === 'admin' || $user->role === 'doctor') {
            $request->validate([
                'patient_id' => 'required|exists:users,id',
                'fee' => 'required|numeric|min:0',
            ]);
            $patientId = $request->patient_id;
            $fee = $request->fee;
        } else {
            $patientId = $user->id;
            // Set default fee or get from doctor's settings
            $fee = $this->getDoctorDefaultFee($validated['doctor_id']);
        }

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Check if the slot is available
        $isBooked = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $appointmentDateTime->toDateString())
            ->whereTime('appointment_date', '>=', $appointmentDateTime->format('H:i'))
            ->whereTime('appointment_date', '<', $appointmentDateTime->addMinutes(30)->format('H:i'))
            ->where('status', 'scheduled')
            ->exists();

        if ($isBooked) {
            return back()
                ->withErrors(['appointment_time' => 'This time slot is already booked.'])
                ->withInput();
        }

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $appointmentDateTime,
            'notes' => $validated['notes'],
            'fee' => $fee,
            'status' => 'scheduled',
        ]);

        // Create payment record
        Payment::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => $patientId,
            'amount' => $fee,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    // New helper method to get doctor's default fee
    private function getDoctorDefaultFee($doctorId)
    {
        // This could be fetched from doctor's profile or system settings
        // For now, we'll use a default value of 50
        return 50.00;
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient', 'payment', 'medicalRecord']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $doctors = User::where('role', 'doctor')->get();
            $patients = User::where('role', 'patient')->get();

            return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
        } else {
            return view('appointments.edit', compact('appointment'));
        }
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Only allow updates if the appointment is still scheduled
        if ($appointment->status !== 'scheduled' && $request->status !== 'canceled') {
            return back()->withErrors(['status' => 'Cannot modify a completed or canceled appointment.']);
        }

        // For doctors, only allow status update, not date/time/fee changes
        if ($user->role === 'doctor') {
            $validated = $request->validate([
                'status' => 'required|in:scheduled,completed,canceled',
                'notes' => 'nullable|string',
            ]);
            
            // Use original appointment date and fee
            $validated['appointment_date'] = $appointment->appointment_date;
            $validated['fee'] = $appointment->fee;
            
            $appointment->update($validated);
            
            return redirect()->route('appointments.show', $appointment)
                ->with('success', 'Appointment status updated successfully.');
        }
        
        // For admin and patients, proceed with full validation
        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Add fee validation depending on role
        if ($user->role === 'admin') {
            $request->validate([
                'fee' => 'required|numeric|min:0',
                'doctor_id' => 'required|exists:users,id',
                'patient_id' => 'required|exists:users,id',
                'status' => 'required|in:scheduled,completed,canceled',
            ]);

            $validated['doctor_id'] = $request->doctor_id;
            $validated['patient_id'] = $request->patient_id;
            $validated['fee'] = $request->fee;
            $validated['status'] = $request->status;
        } else {
            // Patients can't change fee or status
            $validated['fee'] = $appointment->fee;
        }

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        $validated['appointment_date'] = $appointmentDateTime;

        // Check if the slot is available (if date/time changed)
        if ($appointmentDateTime != $appointment->appointment_date) {
            // For patient and admin, validate date is in the future
            if ($user->role !== 'doctor') {
                if ($appointmentDateTime <= now()) {
                    return back()
                        ->withErrors(['appointment_date' => 'Appointment date must be in the future.'])
                        ->withInput();
                }
            }
            
            // Verify the doctor has a schedule for this day
            $dayOfWeek = strtolower($appointmentDateTime->format('l'));
            $hasSchedule = Schedule::where('doctor_id', $appointment->doctor_id)
                ->where('day', $dayOfWeek)
                ->where('is_available', true)
                ->exists();
                
            if (!$hasSchedule) {
                return back()
                    ->withErrors(['appointment_date' => 'Doctor has no schedule for this day.'])
                    ->withInput();
            }
            
            // Check for booking conflicts
            $isBooked = Appointment::where('doctor_id', $appointment->doctor_id)
                ->where('id', '!=', $appointment->id)
                ->whereDate('appointment_date', $appointmentDateTime->toDateString())
                ->whereTime('appointment_date', '>=', $appointmentDateTime->format('H:i'))
                ->whereTime('appointment_date', '<', $appointmentDateTime->addMinutes(30)->format('H:i'))
                ->where('status', 'scheduled')
                ->exists();

            if ($isBooked) {
                return back()
                    ->withErrors(['appointment_time' => 'This time slot is already booked.'])
                    ->withInput();
            }
        }

        // Update appointment
        $appointment->update($validated);

        // Update payment amount if fee changed and admin role
        if ($user->role === 'admin' && $appointment->payment && $validated['fee'] != $appointment->payment->amount) {
            $appointment->payment->update(['amount' => $validated['fee']]);
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        // Set status to canceled instead of deleting
        $appointment->update(['status' => 'canceled']);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment canceled successfully.');
    }
}
