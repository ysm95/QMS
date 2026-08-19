@extends('qms.layout', ['title' => 'Nonconformances - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Quality evidence</p><h1>Nonconformances</h1></div>
    <span class="status-pill">Requirement + evidence + statement</span>
  </div>

  <form class="filter-bar unified-filter" method="GET" action="{{ route('nonconformances.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search NCR, requirement, owner, statement">
    <select name="status">
      <option value="">Any status</option>
      @foreach ($statuses as $status)
        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
      @endforeach
    </select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('nonconformances.index') }}">Clear</a>
  </form>

  <article class="panel wide">
    <div class="panel-header"><h2>NCR register</h2><span class="status-pill">Not every issue becomes CAPA</span></div>
    <div class="table-panel">
      <table>
        <thead><tr><th>NCR</th><th>Requirement</th><th>Evidence</th><th>Classification</th><th>Owner</th><th>Status</th><th>Due</th></tr></thead>
        <tbody>
          @forelse ($nonconformances as $ncr)
            <tr>
              <td><strong>{{ $ncr->reference }}</strong><br>{{ $ncr->nonconformity_statement }}</td>
              <td>{{ $ncr->requirement_reference }}</td>
              <td>{{ $ncr->objective_evidence }}</td>
              <td>{{ $ncr->classification }} / {{ $ncr->severity }}</td>
              <td>{{ $ncr->owner ?? 'Unassigned' }}</td>
              <td><span class="status-pill">{{ $ncr->status }}</span></td>
              <td>{{ optional($ncr->due_date)->format('Y-m-d') ?? 'Not set' }}</td>
            </tr>
          @empty
            <tr><td colspan="7">No nonconformances match this filter.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pager">{{ $nonconformances->links() }}</div>
  </article>
</section>
@endsection
