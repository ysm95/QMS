@extends('qms.layout', ['title' => 'Compliance - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Standards registry</p><h1>Compliance</h1></div><span class="status-pill success">Versioned mappings</span></div>
  <form class="filter-bar" method="GET" action="{{ route('compliance.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search framework by %text%, owner, code">
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('compliance.index') }}">Clear</a>
  </form>

  <article class="panel wide">
    <div class="panel-header"><h2>Standards and regulations</h2><span class="status-pill">No licensed text stored</span></div>
    <div class="table-panel">
      <table>
        <thead><tr><th>Standard</th><th>Issuer</th><th>Edition</th><th>Status</th><th>Effective</th><th>Owner</th></tr></thead>
        <tbody>
          @forelse ($standards as $standard)
            <tr>
              <td><strong>{{ $standard->code }}</strong><br>{{ $standard->title }}</td>
              <td>{{ $standard->issuer }}</td>
              <td>{{ $standard->edition ?? 'Not set' }}</td>
              <td><span class="status-pill">{{ $standard->publication_status }}</span></td>
              <td>{{ optional($standard->effective_date)->format('Y-m-d') ?? 'Pending' }}</td>
              <td>{{ $standard->owner ?? 'Unassigned' }}</td>
            </tr>
          @empty
            <tr><td colspan="6">No standards have been registered.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </article>

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

  <div class="content-grid">
    <article class="panel">
      <div class="panel-header"><h2>Requirement mappings</h2><span class="status-pill">Internal interpretation</span></div>
      <ul class="timeline compact-list">
        @forelse ($requirements as $requirement)
          <li><strong>{{ $requirement->requirement_key }}</strong><span>{{ $requirement->heading }} - {{ $requirement->status }}</span></li>
        @empty
          <li><strong>Not mapped</strong><span>Add internal controls and evidence before audits.</span></li>
        @endforelse
      </ul>
    </article>
    <article class="panel">
      <div class="panel-header"><h2>Change impact</h2><span class="status-pill warning">Human assessment</span></div>
      <ul class="timeline compact-list">
        @forelse ($changes as $change)
          <li><strong>{{ $change->reference }}</strong><span>{{ $change->change_type }} - {{ $change->status }}</span></li>
        @empty
          <li><strong>No active changes</strong><span>Standard changes appear here for impact assessment.</span></li>
        @endforelse
      </ul>
    </article>
    <article class="panel">
      <div class="panel-header"><h2>Taxonomy registry</h2><span class="status-pill">Versioned terms</span></div>
      <ul class="timeline compact-list">
        @forelse ($taxonomies as $term)
          <li><strong>{{ $term->code }}</strong><span>{{ $term->taxonomy }} - {{ $term->label }}</span></li>
        @empty
          <li><strong>No terms</strong><span>Governed aviation/QMS taxonomy will appear here.</span></li>
        @endforelse
      </ul>
    </article>
  </div>
</section>
@endsection
