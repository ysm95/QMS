<?php

namespace Tests\Feature;

use App\Models\QmsNotificationDesign;
use App\Models\QmsReportDesign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase10ProductionDesignersTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_includes_seeded_report_and_notification_designers(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/platform')
            ->assertOk()
            ->assertSee('Report designer')
            ->assertSee('Notification designer')
            ->assertSee('RPT-OCC-001')
            ->assertSee('MSG-OCC-001')
            ->assertSee('Occurrence Register and Risk Summary')
            ->assertSee('Occurrence Submitted');

        $this->assertDatabaseHas('qms_report_designs', [
            'code' => 'RPT-OCC-001',
            'status' => 'Published',
        ]);

        $this->assertDatabaseHas('qms_notification_designs', [
            'code' => 'MSG-OCC-001',
            'event_trigger' => 'occurrence.submitted',
        ]);
    }

    public function test_admin_can_create_report_and_notification_designs(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/platform/report-designs', [
            'code' => 'RPT-AUD-010',
            'name' => 'Audit Findings Report',
            'module' => 'Audits',
            'status' => 'Published',
            'sections' => 'Header, Scope, Findings, Actions, Evidence',
            'columns' => 'Reference, Finding, Severity, Owner, Due Date',
            'data_sources' => 'Audits, Actions, Documents',
            'output_formats' => 'Screen, PDF, Excel',
            'change_note' => 'Production audit report layout.',
        ])->assertRedirect('/platform');

        $this->post('/platform/notification-designs', [
            'code' => 'MSG-AUD-010',
            'name' => 'Audit Finding Assigned',
            'module' => 'Audits',
            'event_trigger' => 'audit.finding.assigned',
            'status' => 'Published',
            'to_recipients' => 'Finding Owner, Lead Auditor',
            'cc_recipients' => 'QMS Manager',
            'conditions' => 'severity:Major, status:Open',
            'subject_template' => '[{{reference}}] audit finding assigned',
            'body_template' => 'Audit finding {{reference}} requires action from {{owner}}.',
            'change_note' => 'Production notification rule.',
        ])->assertRedirect('/platform');

        $reportDesign = QmsReportDesign::where('code', 'RPT-AUD-010')->first();
        $notificationDesign = QmsNotificationDesign::where('code', 'MSG-AUD-010')->first();

        $this->assertContains('Findings', $reportDesign->layout['sections']);
        $this->assertContains('Lead Auditor', $notificationDesign->recipients['to']);
        $this->assertContains('severity:Major', $notificationDesign->conditions['rules']);
    }

    public function test_dashboard_counts_published_designers(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Report designs')
            ->assertSee('Notification rules')
            ->assertSee('Published layouts')
            ->assertSee('Published templates');
    }
}
