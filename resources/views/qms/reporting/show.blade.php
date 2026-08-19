@extends('qms.layout', ['title' => $report->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Report record</p><h1>{{ $report->title }}</h1></div>
    <span class="status-pill warning">{{ $report->status }}</span>
  </div>

  <article class="panel wide record-tabs-panel">
    <div class="record-tabs" role="tablist" aria-label="Report sections">
      <a href="#summary">Summary</a>
      <a href="#submission">Submission</a>
      <a href="#comments">Comments</a>
      <a href="#actions">Actions</a>
      <a href="#attachments">Attachments</a>
      <a href="#related">Related</a>
      <a href="#history">History</a>
    </div>

    <section id="summary" class="record-tab-section">
      <div class="record-head">
        <div><h2>{{ $report->reference }}</h2><p>{{ $report->description }}</p></div>
        <strong class="risk-badge">Severity: {{ $report->severity }}</strong>
      </div>
      <div class="detail-grid">
        <div><span>Type</span><strong>{{ $report->type }}</strong></div>
        <div><span>Classification</span><strong>{{ $report->classification ?? 'Not set' }}</strong></div>
        <div><span>Location</span><strong>{{ $report->location ?? 'Not set' }}</strong></div>
        <div><span>Department</span><strong>{{ $report->department ?? 'Not set' }}</strong></div>
        <div><span>Reporter</span><strong>{{ $report->confidential ? 'Confidential' : ($report->reported_by ?? 'Anonymous') }}</strong></div>
        <div><span>Mandatory</span><strong>{{ $report->mandatory ? 'Yes' : 'No' }}</strong></div>
        <div><span>Incident</span><strong>{{ $report->incident?->reference ?? 'Not created' }}</strong></div>
        <div><span>Reported</span><strong>{{ optional($report->reported_at)->format('Y-m-d H:i') }}</strong></div>
      </div>
      @if ($report->incident)
        <a class="primary-button" href="{{ route('incidents.show', $report->incident) }}">Open incident record</a>
      @endif
      @if ($report->rejection_reason)
        <h3>Rejection reason</h3>
        <p>{{ $report->rejection_reason }}</p>
      @endif
    </section>

    <section id="submission" class="record-tab-section">
      <h2>Submission</h2>
      <div class="detail-grid">
        <div><span>Submitted</span><strong>{{ optional($report->submitted_at)->format('Y-m-d H:i') ?? 'Not set' }}</strong></div>
        <div><span>Reported</span><strong>{{ optional($report->reported_at)->format('Y-m-d H:i') ?? 'Not set' }}</strong></div>
        <div><span>Location</span><strong>{{ $report->location ?? 'Not set' }}</strong></div>
        <div><span>Department</span><strong>{{ $report->department ?? 'Not set' }}</strong></div>
      </div>
    </section>

    <section id="comments" class="record-tab-section">
      <h2>Comments</h2>
      <p class="muted-copy">Internal comments and reporter-visible communication are kept separate by visibility rules.</p>
      <div class="empty-state">No comments recorded for this report.</div>
    </section>

    <section id="actions" class="record-tab-section">
      <div class="panel-header"><h2>Review actions</h2><button class="secondary-button" type="button" onclick="window.print()">Print</button></div>
      @if (! $report->incident && $report->status !== 'Rejected')
        <div class="content-grid action-grid">
          <form class="config-form panel" method="POST" action="{{ route('reporting.accept', $report) }}">
            @csrf
            <h3>Accept</h3>
            <label>Severity<select name="severity">@foreach (['Low', 'Medium', 'High', 'Critical'] as $severity)<option @selected($report->severity === $severity)>{{ $severity }}</option>@endforeach</select></label>
            <label>Classification<input name="classification" value="{{ $report->classification ?? 'Safety Event' }}"></label>
            <label>Department<input name="department" value="{{ $report->department }}"></label>
            <label>Owner<input name="owner" value="Safety Manager"></label>
            <label class="inline-check"><input name="investigation_required" type="checkbox" value="1"> Investigation required</label>
            <label>Notes<textarea name="screening_notes" rows="3" placeholder="Why this report is accepted"></textarea></label>
            <button class="primary-button full">Accept and create incident</button>
          </form>
          <form class="config-form panel" method="POST" action="{{ route('reporting.reject', $report) }}">
            @csrf
            <h3>Reject</h3>
            <label>Reason<textarea name="rejection_reason" rows="3" required placeholder="Reason must be stored for auditability"></textarea></label>
            <label>Internal notes<textarea name="screening_notes" rows="2" placeholder="Internal review note"></textarea></label>
            <button class="secondary-button full">Reject report only</button>
          </form>
        </div>
      @else
        <div class="empty-state">Review decision completed. The original report remains auditable.</div>
      @endif
    </section>

    <section id="attachments" class="record-tab-section">
      <h2>Attachments</h2>
      <div class="empty-state">No attachments recorded for this report.</div>
    </section>

    <section id="related" class="record-tab-section">
      <h2>Related</h2>
      <ul class="timeline compact-list">
        @if ($report->incident)
          <li><strong><a href="{{ route('incidents.show', $report->incident) }}">{{ $report->incident->reference }}</a></strong><span>Incident created from this accepted report</span></li>
        @endif
        @forelse ($similarReports as $similar)
          <li><strong><a href="{{ route('reporting.show', $similar) }}">{{ $similar->reference }}</a></strong><span>{{ $similar->type }} - {{ $similar->location }}</span></li>
        @empty
          <li><strong>No similar reports</strong><span>Type and location comparison found no candidates.</span></li>
        @endforelse
      </ul>
    </section>

    <section id="history" class="record-tab-section">
      <h2>History</h2>
      <ul class="timeline compact-list">
        <li><strong>{{ $report->status }}</strong><span>Current report status</span></li>
        <li><strong>{{ $report->workflow_stage ?? 'Submitted' }}</strong><span>Current review stage</span></li>
      </ul>
    </section>
  </article>
</section>
@endsection
