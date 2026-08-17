@extends('qms.layout', ['title' => $investigation->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Investigation workspace</p><h1>{{ $investigation->title }}</h1></div><span class="status-pill">{{ $investigation->status }}</span></div>
  <div class="content-grid">
    <article class="panel wide"><h2>Scope</h2><p>{{ $investigation->scope }}</p><h2>Findings</h2><p>{{ $investigation->findings }}</p></article>
    <article class="panel"><h2>Analysis tools</h2><ul class="check-list"><li>5 Whys</li><li>Fishbone</li><li>Bow-tie</li><li>SHELL</li></ul></article>
  </div>
</section>
@endsection
