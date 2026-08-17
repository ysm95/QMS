<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsPublicReport;
use Illuminate\Http\Request;

class PublicReportController extends Controller
{
    public function create()
    {
        return view('qms.public.report');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reporter_name' => ['nullable', 'string', 'max:160'],
            'reporter_contact' => ['nullable', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:160'],
            'anonymous' => ['nullable', 'boolean'],
            'confidential' => ['nullable', 'boolean'],
            'description' => ['required', 'string', 'max:5000'],
        ]);

        $report = QmsPublicReport::create([
            'reference' => 'PUB-' . now()->format('Y') . '-' . str_pad((string) (QmsPublicReport::count() + 1), 5, '0', STR_PAD_LEFT),
            'reporter_name' => ($data['anonymous'] ?? false) ? null : ($data['reporter_name'] ?? null),
            'reporter_contact' => ($data['anonymous'] ?? false) ? null : ($data['reporter_contact'] ?? null),
            'category' => $data['category'],
            'location' => $data['location'] ?? null,
            'anonymous' => (bool) ($data['anonymous'] ?? false),
            'confidential' => (bool) ($data['confidential'] ?? false),
            'description' => $data['description'],
            'status' => 'New',
        ]);

        return redirect()->route('portal.report')->with('status', 'Report received. Reference: ' . $report->reference);
    }
}
