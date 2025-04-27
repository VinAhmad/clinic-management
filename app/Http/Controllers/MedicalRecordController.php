<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = MedicalRecord::query();

        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->id);
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }

        $medicalRecords = $query->with(['doctor', 'patient', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('medical-records.index', compact('medicalRecords'));
    }

    public function create(Appointment $appointment = null)
    {
        if (!$appointment) {
            $user = Auth::user();

            // Only doctors and admins can create medical records
            if ($user->role === 'patient') {
                return redirect()->route('dashboard')
                    ->with('error', 'You are not authorized to create medical records.');
            }

            $appointments = Appointment::where('status', 'scheduled');

            if ($user->role === 'doctor') {
                $appointments->where('doctor_id', $user->id);
            }

            $appointments = $appointments->with('patient')
                ->orderBy('appointment_date')
                ->get();

            return view('medical-records.create', compact('appointments'));
        }

        return view('medical-records.create-from-appointment', compact('appointment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
            'treatment_plan' => 'nullable|string',
            'next_appointment' => 'nullable|date|after:today',
        ]);

        // Get appointment details
        $appointment = Appointment::findOrFail($validated['appointment_id']);

        // Create medical record
        MedicalRecord::create([
            'appointment_id' => $validated['appointment_id'],
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'diagnosis' => $validated['diagnosis'],
            'prescription' => $validated['prescription'],
            'treatment_plan' => $validated['treatment_plan'],
            'next_appointment' => $validated['next_appointment'],
        ]);

        // Update appointment status to completed
        $appointment->update(['status' => 'completed']);

        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record created successfully.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $user = Auth::user();

        // Check if user has permission to view this record
        if ($user->role === 'doctor' && $medicalRecord->doctor_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this medical record.');
        }

        if ($user->role === 'patient' && $medicalRecord->patient_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this medical record.');
        }

        $medicalRecord->load(['doctor', 'patient', 'appointment']);

        return view('medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $user = Auth::user();

        // Only the doctor who created the record or admin can edit
        if ($user->role === 'doctor' && $medicalRecord->doctor_id !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to edit this medical record.');
        }

        if ($user->role === 'patient') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to edit medical records.');
        }

        $medicalRecord->load(['doctor', 'patient', 'appointment']);

        return view('medical-records.edit', compact('medicalRecord'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
            'treatment_plan' => 'nullable|string',
            'next_appointment' => 'nullable|date|after:today',
        ]);

        $medicalRecord->update($validated);

        return redirect()->route('medical-records.show', $medicalRecord)
            ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $user = Auth::user();

        // Only admin can delete medical records
        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to delete medical records.');
        }

        $medicalRecord->delete();

        return redirect()->route('medical-records.index')
            ->with('success', 'Medical record deleted successfully.');
    }

    public function patientHistory($patientId)
    {
        $user = Auth::user();

        // Only admins and doctors can view patient history
        if ($user->role === 'patient' && $user->id != $patientId) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to view this patient\'s history.');
        }

        $medicalRecords = MedicalRecord::where('patient_id', $patientId)
            ->with(['doctor', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('medical-records.patient-history', compact('medicalRecords', 'patientId'));
    }
}
