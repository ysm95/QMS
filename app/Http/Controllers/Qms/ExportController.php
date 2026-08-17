<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAuditLog;
use App\Models\QmsDocument;
use App\Models\QmsOccurrence;
use App\Models\QmsRisk;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function occurrences(): StreamedResponse
    {
        return $this->csv('qms-occurrences.csv', ['Reference', 'Title', 'Type', 'Stage', 'Risk', 'Reporter'], QmsOccurrence::latest()->get()->map(fn ($record) => [
            $record->reference, $record->title, $record->type, $record->workflow_stage, $record->risk_rating, $record->reported_by,
        ]));
    }

    public function actions(): StreamedResponse
    {
        return $this->csv('qms-actions.csv', ['Reference', 'Source', 'Title', 'Owner', 'Priority', 'Status', 'Due'], QmsAction::latest()->get()->map(fn ($record) => [
            $record->reference, $record->source_reference, $record->title, $record->owner, $record->priority, $record->status, optional($record->due_date)->format('Y-m-d'),
        ]));
    }

    public function risks(): StreamedResponse
    {
        return $this->csv('qms-risks.csv', ['Reference', 'Hazard', 'Owner', 'Rating', 'Controls', 'Review'], QmsRisk::latest()->get()->map(fn ($record) => [
            $record->reference, $record->hazard, $record->owner, $record->rating, $record->controls, optional($record->review_date)->format('Y-m-d'),
        ]));
    }

    public function documents(): StreamedResponse
    {
        return $this->csv('qms-documents.csv', ['Reference', 'Title', 'Version', 'Owner', 'Status', 'Review'], QmsDocument::latest()->get()->map(fn ($record) => [
            $record->reference, $record->title, $record->version, $record->owner, $record->status, optional($record->review_date)->format('Y-m-d'),
        ]));
    }

    public function auditTrail(): StreamedResponse
    {
        return $this->csv('qms-audit-trail.csv', ['Reference', 'Action', 'Actor', 'Note', 'Date'], QmsAuditLog::latest()->get()->map(fn ($record) => [
            $record->reference, $record->action, $record->actor, $record->note, $record->created_at->format('Y-m-d H:i'),
        ]));
    }

    private function csv(string $filename, array $headers, $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
