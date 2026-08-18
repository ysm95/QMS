@extends('qms.layout', ['title' => $report->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Screening workspace</p><h1>{{ $report->title }}</h1></div>
    <span class="status-pill warning">{{ $report->status }}</span>
  </div>

  <div class="record-layout screening-desk">
    <aside class="panel">
      <h2>Review queue</h2>
      <ul class="timeline">
        <li><strong>New</strong><span>{{ $report->where('status', 'Submitted')->count() }} waiting</span></li>
        <li><strong>Returned</strong><span>{{ $report->where('status', 'Returned for Information')->count() }} waiting</span></li>
        <li><strong>Confidential</strong><span>{{ $report->where('confidential', true)->count() }} restricted</span></li>
      </ul>
      <h2>Similar reports</h2>
      <ul class="timeline">
        @forelse ($similarReports as $similar)
          <li><strong><a href="{{ route('reporting.show', $similar) }}">{{ $similar->reference }}</a></strong><span>{{ $similar->type }} - {{ $similar->location }}</span></li>
        @empty
          <li><strong>No similar reports</strong><span>Type and location comparison found no candidates.</span></li>
        @endforelse
      </ul>
    </aside>

    <article class="panel wide">
      <div class="record-head">
        <div><h2>{{ $report->reference }}</h2><p>{{ $report->description }}</p></div>
        <strong class="risk-badge">Severity: {{ $report->severity }}</strong>
      </div>
      <div class="workflow-ribbon compact">
        @foreach (['Draft', 'Submitted', 'Screening', 'Returned for Information', 'Accepted', 'Rejected'] as $stage)
          <span class="{{ $report->workflow_stage === $stage || $report->status === $stage ? 'active' : '' }}">{{ $stage }}</span>
        @endforeach
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
        <a class="primary-button" href="{{ route('incidents.show', $report->incident) }}">Open incident workspace</a>
      @endif
      @if ($report->rejection_reason)
        <h3>Rejection reason</h3>
        <p>{{ $report->rejection_reason }}</p>
      @endif
    </article>

    <aside class="panel">
      <h2>Screening panel</h2>
      @if (! $report->incident && $report->status !== 'Rejected')
        <form method="POST" action="{{ route('reporting.accept', $report) }}">
          @csrf
          <label>Severity<select name="severity">@foreach (['Low', 'Medium', 'High', 'Critical'] as $severity)<option @selected($report->severity === $severity)>{{ $severity }}</option>@endforeach</select></label>
          <label>Classification<input name="classification" value="{{ $report->classification ?? 'Safety Event' }}"></label>
          <label>Department<input name="department" value="{{ $report->department }}"></label>
          <label>Owner<input name="owner" value="Safety Manager"></label>
          <label class="inline-check"><input name="investigation_required" type="checkbox" value="1"> Investigation required</label>
          <label>Screening notes<textarea name="screening_notes" rows="3" placeholder="Why this report is accepted as an incident"></textarea></label>
          <button class="primary-button full">Accept and create incident</button>
        </form>
        <form method="POST" action="{{ route('reporting.reject', $report) }}">
          @csrf
          <label>Rejection reason<textarea name="rejection_reason" rows="3" required placeholder="Reason must be stored for auditability"></textarea></label>
          <label>Internal notes<textarea name="screening_notes" rows="2" placeholder="Internal review note"></textarea></label>
          <button class="secondary-button full">Reject report only</button>
        </form>
      @else
        <div class="empty-state">Screening decision completed. The original report remains auditable.</div>
      @endif
    </aside>
  </div>
</section>
@endsection
