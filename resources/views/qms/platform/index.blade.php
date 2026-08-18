@extends('qms.layout', ['title' => 'Platform Config - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Configurable platform</p><h1>Forms, workflows, and saved views</h1></div><span class="status-pill warning">Version controlled</span></div>

  <div class="studio-grid">
    <form class="studio-shell" method="POST" action="{{ route('platform.forms.store') }}" data-form-studio>
      @csrf
      <input type="hidden" name="canonical_schema" data-studio-schema>
      <div class="studio-toolbar">
        <div><p class="eyebrow">Form Studio</p><h2>Visual schema builder</h2></div>
        <div class="button-row">
          @foreach ($formStudio['previewModes'] as $mode)
            <button class="secondary-button" type="button" data-preview-mode="{{ $mode }}">{{ $mode }}</button>
          @endforeach
          <button class="primary-button">Publish draft</button>
        </div>
      </div>
      <div class="studio-meta">
        <label>Code<input name="code" value="FORM-STUDIO-{{ now()->format('His') }}" required></label>
        <label>Name<input name="name" value="Studio Designed QMS Form" required></label>
        <label>Module<input name="module" value="Reporting" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Retired</option></select></label>
      </div>
      <div class="studio-columns">
        <aside class="studio-palette">
          @foreach ($formStudio['componentGroups'] as $group => $components)
            <section>
              <h3>{{ $group }}</h3>
              @foreach ($components as $component)
                <button type="button" draggable="true" data-component='@json($component)'>{{ $component['label'] }}</button>
              @endforeach
            </section>
          @endforeach
        </aside>
        <main class="studio-canvas" data-form-canvas>
          <div class="canvas-section">
            <span>General</span>
            <div class="canvas-field" data-key="event_title" data-type="text" data-category="Basic" data-required="true">Event title</div>
            <div class="canvas-field" data-key="reported_by" data-type="user" data-category="Directory" data-data-source="DS-USERS-LOCAL" data-required="true">Reported by</div>
            <div class="canvas-field" data-key="location" data-type="searchable_dropdown" data-category="Choice" data-data-source="DS-USERS-LOCAL" data-required="true">Location</div>
          </div>
          <div class="canvas-section">
            <span>Risk and evidence</span>
            <div class="canvas-field" data-key="severity" data-type="dropdown" data-category="Choice" data-required="true">Severity</div>
            <div class="canvas-field" data-key="description" data-type="textarea" data-category="Basic" data-required="true">Description</div>
          </div>
        </main>
        <aside class="studio-inspector">
          <h3>Properties</h3>
          <label>Selected label<input data-field-label value="Description"></label>
          <label>Field key<input data-field-key value="description"></label>
          <label>Required<select data-field-required><option value="true">Required</option><option value="false">Optional</option></select></label>
          <label>Data source<select data-field-data-source><option value="">None</option>@foreach ($studioDataSources as $source)<option value="{{ $source->code }}">{{ $source->name }}</option>@endforeach</select></label>
          <label>Sections<input name="sections" data-sections value="General, Risk and evidence"></label>
          <label>Required fields<input name="required_fields" data-required-fields value="Event title, Reported by, Location, Severity, Description"></label>
          <label>Change note<textarea name="change_note" rows="2">Studio draft with canonical schema, permissions, conditions and data-source metadata.</textarea></label>
          <div class="schema-tree"><strong>Schema tree</strong><pre data-schema-preview></pre></div>
        </aside>
      </div>
    </form>

    <form class="studio-shell" method="POST" action="{{ route('platform.workflows.store') }}" data-workflow-studio>
      @csrf
      <input type="hidden" name="canonical_workflow" data-workflow-schema>
      <div class="studio-toolbar">
        <div><p class="eyebrow">Workflow Studio</p><h2>Stage and rule canvas</h2></div>
        <div class="button-row"><button class="secondary-button" type="button" data-simulate-workflow>Simulate</button><button class="primary-button">Publish workflow</button></div>
      </div>
      <div class="studio-meta">
        <label>Code<input name="code" value="WF-STUDIO-{{ now()->format('His') }}" required></label>
        <label>Name<input name="name" value="Studio Designed Workflow" required></label>
        <label>Module<input name="module" value="Reporting" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Retired</option></select></label>
      </div>
      <div class="studio-columns workflow-columns">
        <aside class="studio-palette">
          @foreach ($workflowStudio['nodeGroups'] as $group => $nodes)
            <section>
              <h3>{{ $group }}</h3>
              @foreach ($nodes as $node)
                <button type="button" draggable="true" data-node='@json($node)'>{{ $node['label'] }}</button>
              @endforeach
            </section>
          @endforeach
        </aside>
        <main class="studio-canvas workflow-canvas" data-workflow-canvas>
          @foreach ($workflowStudio['defaultStages'] as $index => $stage)
            <div class="workflow-node" data-type="{{ $index === 0 ? 'start' : ($loop->last ? 'end' : 'human_task') }}" data-kind="{{ $index === 0 ? 'Start event' : ($loop->last ? 'End event' : 'Task') }}" data-sla="P3D">{{ $stage }}</div>
          @endforeach
        </main>
        <aside class="studio-inspector">
          <h3>Rules</h3>
          <label>Stages<input name="stages" data-workflow-stages value="{{ implode(', ', $workflowStudio['defaultStages']) }}" required></label>
          <label>Routing rule<textarea name="routing_rule" rows="4">Route by risk, confidentiality, department, assigned key user, SLA and separation of duties.</textarea></label>
          <label>Change note<textarea name="change_note" rows="2">Workflow Studio draft with canonical nodes, SLA, escalation and simulation readiness.</textarea></label>
          <div class="schema-tree"><strong>Workflow JSON</strong><pre data-workflow-preview></pre></div>
        </aside>
      </div>
    </form>
  </div>

  <div class="content-grid admin-console">

    <form class="panel config-form" method="POST" action="{{ route('platform.report-designs.store') }}">
      @csrf
      <h2>Report designer</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="RPT-OCC-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="Occurrence Register Report" required></label>
        <label>Module<input name="module" placeholder="Occurrences" required></label>
      </div>
      <label>Sections<input name="sections" placeholder="Header, Filters, Register Table, Risk Summary, CAPA Summary" required></label>
      <label>Columns<input name="columns" placeholder="Reference, Title, Type, Stage, Risk, Owner, Due Date"></label>
      <label>Data sources<input name="data_sources" placeholder="Occurrences, Actions, Risks, Audit Trail"></label>
      <label>Output formats<input name="output_formats" placeholder="Screen, CSV, PDF, Excel"></label>
      <label>Change note<textarea name="change_note" rows="2" placeholder="Reason for report layout"></textarea></label>
      <button class="primary-button">Create report design</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.email-designs.store') }}">
      @csrf
      <h2>Email designer</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="EMAIL-OCC-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Archived</option></select></label>
        <label>Name<input name="name" placeholder="Major Incident Email Layout" required></label>
        <label>Variables<input name="variables" placeholder="user.name, incident.reference, url.view_record"></label>
      </div>
      <label>Components<input name="components" placeholder="Logo, Heading, Record Info, Action Button, Footer"></label>
      <label>HTML snapshot<textarea name="html_snapshot" rows="3" placeholder="Optional portable HTML snapshot from approved email editor"></textarea></label>
      <label>Change note<textarea name="change_note" rows="2" placeholder="Reason for email layout"></textarea></label>
      <button class="primary-button">Create email design</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.notification-templates.store') }}">
      @csrf
      <h2>Notification template</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="NTF-OCC-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Archived</option></select></label>
        <label>Name<input name="name" placeholder="Occurrence Requires Review" required></label>
        <label>Module<input name="module" placeholder="Occurrences" required></label>
      </div>
      <label>Subject template<input name="subject_template" placeholder="[@{{record.reference}}] @{{record.title}}" required></label>
      <label>Body template<textarea name="body_template" rows="3" placeholder="Hello @{{user.name}}, record @{{record.reference}} requires your review." required></textarea></label>
      <label>Allowed variables<input name="allowed_variables" placeholder="user.name, record.reference, record.title, url.view_record"></label>
      <button class="primary-button">Create template</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.notification-designs.store') }}">
      @csrf
      <h2>Notification designer</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="MSG-OCC-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="Occurrence Submitted" required></label>
        <label>Module<input name="module" placeholder="Occurrences" required></label>
        <label>Trigger<input name="event_trigger" placeholder="occurrence.submitted" required></label>
        <label>To recipients<input name="to_recipients" placeholder="Occurrence Owner, HSE Reviewer" required></label>
      </div>
      <label>CC recipients<input name="cc_recipients" placeholder="Reporter, Department Manager"></label>
      <label>Conditions<input name="conditions" placeholder="risk:High, confidential:false, status:Submitted"></label>
      <label>Subject template<input name="subject_template" placeholder="[@{{reference}}] @{{title}} requires review" required></label>
      <label>Body template<textarea name="body_template" rows="3" placeholder="Record @{{reference}} is waiting for @{{stage}}. Please review risk, evidence, and due dates." required></textarea></label>
      <label>Change note<textarea name="change_note" rows="2" placeholder="Reason for message rule"></textarea></label>
      <button class="primary-button">Create notification design</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.notification-rules.store') }}">
      @csrf
      <h2>Notification rule builder</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="RULE-OCC-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Paused</option></select></label>
        <label>Name<input name="name" placeholder="Major occurrence escalation" required></label>
        <label>Module<input name="module" placeholder="Occurrences" required></label>
        <label>When<input name="event_trigger" placeholder="occurrence.accepted" required></label>
        <label>Channels<input name="channels" placeholder="In-App, Email" required></label>
      </div>
      <label>If conditions<input name="conditions" placeholder="severity:Major, department:Flight Operations, status:Accepted"></label>
      <label>Recipients<input name="recipients" placeholder="Safety Key Users, Department Manager, Reporter" required></label>
      <label>Timing<input name="timing" placeholder="Immediately, T-3 reminder, +7 escalation"></label>
      <label>Change note<textarea name="change_note" rows="2" placeholder="Reason for rule"></textarea></label>
      <button class="primary-button">Create rule</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.permission-templates.store') }}">
      @csrf
      <h2>Permission template</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="PERM-KEY-SAFETY" required></label>
        <label>Status<select name="status"><option>Active</option><option>Draft</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="Safety Key User" required></label>
        <label>Scopes<input name="default_scopes" placeholder="DEPARTMENT, ASSIGNED"></label>
      </div>
      <label>Permissions<input name="permissions" placeholder="occurrences.view.department, occurrences.review, recommendations.create, actions.assign" required></label>
      <label>Description<textarea name="description" rows="2" placeholder="What this template allows"></textarea></label>
      <button class="primary-button">Create permission template</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.numbering-rules.store') }}">
      @csrf
      <h2>Numbering designer</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="NUM-INC" required></label>
        <label>Status<select name="status"><option>Active</option><option>Draft</option><option>Retired</option></select></label>
        <label>Module<input name="module" placeholder="Incidents" required></label>
        <label>Prefix<input name="prefix" placeholder="INC" required></label>
        <label>Pattern<input name="pattern" value="{PREFIX}-{YYYY}-{SEQ:6}" required></label>
        <label>Next sequence<input name="next_sequence" type="number" min="1" value="1" required></label>
      </div>
      <label class="inline-check"><input name="reset_annually" type="checkbox" value="1" checked> Reset annually</label>
      <button class="primary-button">Create numbering rule</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.configuration-packages.store') }}">
      @csrf
      <h2>Configuration package</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="CFG-UAT-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Validated</option><option>Approved</option><option>Published</option><option>Rolled back</option></select></label>
        <label>Name<input name="name" placeholder="UAT baseline configuration" required></label>
        <label>Effective date<input name="effective_date" type="date"></label>
      </div>
      <label>Package summary<textarea name="payload_summary" rows="2" placeholder="Forms, workflows, roles, numbering, reports"></textarea></label>
      <label>Validation summary<textarea name="validation_summary" rows="2" placeholder="Dependency checks and impact preview"></textarea></label>
      <button class="primary-button">Create package</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.data-sources.store') }}">
      @csrf
      <h2>Data source registry</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="DS-ENTRA-USERS" required></label>
        <label>Status<select name="status"><option>Active</option><option>Draft</option><option>Paused</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="Microsoft Entra Users" required></label>
        <label>Source type<select name="source_type"><option>Local Database</option><option>Entra Sync</option><option>REST Adapter</option><option>Reference Data</option></select></label>
        <label>Connector<input name="connector" placeholder="entra-directory-sync"></label>
        <label>Entity<input name="entity" placeholder="users" required></label>
        <label>Key field<input name="key_field" value="id" required></label>
        <label>Display field<input name="display_field" placeholder="name" required></label>
        <label>Search fields<input name="search_fields" placeholder="name, employee_id, email" required></label>
        <label>Context fields<input name="secondary_display_fields" placeholder="employee_id, department, station"></label>
        <label>Permission scope<input name="permission_scope" value="current_user_scope" required></label>
        <label>Organization scope<input name="organization_scope" value="default" required></label>
        <label>Cache policy<input name="cache_policy" value="indexed_local" required></label>
        <label>Refresh policy<input name="refresh_policy" value="scheduled" required></label>
        <label>Maximum results<input name="max_results" type="number" min="1" max="500" value="50" required></label>
        <label>Failure policy<input name="failure_policy" value="show_governed_empty_state" required></label>
      </div>
      <label>Filters<input name="filters" placeholder="active:true, scope:department, expose_sensitive:false"></label>
      <label>Governance notes<textarea name="governance_notes" rows="2" placeholder="No raw SQL. Permission filtering applies before choices are shown."></textarea></label>
      <button class="primary-button">Register source</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.domain-packs.store') }}">
      @csrf
      <h2>Domain pack</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="PACK-LAB" required></label>
        <label>Status<select name="status"><option>Planned</option><option>Active</option><option>UAT</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="Laboratory / Calibration Pack" required></label>
        <label>Category<select name="category"><option>Core</option><option>Aviation</option><option>Supplier</option><option>Manufacturing</option><option>Service</option><option>Laboratory</option><option>Future Regulated</option></select></label>
        <label>License code<input name="license_code" placeholder="LAB"></label>
        <label class="inline-check"><input name="enabled" type="checkbox" value="1"> Enabled</label>
      </div>
      <label>Capabilities<input name="capabilities" placeholder="Calibration certificates, out-of-tolerance event, impact assessment" required></label>
      <label>Shared engines<input name="shared_engines" placeholder="Workflow, Actions, Audit Trail, Attachments, Reporting, AI Gateway"></label>
      <label>Governance notes<textarea name="governance_notes" rows="2" placeholder="Reuse shared engines. Do not claim regulatory compliance until configured and validated."></textarea></label>
      <button class="primary-button">Create domain pack</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.saved-views.store') }}">
      @csrf
      <h2>Create saved view</h2>
      <div class="form-grid two">
        <label>Name<input name="name" placeholder="Confidential high-risk intake" required></label>
        <label>Module<input name="module" placeholder="Public Reports" required></label>
        <label>Owner<input name="owner" value="{{ auth()->user()->name }}"></label>
        <label>Shared<select name="shared"><option value="1">Shared</option><option value="0">Private</option></select></label>
      </div>
      <label>Filters<input name="filters" placeholder="status:New confidential:true risk:High"></label>
      <button class="primary-button">Create view</button>
    </form>
  </div>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Form definitions</h2><span class="status-pill">Historical safe</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Module</th><th>Version</th><th>Status</th><th>Sections</th></tr></thead><tbody>
        @foreach ($forms as $form)
          <tr><td>{{ $form->code }}</td><td>{{ $form->name }}</td><td>{{ $form->module }}</td><td>v{{ $form->version }}</td><td><span class="status-pill">{{ $form->status }}</span></td><td>{{ implode(', ', $form->schema['sections'] ?? $form->schema['supports'] ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Workflow definitions</h2><span class="status-pill">Stage controlled</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Module</th><th>Version</th><th>Status</th><th>Stages</th></tr></thead><tbody>
        @foreach ($workflows as $workflow)
          <tr><td>{{ $workflow->code }}</td><td>{{ $workflow->name }}</td><td>{{ $workflow->module }}</td><td>v{{ $workflow->version }}</td><td><span class="status-pill">{{ $workflow->status }}</span></td><td>{{ implode(' > ', $workflow->stages ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Report designs</h2><span class="status-pill">Layout controlled</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Module</th><th>Version</th><th>Status</th><th>Sections</th><th>Outputs</th></tr></thead><tbody>
        @foreach ($reportDesigns as $design)
          <tr><td>{{ $design->code }}</td><td>{{ $design->name }}</td><td>{{ $design->module }}</td><td>v{{ $design->version }}</td><td><span class="status-pill">{{ $design->status }}</span></td><td>{{ implode(', ', $design->layout['sections'] ?? []) }}</td><td>{{ implode(', ', $design->output_formats ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Email designs</h2><span class="status-pill">Visual layout</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Version</th><th>Status</th><th>Components</th><th>Variables</th></tr></thead><tbody>
        @foreach ($emailDesigns as $design)
          <tr><td>{{ $design->code }}</td><td>{{ $design->name }}</td><td>v{{ $design->version }}</td><td><span class="status-pill">{{ $design->status }}</span></td><td>{{ implode(', ', $design->builder_schema['components'] ?? []) }}</td><td>{{ implode(', ', $design->variables ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Notification templates</h2><span class="status-pill">Message content</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Module</th><th>Status</th><th>Subject</th><th>Variables</th></tr></thead><tbody>
        @foreach ($notificationTemplates as $template)
          <tr><td>{{ $template->code }}</td><td>{{ $template->name }}</td><td>{{ $template->module }}</td><td><span class="status-pill">{{ $template->status }}</span></td><td>{{ $template->subject_template }}</td><td>{{ implode(', ', $template->allowed_variables ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Notification designs</h2><span class="status-pill">Recipient controlled</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Trigger</th><th>Status</th><th>To</th><th>Conditions</th><th>Subject</th></tr></thead><tbody>
        @foreach ($notificationDesigns as $design)
          <tr><td>{{ $design->code }}</td><td>{{ $design->name }}</td><td>{{ $design->event_trigger }}</td><td><span class="status-pill">{{ $design->status }}</span></td><td>{{ implode(', ', $design->recipients['to'] ?? []) }}</td><td>{{ implode(', ', $design->conditions['rules'] ?? []) }}</td><td>{{ $design->subject_template }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Notification rules</h2><span class="status-pill">Automation</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Trigger</th><th>Status</th><th>Recipients</th><th>Channels</th><th>Timing</th></tr></thead><tbody>
        @foreach ($notificationRules as $rule)
          <tr><td>{{ $rule->code }}</td><td>{{ $rule->name }}</td><td>{{ $rule->event_trigger }}</td><td><span class="status-pill">{{ $rule->status }}</span></td><td>{{ implode(', ', $rule->recipients['targets'] ?? []) }}</td><td>{{ implode(', ', $rule->channels ?? []) }}</td><td>{{ $rule->timing['schedule'] ?? 'Immediately' }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Permission templates and scopes</h2><span class="status-pill">RBAC + scope</span></div>
      <div class="table-panel"><table><thead><tr><th>Template</th><th>Status</th><th>Permissions</th><th>Default scopes</th></tr></thead><tbody>
        @foreach ($permissionTemplates as $template)
          <tr><td>{{ $template->name }}</td><td><span class="status-pill">{{ $template->status }}</span></td><td>{{ implode(', ', $template->permissions ?? []) }}</td><td>{{ implode(', ', $template->default_scopes ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Branding and system settings</h2><span class="status-pill">Control center</span></div>
      <div class="table-panel"><table><thead><tr><th>Group</th><th>Key</th><th>Status</th><th>Value</th></tr></thead><tbody>
        @foreach ($systemSettings as $setting)
          <tr><td>{{ $setting->group }}</td><td>{{ $setting->key }}</td><td><span class="status-pill">{{ $setting->status }}</span></td><td>{{ $setting->is_sensitive ? 'Sensitive' : json_encode($setting->value) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Module licenses</h2><span class="status-pill">Feature control</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Status</th><th>Enabled</th><th>Expiry</th><th>Limits</th></tr></thead><tbody>
        @foreach ($moduleLicenses as $license)
          <tr><td>{{ $license->code }}</td><td>{{ $license->name }}</td><td><span class="status-pill">{{ $license->status }}</span></td><td>{{ $license->enabled ? 'Yes' : 'No' }}</td><td>{{ optional($license->expires_on)->format('Y-m-d') ?? 'Not set' }}</td><td>{{ json_encode($license->limits) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Numbering rules</h2><span class="status-pill">ID control</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Module</th><th>Prefix</th><th>Pattern</th><th>Next</th><th>Status</th></tr></thead><tbody>
        @foreach ($numberingRules as $rule)
          <tr><td>{{ $rule->code }}</td><td>{{ $rule->module }}</td><td>{{ $rule->prefix }}</td><td>{{ $rule->pattern }}</td><td>{{ $rule->next_sequence }}</td><td><span class="status-pill">{{ $rule->status }}</span></td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Configuration packages</h2><span class="status-pill">Promotion ready</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Version</th><th>Status</th><th>Effective</th><th>Validation</th></tr></thead><tbody>
        @foreach ($configurationPackages as $package)
          <tr><td>{{ $package->code }}</td><td>{{ $package->name }}</td><td>v{{ $package->version }}</td><td><span class="status-pill">{{ $package->status }}</span></td><td>{{ optional($package->effective_date)->format('Y-m-d') ?? 'Not set' }}</td><td>{{ $package->validation_summary ?: 'Pending validation' }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Data source registry</h2><span class="status-pill">Approved lookups</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Type</th><th>Entity</th><th>Search</th><th>Scope</th><th>Status</th></tr></thead><tbody>
        @foreach ($dataSources as $source)
          <tr><td>{{ $source->code }}</td><td>{{ $source->name }}</td><td>{{ $source->source_type }}</td><td>{{ $source->entity }}</td><td>{{ implode(', ', $source->search_fields ?? []) }}</td><td>{{ $source->permission_scope }}</td><td><span class="status-pill">{{ $source->status }}</span></td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Domain pack matrix</h2><span class="status-pill">Licensed expansion</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Category</th><th>License</th><th>Enabled</th><th>Capabilities</th><th>Status</th></tr></thead><tbody>
        @foreach ($domainPacks as $pack)
          <tr><td>{{ $pack->code }}</td><td>{{ $pack->name }}</td><td>{{ $pack->category }}</td><td>{{ $pack->license_code ?? 'Core' }}</td><td>{{ $pack->enabled ? 'Yes' : 'No' }}</td><td>{{ implode(', ', $pack->capabilities ?? []) }}</td><td><span class="status-pill">{{ $pack->status }}</span></td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Sync adapters and offline profiles</h2><span class="status-pill">Mobile and Entra ready</span></div>
      <div class="table-panel"><table><thead><tr><th>Name</th><th>Provider / Module</th><th>Purpose</th><th>Status</th><th>Policy</th></tr></thead><tbody>
        @foreach ($syncAdapters as $adapter)
          <tr><td>{{ $adapter->name }}</td><td>{{ $adapter->provider }}</td><td>{{ $adapter->purpose }}</td><td><span class="status-pill">{{ $adapter->status }}</span></td><td>{{ json_encode($adapter->sync_policy) }}</td></tr>
        @endforeach
        @foreach ($offlineProfiles as $profile)
          <tr><td>{{ $profile->name }}</td><td>{{ $profile->module }}</td><td>Offline profile</td><td><span class="status-pill">{{ $profile->status }}</span></td><td>{{ $profile->conflict_policy }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Production monitors</h2><span class="status-pill">Operations</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Area</th><th>Status</th><th>Checks</th><th>Last result</th></tr></thead><tbody>
        @foreach ($systemMonitors as $monitor)
          <tr><td>{{ $monitor->code }}</td><td>{{ $monitor->name }}</td><td>{{ $monitor->area }}</td><td><span class="status-pill">{{ $monitor->status }}</span></td><td>{{ implode(', ', $monitor->checks ?? []) }}</td><td>{{ $monitor->last_result }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel">
      <h2>Saved views</h2>
      <ul class="timeline">
        @foreach ($views as $view)
          <li><strong>{{ $view->name }}</strong><span>{{ $view->module }} - {{ $view->shared ? 'Shared' : 'Private' }}</span></li>
        @endforeach
      </ul>
    </article>
  </div>
</section>
@endsection
