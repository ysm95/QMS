@extends('qms.layout', ['title' => 'Admin Center - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Administration</p><h1>Control center</h1></div></div>

  <div class="metric-grid compact-metrics">
    @foreach ($moduleCounts as $module => $count)
      <article class="metric"><span>{{ $module }}</span><strong>{{ $count }}</strong><small>Seeded / live records</small></article>
    @endforeach
  </div>

  <form class="filter-bar" method="GET" action="{{ route('admin.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search users by %text%, role, email, job">
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('admin.index') }}">Clear</a>
  </form>

  <div class="content-grid">
    <article class="panel wide"><h2>Users</h2><ul class="timeline">@foreach ($users as $user)<li><strong>{{ $user->name }}</strong><span>{{ $user->qms_role }} - {{ $user->email }} - {{ $user->job_title }}</span></li>@endforeach</ul><div class="pager">{{ $users->links() }}</div></article>
    <article class="panel"><h2>Departments</h2><ul class="timeline">@foreach ($departments as $department)<li><strong>{{ $department->code }}</strong><span>{{ $department->name }}</span></li>@endforeach</ul></article>
    <article class="panel"><h2>Locations</h2><ul class="timeline">@foreach ($locations as $location)<li><strong>{{ $location->code }}</strong><span>{{ $location->name }}</span></li>@endforeach</ul></article>
  </div>
</section>
@endsection
