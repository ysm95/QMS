<?php

namespace Tests\Feature;

use App\Models\QmsAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_assurance_risk_document_and_admin_pages_load_with_search(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/audits?search=August')
            ->assertOk()
            ->assertSee('Audits')
            ->assertSee('AUD-2026-00008');

        $this->get('/risks?search=Contractor')
            ->assertOk()
            ->assertSee('Risk register')
            ->assertSee('RSK-2026-00031');

        $this->get('/documents?search=Contractor')
            ->assertOk()
            ->assertSee('Documents')
            ->assertSee('DOC-HSE-001');

        $this->get('/admin?search=Yahya')
            ->assertOk()
            ->assertSee('Control center')
            ->assertSee('Yahya Al Naaimi');
    }

    public function test_actions_can_be_filtered_and_updated_with_evidence(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $action = QmsAction::where('reference', 'CAPA-2026-00077')->first();

        $this->get('/actions?priority=High&search=barricade')
            ->assertOk()
            ->assertSee('CAPA-2026-00077')
            ->assertSee('Revise barricade control checklist');

        $this->patch('/actions/' . $action->id, [
            'status' => 'Verification',
            'evidence' => 'Checklist draft uploaded for verification.',
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_actions', [
            'reference' => 'CAPA-2026-00077',
            'status' => 'Verification',
            'evidence' => 'Checklist draft uploaded for verification.',
        ]);
    }
}
