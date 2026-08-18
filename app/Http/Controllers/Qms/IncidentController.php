<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAuditLog;
use App\Models\QmsIncident;
use App\Models\QmsRecordLink;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsIncident::query()->with('sourceReport')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->string('severity'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('source_report_reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%");
            });
        }

        return view('qms.incidents.index', [
            'incidents' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsIncident::select('status')->distinct()->orderBy('status')->pluck('status'),
            'severities' => QmsIncident::select('severity')->distinct()->orderBy('severity')->pluck('severity'),
        ]);
    }

    public function show(QmsIncident $incident)
    {
        return view('qms.incidents.show', [
            'incident' => $incident->load('sourceReport'),
            'actions' => QmsAction::where('source_reference', $incident->reference)->latest()->get(),
            'links' => QmsRecordLink::where('target_reference', $incident->reference)->orWhere('source_reference', $incident->reference)->latest()->get(),
            'auditLogs' => QmsAuditLog::where('auditable_type', QmsIncident::class)->where('auditable_id', $incident->id)->latest()->get(),
            'closureGates' => [
                'Source report preserved',
                'Required actions completed',
                'Risk assessment approved',
                'Investigation completed when required',
                'Evidence attached where required',
                'Closure approval signed',
                'Effectiveness review scheduled',
            ],
        ]);
    }
}
