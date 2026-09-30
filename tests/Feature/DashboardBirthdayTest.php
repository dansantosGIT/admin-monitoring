<?php

namespace Tests\Feature;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardBirthdayTest extends TestCase
{
    use RefreshDatabase;

    public function test_birthdays_today_returns_active_employees_and_excludes_inactive_employees(): void
    {
        $celebrant = Employee::create([
            'first_name' => 'Ana',
            'last_name' => 'Santos',
            'birthdate' => today()->subYears(30),
            'status' => 'Active',
        ]);
        Employee::create([
            'first_name' => 'Inactive',
            'last_name' => 'Staff',
            'birthdate' => today()->subYears(40),
            'status' => 'Inactive',
        ]);
        Employee::create([
            'first_name' => 'Tomorrow',
            'last_name' => 'Staff',
            'birthdate' => today()->subYears(25)->addDay(),
            'status' => 'Active',
        ]);

        $this->assertTrue(Employee::birthdaysToday()->pluck('id')->contains($celebrant->id));
        $this->assertCount(1, Employee::birthdaysToday()->get());
    }

    public function test_february_29_birthdays_are_shown_on_february_28_in_non_leap_years(): void
    {
        $celebrant = Employee::create([
            'first_name' => 'Leap',
            'last_name' => 'Day',
            'birthdate' => '2000-02-29',
            'status' => 'Active',
        ]);

        $this->assertTrue(Employee::birthdaysToday(Carbon::create(2025, 2, 28))->pluck('id')->contains($celebrant->id));
    }
}
