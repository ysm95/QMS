<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAuditLog;
use App\Models\QmsLocation;
use App\Models\QmsOccurrence;
use App\Models\QmsRecordNote;
use App\Models\User;
use App\Support\QmsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Qms\ReportingController;

class OccurrenceController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsOccurrence::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('stage')) {
            $query->where('workflow_stage', $request->string('stage'));
        }

        if ($request->filled('risk')) {
            $query->where('risk_rating', $request->string('risk'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('reported_by', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return view('qms.occurrences.index', [
            'occurrences' => $query->paginate(12)->withQueryString(),
            'stages' => QmsOccurrence::select('workflow_stage')->distinct()->orderBy('workflow_stage')->pluck('workflow_stage'),
            'risks' => QmsOccurrence::select('risk_rating')->distinct()->orderBy('risk_rating')->pluck('risk_rating'),
            'types' => QmsOccurrence::select('type')->distinct()->orderBy('type')->pluck('type'),
        ]);
    }

    public function create()
    {
        $reportTypes = ReportingController::reportTypes();
        $selectedReportKey = request('report_type', 'dispatch-occurrence');

        if (! array_key_exists($selectedReportKey, $reportTypes)) {
            $selectedReportKey = 'dispatch-occurrence';
        }

        return view('qms.occurrences.create', [
            'locations' => QmsLocation::where('active', true)->orderBy('name')->get(),
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'reportTypes' => $reportTypes,
            'selectedReportKey' => $selectedReportKey,
            'selectedReportType' => $reportTypes[$selectedReportKey],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_key' => ['nullable', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:120'],
            'event_title' => ['nullable', 'string', 'max:180'],
            'event_date' => ['nullable', 'date'],
            'area_fleet' => ['nullable', 'string', 'max:160'],
            'sector_to' => ['nullable', 'string', 'max:80'],
            'sector_diverted' => ['nullable', 'string', 'max:80'],
            'location' => ['required', 'string', 'max:160'],
            'exact_location' => ['nullable', 'string', 'max:255'],
            'reported_by' => ['required', 'string', 'max:160'],
            'pilot_name' => ['nullable', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:5000'],
            'confidential' => ['nullable', 'boolean'],
            'mor' => ['nullable', 'boolean'],
            'event_categories' => ['nullable', 'array'],
            'event_categories.*' => ['string', 'max:120'],
            'aircraft_type' => ['nullable', 'string', 'max:120'],
            'aircraft_registration' => ['nullable', 'string', 'max:120'],
            'flight_number' => ['nullable', 'string', 'max:80'],
            'time_of_occurrence' => ['nullable', 'date_format:H:i'],
            'flight_cancelled' => ['nullable', 'boolean'],
            'personnel_involved' => ['nullable', 'array'],
            'flight_plan_details' => ['nullable', 'string', 'max:5000'],
            'action_taken' => ['nullable', 'array'],
            'action_taken.*' => ['string', 'max:160'],
            'immediate_corrective_action' => ['nullable', 'string', 'max:5000'],
            'feedback_to_reporter' => ['nullable', 'string', 'max:3000'],
        ]);

        $occurrence = QmsOccurrence::create([
            'reference' => 'QMS-' . now()->format('Y') . '-' . str_pad((string) (QmsOccurrence::count() + 1), 5, '0', STR_PAD_LEFT),
            'report_key' => $data['report_key'] ?? null,
            'title' => $data['event_title'] ?: Str::limit($data['description'], 80),
            'event_title' => $data['event_title'] ?? null,
            'type' => $data['type'],
            'area_fleet' => $data['area_fleet'] ?? null,
            'sector_to' => $data['sector_to'] ?? null,
            'sector_diverted' => $data['sector_diverted'] ?? null,
            'location' => $data['location'],
            'exact_location' => $data['exact_location'] ?? null,
            'reported_by' => $data['reported_by'],
            'pilot_name' => $data['pilot_name'] ?? null,
            'description' => $data['description'],
            'flight_plan_details' => $data['flight_plan_details'] ?? null,
            'action_taken' => $data['action_taken'] ?? [],
            'immediate_corrective_action' => $data['immediate_corrective_action'] ?? null,
            'feedback_to_reporter' => $data['feedback_to_reporter'] ?? null,
            'status' => 'Submitted',
            'workflow_stage' => 'HSE Review',
            'risk_rating' => 'Medium',
            'confidential' => (bool) ($data['confidential'] ?? false),
            'mor' => (bool) ($data['mor'] ?? false),
            'event_categories' => $data['event_categories'] ?? [],
            'aircraft_type' => $data['aircraft_type'] ?? null,
            'aircraft_registration' => $data['aircraft_registration'] ?? null,
            'flight_number' => $data['flight_number'] ?? null,
            'time_of_occurrence' => $data['time_of_occurrence'] ?? null,
            'flight_cancelled' => (bool) ($data['flight_cancelled'] ?? false),
            'personnel_involved' => $data['personnel_involved'] ?? [],
            'event_date' => $data['event_date'] ?? null,
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

        QmsAuditTrail::record($request, $occurrence, 'submitted', [], [
            'status' => $occurrence->status,
            'workflow_stage' => $occurrence->workflow_stage,
            'risk_rating' => $occurrence->risk_rating,
        ], 'Occurrence submitted into QMS workflow.');

        return redirect()->route('occurrences.show', $occurrence)->with('status', 'Occurrence submitted to HSE Review.');
    }

    public function show(QmsOccurrence $occurrence)
    {
        return view('qms.occurrences.show', [
            'occurrence' => $occurrence,
            'actions' => QmsAction::where('source_reference', $occurrence->reference)->latest()->get(),
            'notes' => QmsRecordNote::where('record_type', QmsOccurrence::class)->where('record_id', $occurrence->id)->latest()->get(),
            'auditLogs' => QmsAuditLog::where('auditable_type', QmsOccurrence::class)->where('auditable_id', $occurrence->id)->latest()->get(),
        ]);
    }

    public function advance(Request $request, QmsOccurrence $occurrence)
    {
        $data = $request->validate([
            'workflow_stage' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:80'],
            'risk_rating' => ['required', 'string', 'max:40'],
        ]);

        $oldValues = $occurrence->only(['workflow_stage', 'status', 'risk_rating']);
        $occurrence->update($data);

        QmsAuditTrail::record($request, $occurrence, 'workflow_updated', $oldValues, $data, 'Workflow, status, or risk rating updated.');

        return back()->with('status', 'Workflow updated.');
    }

    public function storeNote(Request $request, QmsOccurrence $occurrence)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:3000'],
            'visibility' => ['required', 'string', 'max:80'],
        ]);

        QmsRecordNote::create([
            'record_type' => QmsOccurrence::class,
            'record_id' => $occurrence->id,
            'reference' => $occurrence->reference,
            'user_id' => $request->user()?->id,
            'author' => $request->user()?->name ?? 'System',
            'visibility' => $data['visibility'],
            'body' => $data['body'],
        ]);

        QmsAuditTrail::record($request, $occurrence, 'note_added', [], ['visibility' => $data['visibility']], 'Record note added.');

        return back()->with('status', 'Note added.');
    }
}
