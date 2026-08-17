@extends('qms.layout', ['title' => 'Compliance - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Framework mapping</p><h1>Compliance frameworks</h1></div><span class="status-pill success">Evidence ready</span></div>
  <form class="filter-bar" method="GET" action="{{ route('compliance.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search framework by %text%, owner, code">
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('compliance.index') }}">Clear</a>
  </form>

  <div class="content-grid">
    @foreach ($frameworks as $framework)
      <article class="panel">
        <div class="panel-header"><h2>{{ $framework->code }}</h2><span class="status-pill">{{ $framework->status }}</span></div>
        <p><strong>{{ $framework->name }}</strong></p>
        <p>Owner: {{ $framework->owner }}</p>
        <ul class="check-list">
          @foreach (($framework->requirements ?? []) as $requirement)
            <li>{{ $requirement }}</li>
          @endforeach
        </ul>
      </article>
    @endforeach
  </div>
  @if ($frameworks->isEmpty())<div class="empty-state">No compliance frameworks match this filter.</div>@endif
  <div class="pager">{{ $frameworks->links() }}</div>
</section>
@endsection
