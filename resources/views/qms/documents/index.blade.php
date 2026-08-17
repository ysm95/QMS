@extends('qms.layout', ['title' => 'Documents - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Controlled information</p><h1>Documents</h1></div><div class="button-row"><a class="secondary-button" href="{{ route('exports.documents') }}">Export CSV</a><span class="status-pill success">Document control</span></div></div>

  <form class="filter-bar" method="GET" action="{{ route('documents.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search document by %text%, owner, version">
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('documents.index') }}">Clear</a>
  </form>

  <div class="table-panel"><table><thead><tr><th>Reference</th><th>Title</th><th>Version</th><th>Owner</th><th>Status</th><th>Review</th><th>Update</th></tr></thead><tbody>
    @foreach ($documents as $document)
      <tr>
        <td>{{ $document->reference }}</td><td>{{ $document->title }}</td><td>{{ $document->version }}</td><td>{{ $document->owner }}</td><td><span class="status-pill">{{ $document->status }}</span></td><td>{{ optional($document->review_date)->format('Y-m-d') }}</td>
        <td><form class="inline-update" method="POST" action="{{ route('documents.update', $document) }}">@csrf @method('PATCH')<input name="version" value="{{ $document->version }}"><select name="status">@foreach (['Draft', 'Review', 'Approved', 'Published', 'Archived'] as $status)<option @selected($document->status === $status)>{{ $status }}</option>@endforeach</select><input type="date" name="review_date" value="{{ optional($document->review_date)->format('Y-m-d') }}"><button class="secondary-button">Save</button></form></td>
      </tr>
    @endforeach
  </tbody></table></div>
  @if ($documents->isEmpty())<div class="empty-state">No controlled documents match this filter.</div>@endif
  <div class="pager">{{ $documents->links() }}</div>
</section>
@endsection
