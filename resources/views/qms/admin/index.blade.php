@extends('qms.layout', ['title' => 'Admin Center - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Administration</p><h1>Control center</h1></div></div>
  <div class="content-grid">
    <article class="panel"><h2>Users</h2><ul class="timeline">@foreach ($users as $user)<li><strong>{{ $user->name }}</strong><span>{{ $user->qms_role }} - {{ $user->email }}</span></li>@endforeach</ul></article>
    <article class="panel"><h2>Departments</h2><ul class="timeline">@foreach ($departments as $department)<li><strong>{{ $department->code }}</strong><span>{{ $department->name }}</span></li>@endforeach</ul></article>
    <article class="panel"><h2>Locations</h2><ul class="timeline">@foreach ($locations as $location)<li><strong>{{ $location->code }}</strong><span>{{ $location->name }}</span></li>@endforeach</ul></article>
  </div>
</section>
@endsection
