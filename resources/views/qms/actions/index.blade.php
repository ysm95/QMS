@extends('qms.layout', ['title' => 'CAPA Actions - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">CAPA</p><h1>Actions</h1></div><a class="secondary-button" href="{{ route('exports.actions') }}">Export CSV</a></div>

  <form class="filter-bar" method="GET" action="{{ route('actions.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search action by %text%, owner, evidence, source">
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <select name="priority"><option value="">All priorities</option>@foreach ($priorities as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('actions.index') }}">Clear</a>
  </form>

  <div class="table-panel"><table><thead><tr><th>ID</th><th>Source</th><th>Action</th><th>Owner</th><th>Priority</th><th>Due</th><th>Status</th><th>Progress</th><th>Update</th></tr></thead><tbody>
    @foreach ($actions as $action)
      <tr>
        <td>{{ $action->reference }}</td>
        <td>{{ $action->source_reference ?? 'Standalone' }}</td>
        <td>{{ $action->title }}</td>
        <td>{{ $action->owner }}</td>
        <td>{{ $action->priority }}</td>
        <td>{{ optional($action->due_date)->format('Y-m-d') }}</td>
        <td><span class="status-pill {{ optional($action->due_date)->isPast() && ! in_array($action->status, ['Closed', 'Verified']) ? 'warning' : '' }}">{{ $action->status }}</span></td>
        <td>{{ $action->progress ?? 0 }}%</td>
        <td><form class="inline-update" method="POST" action="{{ route('actions.update', $action) }}">@csrf @method('PATCH')<input name="evidence" value="{{ $action->evidence }}" placeholder="Evidence note"><input name="progress" type="number" min="0" max="100" value="{{ $action->progress ?? 0 }}"><select name="status">@foreach (['Open', 'Assigned', 'Accepted', 'In progress', 'Evidence Submitted', 'Verification', 'Returned', 'Effectiveness Review', 'Closed', 'Verified'] as $status)<option @selected($action->status === $status)>{{ $status }}</option>@endforeach</select><button class="secondary-button">Save</button></form></td>
      </tr>
    @endforeach
  </tbody></table></div>
  @if ($actions->isEmpty())
    <div class="empty-state">No CAPA actions match this filter.</div>
  @endif
  <div class="pager">{{ $actions->links() }}</div>
</section>
@endsection
