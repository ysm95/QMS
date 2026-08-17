@extends('qms.layout', ['title' => 'Risks - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Safety risk management</p><h1>Risk register</h1></div><div class="button-row"><a class="secondary-button" href="{{ route('exports.risks') }}">Export CSV</a><span class="status-pill warning">5 x 5 register</span></div></div>

  <form class="filter-bar" method="GET" action="{{ route('risks.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search risk by %text%, hazard, owner, control">
    <select name="rating"><option value="">All ratings</option>@foreach ($ratings as $rating)<option value="{{ $rating }}" @selected(request('rating') === $rating)>{{ $rating }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button>
    <a class="secondary-button" href="{{ route('risks.index') }}">Clear</a>
  </form>

  <div class="table-panel"><table><thead><tr><th>Reference</th><th>Hazard</th><th>Owner</th><th>Rating</th><th>Controls</th><th>Review</th><th>Update</th></tr></thead><tbody>
    @foreach ($risks as $risk)
      <tr>
        <td>{{ $risk->reference }}</td><td>{{ $risk->hazard }}</td><td>{{ $risk->owner }}</td><td><span class="risk-badge">{{ $risk->rating }}</span></td><td>{{ $risk->controls }}</td><td>{{ optional($risk->review_date)->format('Y-m-d') }}</td>
        <td><form class="inline-update" method="POST" action="{{ route('risks.update', $risk) }}">@csrf @method('PATCH')<select name="rating">@foreach (['Low', 'Medium', 'High', 'Critical'] as $rating)<option @selected($risk->rating === $rating)>{{ $rating }}</option>@endforeach</select><input name="controls" value="{{ $risk->controls }}" placeholder="Controls"><input type="date" name="review_date" value="{{ optional($risk->review_date)->format('Y-m-d') }}"><button class="secondary-button">Save</button></form></td>
      </tr>
    @endforeach
  </tbody></table></div>
  @if ($risks->isEmpty())<div class="empty-state">No risks match this filter.</div>@endif
  <div class="pager">{{ $risks->links() }}</div>
</section>
@endsection
