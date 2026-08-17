<?php

namespace Tests\Feature;

use App\Models\QmsOccurrence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Phase2CoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_users_have_expected_demo_access(): void
    {
        $this->seed();

        $this->assertTrue(Hash::check('Yahya@2026', User::where('email', 'yahya.alnaaimi@qms.test')->first()->password));
        $this->assertTrue(Hash::check('Mazin@2026', User::where('email', 'mazin.alfarsi@qms.test')->first()->password));
        $this->assertTrue(Hash::check('Dummy@2026', User::where('email', 'aisha.albalushi@qms.test')->first()->password));
        $this->assertTrue(Hash::check('Dummy@2026', User::where('email', 'omar.alharthy@qms.test')->first()->password));
    }

    public function test_login_and_dashboard_work(): void
    {
        $this->seed();

        $response = $this->post('/login', [
            'email' => 'admin@qms.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->get('/dashboard')->assertOk()->assertSee('Command dashboard');
    }

    public function test_authenticated_user_can_create_occurrence(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'yahya.alnaaimi@qms.test')->first());

        $this->post('/occurrences', [
            'type' => 'Ground safety',
            'location' => 'OQB Locations',
            'exact_location' => 'Ramp area',
            'reported_by' => 'Yahya Al Naaimi',
            'description' => 'A test occurrence with enough operational detail for screening.',
            'confidential' => 0,
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_occurrences', [
            'type' => 'Ground safety',
            'workflow_stage' => 'HSE Review',
        ]);
        $this->assertSame(2, QmsOccurrence::count());
    }
}
