<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsObjective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsObjective::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(fn ($builder) => $builder->where('reference', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('owner', 'like', "%{$search}%")
                ->orWhere('measure', 'like', "%{$search}%"));
        }

        return view('qms.objectives.index', [
            'objectives' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsObjective::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
