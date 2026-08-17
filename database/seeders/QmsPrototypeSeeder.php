<?php

namespace Database\Seeders;

use App\Models\QmsAction;
use App\Models\QmsAiProvider;
use App\Models\QmsAudit;
use App\Models\QmsComplianceFramework;
use App\Models\QmsDocument;
use App\Models\QmsFormDefinition;
use App\Models\QmsInvestigation;
use App\Models\QmsManagementReview;
use App\Models\QmsNotification;
use App\Models\QmsObjective;
use App\Models\QmsOccurrence;
use App\Models\QmsRecordLink;
use App\Models\QmsRisk;
use App\Models\QmsSavedView;
use App\Models\QmsSupplier;
use App\Models\QmsTrainingRecord;
use App\Models\QmsWorkflowDefinition;
use App\Models\User;
use Illuminate\Database\Seeder;

class QmsPrototypeSeeder extends Seeder
{
    public function run(): void
    {
        QmsOccurrence::updateOrCreate(['reference' => 'QMS-2026-00435'], [
            'report_key' => 'ground-occurrence',
            'title' => 'Unsafe condition near scaffolding',
            'event_title' => 'Unsafe condition near scaffolding',
            'type' => 'Ground safety',
            'location' => 'OQB Locations',
            'area_fleet' => 'Engineering / Ground Operations',
            'exact_location' => 'CAE 135 equipment area',
            'reported_by' => 'Mazin Al Farsi',
            'description' => 'A rusted pipe was observed and there was no signage displayed in an area where scaffolding erection was in progress.',
            'status' => 'Submitted',
            'workflow_stage' => 'HSE Review',
            'risk_rating' => 'High',
            'confidential' => false,
            'mor' => false,
            'event_categories' => ['Compliance / Regulatory', 'Human Factors'],
            'aircraft_type' => null,
            'aircraft_registration' => null,
            'flight_number' => null,
            'flight_cancelled' => false,
            'personnel_involved' => ['staff_1' => 'Contractor employee'],
            'flight_plan_details' => null,
            'action_taken' => ['Informed supervisor'],
            'immediate_corrective_action' => 'Advised crew to display signage and barricade the area.',
            'feedback_to_reporter' => 'HSE review initiated.',
            'event_date' => now()->toDateString(),
            'reported_at' => now(),
        ]);

        QmsAction::updateOrCreate(['reference' => 'CAPA-2026-00077'], [
            'source_reference' => 'QMS-2026-00435',
            'title' => 'Revise barricade control checklist',
            'owner' => 'Engineering',
            'priority' => 'High',
            'status' => 'Open',
            'due_date' => now()->addDays(3)->toDateString(),
        ]);

        QmsAction::updateOrCreate(['reference' => 'ACT-2026-00118'], [
            'source_reference' => 'QMS-2026-00435',
            'title' => 'Brief contractors on signage requirements',
            'owner' => 'HSE',
            'priority' => 'Medium',
            'status' => 'In progress',
            'due_date' => now()->addDays(5)->toDateString(),
        ]);

        QmsInvestigation::updateOrCreate(['reference' => 'INV-2026-00012'], [
            'source_reference' => 'QMS-2026-00435',
            'title' => 'Scaffolding signage and barricade control review',
            'lead_investigator' => 'HSE Reviewer',
            'status' => 'Open',
            'scope' => 'Review worksite controls, contractor briefing, and supervisory verification.',
            'findings' => 'Preliminary finding: area control verification was not documented before work started.',
        ]);

        QmsAudit::updateOrCreate(['reference' => 'AUD-2026-00008'], [
            'title' => 'August internal QMS/SMS assurance audit',
            'standard' => 'ISO 9001 / SMS',
            'lead_auditor' => 'Quality Admin',
            'status' => 'Planned',
            'scheduled_date' => now()->addDays(14)->toDateString(),
        ]);

        QmsRisk::updateOrCreate(['reference' => 'RSK-2026-00031'], [
            'hazard' => 'Contractor work area not clearly segregated',
            'owner' => 'Engineering',
            'rating' => 'High',
            'controls' => 'Barricade, signage, toolbox talk, supervisor verification.',
            'review_date' => now()->addMonth()->toDateString(),
        ]);

        QmsDocument::updateOrCreate(['reference' => 'DOC-HSE-001'], [
            'title' => 'Contractor HSE Manual',
            'version' => 'v2.0',
            'owner' => 'HSE',
            'status' => 'Review',
            'review_date' => now()->addMonths(2)->toDateString(),
        ]);

        QmsObjective::updateOrCreate(['reference' => 'OBJ-2026-00001'], [
            'title' => 'Reduce overdue CAPA actions',
            'owner' => 'Quality',
            'measure' => 'Overdue actions at month end',
            'target' => '<= 2 overdue actions',
            'current_value' => '1 overdue action',
            'period' => 'Monthly',
            'status' => 'On track',
            'review_date' => now()->addMonth()->toDateString(),
        ]);

        QmsObjective::updateOrCreate(['reference' => 'SPI-2026-00002'], [
            'title' => 'Improve voluntary safety reporting',
            'owner' => 'Safety',
            'measure' => 'Voluntary reports per quarter',
            'target' => '>= 12 reports',
            'current_value' => '8 reports',
            'period' => 'Quarterly',
            'status' => 'Watch',
            'review_date' => now()->addWeeks(3)->toDateString(),
        ]);

        QmsManagementReview::updateOrCreate(['reference' => 'MR-2026-00001'], [
            'title' => 'Q3 QMS/SMS Management Review',
            'chair' => 'QMS Administrator',
            'meeting_date' => now()->addWeeks(4)->toDateString(),
            'status' => 'Planned',
            'inputs' => ['Audit results', 'CAPA performance', 'Risk register', 'Training status', 'Supplier performance'],
            'decisions' => 'Agenda prepared for leadership review.',
            'actions_summary' => 'Actions will be assigned after review.',
        ]);

        QmsTrainingRecord::updateOrCreate(['reference' => 'TRN-2026-00044'], [
            'person_name' => 'Omar Al Harthy',
            'course' => 'Contractor HSE Induction',
            'competency_area' => 'HSE awareness',
            'completed_on' => now()->subMonth()->toDateString(),
            'expires_on' => now()->addMonths(11)->toDateString(),
            'status' => 'Current',
        ]);

        QmsTrainingRecord::updateOrCreate(['reference' => 'TRN-2026-00045'], [
            'person_name' => 'Mazin Al Farsi',
            'course' => 'Internal Auditor Refresher',
            'competency_area' => 'Audit',
            'completed_on' => now()->subMonths(10)->toDateString(),
            'expires_on' => now()->addMonth()->toDateString(),
            'status' => 'Expiring soon',
        ]);

        QmsSupplier::updateOrCreate(['reference' => 'SUP-2026-00012'], [
            'name' => 'Training Engineering LLC',
            'category' => 'Contractor',
            'owner' => 'Engineering',
            'risk_rating' => 'High',
            'status' => 'Approved with controls',
            'next_review_date' => now()->addMonth()->toDateString(),
            'notes' => 'Supplier linked to contractor HSE and work-area control monitoring.',
        ]);

        QmsComplianceFramework::updateOrCreate(['code' => 'SMS-ICAO'], [
            'name' => 'ICAO Safety Management System',
            'owner' => 'Safety',
            'status' => 'Active',
            'requirements' => [
                'Safety policy and objectives',
                'Safety risk management',
                'Safety assurance',
                'Safety promotion',
            ],
        ]);

        QmsComplianceFramework::updateOrCreate(['code' => 'ISO-9001'], [
            'name' => 'ISO 9001 Quality Management',
            'owner' => 'Quality',
            'status' => 'Active',
            'requirements' => [
                'Context and interested parties',
                'Leadership and accountability',
                'Operational control',
                'Performance evaluation',
                'Improvement and corrective action',
            ],
        ]);

        $occurrence = QmsOccurrence::where('reference', 'QMS-2026-00435')->first();
        $action = QmsAction::where('reference', 'CAPA-2026-00077')->first();
        $risk = QmsRisk::where('reference', 'RSK-2026-00031')->first();

        if ($occurrence && $action) {
            QmsRecordLink::updateOrCreate([
                'source_type' => QmsOccurrence::class,
                'source_id' => $occurrence->id,
                'target_type' => QmsAction::class,
                'target_id' => $action->id,
            ], [
                'relationship' => 'Generated CAPA',
                'source_reference' => $occurrence->reference,
                'target_reference' => $action->reference,
            ]);
        }

        if ($occurrence && $risk) {
            QmsRecordLink::updateOrCreate([
                'source_type' => QmsOccurrence::class,
                'source_id' => $occurrence->id,
                'target_type' => QmsRisk::class,
                'target_id' => $risk->id,
            ], [
                'relationship' => 'Risk signal',
                'source_reference' => $occurrence->reference,
                'target_reference' => $risk->reference,
            ]);
        }

        $admin = User::where('email', 'admin@qms.test')->first();
        QmsNotification::updateOrCreate([
            'title' => 'HSE review required',
            'source_reference' => 'QMS-2026-00435',
        ], [
            'user_id' => $admin?->id,
            'body' => 'Ground safety occurrence is waiting for screening and assignment.',
            'read_at' => null,
        ]);

        QmsNotification::updateOrCreate([
            'title' => 'Document review due',
            'source_reference' => 'DOC-HSE-001',
        ], [
            'user_id' => $admin?->id,
            'body' => 'Contractor HSE Manual is in review status.',
            'read_at' => null,
        ]);

        QmsAiProvider::updateOrCreate(['name' => 'Entity Secure AI - Pending Approval'], [
            'provider_type' => 'Paid secured enterprise API',
            'model_name' => 'Entity-trained controlled model',
            'training_scope' => 'Entity-trained approved QMS/SMS knowledge only',
            'security_tier' => 'Paid secured enterprise',
            'data_residency' => 'Contract-controlled hosting region',
            'is_approved' => false,
            'is_enabled' => false,
            'governance_notes' => 'AI remains blocked until legal, IT security, DPA, paid provider, and entity training controls are approved.',
        ]);

        QmsFormDefinition::updateOrCreate(['code' => 'FORM-DOR-001'], [
            'name' => 'Dispatch Occurrence Report',
            'version' => 1,
            'module' => 'Occurrence',
            'status' => 'Published',
            'schema' => [
                'required' => ['title', 'reported_by', 'event_date', 'location', 'description'],
                'sections' => ['Header', 'Commander voyage details', 'Aircraft and flight details', 'Action taken'],
                'conditional' => ['flight_fields_when' => 'aviation report type'],
            ],
            'change_note' => 'Initial BRSD/DOR-aligned controlled form definition.',
        ]);

        QmsFormDefinition::updateOrCreate(['code' => 'FORM-PUBLIC-001'], [
            'name' => 'Public Safety Reporting Intake',
            'version' => 1,
            'module' => 'Public Portal',
            'status' => 'Published',
            'schema' => [
                'required' => ['category', 'description'],
                'supports' => ['anonymous', 'confidential'],
            ],
            'change_note' => 'Public voluntary/confidential intake form.',
        ]);

        QmsWorkflowDefinition::updateOrCreate(['code' => 'WF-OCC-001'], [
            'name' => 'Occurrence to CAPA Closure',
            'version' => 1,
            'module' => 'Occurrence',
            'status' => 'Published',
            'stages' => ['Submitted', 'HSE Review', 'Investigation', 'CAPA', 'Verification', 'Closed'],
            'rules' => [
                'high_risk_requires_investigation' => true,
                'closure_requires_action_verification' => true,
                'confidential_identity_restricted' => true,
            ],
            'change_note' => 'Core SMS/QMS occurrence workflow.',
        ]);

        QmsWorkflowDefinition::updateOrCreate(['code' => 'WF-DOC-001'], [
            'name' => 'Controlled Document Lifecycle',
            'version' => 1,
            'module' => 'Documents',
            'status' => 'Published',
            'stages' => ['Draft', 'Review', 'Approved', 'Published', 'Archived'],
            'rules' => ['published_requires_version' => true, 'review_date_required' => true],
            'change_note' => 'Documented information lifecycle.',
        ]);

        QmsSavedView::updateOrCreate(['name' => 'Executive high-risk watch', 'module' => 'Intelligence'], [
            'owner' => 'QMS Administrator',
            'filters' => ['risk' => ['High', 'Critical'], 'status' => ['Open', 'In progress']],
            'shared' => true,
        ]);
    }
}
