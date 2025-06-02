<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    public function show(User $doctor)
    {
        // Ensure the user is actually a doctor
        if ($doctor->role !== 'doctor') {
            return redirect()->route('doctors.index')
                ->with('error', 'User not found or not a doctor.');
        }

        // Load appointments for this doctor
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Load schedules for this doctor
        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->orderBy('day')
            ->get();

        return view('doctors.show', compact('doctor', 'appointments', 'schedules'));
    }

    public function create()
    {
        // Only admin can create doctors
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to create doctors.');
        }

        return view('doctors.create');
    }

    public function store(Request $request)
    {
        // Only admin can create doctors
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to create doctors.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['role'] = 'doctor';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function edit(User $doctor)
    {
        // Only admin can edit doctors
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to edit doctors.');
        }

        // Ensure the user is actually a doctor
        if ($doctor->role !== 'doctor') {
            return redirect()->route('doctors.index')
                ->with('error', 'User not found or not a doctor.');
        }

        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, User $doctor)
    {
        // Only admin can update doctors
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to update doctors.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->id,
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:500',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $doctor->update($validated);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(User $doctor)
    {
        // Only admin can delete doctors
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to delete doctors.');
        }

        // Ensure the user is actually a doctor
        if ($doctor->role !== 'doctor') {
            return redirect()->route('doctors.index')
                ->with('error', 'User not found or not a doctor.');
        }

        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}
