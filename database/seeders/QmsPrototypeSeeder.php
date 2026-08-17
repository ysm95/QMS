<?php

namespace Database\Seeders;

use App\Models\QmsAction;
use App\Models\QmsAudit;
use App\Models\QmsDocument;
use App\Models\QmsInvestigation;
use App\Models\QmsOccurrence;
use App\Models\QmsRisk;
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
    }
}
