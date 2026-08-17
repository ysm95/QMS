<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAction;
use App\Models\QmsAudit;
use App\Models\QmsDocument;
use App\Models\QmsOccurrence;
use App\Models\QmsRisk;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = trim((string) $request->query('q'));

        return view('qms.search.index', [
            'term' => $term,
            'occurrences' => $this->occurrences($term),
            'actions' => $this->actions($term),
            'audits' => $this->audits($term),
            'risks' => $this->risks($term),
            'documents' => $this->documents($term),
        ]);
    }

    private function occurrences(string $term)
    {
        return QmsOccurrence::query()
            ->when($term !== '', fn ($query) => $query->where(function ($builder) use ($term) {
                $builder->where('reference', 'like', "%{$term}%")
                    ->orWhere('title', 'like', "%{$term}%")
                    ->orWhere('type', 'like', "%{$term}%")
                    ->orWhere('reported_by', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            }))
            ->latest()
            ->limit(8)
            ->get();
    }

    private function actions(string $term)
    {
        return QmsAction::query()
            ->when($term !== '', fn ($query) => $query->where(function ($builder) use ($term) {
                $builder->where('reference', 'like', "%{$term}%")
                    ->orWhere('source_reference', 'like', "%{$term}%")
                    ->orWhere('title', 'like', "%{$term}%")
                    ->orWhere('owner', 'like', "%{$term}%");
            }))
            ->latest()
            ->limit(8)
            ->get();
    }

    private function audits(string $term)
    {
        return QmsAudit::query()
            ->when($term !== '', fn ($query) => $query->where('reference', 'like', "%{$term}%")->orWhere('title', 'like', "%{$term}%")->orWhere('standard', 'like', "%{$term}%"))
            ->latest()
            ->limit(8)
            ->get();
    }

    private function risks(string $term)
    {
        return QmsRisk::query()
            ->when($term !== '', fn ($query) => $query->where('reference', 'like', "%{$term}%")->orWhere('hazard', 'like', "%{$term}%")->orWhere('controls', 'like', "%{$term}%"))
            ->latest()
            ->limit(8)
            ->get();
    }

    private function documents(string $term)
    {
        return QmsDocument::query()
            ->when($term !== '', fn ($query) => $query->where('reference', 'like', "%{$term}%")->orWhere('title', 'like', "%{$term}%")->orWhere('owner', 'like', "%{$term}%"))
            ->latest()
            ->limit(8)
            ->get();
    }
}
