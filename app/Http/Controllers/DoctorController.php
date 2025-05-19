<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::where('role', 'doctor')->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['required', 'string'],
            'specialization' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'specialization' => $validated['specialization'],
            'role' => 'doctor',
        ]);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function show(User $doctor)
    {
        // Ensure we're only showing doctors
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $appointments = $doctor->doctorAppointments()
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();

        $schedules = $doctor->schedules;

        return view('doctors.show', compact('doctor', 'appointments', 'schedules'));
    }

    public function edit(User $doctor)
    {
        // Ensure we're only editing doctors
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, User $doctor)
    {
        // Ensure we're only updating doctors
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $doctor->id],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'address' => ['required', 'string'],
            'specialization' => ['required', 'string'],
        ]);

        // Optional password update
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $doctor->update($validated);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(User $doctor)
    {
        // Ensure we're only deleting doctors
        if ($doctor->role !== 'doctor') {
            abort(404);
        }

        // Check if the doctor has related records
        $hasAppointments = $doctor->doctorAppointments()->exists();
        $hasMedicalRecords = $doctor->doctorMedicalRecords()->exists();

        if ($hasAppointments || $hasMedicalRecords) {
            return back()->with('error', 'Cannot delete this doctor because they have related records.');
        }

        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}
