<?php

namespace App\Support;

use App\Models\QmsAccessScope;
use App\Models\QmsAction;
use App\Models\QmsAiProvider;
use App\Models\QmsAttachment;
use App\Models\QmsAudit;
use App\Models\QmsAuditLog;
use App\Models\QmsComplianceFramework;
use App\Models\QmsConfigurationPackage;
use App\Models\QmsDataSource;
use App\Models\QmsDepartment;
use App\Models\QmsDocument;
use App\Models\QmsDomainPack;
use App\Models\QmsElectronicSignature;
use App\Models\QmsEmailDesign;
use App\Models\QmsFormDefinition;
use App\Models\QmsIncident;
use App\Models\QmsIntegrationEvent;
use App\Models\QmsInvestigation;
use App\Models\QmsKeyUserAssignment;
use App\Models\QmsLocation;
use App\Models\QmsManagementReview;
use App\Models\QmsModuleLicense;
use App\Models\QmsNotificationDelivery;
use App\Models\QmsNotificationDesign;
use App\Models\QmsNotificationGroup;
use App\Models\QmsNotificationRule;
use App\Models\QmsNotificationTemplate;
use App\Models\QmsNumberingRule;
use App\Models\QmsObjective;
use App\Models\QmsOccurrence;
use App\Models\QmsOfflineProfile;
use App\Models\QmsPermissionTemplate;
use App\Models\QmsPublicReport;
use App\Models\QmsRecommendation;
use App\Models\QmsReport;
use App\Models\QmsReportDesign;
use App\Models\QmsRetentionRule;
use App\Models\QmsRisk;
use App\Models\QmsSavedView;
use App\Models\QmsSupplier;
use App\Models\QmsSyncAdapter;
use App\Models\QmsSystemMonitor;
use App\Models\QmsSystemSetting;
use App\Models\QmsTrainingRecord;
use App\Models\QmsWorkflowDefinition;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QmsAdminControlCenter
{
    public function summary(): array
    {
        return [
            'Active users' => User::where('is_active', true)->count(),
            'Enabled modules' => QmsModuleLicense::where('enabled', true)->count(),
            'Published builders' => QmsFormDefinition::where('status', 'Published')->count()
                + QmsWorkflowDefinition::where('status', 'Published')->count()
                + QmsReportDesign::where('status', 'Published')->count()
                + QmsNotificationDesign::where('status', 'Published')->count(),
            'Open work' => $this->openWorkCount(),
            'Governance records' => QmsAuditLog::count()
                + QmsElectronicSignature::count()
                + QmsRetentionRule::where('status', 'Active')->count(),
        ];
    }

    public function workspaces(): Collection
    {
        return collect([
            [
                'name' => 'Organization',
                'purpose' => 'Departments, locations, active workforce and local master data.',
                'status' => $this->statusFromCounts(QmsDepartment::count(), QmsLocation::count(), User::where('is_active', true)->count()),
                'route' => 'admin.index',
                'items' => [
                    'Departments' => QmsDepartment::where('active', true)->count(),
                    'Locations' => QmsLocation::where('active', true)->count(),
                    'Users' => User::where('is_active', true)->count(),
                ],
            ],
            [
                'name' => 'Identity & Access',
                'purpose' => 'Role templates, key-user assignments and scoped access control.',
                'status' => $this->statusFromCounts(QmsPermissionTemplate::count(), QmsKeyUserAssignment::count(), QmsAccessScope::count()),
                'route' => 'platform.index',
                'items' => [
                    'Permission templates' => QmsPermissionTemplate::where('status', 'Active')->count(),
                    'Key users' => QmsKeyUserAssignment::where('status', 'Active')->count(),
                    'Access scopes' => QmsAccessScope::where('status', 'Active')->count(),
                ],
            ],
            [
                'name' => 'Platform Studios',
                'purpose' => 'Form builder, workflow builder, report designer and email designer.',
                'status' => $this->statusFromCounts(QmsFormDefinition::count(), QmsWorkflowDefinition::count(), QmsReportDesign::count(), QmsEmailDesign::count()),
                'route' => 'platform.index',
                'items' => [
                    'Forms' => QmsFormDefinition::count(),
                    'Workflows' => QmsWorkflowDefinition::count(),
                    'Report designer' => QmsReportDesign::count(),
                    'Email designer' => QmsEmailDesign::count(),
                ],
            ],
            [
                'name' => 'Notification Designer',
                'purpose' => 'Notification designs, templates, rules, groups and delivery evidence.',
                'status' => $this->statusFromCounts(QmsNotificationDesign::count(), QmsNotificationTemplate::count(), QmsNotificationRule::count()),
                'route' => 'platform.index',
                'items' => [
                    'Designs' => QmsNotificationDesign::count(),
                    'Templates' => QmsNotificationTemplate::count(),
                    'Rules' => QmsNotificationRule::count(),
                    'Groups' => QmsNotificationGroup::count(),
                    'Deliveries' => QmsNotificationDelivery::count(),
                ],
            ],
            [
                'name' => 'Data Management',
                'purpose' => 'Governed lookup sources, numbering, configuration packages and domain packs.',
                'status' => $this->statusFromCounts(QmsDataSource::count(), QmsNumberingRule::count(), QmsConfigurationPackage::count()),
                'route' => 'platform.index',
                'items' => [
                    'Data sources' => QmsDataSource::count(),
                    'Numbering rules' => QmsNumberingRule::where('status', 'Active')->count(),
                    'Config packages' => QmsConfigurationPackage::count(),
                    'Domain packs' => QmsDomainPack::count(),
                ],
            ],
            [
                'name' => 'Operations',
                'purpose' => 'Sync adapters, offline profiles, integration events and production monitors.',
                'status' => $this->statusFromCounts(QmsSystemMonitor::count(), QmsSyncAdapter::count(), QmsIntegrationEvent::count()),
                'route' => 'platform.index',
                'items' => [
                    'System monitors' => QmsSystemMonitor::count(),
                    'Sync adapters' => QmsSyncAdapter::count(),
                    'Offline profiles' => QmsOfflineProfile::count(),
                    'Integration events' => QmsIntegrationEvent::count(),
                ],
            ],
            [
                'name' => 'Controlled AI',
                'purpose' => 'Paid secured provider approval, entity-trained scope and AI audit trail.',
                'status' => QmsAiProvider::where('is_approved', true)->where('is_enabled', true)->exists() ? 'Ready' : 'Blocked',
                'route' => 'ai.index',
                'items' => [
                    'Approved providers' => QmsAiProvider::where('is_approved', true)->count(),
                    'Enabled providers' => QmsAiProvider::where('is_enabled', true)->count(),
                    'Blocked providers' => QmsAiProvider::where(function (Builder $query) {
                        $query->where('is_approved', false)->orWhere('is_enabled', false);
                    })->count(),
                ],
            ],
            [
                'name' => 'Governance',
                'purpose' => 'Audit trail, signatures, attachments, retention and compliance evidence.',
                'status' => $this->statusFromCounts(QmsRetentionRule::count(), QmsAttachment::count(), QmsElectronicSignature::count()),
                'route' => 'intelligence.index',
                'items' => [
                    'Audit logs' => QmsAuditLog::count(),
                    'Signatures' => QmsElectronicSignature::count(),
                    'Attachments' => QmsAttachment::count(),
                    'Retention rules' => QmsRetentionRule::where('status', 'Active')->count(),
                ],
            ],
        ]);
    }

    public function readiness(): Collection
    {
        return collect([
            [
                'area' => 'Reporting separation',
                'status' => QmsReport::exists() && QmsIncident::exists() ? 'Ready' : 'Needs records',
                'detail' => QmsReport::count().' reports and '.QmsIncident::count().' incidents with independent registers.',
            ],
            [
                'area' => 'Builder coverage',
                'status' => QmsFormDefinition::exists() && QmsWorkflowDefinition::exists() && QmsReportDesign::exists() && QmsNotificationDesign::exists() ? 'Ready' : 'Needs configuration',
                'detail' => 'Forms, workflows, report designer and notification designer are tracked as controlled definitions.',
            ],
            [
                'area' => 'User lookup governance',
                'status' => QmsDataSource::where('entity', 'users')->exists() ? 'Ready' : 'Needs data source',
                'detail' => 'Search-by-text selectors use governed data source definitions before external sync is enabled.',
            ],
            [
                'area' => 'Controlled AI',
                'status' => QmsAiProvider::where('is_enabled', true)->where('is_approved', true)->exists() ? 'Ready' : 'Blocked',
                'detail' => 'AI remains unavailable until a paid secured entity-trained provider is approved.',
            ],
            [
                'area' => 'Production operation',
                'status' => QmsSystemMonitor::whereIn('status', ['Ready', 'Active'])->exists() ? 'Ready' : 'Needs monitors',
                'detail' => QmsSystemMonitor::count().' monitors registered for queues, scheduler, exports and AI controls.',
            ],
        ]);
    }

    public function moduleCounts(): array
    {
        return [
            'Reports' => QmsReport::count(),
            'Incidents' => QmsIncident::count(),
            'Occurrences' => QmsOccurrence::count(),
            'Actions' => QmsAction::count(),
            'Investigations' => QmsInvestigation::count(),
            'Audits' => QmsAudit::count(),
            'Risks' => QmsRisk::count(),
            'Documents' => QmsDocument::count(),
            'Compliance' => QmsComplianceFramework::count(),
            'Objectives' => QmsObjective::count(),
            'Reviews' => QmsManagementReview::count(),
            'Training' => QmsTrainingRecord::count(),
            'Suppliers' => QmsSupplier::count(),
            'Public intake' => QmsPublicReport::count(),
            'Recommendations' => QmsRecommendation::count(),
            'Saved views' => QmsSavedView::count(),
        ];
    }

    public function evidence(): Collection
    {
        return collect([
            [
                'name' => 'Configuration packages',
                'records' => QmsConfigurationPackage::orderBy('code')->get(['code', 'name', 'status']),
            ],
            [
                'name' => 'Production monitors',
                'records' => QmsSystemMonitor::orderBy('code')->get(['code', 'name', 'status']),
            ],
            [
                'name' => 'Data sources',
                'records' => QmsDataSource::orderBy('code')->get(['code', 'name', 'status']),
            ],
            [
                'name' => 'AI providers',
                'records' => QmsAiProvider::orderBy('name')->get(['name as code', 'model_name as name', 'security_tier as status']),
            ],
        ]);
    }

    private function openWorkCount(): int
    {
        return QmsReport::whereNotIn('status', ['Accepted', 'Rejected', 'Closed'])->count()
            + QmsIncident::whereNotIn('status', ['Closed', 'Rejected'])->count()
            + QmsAction::whereNotIn('status', ['Closed', 'Verified'])->count()
            + QmsInvestigation::whereNotIn('status', ['Closed', 'Completed'])->count()
            + QmsPublicReport::whereNotIn('status', ['Closed', 'Rejected', 'Converted'])->count();
    }

    private function statusFromCounts(int ...$counts): string
    {
        return collect($counts)->filter()->isNotEmpty() ? 'Ready' : 'Needs setup';
    }
}
