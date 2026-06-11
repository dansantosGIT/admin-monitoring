<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\IncidentController;

// Welcome Landing Page — redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Monitoring Dashboard
Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

// System Health Check
Route::get('/health', [ReportController::class, 'systemHealth'])->name('health');

// Reports Resource Routes
Route::resource('reports', ReportController::class);

// Report Export Routes
Route::get('/reports/{report}/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
Route::get('/reports/{report}/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export-pdf');

// Legacy Reports URL — redirect to the new reports index
Route::get('/admin/reports', function () {
    return redirect()->route('reports.index');
});

// Registration route (simple preview)
Route::get('/register', function () {
    return view('register');
})->name('register');

// Google OAuth stub for preview
Route::get('/auth/google', function () {
    // Placeholder: Implement OAuth redirect in real flow
    return redirect()->route('register');
})->name('auth.google');

// Simple login route stub for preview
Route::get('/login', function () {
    return view('login');
})->name('login');

// Resource routes for sidebar pages
Route::resource('employees', EmployeeController::class);
Route::resource('attendance', AttendanceController::class);
Route::resource('incidents', IncidentController::class);

// Login POST handler
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
})->name('login.submit');

// Password reset stub (preview)
Route::get('/password/reset', function () {
    return '<h2>Password reset is not yet implemented. Please contact admin.</h2>';
})->name('password.request');

