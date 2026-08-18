<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Support\QmsAuditTrail;
use App\Support\QmsNotify;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsAction::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('source_reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%")
                    ->orWhere('evidence', 'like', "%{$search}%");
            });
        }

        return view('qms.actions.index', [
            'actions' => $query->paginate(14)->withQueryString(),
            'statuses' => QmsAction::select('status')->distinct()->orderBy('status')->pluck('status'),
            'priorities' => QmsAction::select('priority')->distinct()->orderBy('priority')->pluck('priority'),
        ]);
    }

    public function update(Request $request, QmsAction $action)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:80'],
            'evidence' => ['nullable', 'string', 'max:3000'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'verification_note' => ['nullable', 'string', 'max:3000'],
            'effectiveness_review' => ['nullable', 'string', 'max:3000'],
        ]);

        $oldValues = $action->only(['status', 'evidence', 'progress', 'verification_note', 'effectiveness_review']);
        $data['progress'] = $data['progress'] ?? $action->progress;

        if ($data['status'] === 'Accepted' && ! $action->accepted_at) {
            $data['accepted_at'] = now();
        }
        if ($data['status'] === 'Evidence Submitted' && ! $action->completed_at) {
            $data['completed_at'] = now();
        }
        if (in_array($data['status'], ['Verification', 'Verified'], true) && ! $action->verified_at) {
            $data['verified_at'] = now();
        }
        if (in_array($data['status'], ['Closed', 'Verified'], true) && ! $action->closed_at) {
            $data['closed_at'] = now();
        }

        $action->update($data);

        QmsAuditTrail::record($request, $action, 'action_updated', $oldValues, $data, 'CAPA action status or evidence updated.');

        if (in_array($action->status, ['Verification', 'Closed', 'Verified'], true)) {
            QmsNotify::everyone('CAPA action updated', $action->reference . ' moved to ' . $action->status . '.', $action->reference);
        }

        return back()->with('status', 'Action updated.');
    }
}
