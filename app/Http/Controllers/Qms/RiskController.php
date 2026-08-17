<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsRisk;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsRisk::query()->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->string('rating'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('hazard', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%")
                    ->orWhere('controls', 'like', "%{$search}%");
            });
        }

        return view('qms.risks.index', [
            'risks' => $query->paginate(12)->withQueryString(),
            'ratings' => QmsRisk::select('rating')->distinct()->orderBy('rating')->pluck('rating'),
        ]);
    }
}
