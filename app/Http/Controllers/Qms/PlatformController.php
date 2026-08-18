<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsFormDefinition;
use App\Models\QmsSavedView;
use App\Models\QmsWorkflowDefinition;
use App\Support\QmsAuditTrail;
use Illuminate\Http\Request;

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

    public function storeForm(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_form_definitions,code'],
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:40'],
            'sections' => ['nullable', 'string', 'max:1200'],
            'required_fields' => ['nullable', 'string', 'max:1200'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $form = QmsFormDefinition::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'status' => $data['status'],
            'schema' => [
                'sections' => $this->listFromText($data['sections'] ?? ''),
                'required' => $this->listFromText($data['required_fields'] ?? ''),
            ],
            'change_note' => $data['change_note'] ?? 'Created from Platform Config',
        ]);

        QmsAuditTrail::record($request, $form, 'form_definition_created', [], $form->getAttributes(), $form->change_note);

        return redirect()->route('platform.index')->with('status', 'Form definition created.');
    }

    public function storeWorkflow(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_workflow_definitions,code'],
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:40'],
            'stages' => ['required', 'string', 'max:1200'],
            'routing_rule' => ['nullable', 'string', 'max:2000'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $workflow = QmsWorkflowDefinition::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'status' => $data['status'],
            'stages' => $this->listFromText($data['stages']),
            'rules' => ['routing' => $data['routing_rule'] ?? 'Owner moves record by authority and stage gate'],
            'change_note' => $data['change_note'] ?? 'Created from Platform Config',
        ]);

        QmsAuditTrail::record($request, $workflow, 'workflow_definition_created', [], $workflow->getAttributes(), $workflow->change_note);

        return redirect()->route('platform.index')->with('status', 'Workflow definition created.');
    }

    public function storeSavedView(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'owner' => ['nullable', 'string', 'max:160'],
            'filters' => ['nullable', 'string', 'max:1200'],
            'shared' => ['nullable', 'boolean'],
        ]);

        QmsSavedView::create([
            'name' => $data['name'],
            'module' => $data['module'],
            'owner' => $data['owner'] ?: ($request->user()->name ?? 'QMS Admin'),
            'filters' => ['query' => $data['filters'] ?? ''],
            'shared' => (bool) ($data['shared'] ?? false),
        ]);

        return redirect()->route('platform.index')->with('status', 'Saved view created.');
    }

    private function listFromText(string $value): array
    {
        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
