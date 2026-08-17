@extends('qms.layout', ['title' => 'Reporting Catalogue - QMS'])

@section('content')
<section class="view active-view reporting-catalogue">
  <div class="page-title">
    <div>
      <p class="eyebrow">BRSD FR-001 / SMS reporting</p>
      <h1>Reporting catalogue</h1>
    </div>
    <span class="status-pill success">Sync ready</span>
  </div>

  <div class="reporting-frame">
    <aside class="mobile-menu-panel">
      <div class="mobile-user"><span>Signed in</span><strong>{{ auth()->user()->name }}</strong></div>
      <a href="{{ route('qms.dashboard') }}">Home</a>
      <a class="active" href="{{ route('reporting.index') }}">Reporting</a>
      <a href="{{ route('occurrences.index') }}">Documents</a>
      <a href="{{ route('admin.index') }}">Settings</a>
      <form method="POST" action="{{ route('logout') }}">@csrf<button>Logout</button></form>
    </aside>

    <div class="report-list-panel">
      <div class="catalogue-header">
        <h2>Choose report type</h2>
        <p>Fast aviation and enterprise reporting modelled from the DOR guide structure, redesigned for QMS.</p>
      </div>

      <div class="report-type-list">
        @foreach ($reportTypes as $key => $report)
          <a class="report-type-row" href="{{ route('reporting.create', $key) }}">
            <span class="report-logo">{{ substr($report['module'], 0, 1) }}</span>
            <span class="report-copy">
              <strong>{{ $report['title'] }}</strong>
              <small>{{ $report['description'] }}</small>
            </span>
            <em>{{ $report['priority'] }}</em>
          </a>
        @endforeach
      </div>
    </div>
  </div>

  <div class="sync-bar"><span>Syncing</span><strong></strong></div>
</section>
@endsection
