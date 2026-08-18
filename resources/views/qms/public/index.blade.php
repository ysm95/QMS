@extends('qms.layout', ['title' => 'Public Intake - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Public reporting intake</p><h1>External and confidential reports</h1></div>
    <a class="secondary-button" href="{{ route('portal.report') }}">Open portal</a>
  </div>

  <div class="metric-grid compact-metrics">
    <article class="metric"><span>New reports</span><strong>{{ $metrics['new'] }}</strong><small>Awaiting triage</small></article>
    <article class="metric"><span>Confidential</span><strong>{{ $metrics['confidential'] }}</strong><small>Restricted handling</small></article>
    <article class="metric"><span>Anonymous</span><strong>{{ $metrics['anonymous'] }}</strong><small>Reporter identity hidden</small></article>
  </div>

  <form class="filter-bar" method="GET" action="{{ route('public-reports.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search by %text%, reference, category, location">
    <select name="status">
      <option value="">Any status</option>
      @foreach (['New', 'Screening', 'Converted', 'Closed', 'Rejected'] as $status)
        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('public-reports.index') }}">Clear</a>
  </form>

  <article class="panel wide">
    <div class="panel-header"><h2>Reports</h2><span class="status-pill">Screen before conversion</span></div>
    <div class="table-panel">
      <table>
        <thead><tr><th>Reference</th><th>Category</th><th>Reporter</th><th>Location</th><th>Status</th><th>Description</th></tr></thead>
        <tbody>
          @forelse ($reports as $report)
            <tr>
              <td>{{ $report->reference }}</td>
              <td>{{ $report->category }}</td>
              <td>{{ $report->anonymous ? 'Anonymous' : ($report->reporter_name ?: 'Not provided') }}{{ $report->confidential ? ' / Confidential' : '' }}</td>
              <td>{{ $report->location ?: 'Not provided' }}</td>
              <td><span class="status-pill {{ $report->confidential ? 'warning' : '' }}">{{ $report->status }}</span></td>
              <td>{{ str($report->description)->limit(110) }}</td>
            </tr>
          @empty
            <tr><td colspan="6" class="empty-state">No public reports found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pager">{{ $reports->links() }}</div>
  </article>
</section>
@endsection
