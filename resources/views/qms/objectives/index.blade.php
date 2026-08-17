@extends('qms.layout', ['title' => 'Objectives - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Objectives / SPI / KPI</p><h1>Performance objectives</h1></div><span class="status-pill success">Management system</span></div>
  <form class="filter-bar" method="GET" action="{{ route('objectives.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search objective by %text%, owner, measure">
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button><a class="secondary-button" href="{{ route('objectives.index') }}">Clear</a>
  </form>
  <div class="table-panel"><table><thead><tr><th>Reference</th><th>Objective</th><th>Owner</th><th>Measure</th><th>Target</th><th>Current</th><th>Status</th><th>Review</th></tr></thead><tbody>
    @foreach ($objectives as $objective)
      <tr><td>{{ $objective->reference }}</td><td>{{ $objective->title }}</td><td>{{ $objective->owner }}</td><td>{{ $objective->measure }}</td><td>{{ $objective->target }}</td><td>{{ $objective->current_value }}</td><td><span class="status-pill">{{ $objective->status }}</span></td><td>{{ optional($objective->review_date)->format('Y-m-d') }}</td></tr>
    @endforeach
  </tbody></table></div>
  @if ($objectives->isEmpty())<div class="empty-state">No objectives match this filter.</div>@endif
  <div class="pager">{{ $objectives->links() }}</div>
</section>
@endsection
