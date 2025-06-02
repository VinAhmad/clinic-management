<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $doctors = User::where('role', 'doctor')->get();
            return view('schedules.admin-index', compact('doctors'));
        } else {
            $schedules = Schedule::where('doctor_id', $user->id)->get();
            return view('schedules.doctor-index', compact('schedules'));
        }
    }

    public function showDoctorSchedules(User $doctor)
    {
        // Ensure the user is actually a doctor
        if ($doctor->role !== 'doctor') {
            return redirect()->route('doctors.index')
                ->with('error', 'User not found or not a doctor.');
        }

        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->orderBy('day')
            ->get();

        return view('schedules.show', compact('doctor', 'schedules'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $doctors = User::where('role', 'doctor')->get();
            return view('schedules.create', compact('doctors'));
        } else {
            return view('schedules.create');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        // Check for overlapping schedules
        $existingSchedule = Schedule::where('doctor_id', $validated['doctor_id'])
            ->where('day', $validated['day'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->first();

        if ($existingSchedule) {
            return back()
                ->withErrors(['overlap' => 'This schedule overlaps with an existing schedule.'])
                ->withInput();
        }

        Schedule::create($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $doctors = User::where('role', 'doctor')->get();
            return view('schedules.edit', compact('schedule', 'doctors'));
        } else {
            return view('schedules.edit', compact('schedule'));
        }
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'boolean',
        ]);

        // Check for overlapping schedules (excluding this schedule)
        $existingSchedule = Schedule::where('doctor_id', $validated['doctor_id'])
            ->where('day', $validated['day'])
            ->where('id', '!=', $schedule->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->first();

        if ($existingSchedule) {
            return back()
                ->withErrors(['overlap' => 'This schedule overlaps with an existing schedule.'])
                ->withInput();
        }

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
