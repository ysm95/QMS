<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAuditLog;
use App\Models\QmsInvestigation;
use App\Models\QmsLocation;
use App\Models\QmsOccurrence;
use App\Models\QmsRecommendation;
use App\Models\QmsRecordNote;
use App\Models\User;
use App\Support\QmsAuditTrail;
use App\Support\QmsReportWorkflow;
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

        $report = QmsReportWorkflow::submit($request, $data);

        return redirect()->route('reporting.show', $report)->with('status', 'Report submitted to Screening.');
    }

    public function show(QmsOccurrence $occurrence)
    {
        return view('qms.occurrences.show', [
            'occurrence' => $occurrence,
            'actions' => QmsAction::where('source_reference', $occurrence->reference)->latest()->get(),
            'recommendations' => QmsRecommendation::where('source_reference', $occurrence->reference)->latest()->get(),
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

    public function storeRecommendation(Request $request, QmsOccurrence $occurrence)
    {
        $data = $request->validate([
            'finding' => ['nullable', 'string', 'max:500'],
            'root_cause' => ['nullable', 'string', 'max:2000'],
            'recommendation' => ['required', 'string', 'max:3000'],
            'rationale' => ['nullable', 'string', 'max:3000'],
            'priority' => ['required', 'string', 'max:40'],
            'safety_relevance' => ['required', 'string', 'max:120'],
            'owner' => ['nullable', 'string', 'max:160'],
            'status' => ['required', 'string', 'max:80'],
        ]);

        $recommendation = QmsRecommendation::create([
            'reference' => 'REC-' . now()->format('Y') . '-' . str_pad((string) (QmsRecommendation::count() + 1), 5, '0', STR_PAD_LEFT),
            'source_reference' => $occurrence->reference,
            'investigation_reference' => QmsInvestigation::where('source_reference', $occurrence->reference)->value('reference'),
            ...$data,
            'approval_decision' => 'Pending',
        ]);

        QmsAuditTrail::record($request, $occurrence, 'recommendation_created', [], [
            'recommendation_reference' => $recommendation->reference,
            'status' => $recommendation->status,
        ], 'Structured incident recommendation created.');

        return back()->with('status', 'Recommendation created.');
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
