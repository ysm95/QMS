<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsSafetyPromotion;
use Illuminate\Http\Request;

class SafetyPromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsSafetyPromotion::query()->latest();

        if ($request->filled('status')) {
            $query->where('approval_status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('source_reference', 'like', "%{$search}%")
                    ->orWhere('deidentified_learning', 'like', "%{$search}%");
            });
        }

        return view('qms.safety-promotions.index', [
            'lessons' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsSafetyPromotion::select('approval_status')->distinct()->orderBy('approval_status')->pluck('approval_status'),
        ]);
    }
}
