<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase16AdminControlCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_center_exposes_real_enterprise_workspaces(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Administration Control Center')
            ->assertSee('Identity &amp; Access', false)
            ->assertSee('Platform Studios')
            ->assertSee('Report designer')
            ->assertSee('Notification Designer')
            ->assertSee('Data Management')
            ->assertSee('Operations')
            ->assertSee('Controlled AI')
            ->assertSee('Governance')
            ->assertSee('CFG-BASELINE-001')
            ->assertSee('MON-AI')
            ->assertSee('AI remains unavailable until a paid secured entity-trained provider is approved.');
    }

    public function test_admin_center_user_search_matches_text_role_email_and_job(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/admin?search=Mazin')
            ->assertOk()
            ->assertSee('Mazin Al Farsi')
            ->assertDontSee('Omar Al Harthy');

        $this->get('/admin?search=Safety Admin')
            ->assertOk()
            ->assertSee('Yahya Al Naaimi')
            ->assertDontSee('Mazin Al Farsi');
    }
}
