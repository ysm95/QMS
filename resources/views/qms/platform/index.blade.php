@extends('qms.layout', ['title' => 'Platform Config - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Configurable platform</p><h1>Forms, workflows, and saved views</h1></div><span class="status-pill warning">Version controlled</span></div>

  <div class="content-grid admin-console">
    <form class="panel config-form" method="POST" action="{{ route('platform.forms.store') }}">
      @csrf
      <h2>Create form definition</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="FORM-HSE-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="HSE Observation Report" required></label>
        <label>Module<input name="module" placeholder="Occurrences" required></label>
      </div>
      <label>Sections<input name="sections" placeholder="Reporter, Event, Risk, Evidence, Actions"></label>
      <label>Required fields<input name="required_fields" placeholder="Title, Reported By, Event Date, Location, Description"></label>
      <label>Change note<textarea name="change_note" rows="2" placeholder="Reason for creating this form"></textarea></label>
      <button class="primary-button">Create form</button>
    </form>

    <form class="panel config-form" method="POST" action="{{ route('platform.workflows.store') }}">
      @csrf
      <h2>Create workflow</h2>
      <div class="form-grid two">
        <label>Code<input name="code" placeholder="WF-NCR-001" required></label>
        <label>Status<select name="status"><option>Draft</option><option>Published</option><option>Retired</option></select></label>
        <label>Name<input name="name" placeholder="NCR Workflow" required></label>
        <label>Module<input name="module" placeholder="Nonconformance" required></label>
      </div>
      <label>Stages<input name="stages" placeholder="Draft, Submitted, QA Review, Root Cause, CAPA, Verification, Closed" required></label>
      <label>Routing rule<textarea name="routing_rule" rows="2" placeholder="Route by department, risk rating, owner role, and due date"></textarea></label>
      <label>Change note<textarea name="change_note" rows="2" placeholder="Reason for workflow"></textarea></label>
      <button class="primary-button">Create workflow</button>
    </form>

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
      <div class="panel-header"><h2>Notification designs</h2><span class="status-pill">Recipient controlled</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Trigger</th><th>Status</th><th>To</th><th>Conditions</th><th>Subject</th></tr></thead><tbody>
        @foreach ($notificationDesigns as $design)
          <tr><td>{{ $design->code }}</td><td>{{ $design->name }}</td><td>{{ $design->event_trigger }}</td><td><span class="status-pill">{{ $design->status }}</span></td><td>{{ implode(', ', $design->recipients['to'] ?? []) }}</td><td>{{ implode(', ', $design->conditions['rules'] ?? []) }}</td><td>{{ $design->subject_template }}</td></tr>
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
