@extends('reporter.layout')

@section('title', 'Reporter Notifications')
@section('screen-title', 'Notifications')

@section('content')
  <section class="reporter-hero">
    <p class="eyebrow">Reporter notifications</p>
    <h1>Updates that need your attention</h1>
    <p>Only reporter-visible messages are shown here.</p>
  </section>

  <section class="reporter-list">
    @forelse ($reports as $report)
      <a href="{{ route('reporter.receipt', $report->receipt_token) }}">
        <strong>{{ $report->reference }}</strong>
        <span>{{ collect($report->reporter_visible_messages)->last()['message'] ?? 'Report update available' }}</span>
        <em>{{ $report->public_status ?? 'Submitted' }}</em>
      </a>
    @empty
      <div class="reporter-empty">No reporter-visible notifications.</div>
    @endforelse
  </section>

  <div class="pager">{{ $reports->links() }}</div>
@endsection
