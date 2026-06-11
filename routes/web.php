<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

// Welcome Landing Page
Route::get('/', function () {
    return view('welcome');
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

// Legacy Reports View (for backward compatibility)
Route::get('/admin/reports', function () {
    $reports = [
        [
            'name' => 'Maria Santos',
            'position' => 'HR Officer',
            'incident_type' => 'Late attendance',
            'date' => '2026-06-03',
        ],
        [
            'name' => 'James Rivera',
            'position' => 'Security Guard',
            'incident_type' => 'Unauthorized access attempt',
            'date' => '2026-06-05',
        ],
        [
            'name' => 'Angela Cruz',
            'position' => 'Office Assistant',
            'incident_type' => 'Equipment misuse',
            'date' => '2026-06-06',
        ],
    ];

    return view('reports', compact('reports'));
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

