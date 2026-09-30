<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportStaffNameTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_name_is_required_when_creating_a_report(): void
    {
        $user = User::factory()->create();
        $employee = Employee::create([
            'first_name' => 'Maria',
            'last_name' => 'Santos',
        ]);

        $this->actingAs($user)
            ->from(route('reports.create'))
            ->post(route('reports.store'), $this->reportPayload($employee->id))
            ->assertSessionHasErrors('reported_by_name');
    }

    public function test_staff_name_is_trimmed_and_saved(): void
    {
        $user = User::factory()->create();
        $employee = Employee::create([
            'first_name' => 'Maria',
            'last_name' => 'Santos',
        ]);

        $this->actingAs($user)
            ->post(route('reports.store'), $this->reportPayload($employee->id, '  Juan Dela Cruz  '))
            ->assertRedirect();

        $this->assertDatabaseHas('incident_reports', [
            'reported_by_name' => 'Juan Dela Cruz',
            'reported_by' => $user->id,
        ]);
    }

    public function test_legacy_report_displays_not_recorded_without_a_staff_name(): void
    {
        $user = User::factory()->create();
        $report = Report::create([
            'incident_code' => 'MIR-2026-9999',
            'report_number' => 'MIR-2026-9999',
            'employee_id' => null,
            'submitted_by' => $user->id,
            'department' => 'CEDOC',
            'incident_type' => 'equipment_damage',
            'item_name' => 'Radio',
            'description' => 'Legacy report.',
            'location' => 'San Juan City Hall',
            'date_of_incident' => '2026-09-30',
            'incident_date' => '2026-09-30',
            'severity' => 'minor',
            'status' => 'pending',
            'reported_by' => $user->id,
            'reported_by_name' => null,
        ]);

        $this->actingAs($user)
            ->get(route('reports.show', $report))
            ->assertOk()
            ->assertSee('Not recorded');
    }

    private function reportPayload(int $employeeId, ?string $staffName = null): array
    {
        return [
            'reported_by_name' => $staffName,
            'employee_id' => $employeeId,
            'department' => 'CEDOC',
            'incident_type' => 'equipment_damage',
            'item_name' => 'Radio',
            'description' => 'Equipment was damaged during response.',
            'location' => 'San Juan City Hall',
            'date_of_incident' => '2026-09-30',
            'severity' => 'minor',
            'status' => 'pending',
        ];
    }
}
