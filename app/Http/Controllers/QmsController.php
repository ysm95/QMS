<?php

namespace App\Http\Controllers;

use App\Models\QmsAction;
use App\Models\QmsAudit;
use App\Models\QmsDocument;
use App\Models\QmsInvestigation;
use App\Models\QmsOccurrence;
use App\Models\QmsRisk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QmsController extends Controller
{
    public function index()
    {
        return view('qms.app', [
            'metrics' => [
                'openOccurrences' => QmsOccurrence::whereNotIn('status', ['Closed', 'Rejected'])->count(),
                'overdueActions' => QmsAction::where('due_date', '<', now()->toDateString())->whereNotIn('status', ['Closed', 'Verified'])->count(),
                'highRisks' => QmsRisk::whereIn('rating', ['High', 'Critical'])->count(),
                'auditReadiness' => 86,
            ],
            'occurrences' => QmsOccurrence::latest()->limit(8)->get(),
            'actions' => QmsAction::latest()->limit(8)->get(),
            'investigations' => QmsInvestigation::latest()->limit(5)->get(),
            'audits' => QmsAudit::latest()->limit(5)->get(),
            'risks' => QmsRisk::latest()->limit(5)->get(),
            'documents' => QmsDocument::latest()->limit(5)->get(),
        ]);
    }

    public function storeOccurrence(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'max:120'],
            'location' => ['required', 'string', 'max:160'],
            'exact_location' => ['nullable', 'string', 'max:255'],
            'reported_by' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:5000'],
            'confidential' => ['nullable', 'boolean'],
        ]);

        $occurrence = QmsOccurrence::create([
            'reference' => 'QMS-' . now()->format('Y') . '-' . str_pad((string) (QmsOccurrence::count() + 1), 5, '0', STR_PAD_LEFT),
            'title' => Str::limit($data['description'], 80),
            'type' => $data['type'],
            'location' => $data['location'],
            'exact_location' => $data['exact_location'] ?? null,
            'reported_by' => $data['reported_by'],
            'description' => $data['description'],
            'status' => 'Submitted',
            'workflow_stage' => 'HSE Review',
            'risk_rating' => 'Medium',
            'confidential' => (bool) ($data['confidential'] ?? false),
            'reported_at' => now(),
        ]);

        QmsAction::create([
            'reference' => 'ACT-' . now()->format('Y') . '-' . str_pad((string) (QmsAction::count() + 1), 5, '0', STR_PAD_LEFT),
            'source_reference' => $occurrence->reference,
            'title' => 'Initial screening for ' . $occurrence->reference,
            'owner' => 'HSE Review Team',
            'priority' => 'High',
            'status' => 'Open',
            'due_date' => now()->addDays(2)->toDateString(),
        ]);

        return redirect()->route('qms.index')->with('status', 'Occurrence submitted to HSE Review.');
    }
}
