@extends('qms.layout', ['title' => 'Administration - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div>
      <p class="eyebrow">Administration</p>
      <h1>Control center</h1>
    </div>
    <div class="button-row">
      <a class="secondary-button" href="{{ route('platform.index') }}">Configuration</a>
      <a class="secondary-button" href="{{ route('ai.index') }}">Controlled AI</a>
    </div>
  </div>

  <div class="metric-grid compact-metrics">
    @foreach ($summary as $label => $count)
      <article class="metric"><span>{{ $label }}</span><strong>{{ $count }}</strong><small>Live configuration</small></article>
    @endforeach
  </div>

  <div class="admin-group-grid">
    @foreach ($workspaces as $workspace)
      <a class="admin-group-card" href="{{ route($workspace['route']) }}">
        <div class="panel-header">
          <h2>{{ $workspace['name'] }}</h2>
          <span class="status-pill {{ $workspace['status'] === 'Blocked' ? 'warning' : ($workspace['status'] === 'Ready' ? 'success' : '') }}">{{ $workspace['status'] }}</span>
        </div>
        <p>{{ $workspace['purpose'] }}</p>
        <dl>
          @foreach ($workspace['items'] as $item => $count)
            <div><dt>{{ $item }}</dt><dd>{{ $count }}</dd></div>
          @endforeach
        </dl>
      </a>
    @endforeach
  </div>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Production readiness</h2><span class="status-pill">v7</span></div>
      <div class="table-panel nested-table">
        <table>
          <thead><tr><th>Area</th><th>Status</th><th>Evidence</th></tr></thead>
          <tbody>
            @foreach ($readiness as $item)
              <tr>
                <td>{{ $item['area'] }}</td>
                <td><span class="status-pill {{ $item['status'] === 'Blocked' ? 'warning' : ($item['status'] === 'Ready' ? 'success' : '') }}">{{ $item['status'] }}</span></td>
                <td>{{ $item['detail'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </article>

    <article class="panel">
      <div class="panel-header"><h2>Module register</h2><span class="status-pill">Live records</span></div>
      <ul class="timeline compact-list">
        @foreach ($moduleCounts as $module => $count)
          <li><strong>{{ $module }}</strong><span>{{ $count }}</span></li>
        @endforeach
      </ul>
    </article>
  </div>

  <div class="content-grid">
    @foreach ($evidence as $group)
      <article class="panel">
        <div class="panel-header"><h2>{{ $group['name'] }}</h2><span class="status-pill">Evidence</span></div>
        <ul class="timeline compact-list">
          @forelse ($group['records'] as $record)
            <li><strong>{{ $record->code }}</strong><span>{{ $record->name }} - {{ $record->status }}</span></li>
          @empty
            <li><strong>None</strong><span>No controlled records registered.</span></li>
          @endforelse
        </ul>
      </article>
    @endforeach
  </div>

  <form class="filter-bar" method="GET" action="{{ route('admin.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search users by %text%, role, email, job">
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('admin.index') }}">Clear</a>
  </form>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Users</h2><span class="status-pill">Effective access starts here</span></div>
      <ul class="timeline">
        @forelse ($users as $user)
          <li><strong>{{ $user->name }}</strong><span>{{ $user->qms_role }} - {{ $user->email }} - {{ $user->job_title }}</span></li>
        @empty
          <li><strong>No users found</strong><span>Adjust the search text.</span></li>
        @endforelse
      </ul>
      <div class="pager">{{ $users->links() }}</div>
    </article>
    <article class="panel">
      <div class="panel-header"><h2>Departments</h2><span class="status-pill">Organization</span></div>
      <ul class="timeline">@foreach ($departments as $department)<li><strong>{{ $department->code }}</strong><span>{{ $department->name }}</span></li>@endforeach</ul>
    </article>
    <article class="panel">
      <div class="panel-header"><h2>Locations</h2><span class="status-pill">Master data</span></div>
      <ul class="timeline">@foreach ($locations as $location)<li><strong>{{ $location->code }}</strong><span>{{ $location->name }}</span></li>@endforeach</ul>
    </article>
  </div>
</section>
@endsection
