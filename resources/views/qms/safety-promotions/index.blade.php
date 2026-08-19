@extends('qms.layout', ['title' => 'Lessons Learned - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Safety promotion</p><h1>Lessons learned</h1></div>
    <span class="status-pill">De-identified before publication</span>
  </div>

  <form class="filter-bar unified-filter" method="GET" action="{{ route('safety-promotions.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search lesson, reference, learning">
    <select name="status">
      <option value="">Any status</option>
      @foreach ($statuses as $status)
        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('safety-promotions.index') }}">Clear</a>
  </form>

  <div class="content-grid">
    @forelse ($lessons as $lesson)
      <article class="panel">
        <div class="panel-header"><h2>{{ $lesson->reference }}</h2><span class="status-pill">{{ $lesson->approval_status }}</span></div>
        <p><strong>{{ $lesson->title }}</strong></p>
        <p>{{ $lesson->deidentified_learning }}</p>
        <p class="muted-copy">Confidentiality review: {{ $lesson->confidentiality_review }}</p>
      </article>
    @empty
      <div class="empty-state">No lessons learned match this filter.</div>
    @endforelse
  </div>
  <div class="pager">{{ $lessons->links() }}</div>
</section>
@endsection
