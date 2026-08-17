<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAudit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsAudit::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('standard', 'like', "%{$search}%")
                    ->orWhere('lead_auditor', 'like', "%{$search}%");
            });
        }

        return view('qms.audits.index', [
            'audits' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsAudit::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
