<?php

namespace App\Support;

use App\Models\QmsAction;
use App\Models\QmsIncident;
use App\Models\QmsReport;
use App\Models\QmsRecordLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QmsReportWorkflow
{
    public static function submit(Request $request, array $data): QmsReport
    {
        return DB::transaction(function () use ($request, $data) {
            $reference = QmsNumbering::next('NUM-REP', 'Reporting', 'REP');

            $report = QmsReport::create([
                'reference' => $reference,
                'report_key' => $data['report_key'] ?? null,
                'title' => $data['event_title'] ?: str($data['description'])->limit(80)->toString(),
                'type' => $data['type'],
                'category' => $data['type'],
                'classification' => $data['mor'] ?? false ? 'Mandatory' : 'Voluntary',
                'severity' => 'Medium',
                'status' => 'Submitted',
                'workflow_stage' => 'Screening',
                'location' => $data['location'] ?? null,
                'department' => $data['area_fleet'] ?? null,
                'reported_by' => $data['reported_by'] ?? $request->user()?->name,
                'reporter_user_id' => $request->user()?->id,
                'anonymous' => false,
                'confidential' => (bool) ($data['confidential'] ?? false),
                'mandatory' => (bool) ($data['mor'] ?? false),
                'description' => $data['description'],
                'payload' => $data,
                'reported_at' => now(),
                'submitted_at' => now(),
            ]);

            QmsAction::create([
                'reference' => QmsNumbering::next('NUM-ACT', 'Actions', 'ACT'),
                'source_reference' => $report->reference,
                'title' => 'Screen report ' . $report->reference,
                'owner' => 'Screening Team',
                'priority' => 'High',
                'status' => 'Open',
                'due_date' => now()->addDays(2)->toDateString(),
            ]);

            QmsAuditTrail::record($request, $report, 'report_submitted', [], [
                'status' => $report->status,
                'workflow_stage' => $report->workflow_stage,
            ], 'Report submitted into Reporting screening.');

            return $report;
        });
    }

    public static function accept(Request $request, QmsReport $report, array $data): QmsIncident
    {
        return DB::transaction(function () use ($request, $report, $data) {
            $lockedReport = QmsReport::query()->whereKey($report->id)->lockForUpdate()->firstOrFail();

            if ($lockedReport->incident) {
                return $lockedReport->incident;
            }

            abort_if(in_array($lockedReport->status, ['Rejected', 'Closed'], true), 422, 'This report cannot be accepted from its current state.');

            $incident = QmsIncident::create([
                'reference' => QmsNumbering::next('NUM-INC', 'Incidents', 'INC'),
                'source_report_id' => $lockedReport->id,
                'source_report_reference' => $lockedReport->reference,
                'title' => $lockedReport->title,
                'type' => $lockedReport->type,
                'classification' => $data['classification'] ?? $lockedReport->classification,
                'severity' => $data['severity'],
                'status' => 'Open',
                'workflow_stage' => 'Classification',
                'owner' => $data['owner'] ?? 'Safety Manager',
                'department' => $data['department'] ?? $lockedReport->department,
                'location' => $lockedReport->location,
                'investigation_required' => (bool) ($data['investigation_required'] ?? false),
                'closure_blocked' => true,
                'source_snapshot' => [
                    'report_reference' => $lockedReport->reference,
                    'reported_by' => $lockedReport->reported_by,
                    'confidential' => $lockedReport->confidential,
                    'mandatory' => $lockedReport->mandatory,
                    'description' => $lockedReport->description,
                    'payload' => $lockedReport->payload,
                ],
                'accepted_at' => now(),
                'accepted_by' => $request->user()?->id,
            ]);

            QmsRecordLink::updateOrCreate([
                'source_type' => QmsReport::class,
                'source_id' => $lockedReport->id,
                'target_type' => QmsIncident::class,
                'target_id' => $incident->id,
            ], [
                'relationship' => 'Accepted as incident',
                'source_reference' => $lockedReport->reference,
                'target_reference' => $incident->reference,
            ]);

            $oldValues = $lockedReport->only(['status', 'workflow_stage']);
            $lockedReport->update([
                'status' => 'Accepted',
                'workflow_stage' => 'Accepted',
                'screened_at' => now(),
                'screened_by' => $request->user()?->id,
                'screening_notes' => $data['screening_notes'] ?? null,
            ]);

            QmsAuditTrail::record($request, $lockedReport, 'report_accepted', $oldValues, [
                'status' => 'Accepted',
                'incident_reference' => $incident->reference,
            ], 'Report accepted and incident created transactionally.');

            QmsAuditTrail::record($request, $incident, 'incident_created_from_report', [], [
                'source_report_reference' => $lockedReport->reference,
                'workflow_stage' => $incident->workflow_stage,
            ], 'Incident created from accepted report.');

            return $incident;
        });
    }

    public static function reject(Request $request, QmsReport $report, array $data): QmsReport
    {
        return DB::transaction(function () use ($request, $report, $data) {
            $lockedReport = QmsReport::query()->whereKey($report->id)->lockForUpdate()->firstOrFail();

            abort_if($lockedReport->incident()->exists(), 422, 'Accepted reports with incidents cannot be rejected.');

            $oldValues = $lockedReport->only(['status', 'workflow_stage']);
            $lockedReport->update([
                'status' => 'Rejected',
                'workflow_stage' => 'Rejected',
                'screened_at' => now(),
                'screened_by' => $request->user()?->id,
                'rejection_reason' => $data['rejection_reason'],
                'screening_notes' => $data['screening_notes'] ?? null,
            ]);

            QmsAuditTrail::record($request, $lockedReport, 'report_rejected', $oldValues, [
                'status' => 'Rejected',
                'rejection_reason' => $lockedReport->rejection_reason,
            ], 'Report rejected without incident creation.');

            return $lockedReport;
        });
    }
}
