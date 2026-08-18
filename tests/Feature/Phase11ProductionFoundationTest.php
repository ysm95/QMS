<?php

namespace Tests\Feature;

use App\Models\QmsEmailDesign;
use App\Models\QmsKeyUserAssignment;
use App\Models\QmsNotificationRule;
use App\Models\QmsNotificationTemplate;
use App\Models\QmsPermissionTemplate;
use App\Models\QmsRecommendation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase11ProductionFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_production_foundation_is_available_in_platform_config(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/platform')
            ->assertOk()
            ->assertSee('Email designer')
            ->assertSee('Notification template')
            ->assertSee('Notification rule builder')
            ->assertSee('Permission template')
            ->assertSee('EMAIL-OCC-001')
            ->assertSee('NTF-OCC-001')
            ->assertSee('RULE-OCC-MAJOR-001')
            ->assertSee('Safety Key User');

        $this->assertDatabaseHas('qms_email_designs', ['code' => 'EMAIL-OCC-001', 'status' => 'Published']);
        $this->assertDatabaseHas('qms_notification_templates', ['code' => 'NTF-OCC-001', 'status' => 'Published']);
        $this->assertDatabaseHas('qms_notification_rules', ['code' => 'RULE-OCC-MAJOR-001', 'status' => 'Published']);
        $this->assertDatabaseHas('qms_permission_templates', ['code' => 'PERM-SAFETY-KEY-USER']);
        $this->assertSame(1, QmsKeyUserAssignment::count());
    }

    public function test_admin_can_create_separated_notification_architecture_records(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/platform/email-designs', [
            'code' => 'EMAIL-ACT-010',
            'name' => 'Action Request Email',
            'status' => 'Published',
            'components' => 'Heading, Record Info, Action Button, Footer',
            'variables' => 'user.name, action.reference, action.due_date',
        ])->assertRedirect('/platform');

        $this->post('/platform/notification-templates', [
            'code' => 'NTF-ACT-010',
            'name' => 'Action Requires Acceptance',
            'module' => 'Actions',
            'status' => 'Published',
            'subject_template' => '[{{action.reference}}] action acceptance required',
            'body_template' => 'Hello {{user.name}}, action {{action.reference}} requires acceptance.',
            'allowed_variables' => 'user.name, action.reference, action.due_date',
        ])->assertRedirect('/platform');

        $this->post('/platform/notification-rules', [
            'code' => 'RULE-ACT-010',
            'name' => 'Action acceptance reminder',
            'module' => 'Actions',
            'event_trigger' => 'action.assigned',
            'status' => 'Published',
            'conditions' => 'status:Assigned, accepted_at:null',
            'recipients' => 'Action Owner, Department Key User',
            'channels' => 'In-App, Email',
            'timing' => 'Immediately, +3 days',
        ])->assertRedirect('/platform');

        $this->post('/platform/permission-templates', [
            'code' => 'PERM-ACTION-USER',
            'name' => 'Action User',
            'status' => 'Active',
            'permissions' => 'actions.view.assigned, actions.accept, actions.submit_evidence',
            'default_scopes' => 'ASSIGNED',
            'description' => 'Can process assigned actions only.',
        ])->assertRedirect('/platform');

        $this->assertContains('Action Button', QmsEmailDesign::where('code', 'EMAIL-ACT-010')->first()->builder_schema['components']);
        $this->assertContains('action.reference', QmsNotificationTemplate::where('code', 'NTF-ACT-010')->first()->allowed_variables);
        $this->assertContains('Email', QmsNotificationRule::where('code', 'RULE-ACT-010')->first()->channels);
        $this->assertContains('actions.accept', QmsPermissionTemplate::where('code', 'PERM-ACTION-USER')->first()->permissions);
    }

    public function test_incident_workspace_can_create_structured_recommendation(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/occurrences/1/recommendations', [
            'finding' => 'Temporary controls were not verified.',
            'root_cause' => 'Pre-task checklist was incomplete.',
            'recommendation' => 'Require supervisor sign-off before work starts.',
            'rationale' => 'Formal sign-off improves accountability.',
            'priority' => 'High',
            'safety_relevance' => 'Ground Safety',
            'owner' => 'HSE',
            'status' => 'Review',
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_recommendations', [
            'source_reference' => 'QMS-2026-00435',
            'recommendation' => 'Require supervisor sign-off before work starts.',
            'approval_decision' => 'Pending',
        ]);

        $this->assertGreaterThanOrEqual(2, QmsRecommendation::count());
    }
}
