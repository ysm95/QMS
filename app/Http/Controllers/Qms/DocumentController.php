<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsDocument;
use App\Support\QmsAuditTrail;
use App\Support\QmsNotify;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = QmsDocument::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('version', 'like', "%{$search}%")
                    ->orWhere('owner', 'like', "%{$search}%");
            });
        }

        return view('qms.documents.index', [
            'documents' => $query->paginate(12)->withQueryString(),
            'statuses' => QmsDocument::select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }

    public function update(Request $request, QmsDocument $document)
    {
        $data = $request->validate([
            'version' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:80'],
            'review_date' => ['nullable', 'date'],
        ]);

        $oldValues = $document->only(['version', 'status', 'review_date']);
        $document->update($data);

        QmsAuditTrail::record($request, $document, 'document_updated', $oldValues, $data, 'Controlled document metadata updated.');
        QmsNotify::everyone('Controlled document updated', $document->reference . ' is now ' . $document->status . ' at ' . $document->version . '.', $document->reference);

        return back()->with('status', 'Document updated.');
    }
}
