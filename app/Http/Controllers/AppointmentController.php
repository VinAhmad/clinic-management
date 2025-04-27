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
            $patients = User::where('role', 'patient')->get();

            return view('appointments.create', compact('doctors', 'patients'));
        } elseif ($user->role === 'patient') {
            $doctors = User::where('role', 'doctor')->get();

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
            return response()->json(['slots' => []]);
        }

        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        $slots = [];
        $currentTime = $startTime->copy();

        while ($currentTime < $endTime) {
            $slotStart = $currentTime->format('H:i');
            $slotEnd = $currentTime->addMinutes(30)->format('H:i');

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

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
            'fee' => 'required|numeric|min:0',
        ]);

        if ($user->role === 'admin') {
            $request->validate([
                'patient_id' => 'required|exists:users,id',
            ]);
            $patientId = $request->patient_id;
        } elseif ($user->role === 'doctor') {
            $request->validate([
                'patient_id' => 'required|exists:users,id',
            ]);
            $patientId = $request->patient_id;
        } else {
            $patientId = $user->id;
        }

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

        // Check if the slot is available
        $isBooked = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $appointmentDateTime->toDateString())
            ->whereTime('appointment_date', $appointmentDateTime->format('H:i'))
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
            'fee' => $validated['fee'],
            'status' => 'scheduled',
        ]);

        // Create payment record
        Payment::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => $patientId,
            'amount' => $validated['fee'],
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient', 'payment', 'medicalRecord']);

        return view('appointments.show', compact('appointment'));
    }


    public function edit(Appointment $appointment)
    {
        // Get the list of doctors (only doctors)
        $doctors = User::where('role', 'doctor')->get();

        // If the logged-in user is an admin or doctor, they can select a patient
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'doctor') {
            $patients = User::where('role', 'patient')->get(); // Get all patients
        } else {
            $patients = collect(); // No patients are available for other roles
        }

        // Pass the necessary data to the view
        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }


    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Only allow updates if the appointment is still scheduled
        if ($appointment->status !== 'scheduled') {
            return back()->withErrors(['status' => 'Cannot modify a completed or canceled appointment.']);
        }

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
            'fee' => 'required|numeric|min:0',
        ]);

        if ($user->role === 'admin') {
            $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'patient_id' => 'required|exists:users,id',
                'status' => 'required|in:scheduled,completed,canceled',
            ]);

            $validated['doctor_id'] = $request->doctor_id;
            $validated['patient_id'] = $request->patient_id;
            $validated['status'] = $request->status;
        } elseif ($user->role === 'doctor') {
            $request->validate([
                'status' => 'required|in:scheduled,completed,canceled',
            ]);

            $validated['status'] = $request->status;
        }

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);
        $validated['appointment_date'] = $appointmentDateTime;

        // Check if the slot is available (if date/time changed)
        if ($appointmentDateTime != $appointment->appointment_date) {
            $isBooked = Appointment::where('doctor_id', $appointment->doctor_id)
                ->where('id', '!=', $appointment->id)
                ->whereDate('appointment_date', $appointmentDateTime->toDateString())
                ->whereTime('appointment_date', $appointmentDateTime->format('H:i'))
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

        // Update payment amount if fee changed
        if ($appointment->payment && $validated['fee'] != $appointment->payment->amount) {
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
