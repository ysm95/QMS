<?php

namespace Tests\Feature;

use App\Models\QmsFormDefinition;
use App\Models\QmsWorkflowDefinition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase8CompetitivePlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_intelligence_dashboard_shows_cross_module_readiness_and_traceability(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/intelligence')
            ->assertOk()
            ->assertSee('System command intelligence')
            ->assertSee('Traceability graph')
            ->assertSee('QMS-2026-00435')
            ->assertSee('Generated CAPA')
            ->assertSee('Controlled AI is blocked');
    }

    public function test_platform_config_shows_versioned_forms_workflows_and_saved_views(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/platform')
            ->assertOk()
            ->assertSee('Forms, workflows, and saved views')
            ->assertSee('FORM-DOR-001')
            ->assertSee('WF-OCC-001')
            ->assertSee('Executive high-risk watch');

        $this->assertDatabaseHas('qms_form_definitions', [
            'code' => 'FORM-DOR-001',
            'status' => 'Published',
        ]);

        $this->assertDatabaseHas('qms_workflow_definitions', [
            'code' => 'WF-OCC-001',
            'status' => 'Published',
        ]);

        $this->assertSame(2, QmsFormDefinition::count());
        $this->assertSame(2, QmsWorkflowDefinition::count());
    }
}
