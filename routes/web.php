<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('home');

// Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/redirect', [DashboardController::class, 'redirectToDashboard'])->name('dashboard.redirect');
});

// User profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Appointment routes
Route::middleware('auth')->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::get('/appointments/slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.slots');
});

// Schedule routes
Route::middleware('auth')->group(function () {
    Route::resource('schedules', ScheduleController::class);
    Route::get('/doctors/{doctor}/schedules', [ScheduleController::class, 'showDoctorSchedules'])->name('schedules.doctor');
});

// Medical Record routes
Route::middleware('auth')->group(function () {
    Route::resource('medical-records', MedicalRecordController::class);
    Route::get('/patient/{patientId}/medical-history', [MedicalRecordController::class, 'patientHistory'])->name('medical-records.history');
});

// Payment routes
Route::middleware('auth')->group(function () {
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{payment}/process', [PaymentController::class, 'processPayment'])->name('payments.process');
    Route::get('/payments/{payment}/view-invoice', [App\Http\Controllers\PaymentController::class, 'viewInvoice'])->name('payments.view-invoice');
    Route::get('/payment-reports', [PaymentController::class, 'reports'])->name('payments.reports');
});

require __DIR__.'/auth.php';
