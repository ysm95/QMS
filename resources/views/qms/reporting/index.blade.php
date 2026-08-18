@extends('qms.layout', ['title' => 'Reporting Catalogue - QMS'])

@section('content')
<section class="view active-view reporting-catalogue">
  <div class="page-title">
    <div>
      <p class="eyebrow">BRSD FR-001 / SMS reporting</p>
      <h1>Reporting desk</h1>
    </div>
    <span class="status-pill success">Sync ready</span>
  </div>

  <div class="metric-grid">
    <article class="metric"><span>Submitted</span><strong>{{ $screeningCounts['submitted'] }}</strong><small>Awaiting screening</small></article>
    <article class="metric"><span>Returned</span><strong>{{ $screeningCounts['returned'] }}</strong><small>More information</small></article>
    <article class="metric"><span>Accepted</span><strong>{{ $screeningCounts['accepted'] }}</strong><small>Incident created</small></article>
    <article class="metric"><span>Rejected</span><strong>{{ $screeningCounts['rejected'] }}</strong><small>Report only</small></article>
    <article class="metric"><span>Confidential</span><strong>{{ $screeningCounts['confidential'] }}</strong><small>Restricted identity</small></article>
  </div>

  <div class="reporting-frame">
    <aside class="mobile-menu-panel">
      <div class="mobile-user"><span>Signed in</span><strong>{{ auth()->user()->name }}</strong></div>
      <a href="{{ route('qms.dashboard') }}">Home</a>
      <a class="active" href="{{ route('reporting.index') }}">Reporting</a>
      <a href="{{ route('occurrences.index') }}">Documents</a>
      <a href="{{ route('admin.index') }}">Settings</a>
      <form method="POST" action="{{ route('logout') }}">@csrf<button>Logout</button></form>
    </aside>

    <div class="report-list-panel">
      <div class="catalogue-header">
        <h2>Choose report type</h2>
        <p>Fast aviation and enterprise reporting modelled from the DOR guide structure, redesigned for QMS.</p>
      </div>

      <div class="report-type-list">
        @foreach ($reportTypes as $key => $report)
          <a class="report-type-row" href="{{ route('reporting.create', $key) }}">
            <span class="report-logo">{{ substr($report['module'], 0, 1) }}</span>
            <span class="report-copy">
              <strong>{{ $report['title'] }}</strong>
              <small>{{ $report['description'] }}</small>
            </span>
            <em>{{ $report['priority'] }}</em>
          </a>
        @endforeach
      </div>
    </div>
  </div>

  <div class="sync-bar"><span>Syncing</span><strong></strong></div>

  <article class="panel wide">
    <div class="panel-header"><h2>Screening queue</h2><span class="status-pill">Report != Incident</span></div>
    <div class="table-panel"><table><thead><tr><th>Report</th><th>Title</th><th>Type</th><th>Status</th><th>Severity</th><th>Reporter</th><th>Incident</th></tr></thead><tbody>
      @foreach ($reports as $report)
        <tr>
          <td><a href="{{ route('reporting.show', $report) }}">{{ $report->reference }}</a></td>
          <td>{{ $report->title }}</td>
          <td>{{ $report->type }}</td>
          <td><span class="status-pill">{{ $report->status }}</span></td>
          <td><span class="risk-badge">{{ $report->severity }}</span></td>
          <td>{{ $report->confidential ? 'Confidential' : ($report->reported_by ?? 'Anonymous') }}</td>
          <td>{{ $report->incident?->reference ?? 'Not created' }}</td>
        </tr>
      @endforeach
    </tbody></table></div>
    <div class="pager">{{ $reports->links() }}</div>
  </article>
</section>
@endsection
