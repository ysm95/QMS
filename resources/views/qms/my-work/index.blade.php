@extends('qms.layout', ['title' => 'My Work - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div>
      <p class="eyebrow">Unified task orchestration</p>
      <h1>My Work</h1>
    </div>
    <a class="primary-button" href="{{ route('reporting.index') }}">Submit report</a>
  </div>

  <div class="metric-grid">
    <article class="metric"><span>Total work</span><strong>{{ $counts['total'] }}</strong><small>Across modules</small></article>
    <article class="metric"><span>Overdue</span><strong>{{ $counts['overdue'] }}</strong><small>Needs attention</small></article>
    <article class="metric"><span>Due soon</span><strong>{{ $counts['due_soon'] }}</strong><small>Next 7 days</small></article>
    <article class="metric"><span>High/Critical</span><strong>{{ $counts['critical'] }}</strong><small>Priority queue</small></article>
    <article class="metric"><span>Modules</span><strong>{{ $counts['modules'] }}</strong><small>Connected sources</small></article>
  </div>

  <form class="filter-bar" method="GET" action="{{ route('my-work.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search by %text%, reference, owner, source">
    <select name="module">
      <option value="">All modules</option>
      @foreach ($modules as $module)
        <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
      @endforeach
    </select>
    <select name="priority">
      <option value="">All priorities</option>
      @foreach ($priorities as $priority)
        <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('my-work.index') }}">Clear</a>
  </form>

  <article class="panel wide">
    <div class="panel-header"><h2>Unified queue</h2><span class="status-pill">Real records</span></div>
    <div class="table-panel">
      <table>
        <thead><tr><th>Reference</th><th>Module</th><th>Work item</th><th>Status</th><th>Priority</th><th>Owner</th><th>Due</th><th>Source</th></tr></thead>
        <tbody>
          @foreach ($items as $item)
            <tr>
              <td><a href="{{ $item['url'] }}">{{ $item['reference'] }}</a></td>
              <td>{{ $item['module'] }}</td>
              <td>{{ $item['title'] }}</td>
              <td><span class="status-pill">{{ $item['status'] }}</span></td>
              <td><span class="risk-badge">{{ $item['priority'] }}</span></td>
              <td>{{ $item['owner'] }}</td>
              <td>
                @if ($item['due_at'])
                  <span class="{{ $item['is_overdue'] ? 'risk-badge' : 'status-pill' }}">{{ $item['due_at']->format('Y-m-d') }}</span>
                @else
                  Not set
                @endif
              </td>
              <td>{{ $item['source'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      @if ($items->isEmpty())
        <div class="empty-state">No work items match this filter.</div>
      @endif
    </div>
  </article>
</section>
@endsection
