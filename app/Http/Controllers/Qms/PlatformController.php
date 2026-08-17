<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsFormDefinition;
use App\Models\QmsSavedView;
use App\Models\QmsWorkflowDefinition;

class PlatformController extends Controller
{
    public function index()
    {
        return view('qms.platform.index', [
            'forms' => QmsFormDefinition::orderBy('module')->orderBy('name')->get(),
            'workflows' => QmsWorkflowDefinition::orderBy('module')->orderBy('name')->get(),
            'views' => QmsSavedView::latest()->get(),
        ]);
    }
}
