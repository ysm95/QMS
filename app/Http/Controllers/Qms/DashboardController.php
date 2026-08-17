<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAudit;
use App\Models\QmsDepartment;
use App\Models\QmsDocument;
use App\Models\QmsInvestigation;
use App\Models\QmsLocation;
use App\Models\QmsNotification;
use App\Models\QmsOccurrence;
use App\Models\QmsRisk;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('qms.dashboard', [
            'metrics' => [
                'openOccurrences' => QmsOccurrence::whereNotIn('status', ['Closed', 'Rejected'])->count(),
                'overdueActions' => QmsAction::where('due_date', '<', now()->toDateString())->whereNotIn('status', ['Closed', 'Verified'])->count(),
                'highRisks' => QmsRisk::whereIn('rating', ['High', 'Critical'])->count(),
                'auditReadiness' => 86,
                'users' => User::count(),
                'departments' => QmsDepartment::count(),
                'locations' => QmsLocation::count(),
                'unreadNotifications' => QmsNotification::whereNull('read_at')->count(),
            ],
            'occurrences' => QmsOccurrence::latest()->limit(6)->get(),
            'actions' => QmsAction::latest()->limit(6)->get(),
            'investigations' => QmsInvestigation::latest()->limit(4)->get(),
            'audits' => QmsAudit::latest()->limit(4)->get(),
            'risks' => QmsRisk::latest()->limit(4)->get(),
            'documents' => QmsDocument::latest()->limit(4)->get(),
            'notifications' => QmsNotification::latest()->limit(5)->get(),
        ]);
    }
}
