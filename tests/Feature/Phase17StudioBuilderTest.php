<?php

namespace Tests\Feature;

use App\Models\QmsFormDefinition;
use App\Models\QmsWorkflowDefinition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase17StudioBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_shows_real_form_and_workflow_studio_surfaces(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/platform')
            ->assertOk()
            ->assertSee('Form Studio')
            ->assertSee('Visual schema builder')
            ->assertSee('Aircraft type')
            ->assertSee('Data source')
            ->assertSee('Workflow Studio')
            ->assertSee('Stage and rule canvas')
            ->assertSee('Timer / SLA')
            ->assertSee('Simulate');
    }

    public function test_form_studio_stores_application_owned_canonical_schema(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/platform/forms', [
            'code' => 'FORM-STUDIO-900',
            'name' => 'Studio Safety Report',
            'module' => 'Reporting',
            'status' => 'Draft',
            'sections' => 'General, Aviation',
            'required_fields' => 'Event title, Aircraft registration',
            'canonical_schema' => json_encode([
                'sections' => ['General', 'Aviation'],
                'required' => ['Event title', 'Aircraft registration'],
                'fields' => [
                    ['key' => 'event_title', 'label' => 'Event title', 'type' => 'text', 'category' => 'Basic', 'section' => 'General', 'required' => true],
                    ['key' => 'aircraft_registration', 'label' => 'Aircraft registration', 'type' => 'registration', 'category' => 'Aviation', 'section' => 'Aviation', 'required' => true, 'data_source' => 'DS-FLEET-REGISTRY'],
                ],
                'data_sources' => ['DS-FLEET-REGISTRY'],
            ]),
            'change_note' => 'Created in Form Studio.',
        ])->assertRedirect('/platform');

        $form = QmsFormDefinition::where('code', 'FORM-STUDIO-900')->first();

        $this->assertSame('QMS Form Studio', $form->schema['source']);
        $this->assertSame('registration', $form->schema['fields'][1]['type']);
        $this->assertSame('DS-FLEET-REGISTRY', $form->schema['fields'][1]['data_source']);
        $this->assertTrue($form->schema['translations']['ar_ready']);
    }

    public function test_workflow_studio_stores_nodes_edges_and_governance_rules(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/platform/workflows', [
            'code' => 'WF-STUDIO-900',
            'name' => 'Studio Incident Workflow',
            'module' => 'Incidents',
            'status' => 'Draft',
            'stages' => 'Start, Screening, Approval, End',
            'routing_rule' => 'Route by severity and separation of duties.',
            'canonical_workflow' => json_encode([
                'stages' => ['Start', 'Screening', 'Approval', 'End'],
                'nodes' => [
                    ['id' => 'node_1', 'type' => 'start', 'label' => 'Start', 'kind' => 'Start event'],
                    ['id' => 'node_2', 'type' => 'human_task', 'label' => 'Screening', 'kind' => 'Task', 'assignee' => 'safety_key_user', 'sla' => 'P2D'],
                    ['id' => 'node_3', 'type' => 'approval', 'label' => 'Approval', 'kind' => 'Approval', 'assignee' => 'quality_manager', 'sla' => 'P1D'],
                    ['id' => 'node_4', 'type' => 'end', 'label' => 'End', 'kind' => 'End event'],
                ],
                'edges' => [
                    ['from' => 'node_1', 'to' => 'node_2'],
                    ['from' => 'node_2', 'to' => 'node_3'],
                    ['from' => 'node_3', 'to' => 'node_4'],
                ],
            ]),
        ])->assertRedirect('/platform');

        $workflow = QmsWorkflowDefinition::where('code', 'WF-STUDIO-900')->first();

        $this->assertSame(['Start', 'Screening', 'Approval', 'End'], $workflow->stages);
        $this->assertSame('QMS Workflow Studio', $workflow->rules['source']);
        $this->assertSame('approval', $workflow->rules['nodes'][2]['type']);
        $this->assertTrue($workflow->rules['version_protection']);
        $this->assertTrue($workflow->rules['simulation_ready']);
    }
}
