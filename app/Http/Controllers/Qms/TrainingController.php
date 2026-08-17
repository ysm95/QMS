<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsTrainingRecord;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsTrainingRecord::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(fn ($builder) => $builder->where('reference', 'like', "%{$search}%")
                ->orWhere('person_name', 'like', "%{$search}%")
                ->orWhere('course', 'like', "%{$search}%")
                ->orWhere('competency_area', 'like', "%{$search}%"));
        }

        return view('qms.training.index', [
            'records' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsTrainingRecord::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
