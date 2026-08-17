@extends('qms.layout', ['title' => $occurrence->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Record workspace</p><h1>{{ $occurrence->title }}</h1></div>
    <span class="status-pill warning">{{ $occurrence->workflow_stage }}</span>
  </div>
  <div class="record-layout">
    <article class="panel wide">
      <div class="record-head"><div><h2>{{ $occurrence->reference }}</h2><p>{{ $occurrence->description }}</p></div><strong class="risk-badge">Risk: {{ $occurrence->risk_rating }}</strong></div>
      <div class="workflow-ribbon compact">
        @foreach (['Draft', 'Submitted', 'HSE Review', 'Investigation', 'CAPA', 'Closed'] as $stage)
          <span class="{{ $occurrence->workflow_stage === $stage ? 'active' : '' }}">{{ $stage }}</span>
        @endforeach
      </div>
      <div class="detail-grid">
        <div><span>Type</span><strong>{{ $occurrence->type }}</strong></div>
        <div><span>Location</span><strong>{{ $occurrence->location }}</strong></div>
        <div><span>Reporter</span><strong>{{ $occurrence->reported_by }}</strong></div>
        <div><span>Status</span><strong>{{ $occurrence->status }}</strong></div>
      </div>
      <h3>Linked actions</h3>
      <ul class="timeline">@foreach ($actions as $action)<li><strong>{{ $action->reference }}</strong><span>{{ $action->title }} - {{ $action->status }}</span></li>@endforeach</ul>
    </article>
    <aside class="panel">
      <h2>Workflow update</h2>
      <form method="POST" action="{{ route('occurrences.advance', $occurrence) }}">
        @csrf @method('PATCH')
        <label>Stage<select name="workflow_stage"><option>HSE Review</option><option>Investigation</option><option>CAPA</option><option>Closed</option></select></label>
        <label>Status<select name="status"><option>Submitted</option><option>Accepted</option><option>In progress</option><option>Closed</option></select></label>
        <label>Risk<select name="risk_rating"><option>Low</option><option>Medium</option><option>High</option><option>Critical</option></select></label>
        <button class="primary-button full">Update</button>
      </form>
    </aside>
  </div>
</section>
@endsection
