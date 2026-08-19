@extends('reporter.layout')

@section('title', 'Report Receipt')
@section('screen-title', 'Report Receipt')

@section('content')
  <section class="reporter-receipt">
    <p class="eyebrow">Received</p>
    <h1>{{ $report->reference }}</h1>
    <dl>
      <div><dt>Public status</dt><dd>{{ $report->public_status ?? 'Submitted' }}</dd></div>
      <div><dt>Report type</dt><dd>{{ $report->submitted_payload['report_type_title'] ?? $report->category }}</dd></div>
      <div><dt>Submitted</dt><dd>{{ $report->created_at->format('d M Y H:i') }}</dd></div>
      @if ($report->location)<div><dt>Location</dt><dd>{{ $report->location }}</dd></div>@endif
    </dl>
  </section>

  <section class="reporter-panel">
    <h2>Summary</h2>
    <p>{{ $report->description }}</p>
  </section>

  @if ($report->information_request)
    <section class="reporter-panel">
      <h2>Information requested</h2>
      <p>{{ $report->information_request }}</p>
    </section>
  @endif

  @if ($report->reporter_visible_messages)
    <section class="reporter-panel">
      <h2>Updates</h2>
      <ul class="compact-list">
        @foreach ($report->reporter_visible_messages as $message)
          <li>{{ $message['message'] ?? '' }}</li>
        @endforeach
      </ul>
    </section>
  @endif
@endsection
