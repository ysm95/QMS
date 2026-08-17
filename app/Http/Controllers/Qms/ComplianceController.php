<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsComplianceFramework;
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
        ]);
    }
}
