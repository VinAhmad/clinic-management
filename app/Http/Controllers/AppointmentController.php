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

        // Common data for all user types
        $data = [];

        if ($user->role === 'admin') {
            $data['doctors'] = User::where('role', 'doctor')->get();
            $data['patients'] = User::where('role', 'patient')->get();
        } elseif ($user->role === 'doctor') {
            $data['patients'] = User::where('role', 'patient')->get();
        } else { // Patient
            $data['doctors'] = User::where('role', 'doctor')->get();
        }

        // Load all schedules for use in the view
        $schedulesData = [];
        $schedules = Schedule::all();

        foreach ($schedules as $schedule) {
            $doctorId = $schedule->doctor_id;
            $day = $schedule->day;

            if (!isset($schedulesData[$doctorId])) {
                $schedulesData[$doctorId] = [];
            }

            $schedulesData[$doctorId][$day] = [
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'is_available' => $schedule->is_available
            ];
        }

        $data['schedulesData'] = $schedulesData;

        return view('appointments.create', $data);
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $doctor_id = $request->doctor_id;
        $date = $request->date;
        $day = strtolower(date('l', strtotime($date)));

        // Get doctor's schedule for the selected day
        $schedule = Schedule::where('doctor_id', $doctor_id)
            ->where('day', $day)
            ->where('is_available', true)
            ->first();

        if (!$schedule) {
            return response()->json(['slots' => []]);
        }

        // Get start and end times
        $start = new \DateTime($schedule->start_time);
        $end = new \DateTime($schedule->end_time);

        // Set interval for appointments (e.g., 30 minutes)
        $interval = new \DateInterval('PT30M');

        // Generate time slots
        $slots = [];
        $current = clone $start;

        while ($current < $end) {
            $slotStart = clone $current;
            $current->add($interval);

            // Check if this slot is already booked
            $isBooked = Appointment::where('doctor_id', $doctor_id)
                ->whereDate('appointment_date', $date)
                ->whereTime('appointment_date', '=', $slotStart->format('H:i:s'))
                ->where('status', '!=', 'cancelled')
                ->exists();

            if (!$isBooked) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'formatted' => $slotStart->format('h:i A'),
                ];
            }
        }

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id,role,doctor',
            'patient_id' => Auth::user()->role === 'patient' ? 'nullable' : 'required|exists:users,id,role,patient',
            'appointment_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'fee' => 'numeric|min:0',
        ]);

        // If user is a patient, set patient_id to their ID
        if (Auth::user()->role === 'patient') {
            $validated['patient_id'] = Auth::id();
        }

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Check if the appointment is within doctor's schedule
        $dayOfWeek = strtolower($appointmentDateTime->format('l'));
        $time = $appointmentDateTime->format('H:i');

        $doctorSchedule = Schedule::where('doctor_id', $validated['doctor_id'])
            ->where('day', $dayOfWeek)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->where('is_available', true)
            ->first();

        if (!$doctorSchedule) {
            return back()->withInput()->withErrors([
                'appointment_time' => 'Selected time is not within doctor\'s working hours. Please choose another time.'
            ]);
        }

        // Check for existing appointments in the same time slot
        $existingAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $appointmentDateTime->toDateString())
            ->whereTime('appointment_date', $appointmentDateTime->toTimeString())
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($existingAppointment) {
            return back()->withInput()->withErrors([
                'appointment_time' => 'This time slot is already booked. Please choose another time.'
            ]);
        }

        // For patients, get the doctor's standard fee
        if (Auth::user()->role === 'patient') {
            // You could set a default fee or get it from doctor's profile
            $validated['fee'] = 100.00; // Default fee
        }

        // Create appointment
        $appointment = new Appointment();
        $appointment->doctor_id = $validated['doctor_id'];
        $appointment->patient_id = $validated['patient_id'];
        $appointment->appointment_date = $appointmentDateTime;
        $appointment->status = 'scheduled';
        $appointment->notes = $validated['notes'] ?? null;
        $appointment->fee = $validated['fee'];
        $appointment->save();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully!');
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
