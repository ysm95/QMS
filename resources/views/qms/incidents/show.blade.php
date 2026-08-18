@extends('qms.layout', ['title' => $incident->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Incident workspace</p><h1>{{ $incident->title }}</h1></div>
    <span class="status-pill warning">{{ $incident->workflow_stage }}</span>
  </div>

  <div class="record-layout">
    <article class="panel wide">
      <div class="record-head">
        <div><h2>{{ $incident->reference }}</h2><p>Created from accepted report <a href="{{ route('reporting.show', $incident->sourceReport) }}">{{ $incident->source_report_reference }}</a>. The original report remains intact and auditable.</p></div>
        <strong class="risk-badge">Severity: {{ $incident->severity }}</strong>
      </div>
      <div class="workflow-ribbon compact">
        @foreach (['Classification', 'Ownership', 'Investigation', 'Risk', 'CAPA', 'Verification', 'Closure'] as $stage)
          <span class="{{ $incident->workflow_stage === $stage ? 'active' : '' }}">{{ $stage }}</span>
        @endforeach
      </div>
      <div class="detail-grid">
        <div><span>Type</span><strong>{{ $incident->type }}</strong></div>
        <div><span>Classification</span><strong>{{ $incident->classification ?? 'Not set' }}</strong></div>
        <div><span>Owner</span><strong>{{ $incident->owner ?? 'Unassigned' }}</strong></div>
        <div><span>Department</span><strong>{{ $incident->department ?? 'Not set' }}</strong></div>
        <div><span>Location</span><strong>{{ $incident->location ?? 'Not set' }}</strong></div>
        <div><span>Investigation required</span><strong>{{ $incident->investigation_required ? 'Yes' : 'No' }}</strong></div>
        <div><span>Status</span><strong>{{ $incident->status }}</strong></div>
        <div><span>Closure blocked</span><strong>{{ $incident->closure_blocked ? 'Yes' : 'No' }}</strong></div>
      </div>
      <h3>Source narrative snapshot</h3>
      <p>{{ $incident->source_snapshot['description'] ?? 'No source snapshot available.' }}</p>
      <h3>Related records</h3>
      <ul class="timeline">
        @forelse ($links as $link)
          <li><strong>{{ $link->relationship }}</strong><span>{{ $link->source_reference }} -> {{ $link->target_reference }}</span></li>
        @empty
          <li><strong>No related records</strong><span>Links will appear as actions, risks, investigations and documents are connected.</span></li>
        @endforelse
      </ul>
      <h3>Audit history</h3>
      <ul class="timeline">
        @forelse ($auditLogs as $log)
          <li><strong>{{ $log->action }}</strong><span>{{ $log->actor ?? 'System' }} - {{ $log->created_at->format('Y-m-d H:i') }}</span></li>
        @empty
          <li><strong>No audit entries</strong><span>Incident decisions will be recorded here.</span></li>
        @endforelse
      </ul>
    </article>

    <aside class="panel">
      <h2>Closure gates</h2>
      <ul class="timeline">
        @foreach ($closureGates as $gate)
          <li><strong>{{ $gate }}</strong><span>{{ $incident->closure_blocked ? 'Required before closure' : 'Satisfied' }}</span></li>
        @endforeach
      </ul>
      <h2>Linked actions</h2>
      <ul class="timeline">
        @forelse ($actions as $action)
          <li><strong>{{ $action->reference }}</strong><span>{{ $action->title }} - {{ $action->status }}</span></li>
        @empty
          <li><strong>No incident actions</strong><span>Actions should be created by investigation, risk, audit or CAPA workflows.</span></li>
        @endforelse
      </ul>
    </aside>
  </div>
</section>
@endsection
