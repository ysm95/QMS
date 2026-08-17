@extends('qms.layout', ['title' => $occurrence->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Record workspace</p><h1>{{ $occurrence->title }}</h1></div>
    <span class="status-pill warning">{{ $occurrence->workflow_stage }}</span>
  </div>
  <div class="record-layout">
    <article class="panel wide">
      <div class="record-head"><div><h2>{{ $occurrence->reference }}</h2><p>{{ $occurrence->description }}</p></div><strong class="risk-badge">Risk: {{ $occurrence->risk_rating }}</strong></div>
      <div class="workflow-ribbon compact">
        @foreach (['Draft', 'Submitted', 'HSE Review', 'Investigation', 'CAPA', 'Closed'] as $stage)
          <span class="{{ $occurrence->workflow_stage === $stage ? 'active' : '' }}">{{ $stage }}</span>
        @endforeach
      </div>
      <div class="detail-grid">
        <div><span>Type</span><strong>{{ $occurrence->type }}</strong></div>
        <div><span>Area / fleet</span><strong>{{ $occurrence->area_fleet ?? 'Not set' }}</strong></div>
        <div><span>Sector to</span><strong>{{ $occurrence->sector_to ?? 'Not set' }}</strong></div>
        <div><span>Sector diverted</span><strong>{{ $occurrence->sector_diverted ?? 'Not set' }}</strong></div>
        <div><span>Location</span><strong>{{ $occurrence->location }}</strong></div>
        <div><span>Event date</span><strong>{{ optional($occurrence->event_date)->format('Y-m-d') ?? 'Not set' }}</strong></div>
        <div><span>Reporter</span><strong>{{ $occurrence->reported_by }}</strong></div>
        <div><span>Pilot</span><strong>{{ $occurrence->pilot_name ?? 'Not set' }}</strong></div>
        <div><span>Status</span><strong>{{ $occurrence->status }}</strong></div>
        <div><span>Confidential</span><strong>{{ $occurrence->confidential ? 'Yes' : 'No' }}</strong></div>
        <div><span>MOR</span><strong>{{ $occurrence->mor ? 'Yes' : 'No' }}</strong></div>
      </div>
      @if ($occurrence->event_categories)
        <h3>Type of event</h3>
        <div class="tag-row">@foreach ($occurrence->event_categories as $category)<span>{{ $category }}</span>@endforeach</div>
      @endif
      <h3>Aircraft and flight details</h3>
      <div class="detail-grid">
        <div><span>A/C type</span><strong>{{ $occurrence->aircraft_type ?? 'Not set' }}</strong></div>
        <div><span>A/C registration</span><strong>{{ $occurrence->aircraft_registration ?? 'Not set' }}</strong></div>
        <div><span>Flight number</span><strong>{{ $occurrence->flight_number ?? 'Not set' }}</strong></div>
        <div><span>Occurrence time</span><strong>{{ $occurrence->time_of_occurrence ?? 'Not set' }}</strong></div>
      </div>
      <h3>Flight plan details</h3>
      <p>{{ $occurrence->flight_plan_details ?: 'No flight plan details entered.' }}</p>
      <h3>Immediate corrective action</h3>
      <p>{{ $occurrence->immediate_corrective_action ?: 'No immediate corrective action entered.' }}</p>
      <h3>Linked actions</h3>
      <ul class="timeline">@foreach ($actions as $action)<li><strong>{{ $action->reference }}</strong><span>{{ $action->title }} - {{ $action->status }}</span></li>@endforeach</ul>
    </article>
    <aside class="panel">
      <h2>Workflow update</h2>
      <form method="POST" action="{{ route('occurrences.advance', $occurrence) }}">
        @csrf @method('PATCH')
        <label>Stage<select name="workflow_stage"><option>HSE Review</option><option>Investigation</option><option>CAPA</option><option>Closed</option></select></label>
        <label>Status<select name="status"><option>Submitted</option><option>Accepted</option><option>In progress</option><option>Closed</option></select></label>
        <label>Risk<select name="risk_rating"><option>Low</option><option>Medium</option><option>High</option><option>Critical</option></select></label>
        <button class="primary-button full">Update</button>
      </form>
    </aside>
  </div>
</section>
@endsection
