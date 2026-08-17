@extends('qms.layout', ['title' => 'Training - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Competence and awareness</p><h1>Training records</h1></div><span class="status-pill success">Competency control</span></div>
  <form class="filter-bar" method="GET" action="{{ route('training.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search training by %text%, person, course">
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button><a class="secondary-button" href="{{ route('training.index') }}">Clear</a>
  </form>
  <div class="table-panel"><table><thead><tr><th>Reference</th><th>Person</th><th>Course</th><th>Competency</th><th>Completed</th><th>Expires</th><th>Status</th></tr></thead><tbody>
    @foreach ($records as $record)
      <tr><td>{{ $record->reference }}</td><td>{{ $record->person_name }}</td><td>{{ $record->course }}</td><td>{{ $record->competency_area }}</td><td>{{ optional($record->completed_on)->format('Y-m-d') }}</td><td>{{ optional($record->expires_on)->format('Y-m-d') }}</td><td><span class="status-pill">{{ $record->status }}</span></td></tr>
    @endforeach
  </tbody></table></div>
  @if ($records->isEmpty())<div class="empty-state">No training records match this filter.</div>@endif
  <div class="pager">{{ $records->links() }}</div>
</section>
@endsection
