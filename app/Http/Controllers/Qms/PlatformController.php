<?php

namespace App\Http\Controllers\Qms;

use App\Http\Controllers\Controller;
use App\Models\QmsAccessScope;
use App\Models\QmsConfigurationPackage;
use App\Models\QmsDataSource;
use App\Models\QmsDomainPack;
use App\Models\QmsEmailDesign;
use App\Models\QmsFormDefinition;
use App\Models\QmsModuleLicense;
use App\Models\QmsNotificationDesign;
use App\Models\QmsNotificationGroup;
use App\Models\QmsNotificationRule;
use App\Models\QmsNotificationTemplate;
use App\Models\QmsNumberingRule;
use App\Models\QmsOfflineProfile;
use App\Models\QmsPermissionTemplate;
use App\Models\QmsReportDesign;
use App\Models\QmsSavedView;
use App\Models\QmsSyncAdapter;
use App\Models\QmsSystemMonitor;
use App\Models\QmsSystemSetting;
use App\Models\QmsWorkflowDefinition;
use App\Support\QmsAuditTrail;
use App\Support\QmsStudioCatalog;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index(QmsStudioCatalog $studioCatalog)
    {
        return view('qms.platform.index', [
            'formStudio' => $studioCatalog->formStudio(),
            'workflowStudio' => $studioCatalog->workflowStudio(),
            'studioDataSources' => $studioCatalog->dataSourceOptions(),
            'forms' => QmsFormDefinition::orderBy('module')->orderBy('name')->get(),
            'workflows' => QmsWorkflowDefinition::orderBy('module')->orderBy('name')->get(),
            'reportDesigns' => QmsReportDesign::orderBy('module')->orderBy('name')->get(),
            'emailDesigns' => QmsEmailDesign::orderBy('name')->get(),
            'notificationDesigns' => QmsNotificationDesign::orderBy('module')->orderBy('name')->get(),
            'notificationTemplates' => QmsNotificationTemplate::orderBy('module')->orderBy('name')->get(),
            'notificationRules' => QmsNotificationRule::orderBy('module')->orderBy('name')->get(),
            'notificationGroups' => QmsNotificationGroup::orderBy('name')->get(),
            'permissionTemplates' => QmsPermissionTemplate::orderBy('name')->get(),
            'accessScopes' => QmsAccessScope::orderBy('module')->orderBy('scope_type')->limit(20)->get(),
            'systemSettings' => QmsSystemSetting::orderBy('group')->orderBy('key')->get(),
            'numberingRules' => QmsNumberingRule::orderBy('module')->get(),
            'configurationPackages' => QmsConfigurationPackage::latest()->get(),
            'moduleLicenses' => QmsModuleLicense::orderBy('name')->get(),
            'dataSources' => QmsDataSource::orderBy('source_type')->orderBy('name')->get(),
            'domainPacks' => QmsDomainPack::orderBy('category')->orderBy('name')->get(),
            'syncAdapters' => QmsSyncAdapter::orderBy('provider')->orderBy('name')->get(),
            'systemMonitors' => QmsSystemMonitor::orderBy('area')->orderBy('name')->get(),
            'offlineProfiles' => QmsOfflineProfile::orderBy('module')->orderBy('name')->get(),
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
            'canonical_schema' => ['nullable', 'json', 'max:20000'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $schema = $this->canonicalFormSchema($data);
        $form = QmsFormDefinition::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'status' => $data['status'],
            'schema' => $schema,
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
            'canonical_workflow' => ['nullable', 'json', 'max:20000'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $workflowSchema = $this->canonicalWorkflowSchema($data);
        $workflow = QmsWorkflowDefinition::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'status' => $data['status'],
            'stages' => $workflowSchema['stages'],
            'rules' => $workflowSchema['rules'],
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

    public function storeEmailDesign(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_email_designs,code'],
            'name' => ['required', 'string', 'max:160'],
            'status' => ['required', 'string', 'max:40'],
            'components' => ['nullable', 'string', 'max:1600'],
            'variables' => ['nullable', 'string', 'max:1600'],
            'html_snapshot' => ['nullable', 'string', 'max:8000'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $emailDesign = QmsEmailDesign::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'status' => $data['status'],
            'builder_schema' => [
                'components' => $this->listFromText($data['components'] ?? 'Logo, Heading, Text, Button, Record Info, Footer'),
                'editor' => 'QMS-native placeholder; replace with approved visual editor adapter.',
                'export' => 'Store portable HTML/MJML snapshot and QMS variable whitelist.',
            ],
            'html_snapshot' => $data['html_snapshot'] ?? null,
            'variables' => $this->listFromText($data['variables'] ?? 'user.name, incident.reference, incident.title, action.reference, url.view_record'),
            'change_note' => $data['change_note'] ?? 'Created from Email Designer',
        ]);

        QmsAuditTrail::record($request, $emailDesign, 'email_design_created', [], $emailDesign->getAttributes(), $emailDesign->change_note);

        return redirect()->route('platform.index')->with('status', 'Email design created.');
    }

    public function storeNotificationTemplate(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_notification_templates,code'],
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'status' => ['required', 'string', 'max:40'],
            'subject_template' => ['required', 'string', 'max:220'],
            'body_template' => ['required', 'string', 'max:3000'],
            'allowed_variables' => ['nullable', 'string', 'max:1600'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $template = QmsNotificationTemplate::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'module' => $data['module'],
            'status' => $data['status'],
            'subject_template' => $data['subject_template'],
            'body_template' => $data['body_template'],
            'allowed_variables' => $this->listFromText($data['allowed_variables'] ?? 'user.name, record.reference, record.title, record.status, url.view_record'),
            'change_note' => $data['change_note'] ?? 'Created from Notification Template Builder',
        ]);

        QmsAuditTrail::record($request, $template, 'notification_template_created', [], $template->getAttributes(), $template->change_note);

        return redirect()->route('platform.index')->with('status', 'Notification template created.');
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

    public function storeNotificationRule(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_notification_rules,code'],
            'name' => ['required', 'string', 'max:160'],
            'module' => ['required', 'string', 'max:80'],
            'event_trigger' => ['required', 'string', 'max:120'],
            'status' => ['required', 'string', 'max:40'],
            'conditions' => ['nullable', 'string', 'max:1600'],
            'recipients' => ['required', 'string', 'max:1600'],
            'channels' => ['required', 'string', 'max:400'],
            'timing' => ['nullable', 'string', 'max:800'],
            'change_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $rule = QmsNotificationRule::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'module' => $data['module'],
            'event_trigger' => $data['event_trigger'],
            'status' => $data['status'],
            'conditions' => ['all' => $this->listFromText($data['conditions'] ?? '')],
            'recipients' => ['targets' => $this->listFromText($data['recipients'])],
            'channels' => $this->listFromText($data['channels']),
            'timing' => ['schedule' => $data['timing'] ?? 'Immediately'],
            'change_note' => $data['change_note'] ?? 'Created from Notification Rule Builder',
        ]);

        QmsAuditTrail::record($request, $rule, 'notification_rule_created', [], $rule->getAttributes(), $rule->change_note);

        return redirect()->route('platform.index')->with('status', 'Notification rule created.');
    }

    public function storePermissionTemplate(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_permission_templates,code'],
            'name' => ['required', 'string', 'max:160'],
            'status' => ['required', 'string', 'max:40'],
            'permissions' => ['required', 'string', 'max:2400'],
            'default_scopes' => ['nullable', 'string', 'max:1200'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        QmsPermissionTemplate::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'status' => $data['status'],
            'permissions' => $this->listFromText($data['permissions']),
            'default_scopes' => $this->listFromText($data['default_scopes'] ?? ''),
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('platform.index')->with('status', 'Permission template created.');
    }

    public function storeNumberingRule(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_numbering_rules,code'],
            'module' => ['required', 'string', 'max:80'],
            'prefix' => ['required', 'string', 'max:20'],
            'pattern' => ['required', 'string', 'max:160'],
            'next_sequence' => ['required', 'integer', 'min:1'],
            'reset_annually' => ['nullable', 'boolean'],
            'status' => ['required', 'string', 'max:40'],
        ]);

        QmsNumberingRule::create([
            ...$data,
            'code' => strtoupper($data['code']),
            'reset_annually' => (bool) ($data['reset_annually'] ?? false),
        ]);

        return redirect()->route('platform.index')->with('status', 'Numbering rule created.');
    }

    public function storeConfigurationPackage(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_configuration_packages,code'],
            'name' => ['required', 'string', 'max:160'],
            'status' => ['required', 'string', 'max:40'],
            'effective_date' => ['nullable', 'date'],
            'payload_summary' => ['nullable', 'string', 'max:2000'],
            'validation_summary' => ['nullable', 'string', 'max:2000'],
        ]);

        QmsConfigurationPackage::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'version' => 1,
            'status' => $data['status'],
            'effective_date' => $data['effective_date'] ?? null,
            'payload' => ['summary' => $data['payload_summary'] ?? 'Forms, workflows, notifications, numbering, roles'],
            'validation_summary' => $data['validation_summary'] ?? null,
        ]);

        return redirect()->route('platform.index')->with('status', 'Configuration package created.');
    }

    public function storeDataSource(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_data_sources,code'],
            'name' => ['required', 'string', 'max:160'],
            'source_type' => ['required', 'string', 'max:80'],
            'connector' => ['nullable', 'string', 'max:120'],
            'entity' => ['required', 'string', 'max:120'],
            'key_field' => ['required', 'string', 'max:80'],
            'display_field' => ['required', 'string', 'max:80'],
            'secondary_display_fields' => ['nullable', 'string', 'max:800'],
            'search_fields' => ['required', 'string', 'max:800'],
            'filters' => ['nullable', 'string', 'max:1200'],
            'permission_scope' => ['required', 'string', 'max:120'],
            'organization_scope' => ['required', 'string', 'max:120'],
            'cache_policy' => ['required', 'string', 'max:120'],
            'refresh_policy' => ['required', 'string', 'max:120'],
            'max_results' => ['required', 'integer', 'min:1', 'max:500'],
            'failure_policy' => ['required', 'string', 'max:160'],
            'status' => ['required', 'string', 'max:40'],
            'governance_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        QmsDataSource::create([
            ...$data,
            'code' => strtoupper($data['code']),
            'secondary_display_fields' => $this->listFromText($data['secondary_display_fields'] ?? ''),
            'search_fields' => $this->listFromText($data['search_fields']),
            'filters' => ['rules' => $this->listFromText($data['filters'] ?? '')],
        ]);

        return redirect()->route('platform.index')->with('status', 'Data source registered.');
    }

    public function storeDomainPack(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:qms_domain_packs,code'],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:80'],
            'license_code' => ['nullable', 'string', 'max:80'],
            'enabled' => ['nullable', 'boolean'],
            'status' => ['required', 'string', 'max:40'],
            'capabilities' => ['required', 'string', 'max:2000'],
            'shared_engines' => ['nullable', 'string', 'max:1600'],
            'governance_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        QmsDomainPack::create([
            ...$data,
            'code' => strtoupper($data['code']),
            'enabled' => (bool) ($data['enabled'] ?? false),
            'capabilities' => $this->listFromText($data['capabilities']),
            'shared_engines' => $this->listFromText($data['shared_engines'] ?? 'Workflow, Actions, Audit Trail, Attachments, Reporting, AI Gateway'),
        ]);

        return redirect()->route('platform.index')->with('status', 'Domain pack created.');
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

    private function canonicalFormSchema(array $data): array
    {
        $schema = json_decode($data['canonical_schema'] ?? '', true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($schema) && isset($schema['fields'])) {
            return [
                'version' => '1.0',
                'source' => 'QMS Form Studio',
                'sections' => $this->sanitizeList($schema['sections'] ?? []),
                'required' => $this->sanitizeList($schema['required'] ?? []),
                'fields' => $this->sanitizeFields($schema['fields']),
                'layout' => $schema['layout'] ?? ['mode' => 'single_column'],
                'permissions' => $schema['permissions'] ?? ['visibility' => 'role_and_scope'],
                'conditions' => $schema['conditions'] ?? [],
                'data_sources' => $this->sanitizeList($schema['data_sources'] ?? []),
                'translations' => $schema['translations'] ?? ['en' => true, 'ar_ready' => true],
                'history' => ['draft_created_at' => now()->toISOString()],
            ];
        }

        $sections = $this->listFromText($data['sections'] ?? '');
        $required = $this->listFromText($data['required_fields'] ?? '');

        return [
            'version' => '1.0',
            'source' => 'Structured form definition',
            'sections' => $sections,
            'required' => $required,
            'fields' => collect($required)->map(fn ($field) => [
                'key' => str($field)->slug('_')->toString(),
                'label' => $field,
                'type' => 'text',
                'section' => $sections[0] ?? 'General',
                'required' => true,
            ])->all(),
            'layout' => ['mode' => 'single_column'],
            'permissions' => ['visibility' => 'role_and_scope'],
            'conditions' => [],
            'data_sources' => [],
            'translations' => ['en' => true, 'ar_ready' => true],
        ];
    }

    private function canonicalWorkflowSchema(array $data): array
    {
        $schema = json_decode($data['canonical_workflow'] ?? '', true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($schema) && isset($schema['nodes'])) {
            $stages = $this->sanitizeList($schema['stages'] ?? $data['stages']);

            return [
                'stages' => $stages,
                'rules' => [
                    'source' => 'QMS Workflow Studio',
                    'routing' => $data['routing_rule'] ?? 'Owner moves record by authority and stage gate',
                    'nodes' => $this->sanitizeNodes($schema['nodes']),
                    'edges' => $schema['edges'] ?? [],
                    'sla' => $schema['sla'] ?? ['business_days' => true],
                    'version_protection' => true,
                    'simulation_ready' => true,
                    'separation_of_duties' => $schema['separation_of_duties'] ?? true,
                ],
            ];
        }

        return [
            'stages' => $this->listFromText($data['stages']),
            'rules' => [
                'source' => 'Structured workflow definition',
                'routing' => $data['routing_rule'] ?? 'Owner moves record by authority and stage gate',
                'nodes' => [],
                'edges' => [],
                'version_protection' => true,
            ],
        ];
    }

    private function sanitizeFields(array $fields): array
    {
        return collect($fields)
            ->map(fn ($field) => [
                'key' => str((string) ($field['key'] ?? $field['label'] ?? 'field'))->slug('_')->toString(),
                'label' => (string) ($field['label'] ?? 'Field'),
                'type' => (string) ($field['type'] ?? 'text'),
                'category' => (string) ($field['category'] ?? 'Basic'),
                'section' => (string) ($field['section'] ?? 'General'),
                'required' => (bool) ($field['required'] ?? false),
                'data_source' => $field['data_source'] ?? null,
                'visibility' => $field['visibility'] ?? 'role_and_scope',
                'conditions' => $field['conditions'] ?? [],
            ])
            ->values()
            ->all();
    }

    private function sanitizeNodes(array $nodes): array
    {
        return collect($nodes)
            ->map(fn ($node, $index) => [
                'id' => (string) ($node['id'] ?? 'node_'.$index),
                'type' => (string) ($node['type'] ?? 'human_task'),
                'label' => (string) ($node['label'] ?? 'Workflow step'),
                'kind' => (string) ($node['kind'] ?? 'Task'),
                'assignee' => (string) ($node['assignee'] ?? 'record_owner'),
                'sla' => (string) ($node['sla'] ?? 'P3D'),
            ])
            ->values()
            ->all();
    }

    private function sanitizeList(array|string $items): array
    {
        if (is_string($items)) {
            return $this->listFromText($items);
        }

        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }
}
