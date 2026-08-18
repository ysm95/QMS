<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Support\QmsMyWork;
use Illuminate\Http\Request;

class MyWorkController extends Controller
{
    public function index(Request $request)
    {
        $allItems = QmsMyWork::items();
        $items = $allItems;

        if ($request->filled('module')) {
            $items = $items->where('module', $request->string('module')->toString())->values();
        }

        if ($request->filled('priority')) {
            $items = $items->where('priority', $request->string('priority')->toString())->values();
        }

        if ($request->filled('search')) {
            $search = str($request->string('search')->toString())->lower();
            $items = $items
                ->filter(fn ($item) => str($item['module'].' '.$item['reference'].' '.$item['title'].' '.$item['owner'].' '.$item['source'])->lower()->contains($search))
                ->values();
        }

        return view('qms.my-work.index', [
            'items' => $items,
            'counts' => QmsMyWork::counts($items),
            'modules' => $allItems->pluck('module')->unique()->sort()->values(),
            'priorities' => $allItems->pluck('priority')->unique()->sort()->values(),
        ]);
    }
}
