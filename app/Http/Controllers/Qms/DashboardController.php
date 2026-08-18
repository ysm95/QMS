<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAiProvider;
use App\Models\QmsAudit;
use App\Models\QmsConfigurationPackage;
use App\Models\QmsDepartment;
use App\Models\QmsDocument;
use App\Models\QmsFormDefinition;
use App\Models\QmsModuleLicense;
use App\Models\QmsNumberingRule;
use App\Models\QmsInvestigation;
use App\Models\QmsLocation;
use App\Models\QmsManagementReview;
use App\Models\QmsNotification;
use App\Models\QmsObjective;
use App\Models\QmsOccurrence;
use App\Models\QmsPublicReport;
use App\Models\QmsRecordLink;
use App\Models\QmsReportDesign;
use App\Models\QmsRisk;
use App\Models\QmsSupplier;
use App\Models\QmsTrainingRecord;
use App\Models\QmsWorkflowDefinition;
use App\Models\QmsNotificationDesign;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $occurrenceWorkflow = QmsWorkflowDefinition::where('code', 'WF-OCC-001')->first();
        $workflowStages = $occurrenceWorkflow?->stages ?: ['Draft', 'Submitted', 'HSE Review', 'Investigation', 'CAPA', 'Verification', 'Closed'];
        $readinessChecks = [
            QmsOccurrence::exists(),
            QmsAction::exists(),
            QmsRisk::exists(),
            QmsAudit::exists(),
            QmsDocument::exists(),
            QmsObjective::exists(),
            QmsManagementReview::exists(),
            QmsTrainingRecord::exists(),
            QmsSupplier::exists(),
            QmsFormDefinition::exists(),
            QmsWorkflowDefinition::exists(),
            QmsReportDesign::exists(),
            QmsNotificationDesign::exists(),
            QmsModuleLicense::exists(),
            QmsNumberingRule::exists(),
            QmsConfigurationPackage::exists(),
            QmsRecordLink::exists(),
            QmsAiProvider::exists(),
        ];
        $auditReadiness = (int) round((collect($readinessChecks)->filter()->count() / count($readinessChecks)) * 100);

        return view('qms.dashboard', [
            'metrics' => [
                'openOccurrences' => QmsOccurrence::whereNotIn('status', ['Closed', 'Rejected'])->count(),
                'overdueActions' => QmsAction::where('due_date', '<', now()->toDateString())->whereNotIn('status', ['Closed', 'Verified'])->count(),
                'highRisks' => QmsRisk::whereIn('rating', ['High', 'Critical'])->count(),
                'auditReadiness' => $auditReadiness,
                'users' => User::count(),
                'departments' => QmsDepartment::count(),
                'locations' => QmsLocation::count(),
                'unreadNotifications' => QmsNotification::whereNull('read_at')->count(),
                'publicReports' => QmsPublicReport::whereNotIn('status', ['Closed', 'Rejected'])->count(),
                'trainingDue' => QmsTrainingRecord::where('expires_on', '<=', now()->addDays(45)->toDateString())->count(),
                'supplierWatch' => QmsSupplier::whereIn('risk_rating', ['High', 'Critical'])->count(),
                'objectivesWatch' => QmsObjective::whereIn('status', ['At risk', 'Off track'])->count(),
                'reportDesigns' => QmsReportDesign::where('status', 'Published')->count(),
                'notificationDesigns' => QmsNotificationDesign::where('status', 'Published')->count(),
            ],
            'workflowStages' => $workflowStages,
            'occurrences' => QmsOccurrence::latest()->limit(6)->get(),
            'actions' => QmsAction::latest()->limit(6)->get(),
            'investigations' => QmsInvestigation::latest()->limit(4)->get(),
            'audits' => QmsAudit::latest()->limit(4)->get(),
            'risks' => QmsRisk::latest()->limit(4)->get(),
            'documents' => QmsDocument::latest()->limit(4)->get(),
            'notifications' => QmsNotification::latest()->limit(5)->get(),
            'objectives' => QmsObjective::latest()->limit(4)->get(),
            'training' => QmsTrainingRecord::latest()->limit(4)->get(),
            'suppliers' => QmsSupplier::latest()->limit(4)->get(),
            'publicReports' => QmsPublicReport::latest()->limit(4)->get(),
        ]);
    }
}
