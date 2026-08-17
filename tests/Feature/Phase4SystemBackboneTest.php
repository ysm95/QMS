<?php

namespace Tests\Feature;

use App\Models\QmsAuditLog;
use App\Models\QmsInvestigation;
use App\Models\QmsOccurrence;
use App\Models\QmsRecordNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase4SystemBackboneTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_search_and_compliance_frameworks_are_available(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/search?q=Contractor')
            ->assertOk()
            ->assertSee('Global search')
            ->assertSee('DOC-HSE-001')
            ->assertSee('RSK-2026-00031');

        $this->get('/compliance?search=SMS')
            ->assertOk()
            ->assertSee('Compliance frameworks')
            ->assertSee('SMS-ICAO')
            ->assertSee('Safety assurance');
    }

    public function test_occurrence_notes_and_workflow_updates_create_audit_trail(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'yahya.alnaaimi@qms.test')->first());
        $occurrence = QmsOccurrence::where('reference', 'QMS-2026-00435')->first();

        $this->post('/occurrences/' . $occurrence->id . '/notes', [
            'visibility' => 'Internal',
            'body' => 'Initial screening completed and accepted for investigation.',
        ])->assertRedirect();

        $this->patch('/occurrences/' . $occurrence->id . '/advance', [
            'workflow_stage' => 'Investigation',
            'status' => 'Accepted',
            'risk_rating' => 'High',
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_record_notes', [
            'reference' => 'QMS-2026-00435',
            'body' => 'Initial screening completed and accepted for investigation.',
        ]);

        $this->assertDatabaseHas('qms_audit_logs', [
            'reference' => 'QMS-2026-00435',
            'action' => 'workflow_updated',
        ]);

        $this->assertSame(2, QmsAuditLog::where('reference', 'QMS-2026-00435')->count());
        $this->assertSame(1, QmsRecordNote::where('reference', 'QMS-2026-00435')->count());
    }

    public function test_investigation_workspace_can_be_updated_and_noted(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'mazin.alfarsi@qms.test')->first());
        $investigation = QmsInvestigation::where('reference', 'INV-2026-00012')->first();

        $this->patch('/investigations/' . $investigation->id, [
            'status' => 'Analysis',
            'scope' => 'Updated investigation scope.',
            'findings' => 'Updated investigation finding.',
        ])->assertRedirect();

        $this->post('/investigations/' . $investigation->id . '/notes', [
            'visibility' => 'Investigation team',
            'body' => 'Interview evidence reviewed by lead investigator.',
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_investigations', [
            'reference' => 'INV-2026-00012',
            'status' => 'Analysis',
            'scope' => 'Updated investigation scope.',
        ]);

        $this->assertDatabaseHas('qms_record_notes', [
            'reference' => 'INV-2026-00012',
            'body' => 'Interview evidence reviewed by lead investigator.',
        ]);
    }
}
