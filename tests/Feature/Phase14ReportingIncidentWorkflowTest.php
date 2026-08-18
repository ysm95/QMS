<?php

namespace Tests\Feature;

use App\Models\QmsIncident;
use App\Models\QmsNumberingRule;
use App\Models\QmsReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase14ReportingIncidentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_submission_creates_report_not_incident(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'yahya.alnaaimi@qms.test')->first());

        $this->post('/occurrences', $this->reportPayload('Dispatch fuel planning concern'))->assertRedirect();

        $this->assertDatabaseHas('qms_reports', [
            'title' => 'Dispatch fuel planning concern',
            'status' => 'Submitted',
            'workflow_stage' => 'Screening',
        ]);
        $this->assertDatabaseMissing('qms_incidents', ['title' => 'Dispatch fuel planning concern']);
    }

    public function test_rejected_report_stays_in_reporting_and_creates_no_incident(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $report = QmsReport::create([
            'reference' => 'REP-2026-000900',
            'title' => 'Duplicate low quality report',
            'type' => 'Hazard',
            'severity' => 'Low',
            'status' => 'Submitted',
            'workflow_stage' => 'Screening',
            'description' => 'Duplicate report already handled by another record.',
            'submitted_at' => now(),
        ]);

        $this->post("/reports/{$report->id}/reject", [
            'rejection_reason' => 'Duplicate of existing report.',
            'screening_notes' => 'No incident needed.',
        ])->assertRedirect("/reports/{$report->id}");

        $this->assertDatabaseHas('qms_reports', [
            'id' => $report->id,
            'status' => 'Rejected',
            'rejection_reason' => 'Duplicate of existing report.',
        ]);
        $this->assertDatabaseMissing('qms_incidents', ['source_report_id' => $report->id]);
    }

    public function test_accepted_report_creates_exactly_one_independent_incident(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $report = QmsReport::create([
            'reference' => 'REP-2026-000901',
            'title' => 'Accepted safety report',
            'type' => 'Ground safety',
            'severity' => 'High',
            'status' => 'Submitted',
            'workflow_stage' => 'Screening',
            'description' => 'A report that should become exactly one incident.',
            'submitted_at' => now(),
        ]);

        $this->post("/reports/{$report->id}/accept", [
            'severity' => 'High',
            'classification' => 'Safety Event',
            'department' => 'HSE',
            'owner' => 'Safety Manager',
            'investigation_required' => 1,
            'screening_notes' => 'Accepted for investigation.',
        ])->assertRedirect("/reports/{$report->id}");

        $this->post("/reports/{$report->id}/accept", [
            'severity' => 'High',
            'classification' => 'Safety Event',
            'department' => 'HSE',
            'owner' => 'Safety Manager',
            'investigation_required' => 1,
            'screening_notes' => 'Second click should be idempotent.',
        ])->assertRedirect("/reports/{$report->id}");

        $report->refresh();
        $incident = QmsIncident::where('source_report_id', $report->id)->first();

        $this->assertSame('Accepted', $report->status);
        $this->assertNotNull($incident);
        $this->assertStringStartsWith('INC-', $incident->reference);
        $this->assertSame($report->reference, $incident->source_report_reference);
        $this->assertSame(1, QmsIncident::where('source_report_id', $report->id)->count());
        $this->assertNotSame($report->reference, $incident->reference);
        $this->assertGreaterThan(1, QmsNumberingRule::where('code', 'NUM-INC')->value('next_sequence'));
    }

    private function reportPayload(string $title): array
    {
        return [
            'report_key' => 'dispatch-occurrence',
            'type' => 'Dispatch occurrence',
            'event_title' => $title,
            'event_date' => now()->toDateString(),
            'area_fleet' => 'Dispatch / B737',
            'sector_to' => 'DXB',
            'sector_diverted' => 'MCT',
            'location' => 'OQB Locations',
            'reported_by' => 'Yahya Al Naaimi',
            'description' => 'A report with enough operational detail for screening.',
            'confidential' => 0,
            'mor' => 1,
            'event_categories' => ['Flight Planning'],
            'flight_cancelled' => 0,
            'action_taken' => ['Informed supervisor'],
        ];
    }
}
