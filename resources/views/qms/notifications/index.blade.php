@extends('qms.layout', ['title' => 'Notifications - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Operational inbox</p><h1>Notifications</h1></div></div>
  <form class="filter-bar" method="GET" action="{{ route('notifications.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search notification by %text%, source, message">
    <select name="status"><option value="">All</option><option value="unread" @selected(request('status') === 'unread')>Unread</option></select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('notifications.index') }}">Clear</a>
  </form>

  <div class="table-panel"><table><thead><tr><th>Title</th><th>Source</th><th>Message</th><th>Date</th><th>Status</th><th>Action</th></tr></thead><tbody>
    @foreach ($notifications as $notification)
      <tr>
        <td>{{ $notification->title }}</td>
        <td>{{ $notification->source_reference ?? 'QMS' }}</td>
        <td>{{ $notification->body }}</td>
        <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
        <td><span class="status-pill {{ $notification->read_at ? 'success' : 'warning' }}">{{ $notification->read_at ? 'Read' : 'Unread' }}</span></td>
        <td>@if (! $notification->read_at)<form method="POST" action="{{ route('notifications.read', $notification) }}">@csrf @method('PATCH')<button class="secondary-button">Mark read</button></form>@endif</td>
      </tr>
    @endforeach
  </tbody></table></div>
  @if ($notifications->isEmpty())<div class="empty-state">No notifications match this filter.</div>@endif
  <div class="pager">{{ $notifications->links() }}</div>
</section>
@endsection
