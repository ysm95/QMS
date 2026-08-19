@extends('qms.layout', ['title' => 'Reports - QMS'])

@section('content')
<section class="view active-view reporting-catalogue">
  <div class="page-title">
    <div>
      <p class="eyebrow">Central workspace</p>
      <h1>Reports</h1>
    </div>
    <a class="primary-button" href="#new-report">New report</a>
  </div>

  <div class="saved-view-row" aria-label="Saved views">
    <a class="status-pill {{ $activeFilters['status'] === '' ? 'success' : '' }}" href="{{ route('reporting.index') }}">All reports</a>
    <a class="status-pill" href="{{ route('reporting.index', ['status' => 'Submitted']) }}">Awaiting review</a>
    <a class="status-pill" href="{{ route('reporting.index', ['status' => 'Returned for Information']) }}">Information required</a>
    <a class="status-pill" href="{{ route('reporting.index', ['status' => 'Accepted']) }}">Accepted</a>
    <a class="status-pill" href="{{ route('reporting.index', ['search' => 'confidential']) }}">Confidential</a>
  </div>

  <form class="filter-bar unified-filter" method="GET" action="{{ route('reporting.index') }}">
    <input name="search" type="search" value="{{ $activeFilters['search'] }}" placeholder="Search reports, reporter, location, type">
    <select name="status">
      <option value="">Any status</option>
      @foreach (['Submitted', 'Returned for Information', 'Accepted', 'Rejected'] as $status)
        <option value="{{ $status }}" @selected($activeFilters['status'] === $status)>{{ $status }}</option>
      @endforeach
    </select>
    <select name="type">
      <option value="">Any type</option>
      @foreach (collect($reportTypes)->pluck('type')->unique()->sort() as $type)
        <option value="{{ $type }}" @selected($activeFilters['type'] === $type)>{{ $type }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('reporting.index') }}">Clear</a>
  </form>

  <div class="metric-grid compact-metrics">
    <article class="metric"><span>Submitted</span><strong>{{ $screeningCounts['submitted'] }}</strong><small>Awaiting review</small></article>
    <article class="metric"><span>Returned</span><strong>{{ $screeningCounts['returned'] }}</strong><small>More information</small></article>
    <article class="metric"><span>Accepted</span><strong>{{ $screeningCounts['accepted'] }}</strong><small>Incident created</small></article>
    <article class="metric"><span>Rejected</span><strong>{{ $screeningCounts['rejected'] }}</strong><small>Report only</small></article>
    <article class="metric"><span>Confidential</span><strong>{{ $screeningCounts['confidential'] }}</strong><small>Restricted identity</small></article>
  </div>

  <article class="panel wide reports-workspace">
    <div class="panel-header"><h2>Report list</h2><span class="status-pill">Reports remain separate from incidents</span></div>
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

  <article class="panel wide" id="new-report">
    <div class="panel-header"><h2>Report types</h2><span class="status-pill">Progressive disclosure</span></div>
    <div class="report-type-list compact-report-types">
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
  </article>
</section>
@endsection
