@extends('qms.layout', ['title' => 'Occurrences - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Occurrence management</p><h1>Records</h1></div>
    <a class="primary-button" href="{{ route('occurrences.create') }}">Submit occurrence</a>
  </div>

  <form class="filter-bar" method="GET" action="{{ route('occurrences.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search by %text%, reference, reporter, type, location">
    <select name="stage"><option value="">All stages</option>@foreach ($stages as $stage)<option value="{{ $stage }}" @selected(request('stage') === $stage)>{{ $stage }}</option>@endforeach</select>
    <select name="risk"><option value="">All risks</option>@foreach ($risks as $risk)<option value="{{ $risk }}" @selected(request('risk') === $risk)>{{ $risk }}</option>@endforeach</select>
    <select name="type"><option value="">All types</option>@foreach ($types as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('occurrences.index') }}">Clear</a>
  </form>

  <div class="table-panel">
    <table>
      <thead><tr><th>Reference</th><th>Title</th><th>Type</th><th>Stage</th><th>Risk</th><th>Reporter</th><th>Date</th></tr></thead>
      <tbody>
        @foreach ($occurrences as $occurrence)
          <tr>
            <td><a href="{{ route('occurrences.show', $occurrence) }}">{{ $occurrence->reference }}</a></td>
            <td>{{ $occurrence->title }}</td>
            <td>{{ $occurrence->type }}</td>
            <td><span class="status-pill">{{ $occurrence->workflow_stage }}</span></td>
            <td><span class="risk-badge">{{ $occurrence->risk_rating }}</span></td>
            <td>{{ $occurrence->reported_by }}</td>
            <td>{{ optional($occurrence->event_date)->format('Y-m-d') ?? optional($occurrence->reported_at)->format('Y-m-d') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @if ($occurrences->isEmpty())
      <div class="empty-state">No occurrence records match this filter.</div>
    @endif
  </div>
  <div class="pager">{{ $occurrences->links() }}</div>
</section>
@endsection
