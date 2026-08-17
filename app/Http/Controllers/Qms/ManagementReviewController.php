<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsManagementReview;
use Illuminate\Http\Request;

class ManagementReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsManagementReview::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(fn ($builder) => $builder->where('reference', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('chair', 'like', "%{$search}%")
                ->orWhere('decisions', 'like', "%{$search}%"));
        }

        return view('qms.management-reviews.index', [
            'reviews' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsManagementReview::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
