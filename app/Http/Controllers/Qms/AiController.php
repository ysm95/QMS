<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAiInteraction;
use App\Models\QmsAiProvider;
use App\Support\ControlledAiGateway;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function index()
    {
        return view('qms.ai.index', [
            'providers' => QmsAiProvider::latest()->get(),
            'interactions' => QmsAiInteraction::latest()->paginate(12),
            'activeProvider' => ControlledAiGateway::provider(),
        ]);
    }

    public function requestReview(Request $request)
    {
        $data = $request->validate([
            'module' => ['required', 'string', 'max:120'],
            'source_reference' => ['nullable', 'string', 'max:120'],
            'prompt_summary' => ['required', 'string', 'max:2000'],
        ]);

        ControlledAiGateway::submit($request, $data['module'], $data['prompt_summary'], $data['source_reference'] ?? null);

        return back()->with('status', 'Controlled AI request recorded.');
    }
}
