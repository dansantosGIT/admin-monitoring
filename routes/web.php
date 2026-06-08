<?php

use Illuminate\Support\Facades\Route;

// Welcome Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Reports Dashboard
Route::get('/reports', function () {
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
