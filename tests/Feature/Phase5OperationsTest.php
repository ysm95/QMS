<?php

namespace Tests\Feature;

use App\Models\QmsDocument;
use App\Models\QmsNotification;
use App\Models\QmsRisk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5OperationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_inbox_loads_and_can_mark_read(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());
        $notification = QmsNotification::where('source_reference', 'QMS-2026-00435')->first();

        $this->get('/notifications?status=unread')
            ->assertOk()
            ->assertSee('Operational inbox')
            ->assertSee('HSE review required');

        $this->patch('/notifications/' . $notification->id . '/read')->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_exports_are_available_for_core_registers(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/exports/occurrences')->assertOk()->assertHeader('content-disposition');
        $this->get('/exports/actions')->assertOk()->assertHeader('content-disposition');
        $this->get('/exports/risks')->assertOk()->assertHeader('content-disposition');
        $this->get('/exports/documents')->assertOk()->assertHeader('content-disposition');
    }

    public function test_risk_and_document_updates_create_notifications_and_audit_entries(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());
        $risk = QmsRisk::where('reference', 'RSK-2026-00031')->first();
        $document = QmsDocument::where('reference', 'DOC-HSE-001')->first();

        $this->patch('/risks/' . $risk->id, [
            'rating' => 'Critical',
            'controls' => 'Immediate barricade, supervisor sign-off, and daily inspection.',
            'review_date' => now()->addDays(10)->toDateString(),
        ])->assertRedirect();

        $this->patch('/documents/' . $document->id, [
            'version' => 'v2.1',
            'status' => 'Published',
            'review_date' => now()->addMonth()->toDateString(),
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_risks', ['reference' => 'RSK-2026-00031', 'rating' => 'Critical']);
        $this->assertDatabaseHas('qms_documents', ['reference' => 'DOC-HSE-001', 'version' => 'v2.1', 'status' => 'Published']);
        $this->assertDatabaseHas('qms_audit_logs', ['reference' => 'RSK-2026-00031', 'action' => 'risk_updated']);
        $this->assertDatabaseHas('qms_audit_logs', ['reference' => 'DOC-HSE-001', 'action' => 'document_updated']);
        $this->assertDatabaseHas('qms_notifications', ['source_reference' => 'RSK-2026-00031', 'title' => 'Risk register updated']);
        $this->assertDatabaseHas('qms_notifications', ['source_reference' => 'DOC-HSE-001', 'title' => 'Controlled document updated']);
    }
}
