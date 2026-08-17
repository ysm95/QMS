<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsRisk;
use App\Support\QmsAuditTrail;
use App\Support\QmsNotify;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsRisk::query()->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->string('rating'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('hazard', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%")
                    ->orWhere('controls', 'like', "%{$search}%");
            });
        }

        return view('qms.risks.index', [
            'risks' => $query->paginate(12)->withQueryString(),
            'ratings' => QmsRisk::select('rating')->distinct()->orderBy('rating')->pluck('rating'),
        ]);
    }

    public function update(Request $request, QmsRisk $risk)
    {
        $data = $request->validate([
            'rating' => ['required', 'string', 'max:80'],
            'controls' => ['nullable', 'string', 'max:5000'],
            'review_date' => ['nullable', 'date'],
        ]);

        $oldValues = $risk->only(['rating', 'controls', 'review_date']);
        $risk->update($data);

        QmsAuditTrail::record($request, $risk, 'risk_updated', $oldValues, $data, 'Risk rating, controls, or review date updated.');
        QmsNotify::everyone('Risk register updated', $risk->reference . ' is now rated ' . $risk->rating . '.', $risk->reference);

        return back()->with('status', 'Risk updated.');
    }
}
