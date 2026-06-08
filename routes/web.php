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

