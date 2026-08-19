@extends('qms.layout', ['title' => 'CAPA - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Closed-loop improvement</p><h1>CAPA</h1></div>
    <span class="status-pill">Effectiveness required</span>
  </div>

  <form class="filter-bar unified-filter" method="GET" action="{{ route('capa.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search CAPA, source, problem, owner">
    <select name="phase">
      <option value="">Any phase</option>
      @foreach ($phases as $phase)
        <option value="{{ $phase }}" @selected(request('phase') === $phase)>{{ $phase }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('capa.index') }}">Clear</a>
  </form>

  <article class="panel wide">
    <div class="panel-header"><h2>CAPA cases</h2><span class="status-pill">Root cause to effectiveness</span></div>
    <div class="table-panel">
      <table>
        <thead><tr><th>CAPA</th><th>Problem</th><th>Phase</th><th>Root cause tools</th><th>Owner</th><th>Effectiveness</th><th>Status</th></tr></thead>
        <tbody>
          @forelse ($capaCases as $case)
            <tr>
              <td><strong>{{ $case->reference }}</strong><br>{{ $case->source_reference ?? 'No source linked' }}</td>
              <td>{{ $case->problem_statement }}</td>
              <td><span class="status-pill">{{ $case->phase }}</span></td>
              <td>{{ implode(', ', $case->root_cause_tools ?? []) }}</td>
              <td>{{ $case->owner ?? 'Unassigned' }}</td>
              <td>{{ $case->effectiveness_result ?? $case->effectiveness_criteria ?? 'Not reviewed' }}</td>
              <td>{{ $case->status }}</td>
            </tr>
          @empty
            <tr><td colspan="7">No CAPA cases match this filter.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pager">{{ $capaCases->links() }}</div>
  </article>
</section>
@endsection
