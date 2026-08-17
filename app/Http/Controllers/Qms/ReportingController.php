<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;

class ReportingController extends Controller
{
    public static function reportTypes(): array
    {
        return [
            'commander-voyage' => [
                'title' => 'Commander Voyage Report',
                'type' => 'Commander voyage',
                'module' => 'SMS',
                'description' => 'Operational voyage report for commander observations, decisions, and safety notes.',
                'priority' => 'Operational',
            ],
            'crew-safety' => [
                'title' => 'Crew Safety Report',
                'type' => 'Crew safety',
                'module' => 'SMS',
                'description' => 'Cabin, crew, human factors, fatigue, and onboard safety reporting.',
                'priority' => 'Safety',
            ],
            'ground-occurrence' => [
                'title' => 'Ground Occurrence Report',
                'type' => 'Ground safety',
                'module' => 'HSE',
                'description' => 'Ramp, station, maintenance, contractor, equipment, and ground operation events.',
                'priority' => 'Safety',
            ],
            'abnormal-occurrence' => [
                'title' => 'Abnormal Occurrence Report',
                'type' => 'Abnormal occurrence',
                'module' => 'SMS',
                'description' => 'Irregular, abnormal, or non-routine operational event requiring review.',
                'priority' => 'Assurance',
            ],
            'air-safety' => [
                'title' => 'Air Safety Report',
                'type' => 'Flight safety',
                'module' => 'SMS',
                'description' => 'Flight safety event with aircraft, sector, crew, and operational-risk details.',
                'priority' => 'Safety',
            ],
            'dangerous-goods' => [
                'title' => 'Dangerous Goods Occurrence Report',
                'type' => 'Dangerous goods',
                'module' => 'Compliance',
                'description' => 'Dangerous goods event, documentation concern, undeclared item, spill, or handling issue.',
                'priority' => 'Compliance',
            ],
            'dispatch-occurrence' => [
                'title' => 'Dispatch Occurrence Report',
                'type' => 'Dispatch occurrence',
                'module' => 'SMS',
                'description' => 'DOR-style report for dispatch, flight planning, fuel, weather, ATC, communications, or performance events.',
                'priority' => 'Operational',
            ],
            'fatigue' => [
                'title' => 'Fatigue Reporting Form',
                'type' => 'Fatigue',
                'module' => 'SMS',
                'description' => 'Fatigue concern, roster impact, human performance limitation, or fatigue-risk signal.',
                'priority' => 'Human factors',
            ],
            'hazard' => [
                'title' => 'Hazard Reporting Form',
                'type' => 'Hazard',
                'module' => 'Risk',
                'description' => 'Hazard identification and risk-control request before an incident occurs.',
                'priority' => 'Risk',
            ],
            'confidential-safety' => [
                'title' => 'Safety Confidential Report',
                'type' => 'Confidential safety',
                'module' => 'SMS',
                'description' => 'Protected safety concern where reporter identity requires restricted handling.',
                'priority' => 'Protected',
                'confidential' => true,
            ],
        ];
    }

    public function index()
    {
        return view('qms.reporting.index', [
            'reportTypes' => self::reportTypes(),
        ]);
    }

    public function create(string $reportType)
    {
        abort_unless(array_key_exists($reportType, self::reportTypes()), 404);

        return redirect()->route('occurrences.create', ['report_type' => $reportType]);
    }
}
