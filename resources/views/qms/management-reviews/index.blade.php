@extends('qms.layout', ['title' => 'Management Review - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Leadership review</p><h1>Management reviews</h1></div><span class="status-pill">ISO / SMS review</span></div>
  <form class="filter-bar" method="GET" action="{{ route('management-reviews.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search review by %text%, chair, decision">
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button><a class="secondary-button" href="{{ route('management-reviews.index') }}">Clear</a>
  </form>
  <div class="content-grid">
    @foreach ($reviews as $review)
      <article class="panel"><div class="panel-header"><h2>{{ $review->reference }}</h2><span class="status-pill">{{ $review->status }}</span></div><p><strong>{{ $review->title }}</strong></p><p>Chair: {{ $review->chair }}</p><p>Date: {{ optional($review->meeting_date)->format('Y-m-d') }}</p><ul class="check-list">@foreach (($review->inputs ?? []) as $input)<li>{{ $input }}</li>@endforeach</ul><p>{{ $review->decisions }}</p></article>
    @endforeach
  </div>
  @if ($reviews->isEmpty())<div class="empty-state">No management reviews match this filter.</div>@endif
  <div class="pager">{{ $reviews->links() }}</div>
</section>
@endsection
