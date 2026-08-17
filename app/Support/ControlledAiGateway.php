<?php

namespace App\Support;

use App\Models\QmsAiInteraction;
use App\Models\QmsAiProvider;
use Illuminate\Http\Request;

class ControlledAiGateway
{
    public static function provider(): ?QmsAiProvider
    {
        return QmsAiProvider::where('is_approved', true)->where('is_enabled', true)->first();
    }

    public static function submit(Request $request, string $module, string $promptSummary, ?string $sourceReference = null): QmsAiInteraction
    {
        $provider = self::provider();
        $controls = [
            'paid_enterprise_provider_required',
            'entity_trained_or_entity_approved_knowledge_only',
            'no_public_free_ai',
            'no_training_on_customer_data_without_contract',
            'audit_logged',
            'human_approval_required',
        ];

        if (! $provider) {
            return QmsAiInteraction::create([
                'user_id' => $request->user()?->id,
                'ai_provider_id' => null,
                'module' => $module,
                'source_reference' => $sourceReference,
                'status' => 'Blocked - provider not enabled',
                'prompt_summary' => $promptSummary,
                'response_summary' => 'AI request blocked. Configure an approved paid secured entity-trained provider first.',
                'controls_applied' => $controls,
            ]);
        }

        return QmsAiInteraction::create([
            'user_id' => $request->user()?->id,
            'ai_provider_id' => $provider->id,
            'module' => $module,
            'source_reference' => $sourceReference,
            'status' => 'Ready for secure provider dispatch',
            'prompt_summary' => $promptSummary,
            'response_summary' => 'Controlled AI gateway accepted the request. External dispatch is intentionally disabled until provider credentials are stored securely on the server.',
            'controls_applied' => $controls,
        ]);
    }
}
