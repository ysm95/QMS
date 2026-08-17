@extends('qms.layout', ['title' => 'CAPA Actions - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">CAPA</p><h1>Actions</h1></div></div>
  <div class="table-panel"><table><thead><tr><th>ID</th><th>Action</th><th>Owner</th><th>Due</th><th>Status</th><th>Update</th></tr></thead><tbody>
    @foreach ($actions as $action)
      <tr><td>{{ $action->reference }}</td><td>{{ $action->title }}</td><td>{{ $action->owner }}</td><td>{{ optional($action->due_date)->format('Y-m-d') }}</td><td>{{ $action->status }}</td><td><form method="POST" action="{{ route('actions.update', $action) }}">@csrf @method('PATCH')<input name="evidence" placeholder="Evidence note"><select name="status"><option>Open</option><option>In progress</option><option>Verification</option><option>Closed</option></select><button class="secondary-button">Save</button></form></td></tr>
    @endforeach
  </tbody></table></div>
  <div class="pager">{{ $actions->links() }}</div>
</section>
@endsection
