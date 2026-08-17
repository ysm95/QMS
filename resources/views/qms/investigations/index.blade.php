@extends('qms.layout', ['title' => 'Investigations - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Investigation management</p><h1>Investigations</h1></div></div>
  <div class="content-grid">
    @foreach ($investigations as $investigation)
      <a class="panel" href="{{ route('investigations.show', $investigation) }}"><h2>{{ $investigation->reference }}</h2><p>{{ $investigation->title }}</p><span class="status-pill">{{ $investigation->status }}</span></a>
    @endforeach
  </div>
  <div class="pager">{{ $investigations->links() }}</div>
</section>
@endsection
