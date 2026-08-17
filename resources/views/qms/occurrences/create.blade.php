@extends('qms.layout', ['title' => 'Submit Occurrence - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">FR-001 Reporting</p><h1>Submit occurrence</h1></div></div>
  <div class="workflow-ribbon"><span class="active">Draft</span><span>Submitted</span><span>HSE Review</span><span>Investigation</span><span>CAPA</span><span>Closed</span></div>
  <form class="panel form-panel" method="POST" action="{{ route('occurrences.store') }}">
    @csrf
    <h2>Occurrence details</h2>
    <div class="form-grid">
      <label>Observation type
        <select name="type" id="observationType" required>
          <option>Unsafe condition</option><option>Flight safety</option><option>Ground safety</option><option>Quality nonconformance</option><option>Environmental</option>
        </select>
      </label>
      <label>Location
        <select name="location" required>
          @foreach ($locations as $location)<option>{{ $location->name }}</option>@endforeach
        </select>
      </label>
      <label>Exact location<input name="exact_location" placeholder="Example: CAE 135, equipment area"></label>
      <label>Reported by<input name="reported_by" value="{{ auth()->user()->name }}" required></label>
      <label>Confidential report<select name="confidential"><option value="0">No</option><option value="1">Yes</option></select></label>
    </div>
    <div id="flightFields" class="conditional-box">
      <h3>Flight safety details</h3>
      <div class="form-grid">
        <label>Flight number<input name="flight_number" placeholder="WY123"></label>
        <label>Aircraft registration<input name="aircraft_registration" placeholder="A4O-..."></label>
        <label>Sector<input name="sector" placeholder="MCT-SLL"></label>
        <label>Departure<input name="departure" placeholder="MCT"></label>
        <label>Destination<input name="destination" placeholder="SLL"></label>
      </div>
    </div>
    <label>Description<textarea name="description" rows="6" required placeholder="Describe what happened, immediate action, people involved, and evidence."></textarea></label>
    <div class="button-row"><button class="primary-button">Submit to workflow</button><a class="secondary-button" href="{{ route('occurrences.index') }}">Cancel</a></div>
  </form>
</section>
@endsection
