<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsNonconformance;
use Illuminate\Http\Request;

class NonconformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsNonconformance::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('requirement_reference', 'like', "%{$search}%")
                    ->orWhere('nonconformity_statement', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%");
            });
        }

        return view('qms.nonconformances.index', [
            'nonconformances' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsNonconformance::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
