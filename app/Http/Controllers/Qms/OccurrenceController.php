<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsLocation;
use App\Models\QmsOccurrence;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OccurrenceController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsOccurrence::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return view('qms.occurrences.index', [
            'occurrences' => $query->paginate(12)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('qms.occurrences.create', [
            'locations' => QmsLocation::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
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

        return redirect()->route('qms.occurrences.show', $occurrence)->with('status', 'Occurrence submitted to HSE Review.');
    }

    public function show(QmsOccurrence $occurrence)
    {
        return view('qms.occurrences.show', [
            'occurrence' => $occurrence,
            'actions' => QmsAction::where('source_reference', $occurrence->reference)->latest()->get(),
        ]);
    }

    public function advance(Request $request, QmsOccurrence $occurrence)
    {
        $data = $request->validate([
            'workflow_stage' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:80'],
            'risk_rating' => ['required', 'string', 'max:40'],
        ]);

        $occurrence->update($data);

        return back()->with('status', 'Workflow updated.');
    }
}
