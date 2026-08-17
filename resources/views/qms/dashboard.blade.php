@extends('qms.layout', ['title' => 'QMS Dashboard'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div>
      <p class="eyebrow">BRSD Phase 2 Core</p>
      <h1>Command dashboard</h1>
    </div>
    <a class="primary-button" href="{{ route('occurrences.create') }}">New occurrence</a>
  </div>

  <div class="metric-grid">
    <article class="metric"><span>Open occurrences</span><strong>{{ $metrics['openOccurrences'] }}</strong><small>Live from database</small></article>
    <article class="metric"><span>Overdue CAPA</span><strong>{{ $metrics['overdueActions'] }}</strong><small>Owner action queue</small></article>
    <article class="metric"><span>High risks</span><strong>{{ $metrics['highRisks'] }}</strong><small>Risk register</small></article>
    <article class="metric"><span>Audit readiness</span><strong>{{ $metrics['auditReadiness'] }}%</strong><small>BRSD evidence mapping</small></article>
    <article class="metric"><span>Unread alerts</span><strong>{{ $metrics['unreadNotifications'] }}</strong><small>Notification inbox</small></article>
  </div>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Occurrence workflow board</h2><span class="status-pill">Real records</span></div>
      <div class="kanban">
        @foreach (['Submitted', 'Screening', 'Investigation', 'CAPA'] as $stage)
          <div class="lane">
            <h3>{{ $stage }}</h3>
            @foreach ($occurrences->where('workflow_stage', $stage)->take(3) as $occurrence)
              <a class="work-card {{ $occurrence->risk_rating === 'High' ? 'high' : '' }}" href="{{ route('occurrences.show', $occurrence) }}">
                {{ $occurrence->title }}<span>{{ $occurrence->reference }}</span>
              </a>
            @endforeach
          </div>
        @endforeach
      </div>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>BRSD coverage</h2><span class="status-pill success">Core</span></div>
      <ul class="coverage-list">
        <li><strong>FR-001</strong><span>Structured occurrence reporting</span></li>
        <li><strong>FR-002</strong><span>Visible workflow status model</span></li>
        <li><strong>SMS-001</strong><span>Safety occurrence foundation</span></li>
        <li><strong>UX-003</strong><span>Record workspace</span></li>
        <li><strong>SEC-001</strong><span>Authenticated access</span></li>
      </ul>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>Assurance queue</h2><a class="secondary-button" href="{{ route('audits.index') }}">Audits</a></div>
      <ul class="timeline">
        @foreach ($audits as $audit)
          <li><strong>{{ $audit->reference }}</strong><span>{{ $audit->title }} - {{ $audit->status }}</span></li>
        @endforeach
      </ul>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>Risk watch</h2><a class="secondary-button" href="{{ route('risks.index') }}">Risks</a></div>
      <ul class="timeline">
        @foreach ($risks as $risk)
          <li><strong>{{ $risk->rating }}</strong><span>{{ $risk->hazard }} - {{ $risk->owner }}</span></li>
        @endforeach
      </ul>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>Controlled documents</h2><a class="secondary-button" href="{{ route('documents.index') }}">Documents</a></div>
      <ul class="timeline">
        @foreach ($documents as $document)
          <li><strong>{{ $document->reference }}</strong><span>{{ $document->title }} - {{ $document->status }}</span></li>
        @endforeach
      </ul>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>Notifications</h2><a class="secondary-button" href="{{ route('notifications.index') }}">Inbox</a></div>
      <ul class="timeline">
        @foreach ($notifications as $notification)
          <li><strong>{{ $notification->title }}</strong><span>{{ $notification->source_reference ?? 'QMS' }} - {{ $notification->created_at->format('Y-m-d H:i') }}</span></li>
        @endforeach
      </ul>
    </article>
  </div>
</section>
@endsection
