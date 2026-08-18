@extends('qms.layout', ['title' => 'Incidents - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Incident management</p><h1>Accepted incidents</h1></div>
    <a class="primary-button" href="{{ route('reporting.index') }}">Screen reports</a>
  </div>

  <form class="filter-bar" method="GET" action="{{ route('incidents.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search incident by %text%, report, owner, type">
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <select name="severity"><option value="">All severities</option>@foreach ($severities as $severity)<option value="{{ $severity }}" @selected(request('severity') === $severity)>{{ $severity }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('incidents.index') }}">Clear</a>
  </form>

  <div class="table-panel">
    <table>
      <thead><tr><th>Incident</th><th>Source report</th><th>Title</th><th>Stage</th><th>Severity</th><th>Owner</th><th>Accepted</th></tr></thead>
      <tbody>
        @foreach ($incidents as $incident)
          <tr>
            <td><a href="{{ route('incidents.show', $incident) }}">{{ $incident->reference }}</a></td>
            <td><a href="{{ route('reporting.show', $incident->sourceReport) }}">{{ $incident->source_report_reference }}</a></td>
            <td>{{ $incident->title }}</td>
            <td><span class="status-pill">{{ $incident->workflow_stage }}</span></td>
            <td><span class="risk-badge">{{ $incident->severity }}</span></td>
            <td>{{ $incident->owner ?? 'Unassigned' }}</td>
            <td>{{ optional($incident->accepted_at)->format('Y-m-d') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @if ($incidents->isEmpty())
      <div class="empty-state">No accepted incidents match this filter.</div>
    @endif
  </div>
  <div class="pager">{{ $incidents->links() }}</div>
</section>
@endsection
