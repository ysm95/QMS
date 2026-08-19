<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsFinding;
use App\Models\QmsInspection;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsInspection::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('inspection_type', 'like', "%{$search}%")
                    ->orWhere('station', 'like', "%{$search}%")
                    ->orWhere('inspector', 'like', "%{$search}%");
            });
        }

        return view('qms.inspections.index', [
            'inspections' => $query->paginate(12)->withQueryString(),
            'findings' => QmsFinding::where('source_type', QmsInspection::class)->latest()->limit(6)->get(),
            'statuses' => QmsInspection::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
