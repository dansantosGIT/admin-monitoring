<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_cannot_open_vehicle_management_routes(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($member)->get(route('reports.vehicle-monitoring.create'))->assertForbidden();
        $this->actingAs($member)->get(route('reports.vehicle-monitoring.edit', $vehicle))->assertForbidden();
        $this->actingAs($member)->delete(route('reports.vehicle-monitoring.destroy', $vehicle))->assertForbidden();
    }

    public function test_admins_can_open_the_create_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('reports.vehicle-monitoring.create'))->assertOk();
    }

    public function test_store_requires_valid_vehicle_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('reports.vehicle-monitoring.create'))
            ->post(route('reports.vehicle-monitoring.store'), [
                'vehicle_type' => 'Ambulance',
                'plate_number' => 'BAD 1234',
                'status' => 'active',
                'team' => 'Alpha',
                'drive_link' => 'not-a-url',
                'year' => 1800,
            ])
            ->assertSessionHasErrors(['call_sign', 'drive_link', 'year']);
    }

    public function test_plate_check_allows_the_current_vehicle_plate_on_edit(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $vehicle = Vehicle::factory()->create(['plate_number' => 'SNI 2701']);

        $this->actingAs($admin)
            ->getJson(route('reports.vehicle-monitoring.check-plate', [
                'plate_number' => 'sni 2701',
                'vehicle_id' => $vehicle->id,
            ]))
            ->assertOk()
            ->assertJson(['available' => true]);
    }
}
