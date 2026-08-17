@extends('qms.layout', ['title' => 'Suppliers - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Supplier and contractor control</p><h1>Approved suppliers</h1></div><span class="status-pill warning">External access ready</span></div>
  <form class="filter-bar" method="GET" action="{{ route('suppliers.index') }}">
    <input name="search" type="search" value="{{ request('search') }}" placeholder="Search supplier by %text%, owner, category">
    <select name="risk"><option value="">All risks</option>@foreach ($risks as $risk)<option value="{{ $risk }}" @selected(request('risk') === $risk)>{{ $risk }}</option>@endforeach</select>
    <select name="status"><option value="">All statuses</option>@foreach ($statuses as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>@endforeach</select>
    <button class="secondary-button">Filter</button><a class="secondary-button" href="{{ route('suppliers.index') }}">Clear</a>
  </form>
  <div class="table-panel"><table><thead><tr><th>Reference</th><th>Name</th><th>Category</th><th>Owner</th><th>Risk</th><th>Status</th><th>Review</th></tr></thead><tbody>
    @foreach ($suppliers as $supplier)
      <tr><td>{{ $supplier->reference }}</td><td>{{ $supplier->name }}</td><td>{{ $supplier->category }}</td><td>{{ $supplier->owner }}</td><td><span class="risk-badge">{{ $supplier->risk_rating }}</span></td><td><span class="status-pill">{{ $supplier->status }}</span></td><td>{{ optional($supplier->next_review_date)->format('Y-m-d') }}</td></tr>
    @endforeach
  </tbody></table></div>
  @if ($suppliers->isEmpty())<div class="empty-state">No suppliers match this filter.</div>@endif
  <div class="pager">{{ $suppliers->links() }}</div>
</section>
@endsection
