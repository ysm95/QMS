@extends('reporter.layout')

@section('title', 'My Reports')
@section('screen-title', 'My Reports')

@section('content')
  <section class="reporter-hero">
    <p class="eyebrow">Receipt history</p>
    <h1>My submitted reports</h1>
    <p>Only reporter-visible receipt details and public statuses are shown here.</p>
  </section>

  <section class="reporter-list">
    @forelse ($reports as $report)
      <a href="{{ route('reporter.receipt', $report->receipt_token) }}">
        <strong>{{ $report->reference }}</strong>
        <span>{{ $report->submitted_payload['report_type_title'] ?? $report->category }}</span>
        <em>{{ $report->public_status ?? 'Submitted' }}</em>
      </a>
    @empty
      <div class="reporter-empty">No submitted reports yet.</div>
    @endforelse
  </section>

  <div class="pager">{{ $reports->links() }}</div>
@endsection
