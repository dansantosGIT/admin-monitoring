<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $employees = Employee::count() ? Employee::all() : collect([
            Employee::create([
                'employee_number' => 'EMP-2026-001',
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'employment_type' => 'Permanent',
                'department' => 'Operations',
                'position' => 'Disaster Response Officer',
                'status' => 'Active',
                'date_hired' => now()->subYears(4),
            ]),
            Employee::create([
                'employee_number' => 'EMP-2026-002',
                'first_name' => 'Maria',
                'middle_name' => 'Lopez',
                'last_name' => 'Santos',
                'employment_type' => 'JO',
                'department' => 'Logistics',
                'position' => 'Supply Custodian',
                'status' => 'Active',
                'date_hired' => now()->subYears(2),
            ]),
            Employee::create([
                'employee_number' => 'EMP-2026-003',
                'first_name' => 'Pedro',
                'middle_name' => 'Reyes',
                'last_name' => 'Cruz',
                'employment_type' => 'Permanent',
                'department' => 'Administration',
                'position' => 'Administrative Assistant',
                'status' => 'Active',
                'date_hired' => now()->subYears(6),
            ]),
        ]);

        $records = [
            ['employee' => $employees[0], 'department' => 'Operations', 'type' => 'vehicle_incident', 'item' => 'Patrol Vehicle 4x4', 'serial' => 'DRRM-VH-001', 'location' => 'Barangay San Isidro', 'severity' => 'major', 'status' => 'under_investigation', 'cost' => 18500, 'action' => 'Vehicle taken to partner shop for inspection.', 'remarks' => 'Minor body dents and damaged side mirror.'],
            ['employee' => $employees[1], 'department' => 'Logistics', 'type' => 'equipment_damage', 'item' => 'Portable Radio', 'serial' => 'RAD-88312', 'location' => 'City DRRM Warehouse', 'severity' => 'minor', 'status' => 'resolved', 'cost' => 2400, 'action' => 'Battery replaced and unit tested.', 'remarks' => 'Reported immediately after field deployment.'],
            ['employee' => $employees[2], 'department' => 'Administration', 'type' => 'equipment_loss', 'item' => 'Laminator Machine', 'serial' => null, 'location' => 'Admin Office', 'severity' => 'critical', 'status' => 'pending', 'cost' => 45000, 'action' => null, 'remarks' => 'Unit missing after inventory count.'],
            ['employee' => $employees[0], 'department' => 'Operations', 'type' => 'other', 'item' => 'First Aid Kit', 'serial' => 'FAK-19', 'location' => 'Evacuation Center A', 'severity' => 'minor', 'status' => 'closed', 'cost' => 800, 'action' => 'Replaced missing supplies and updated logbook.', 'remarks' => 'Closed after inventory reconciliation.'],
            ['employee' => $employees[1], 'department' => 'Logistics', 'type' => 'vehicle_incident', 'item' => 'Motorcycle Unit', 'serial' => 'MV-2210', 'location' => 'Route to Barangay 6', 'severity' => 'major', 'status' => 'resolved', 'cost' => 9600, 'action' => 'Insurance claim filed and repairs completed.', 'remarks' => 'No personnel injuries.'],
            ['employee' => $employees[2], 'department' => 'Administration', 'type' => 'equipment_damage', 'item' => 'Desktop Monitor', 'serial' => 'MON-145', 'location' => 'Records Section', 'severity' => 'minor', 'status' => 'pending', 'cost' => 5200, 'action' => null, 'remarks' => 'Screen cracked during office relocation.'],
        ];

        foreach ($records as $index => $record) {
            Report::create([
                'incident_code' => sprintf('MIR-%d-%04d', now()->year, $index + 1),
                'employee_id' => $record['employee']->id,
                'department' => $record['department'],
                'incident_type' => $record['type'],
                'item_name' => $record['item'],
                'property_serial_no' => $record['serial'],
                'description' => ucfirst(str_replace('_', ' ', $record['type'])) . ' reported during routine operations.',
                'location' => $record['location'],
                'date_of_incident' => now()->subDays($index + 1),
                'severity' => $record['severity'],
                'estimated_cost' => $record['cost'],
                'status' => $record['status'],
                'action_taken' => $record['action'],
                'reported_by' => $user->id,
                'remarks' => $record['remarks'],
            ]);
        }
    }
}
