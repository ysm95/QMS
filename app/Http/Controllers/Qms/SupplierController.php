<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsSupplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsSupplier::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('risk')) {
            $query->where('risk_rating', $request->string('risk'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(fn ($builder) => $builder->where('reference', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('owner', 'like', "%{$search}%"));
        }

        return view('qms.suppliers.index', [
            'suppliers' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsSupplier::select('status')->distinct()->orderBy('status')->pluck('status'),
            'risks' => QmsSupplier::select('risk_rating')->distinct()->orderBy('risk_rating')->pluck('risk_rating'),
        ]);
    }
}
