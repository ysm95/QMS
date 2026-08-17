<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsInvestigation;

class InvestigationController extends Controller
{
    public function index()
    {
        return view('qms.investigations.index', [
            'investigations' => QmsInvestigation::latest()->paginate(12),
        ]);
    }

    public function show(QmsInvestigation $investigation)
    {
        return view('qms.investigations.show', compact('investigation'));
    }
}
