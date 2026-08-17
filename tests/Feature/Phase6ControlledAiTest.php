<?php

namespace Tests\Feature;

use App\Models\QmsAiInteraction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase6ControlledAiTest extends TestCase
{
    use RefreshDatabase;

    public function test_controlled_ai_page_shows_blocked_enterprise_only_provider(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->get('/ai')
            ->assertOk()
            ->assertSee('Controlled AI')
            ->assertSee('Paid secured only')
            ->assertSee('AI blocked until approved')
            ->assertSee('Entity-trained controlled model');
    }

    public function test_ai_requests_are_blocked_until_paid_secured_provider_is_enabled(): void
    {
        $this->seed();
        $this->actingAs(User::where('email', 'admin@qms.test')->first());

        $this->post('/ai/request-review', [
            'module' => 'Occurrence',
            'source_reference' => 'QMS-2026-00435',
            'prompt_summary' => 'Review occurrence quality and missing information.',
        ])->assertRedirect();

        $this->assertDatabaseHas('qms_ai_interactions', [
            'module' => 'Occurrence',
            'source_reference' => 'QMS-2026-00435',
            'status' => 'Blocked - provider not enabled',
        ]);

        $interaction = QmsAiInteraction::first();
        $this->assertContains('no_public_free_ai', $interaction->controls_applied);
        $this->assertContains('entity_trained_or_entity_approved_knowledge_only', $interaction->controls_applied);
    }
}
