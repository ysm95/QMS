<?php

namespace Database\Seeders;

use App\Models\QmsAction;
use App\Models\QmsAccessScope;
use App\Models\QmsAttachment;
use App\Models\QmsAiProvider;
use App\Models\QmsAudit;
use App\Models\QmsComplianceFramework;
use App\Models\QmsConfigurationPackage;
use App\Models\QmsDocument;
use App\Models\QmsEmailDesign;
use App\Models\QmsElectronicSignature;
use App\Models\QmsFormDefinition;
use App\Models\QmsInvestigation;
use App\Models\QmsIntegrationEvent;
use App\Models\QmsKeyUserAssignment;
use App\Models\QmsManagementReview;
use App\Models\QmsModuleLicense;
use App\Models\QmsNotification;
use App\Models\QmsNotificationDesign;
use App\Models\QmsNotificationGroup;
use App\Models\QmsNotificationRule;
use App\Models\QmsNotificationTemplate;
use App\Models\QmsNumberingRule;
use App\Models\QmsObjective;
use App\Models\QmsOccurrence;
use App\Models\QmsPermissionTemplate;
use App\Models\QmsRecordLink;
use App\Models\QmsRecommendation;
use App\Models\QmsRetentionRule;
use App\Models\QmsReportDesign;
use App\Models\QmsRisk;
use App\Models\QmsSavedView;
use App\Models\QmsSupplier;
use App\Models\QmsSystemSetting;
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
            'description' => 'Review barricade checklist and update contractor controls for scaffolding zones.',
            'required_outcome' => 'Updated checklist issued and briefed to affected teams.',
            'owner' => 'Engineering',
            'responsible_department' => 'Engineering',
            'priority' => 'High',
            'risk_relevance' => 'High',
            'evidence_required' => true,
            'status' => 'Open',
            'progress' => 20,
            'due_date' => now()->addDays(3)->toDateString(),
            'assigned_at' => now()->subDay(),
            'notified_at' => now()->subDay(),
        ]);

        QmsAction::updateOrCreate(['reference' => 'ACT-2026-00118'], [
            'source_reference' => 'QMS-2026-00435',
            'title' => 'Brief contractors on signage requirements',
            'description' => 'Conduct toolbox briefing for signage and barricade requirements.',
            'required_outcome' => 'Attendance evidence and briefing material attached.',
            'owner' => 'HSE',
            'responsible_department' => 'HSE',
            'priority' => 'Medium',
            'risk_relevance' => 'Medium',
            'evidence_required' => true,
            'status' => 'In progress',
            'progress' => 50,
            'due_date' => now()->addDays(5)->toDateString(),
            'assigned_at' => now()->subDay(),
            'notified_at' => now()->subDay(),
            'accepted_at' => now()->subHours(18),
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

        QmsReportDesign::updateOrCreate(['code' => 'RPT-OCC-001'], [
            'name' => 'Occurrence Register and Risk Summary',
            'version' => 1,
            'module' => 'Occurrences',
            'status' => 'Published',
            'layout' => [
                'sections' => ['Header', 'Filters', 'Occurrence register', 'Risk summary', 'CAPA summary', 'Audit evidence'],
                'columns' => ['Reference', 'Title', 'Type', 'Stage', 'Risk', 'Reported By', 'Owner', 'Due Date'],
                'grouping' => ['Workflow stage', 'Risk rating', 'Department'],
                'confidentiality' => 'Mask anonymous and confidential reporter identity unless authorized.',
            ],
            'data_sources' => ['qms_occurrences', 'qms_actions', 'qms_risks', 'qms_audit_logs'],
            'output_formats' => ['Screen', 'CSV', 'PDF', 'Excel'],
            'change_note' => 'Default production occurrence register design.',
        ]);

        QmsReportDesign::updateOrCreate(['code' => 'RPT-CAPA-001'], [
            'name' => 'CAPA Effectiveness and Overdue Report',
            'version' => 1,
            'module' => 'Actions',
            'status' => 'Published',
            'layout' => [
                'sections' => ['Header', 'Overdue actions', 'Verification queue', 'Effectiveness review', 'Closure evidence'],
                'columns' => ['Reference', 'Source', 'Action', 'Owner', 'Priority', 'Status', 'Due Date'],
                'grouping' => ['Owner', 'Priority', 'Status'],
                'confidentiality' => 'Show source references only to authorized action owners.',
            ],
            'data_sources' => ['qms_actions', 'qms_occurrences', 'qms_record_notes'],
            'output_formats' => ['Screen', 'CSV', 'PDF'],
            'change_note' => 'Default production CAPA design.',
        ]);

        QmsNotificationDesign::updateOrCreate(['code' => 'MSG-OCC-001'], [
            'name' => 'Occurrence Submitted',
            'version' => 1,
            'module' => 'Occurrences',
            'event_trigger' => 'occurrence.submitted',
            'status' => 'Published',
            'recipients' => [
                'to' => ['HSE Reviewer', 'Occurrence Owner'],
                'cc' => ['Reporter', 'Department Manager'],
            ],
            'conditions' => [
                'rules' => ['status:Submitted', 'risk:any'],
                'restricted_identity' => 'Respect anonymous/confidential flags.',
            ],
            'subject_template' => '[{{reference}}] {{title}} requires QMS review',
            'body_template' => 'Record {{reference}} is waiting for {{stage}}. Review location, risk, evidence, and immediate action.',
            'change_note' => 'Default occurrence submitted message.',
        ]);

        QmsNotificationDesign::updateOrCreate(['code' => 'MSG-CAPA-001'], [
            'name' => 'CAPA Due or Overdue',
            'version' => 1,
            'module' => 'Actions',
            'event_trigger' => 'action.due',
            'status' => 'Published',
            'recipients' => [
                'to' => ['Action Owner'],
                'cc' => ['QMS Manager', 'Source Record Owner'],
            ],
            'conditions' => [
                'rules' => ['status:not Closed', 'due_date:within 3 days or overdue'],
                'restricted_identity' => 'Respect confidential source records.',
            ],
            'subject_template' => '[{{reference}}] CAPA action requires attention',
            'body_template' => 'Action {{reference}} is {{status}} and due on {{due_date}}. Update progress, evidence, or verification.',
            'change_note' => 'Default CAPA reminder and escalation message.',
        ]);

        $emailDesign = QmsEmailDesign::updateOrCreate(['code' => 'EMAIL-OCC-001'], [
            'name' => 'QMS Record Action Email Layout',
            'version' => 1,
            'status' => 'Published',
            'builder_schema' => [
                'components' => ['Logo', 'Heading', 'Record information', 'Action button', 'Footer'],
                'editor' => 'Approved visual builder adapter pending procurement.',
                'layout' => 'Responsive single-column transactional email.',
            ],
            'html_snapshot' => null,
            'variables' => ['user.name', 'record.reference', 'record.title', 'record.status', 'url.view_record'],
            'change_note' => 'Default portable email layout foundation.',
        ]);

        $template = QmsNotificationTemplate::updateOrCreate(['code' => 'NTF-OCC-001'], [
            'name' => 'Occurrence Requires Review',
            'version' => 1,
            'module' => 'Occurrences',
            'status' => 'Published',
            'email_design_id' => $emailDesign->id,
            'subject_template' => '[{{record.reference}}] {{record.title}} requires review',
            'body_template' => 'Hello {{user.name}}, record {{record.reference}} is at {{record.status}} and requires your review. Open {{url.view_record}}.',
            'allowed_variables' => ['user.name', 'record.reference', 'record.title', 'record.status', 'url.view_record'],
            'change_note' => 'Default separated notification content template.',
        ]);

        $group = QmsNotificationGroup::updateOrCreate(['code' => 'NG-SAFETY-KEY-USERS'], [
            'name' => 'Safety Key Users',
            'owner' => 'Safety Manager',
            'status' => 'Active',
            'description' => 'Scoped safety reviewers and escalation recipients.',
        ]);

        $group->members()->updateOrCreate([
            'member_type' => 'role',
            'member_reference' => 'Safety Admin',
        ], [
            'display_name' => 'Safety Admin role',
        ]);

        QmsNotificationRule::updateOrCreate(['code' => 'RULE-OCC-MAJOR-001'], [
            'name' => 'Major occurrence review escalation',
            'module' => 'Occurrences',
            'event_trigger' => 'occurrence.accepted',
            'status' => 'Published',
            'notification_template_id' => $template->id,
            'conditions' => ['all' => ['risk_rating:High or Critical', 'status:Accepted']],
            'recipients' => ['targets' => ['Safety Key Users', 'Department Manager', 'Occurrence Owner']],
            'channels' => ['In-App', 'Email'],
            'timing' => ['schedule' => 'Immediately; +3 days if not reviewed; +7 days manager escalation'],
            'change_note' => 'Default rule builder output for high-risk occurrence acceptance.',
        ]);

        QmsPermissionTemplate::updateOrCreate(['code' => 'PERM-SAFETY-KEY-USER'], [
            'name' => 'Safety Key User',
            'status' => 'Active',
            'permissions' => ['occurrences.view.department', 'occurrences.review', 'recommendations.create', 'actions.assign', 'actions.escalate'],
            'default_scopes' => ['DEPARTMENT', 'ASSIGNED'],
            'description' => 'Can monitor and process incidents within assigned safety scope without global administration access.',
        ]);

        QmsAccessScope::updateOrCreate([
            'principal_type' => 'role',
            'principal_reference' => 'Safety Admin',
            'module' => 'Occurrences',
            'scope_type' => 'ALL',
        ], [
            'scope_value' => 'Safety',
            'status' => 'Active',
        ]);

        $yahya = User::where('email', 'yahya.alnaaimi@qms.test')->first();
        if ($yahya) {
            QmsKeyUserAssignment::updateOrCreate([
                'user_id' => $yahya->id,
                'module' => 'Occurrences',
                'scope_type' => 'DEPARTMENT',
                'scope_value' => 'Safety',
            ], [
                'capabilities' => ['monitor', 'review', 'recommend', 'assign_actions', 'escalate'],
                'effective_from' => now()->toDateString(),
                'effective_until' => null,
                'status' => 'Active',
            ]);
        }

        QmsRecommendation::updateOrCreate(['reference' => 'REC-2026-00021'], [
            'source_reference' => 'QMS-2026-00435',
            'investigation_reference' => 'INV-2026-00012',
            'finding' => 'Scaffolding area lacked consistent barricade and signage controls.',
            'root_cause' => 'Contractor access control and pre-task verification were not consistently applied.',
            'recommendation' => 'Introduce mandatory pre-task signage verification for temporary work zones.',
            'rationale' => 'A formal verification step reduces recurrence and improves supervisor accountability.',
            'priority' => 'High',
            'safety_relevance' => 'Ground Safety',
            'owner' => 'HSE',
            'status' => 'Review',
            'approval_decision' => 'Pending',
        ]);

        QmsSystemSetting::updateOrCreate(['key' => 'brand.primary'], [
            'group' => 'Branding',
            'value' => ['organization' => 'QMS.ysaidea.com', 'system' => 'Enterprise Quality Management System', 'primary_color' => '#0867a8'],
            'is_sensitive' => false,
            'status' => 'Active',
            'change_note' => 'Default branding control center setting.',
        ]);

        foreach ([
            ['QMS-CORE', 'QMS Core'],
            ['SMS', 'Safety Management System'],
            ['HSE', 'Health, Safety and Environment'],
            ['RISK', 'Enterprise Risk Management'],
            ['AUDIT', 'Audit Management'],
            ['AI', 'Controlled AI'],
        ] as [$code, $name]) {
            QmsModuleLicense::updateOrCreate(['code' => $code], [
                'name' => $name,
                'enabled' => $code !== 'AI',
                'status' => $code === 'AI' ? 'Pending Approval' : 'Active',
                'expires_on' => now()->addYear()->toDateString(),
                'limits' => ['users' => 250, 'storage_gb' => 100],
                'notes' => $code === 'AI' ? 'AI is disabled until provider governance is approved.' : 'Initial production module license.',
            ]);
        }

        foreach ([
            ['NUM-INC', 'Incidents', 'INC'],
            ['NUM-NCR', 'Non-Conformance', 'NCR'],
            ['NUM-AUD', 'Audits', 'AUD'],
            ['NUM-ACT', 'Actions', 'ACT'],
        ] as [$code, $module, $prefix]) {
            QmsNumberingRule::updateOrCreate(['code' => $code], [
                'module' => $module,
                'prefix' => $prefix,
                'pattern' => '{PREFIX}-{YYYY}-{SEQ:6}',
                'next_sequence' => 1,
                'reset_annually' => true,
                'status' => 'Active',
            ]);
        }

        QmsConfigurationPackage::updateOrCreate(['code' => 'CFG-BASELINE-001'], [
            'name' => 'Production baseline configuration',
            'version' => 1,
            'status' => 'Validated',
            'payload' => ['includes' => ['forms', 'workflows', 'notifications', 'numbering', 'permission templates']],
            'effective_date' => now()->toDateString(),
            'validation_summary' => 'Initial baseline dependency checks completed for prototype production foundation.',
        ]);

        QmsRetentionRule::updateOrCreate(['code' => 'RET-SAFETY-STD'], [
            'module' => 'Occurrences',
            'classification' => 'Safety Record',
            'retention_years' => 10,
            'legal_hold_allowed' => true,
            'disposition' => 'Archive',
            'status' => 'Active',
        ]);

        QmsAttachment::updateOrCreate(['record_reference' => 'QMS-2026-00435', 'original_name' => 'evidence-placeholder.txt'], [
            'record_type' => QmsOccurrence::class,
            'record_id' => QmsOccurrence::where('reference', 'QMS-2026-00435')->value('id'),
            'uploaded_by' => $admin?->id,
            'stored_path' => 'secure-evidence/evidence-placeholder.txt',
            'mime_type' => 'text/plain',
            'size_bytes' => 0,
            'content_hash' => hash('sha256', 'placeholder'),
            'classification' => 'Internal',
            'scan_status' => 'Pending',
            'quarantined' => false,
            'metadata' => ['note' => 'Metadata-only secure attachment foundation.'],
        ]);

        QmsElectronicSignature::updateOrCreate(['record_reference' => 'DOC-HSE-001', 'meaning' => 'Document review acknowledgement'], [
            'record_type' => QmsDocument::class,
            'record_id' => QmsDocument::where('reference', 'DOC-HSE-001')->value('id'),
            'user_id' => $admin?->id,
            'signer_name' => $admin?->name ?? 'QMS Administrator',
            'record_version' => '1.4',
            'snapshot_hash' => hash('sha256', 'DOC-HSE-001-v1.4'),
            'auth_context' => ['method' => 'session', 'reauth_required' => false],
            'reason' => 'Seeded signature architecture example.',
            'signed_at' => now(),
        ]);

        QmsIntegrationEvent::updateOrCreate(['idempotency_key' => 'baseline-qms-config-published'], [
            'correlation_id' => (string) \Illuminate\Support\Str::uuid(),
            'event_type' => 'configuration.published',
            'source_module' => 'Administration',
            'status' => 'Pending',
            'payload' => ['package' => 'CFG-BASELINE-001'],
            'attempts' => 0,
            'available_at' => now(),
        ]);

        QmsSavedView::updateOrCreate(['name' => 'Executive high-risk watch', 'module' => 'Intelligence'], [
            'owner' => 'QMS Administrator',
            'filters' => ['risk' => ['High', 'Critical'], 'status' => ['Open', 'In progress']],
            'shared' => true,
        ]);
    }
}
