<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsAction::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('source_reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%")
                    ->orWhere('evidence', 'like', "%{$search}%");
            });
        }

        return view('qms.actions.index', [
            'actions' => $query->paginate(14)->withQueryString(),
            'statuses' => QmsAction::select('status')->distinct()->orderBy('status')->pluck('status'),
            'priorities' => QmsAction::select('priority')->distinct()->orderBy('priority')->pluck('priority'),
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
