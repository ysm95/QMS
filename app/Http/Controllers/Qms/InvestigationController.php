<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAuditLog;
use App\Models\QmsInvestigation;
use App\Models\QmsRecordNote;
use App\Support\QmsAuditTrail;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    public function index()
    {
        return view('qms.investigations.index', [
            'investigations' => QmsInvestigation::latest()->paginate(12),
        ]);
    }

    public function show(QmsInvestigation $investigation)
    {
        return view('qms.investigations.show', [
            'investigation' => $investigation,
            'notes' => QmsRecordNote::where('record_type', QmsInvestigation::class)->where('record_id', $investigation->id)->latest()->get(),
            'auditLogs' => QmsAuditLog::where('auditable_type', QmsInvestigation::class)->where('auditable_id', $investigation->id)->latest()->get(),
        ]);
    }

    public function update(Request $request, QmsInvestigation $investigation)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:80'],
            'scope' => ['nullable', 'string', 'max:5000'],
            'findings' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldValues = $investigation->only(['status', 'scope', 'findings']);
        $investigation->update($data);

        QmsAuditTrail::record($request, $investigation, 'investigation_updated', $oldValues, $data, 'Investigation workspace updated.');

        return back()->with('status', 'Investigation updated.');
    }

    public function storeNote(Request $request, QmsInvestigation $investigation)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:3000'],
            'visibility' => ['required', 'string', 'max:80'],
        ]);

        QmsRecordNote::create([
            'record_type' => QmsInvestigation::class,
            'record_id' => $investigation->id,
            'reference' => $investigation->reference,
            'user_id' => $request->user()?->id,
            'author' => $request->user()?->name ?? 'System',
            'visibility' => $data['visibility'],
            'body' => $data['body'],
        ]);

        QmsAuditTrail::record($request, $investigation, 'note_added', [], ['visibility' => $data['visibility']], 'Investigation note added.');

        return back()->with('status', 'Note added.');
    }
}
