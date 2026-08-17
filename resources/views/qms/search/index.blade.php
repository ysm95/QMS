@extends('qms.layout', ['title' => 'Search - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Authorized search</p><h1>Global search</h1></div></div>
  <form class="filter-bar" method="GET" action="{{ route('search.index') }}">
    <input name="q" type="search" value="{{ $term }}" placeholder="Search by %text% across QMS records">
    <button class="secondary-button">Search</button>
  </form>

  <div class="content-grid search-results-grid">
    <article class="panel"><h2>Occurrences</h2><ul class="timeline">@forelse ($occurrences as $record)<li><strong><a href="{{ route('occurrences.show', $record) }}">{{ $record->reference }}</a></strong><span>{{ $record->title }}</span></li>@empty<li><strong>None</strong><span>No occurrence matches.</span></li>@endforelse</ul></article>
    <article class="panel"><h2>Actions</h2><ul class="timeline">@forelse ($actions as $record)<li><strong>{{ $record->reference }}</strong><span>{{ $record->title }} - {{ $record->owner }}</span></li>@empty<li><strong>None</strong><span>No action matches.</span></li>@endforelse</ul></article>
    <article class="panel"><h2>Audits</h2><ul class="timeline">@forelse ($audits as $record)<li><strong>{{ $record->reference }}</strong><span>{{ $record->title }}</span></li>@empty<li><strong>None</strong><span>No audit matches.</span></li>@endforelse</ul></article>
    <article class="panel"><h2>Risks</h2><ul class="timeline">@forelse ($risks as $record)<li><strong>{{ $record->reference }}</strong><span>{{ $record->hazard }}</span></li>@empty<li><strong>None</strong><span>No risk matches.</span></li>@endforelse</ul></article>
    <article class="panel"><h2>Documents</h2><ul class="timeline">@forelse ($documents as $record)<li><strong>{{ $record->reference }}</strong><span>{{ $record->title }}</span></li>@empty<li><strong>None</strong><span>No document matches.</span></li>@endforelse</ul></article>
  </div>
</section>
@endsection
