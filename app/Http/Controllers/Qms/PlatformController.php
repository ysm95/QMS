<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsFormDefinition;
use App\Models\QmsNotificationDesign;
use App\Models\QmsReportDesign;
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
            'reportDesigns' => QmsReportDesign::orderBy('module')->orderBy('name')->get(),
            'notificationDesigns' => QmsNotificationDesign::orderBy('module')->orderBy('name')->get(),
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

    public function storeReportDesign(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_report_designs,code'],
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:40'],
            'sections' => ['required', 'string', 'max:1600'],
            'columns' => ['nullable', 'string', 'max:1600'],
            'data_sources' => ['nullable', 'string', 'max:1600'],
            'output_formats' => ['nullable', 'string', 'max:400'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $reportDesign = QmsReportDesign::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'status' => $data['status'],
            'layout' => [
                'sections' => $this->listFromText($data['sections']),
                'columns' => $this->listFromText($data['columns'] ?? ''),
                'grouping' => ['module', 'status', 'owner'],
                'confidentiality' => 'Mask restricted reporter identity unless user has authority.',
            ],
            'data_sources' => $this->listFromText($data['data_sources'] ?? $data['module']),
            'output_formats' => $this->listFromText($data['output_formats'] ?? 'Screen, CSV, PDF'),
            'change_note' => $data['change_note'] ?? 'Created from Report Designer',
        ]);

        QmsAuditTrail::record($request, $reportDesign, 'report_design_created', [], $reportDesign->getAttributes(), $reportDesign->change_note);

        return redirect()->route('platform.index')->with('status', 'Report design created.');
    }

    public function storeNotificationDesign(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_notification_designs,code'],
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'event_trigger' => ['required', 'string', 'max:120'],
            'status' => ['required', 'string', 'max:40'],
            'to_recipients' => ['required', 'string', 'max:1200'],
            'cc_recipients' => ['nullable', 'string', 'max:1200'],
            'conditions' => ['nullable', 'string', 'max:1600'],
            'subject_template' => ['required', 'string', 'max:220'],
            'body_template' => ['required', 'string', 'max:3000'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $notificationDesign = QmsNotificationDesign::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'event_trigger' => $data['event_trigger'],
            'status' => $data['status'],
            'recipients' => [
                'to' => $this->listFromText($data['to_recipients']),
                'cc' => $this->listFromText($data['cc_recipients'] ?? ''),
            ],
            'conditions' => [
                'rules' => $this->listFromText($data['conditions'] ?? ''),
                'restricted_identity' => 'Respect anonymous/confidential flags.',
            ],
            'subject_template' => $data['subject_template'],
            'body_template' => $data['body_template'],
            'change_note' => $data['change_note'] ?? 'Created from Notification Designer',
        ]);

        QmsAuditTrail::record($request, $notificationDesign, 'notification_design_created', [], $notificationDesign->getAttributes(), $notificationDesign->change_note);

        return redirect()->route('platform.index')->with('status', 'Notification design created.');
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
