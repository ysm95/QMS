<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAudit;
use App\Models\QmsDocument;
use App\Models\QmsManagementReview;
use App\Models\QmsObjective;
use App\Models\QmsOccurrence;
use App\Models\QmsRecordLink;
use App\Models\QmsRisk;
use App\Models\QmsSupplier;
use App\Models\QmsTrainingRecord;

class IntelligenceController extends Controller
{
    public function index()
    {
        return view('qms.intelligence.index', [
            'signals' => [
                'Open occurrence load' => QmsOccurrence::whereNotIn('status', ['Closed', 'Rejected'])->count(),
                'High/Critical risk exposure' => QmsRisk::whereIn('rating', ['High', 'Critical'])->count(),
                'Open CAPA pressure' => QmsAction::whereNotIn('status', ['Closed', 'Verified'])->count(),
                'Expiring competence items' => QmsTrainingRecord::where('status', 'like', '%Expiring%')->count(),
                'Supplier high risk watch' => QmsSupplier::whereIn('risk_rating', ['High', 'Critical'])->count(),
                'Management reviews planned' => QmsManagementReview::where('status', 'Planned')->count(),
            ],
            'readiness' => [
                'Reporting and occurrence workflow' => QmsOccurrence::count() > 0,
                'CAPA lifecycle' => QmsAction::count() > 0,
                'Risk register' => QmsRisk::count() > 0,
                'Audit programme' => QmsAudit::count() > 0,
                'Document control' => QmsDocument::count() > 0,
                'Objectives / SPI' => QmsObjective::count() > 0,
                'Traceability links' => QmsRecordLink::count() > 0,
            ],
            'links' => QmsRecordLink::latest()->limit(10)->get(),
        ]);
    }
}
