<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsEmailDesign;
use App\Models\QmsKeyUserAssignment;
use App\Models\QmsAudit;
use App\Models\QmsDepartment;
use App\Models\QmsDocument;
use App\Models\QmsLocation;
use App\Models\QmsManagementReview;
use App\Models\QmsNotificationDesign;
use App\Models\QmsNotificationRule;
use App\Models\QmsNotificationTemplate;
use App\Models\QmsObjective;
use App\Models\QmsOccurrence;
use App\Models\QmsPermissionTemplate;
use App\Models\QmsRecommendation;
use App\Models\QmsReportDesign;
use App\Models\QmsRisk;
use App\Models\QmsSupplier;
use App\Models\QmsTrainingRecord;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $users->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('qms_role', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        return view('qms.admin.index', [
            'users' => $users->paginate(10)->withQueryString(),
            'departments' => QmsDepartment::orderBy('name')->get(),
            'locations' => QmsLocation::orderBy('name')->get(),
            'moduleCounts' => [
                'Occurrences' => QmsOccurrence::count(),
                'Actions' => QmsAction::count(),
                'Audits' => QmsAudit::count(),
                'Risks' => QmsRisk::count(),
                'Documents' => QmsDocument::count(),
                'Objectives' => QmsObjective::count(),
                'Reviews' => QmsManagementReview::count(),
                'Training' => QmsTrainingRecord::count(),
                'Suppliers' => QmsSupplier::count(),
                'Report designs' => QmsReportDesign::count(),
                'Notification designs' => QmsNotificationDesign::count(),
                'Email designs' => QmsEmailDesign::count(),
                'Notification templates' => QmsNotificationTemplate::count(),
                'Notification rules' => QmsNotificationRule::count(),
                'Permission templates' => QmsPermissionTemplate::count(),
                'Key users' => QmsKeyUserAssignment::count(),
                'Recommendations' => QmsRecommendation::count(),
            ],
        ]);
    }
}
