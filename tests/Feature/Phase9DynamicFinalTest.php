<?php

namespace Tests\Feature;

use App\Models\QmsFormDefinition;
use App\Models\QmsPublicReport;
use App\Models\QmsSavedView;
use App\Models\QmsWorkflowDefinition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase9DynamicFinalTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_uses_dynamic_workflow_and_enterprise_metrics(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Role-aware home')
            ->assertSee('HSE Review')
            ->assertSee('Public intake')
            ->assertSee('Training due')
            ->assertSee('Supplier watch')
            ->assertSee('SHELL')
            ->assertSee('ASSURE');
    }

    public function test_platform_admin_can_create_forms_workflows_and_saved_views(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/platform/forms', [
            'code' => 'FORM-HSE-009',
            'name' => 'Dynamic HSE Observation',
            'module' => 'Occurrences',
            'status' => 'Published',
            'sections' => 'Reporter, Event, Risk, Evidence',
            'required_fields' => 'Title, Location, Description',
            'change_note' => 'Added from final dynamic platform pass',
        ])->assertRedirect('/platform');

        $this->post('/platform/workflows', [
            'code' => 'WF-HSE-009',
            'name' => 'Dynamic HSE Workflow',
            'module' => 'Occurrences',
            'status' => 'Published',
            'stages' => 'Draft, Submitted, HSE Review, CAPA, Verification, Closed',
            'routing_rule' => 'Route high risk to HSE manager and QA assurance',
        ])->assertRedirect('/platform');

        $this->post('/platform/saved-views', [
            'name' => 'New confidential intake',
            'module' => 'Public Reports',
            'owner' => 'Yahya Al Naaimi',
            'filters' => 'status:New confidential:true',
            'shared' => 1,
        ])->assertRedirect('/platform');

        $this->assertDatabaseHas('qms_form_definitions', ['code' => 'FORM-HSE-009']);
        $this->assertDatabaseHas('qms_workflow_definitions', ['code' => 'WF-HSE-009']);
        $this->assertDatabaseHas('qms_saved_views', ['name' => 'New confidential intake', 'shared' => true]);

        $this->assertSame(['Reporter', 'Event', 'Risk', 'Evidence'], QmsFormDefinition::where('code', 'FORM-HSE-009')->first()->schema['sections']);
        $this->assertContains('Verification', QmsWorkflowDefinition::where('code', 'WF-HSE-009')->first()->stages);
        $this->assertSame('Public Reports', QmsSavedView::where('name', 'New confidential intake')->first()->module);
    }

    public function test_public_reports_are_searchable_for_internal_triage(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        QmsPublicReport::create([
            'reference' => 'PUB-2026-00999',
            'category' => 'Confidential supplier safety',
            'location' => 'Training center',
            'anonymous' => true,
            'confidential' => true,
            'status' => 'New',
            'description' => 'Public concern about supplier training controls and access.',
        ]);

        $this->get('/public-reports?search=supplier&status=New')
            ->assertOk()
            ->assertSee('External and confidential reports')
            ->assertSee('PUB-2026-00999')
            ->assertSee('Anonymous / Confidential');
    }
}
