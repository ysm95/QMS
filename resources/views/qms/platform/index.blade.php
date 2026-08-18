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
