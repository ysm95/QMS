@extends('qms.layout', ['title' => 'Inspections - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Field assurance</p><h1>Inspections</h1></div>
    <span class="status-pill">Tablet and mobile ready</span>
  </div>

  <form class="filter-bar unified-filter" method="GET" action="{{ route('inspections.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search inspection, station, inspector, type">
    <select name="status">
      <option value="">Any status</option>
      @foreach ($statuses as $status)
        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('inspections.index') }}">Clear</a>
  </form>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Inspection runs</h2><span class="status-pill">Pass / Fail / N/A</span></div>
      <div class="table-panel">
        <table>
          <thead><tr><th>Inspection</th><th>Type</th><th>Station</th><th>Status</th><th>Progress</th><th>Inspector</th><th>Scheduled</th></tr></thead>
          <tbody>
            @forelse ($inspections as $inspection)
              <tr>
                <td><strong>{{ $inspection->reference }}</strong><br>{{ $inspection->title }}</td>
                <td>{{ $inspection->inspection_type }}</td>
                <td>{{ $inspection->station ?? 'Not set' }}</td>
                <td><span class="status-pill">{{ $inspection->status }}</span></td>
                <td>{{ $inspection->passed_count }} pass / {{ $inspection->failed_count }} fail / {{ $inspection->not_applicable_count }} N/A</td>
                <td>{{ $inspection->inspector ?? 'Unassigned' }}</td>
                <td>{{ optional($inspection->scheduled_date)->format('Y-m-d') ?? 'Not scheduled' }}</td>
              </tr>
            @empty
              <tr><td colspan="7">No inspections match this filter.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="pager">{{ $inspections->links() }}</div>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>Open findings</h2><span class="status-pill warning">From failed items</span></div>
      <ul class="timeline compact-list">
        @forelse ($findings as $finding)
          <li><strong>{{ $finding->reference }}</strong><span>{{ $finding->finding_type }} - {{ $finding->status }}</span></li>
        @empty
          <li><strong>Clear</strong><span>No inspection findings need attention.</span></li>
        @endforelse
      </ul>
    </article>
  </div>
</section>
@endsection
