<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsCapaCase;
use Illuminate\Http\Request;

class CapaController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsCapaCase::query()->latest();

        if ($request->filled('phase')) {
            $query->where('phase', $request->string('phase'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('source_reference', 'like', "%{$search}%")
                    ->orWhere('problem_statement', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%");
            });
        }

        return view('qms.capa.index', [
            'capaCases' => $query->paginate(12)->withQueryString(),
            'phases' => QmsCapaCase::select('phase')->distinct()->orderBy('phase')->pluck('phase'),
        ]);
    }
}
