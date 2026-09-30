<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_report_html_contains_only_the_masked_email(): void
    {
        $user = User::factory()->create([
            'email' => 'superadmin@sanjuan.gov.ph',
            'role' => 'super-admin',
        ]);

        $response = $this->actingAs($user)->get(route('reports.create'));

        $response->assertOk();
        $response->assertSee('su********@sanjuan.gov.ph');
        $response->assertDontSee('superadmin@sanjuan.gov.ph');
    }

    public function test_email_reveal_requires_authentication_and_returns_the_current_account_email(): void
    {
        $this->getJson(route('account.email.reveal'))->assertRedirect(route('login'));

        $user = User::factory()->create(['email' => 'superadmin@sanjuan.gov.ph']);

        $this->actingAs($user)
            ->getJson(route('account.email.reveal'))
            ->assertOk()
            ->assertJson(['email' => 'superadmin@sanjuan.gov.ph']);
    }
}
