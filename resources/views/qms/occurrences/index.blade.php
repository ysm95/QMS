@extends('qms.layout', ['title' => 'Occurrences - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Occurrence management</p><h1>Records</h1></div>
    <a class="primary-button" href="{{ route('occurrences.create') }}">Submit occurrence</a>
  </div>

  <div class="table-panel">
    <table>
      <thead><tr><th>Reference</th><th>Title</th><th>Type</th><th>Stage</th><th>Risk</th><th>Reporter</th></tr></thead>
      <tbody>
        @foreach ($occurrences as $occurrence)
          <tr>
            <td><a href="{{ route('occurrences.show', $occurrence) }}">{{ $occurrence->reference }}</a></td>
            <td>{{ $occurrence->title }}</td>
            <td>{{ $occurrence->type }}</td>
            <td><span class="status-pill">{{ $occurrence->workflow_stage }}</span></td>
            <td><span class="risk-badge">{{ $occurrence->risk_rating }}</span></td>
            <td>{{ $occurrence->reported_by }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="pager">{{ $occurrences->links() }}</div>
</section>
@endsection
