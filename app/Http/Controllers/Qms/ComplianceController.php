<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsComplianceChange;
use App\Models\QmsComplianceFramework;
use App\Models\QmsStandard;
use App\Models\QmsStandardRequirement;
use App\Models\QmsTaxonomyTerm;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsComplianceFramework::query()->orderBy('code');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%");
            });
        }

        return view('qms.compliance.index', [
            'frameworks' => $query->paginate(12)->withQueryString(),
            'standards' => QmsStandard::orderBy('issuer')->orderBy('code')->get(),
            'requirements' => QmsStandardRequirement::latest()->limit(10)->get(),
            'changes' => QmsComplianceChange::latest()->limit(8)->get(),
            'taxonomies' => QmsTaxonomyTerm::where('active', true)
                ->orderBy('taxonomy')
                ->orderBy('code')
                ->limit(14)
                ->get(),
        ]);
    }
}
