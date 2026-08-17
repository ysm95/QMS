<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function index()
    {
        return view('qms.actions.index', [
            'actions' => QmsAction::latest()->paginate(14),
        ]);
    }

    public function update(Request $request, QmsAction $action)
    {
        $action->update($request->validate([
            'status' => ['required', 'string', 'max:80'],
            'evidence' => ['nullable', 'string', 'max:3000'],
        ]));

        return back()->with('status', 'Action updated.');
    }
}
