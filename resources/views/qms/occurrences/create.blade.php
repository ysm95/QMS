@extends('qms.layout', ['title' => 'Submit Report - QMS'])

@section('content')
@php
  $selected = $selectedReportType ?? null;
  $selectedKey = $selectedReportKey ?? 'general';
  $defaultType = $selected['type'] ?? 'Unsafe condition';
  $defaultTitle = $selected['title'] ?? 'Occurrence Report';
  $defaultConfidential = ! empty($selected['confidential']);
  $areas = ['A330 Fleet', 'B737 Fleet', 'B787 Fleet', 'A320 Fleet', 'CRB', 'Cabin Appearance', 'Cargo', 'Catering', 'Crew Accommodations', 'Engineering', 'GSEW', 'Ground Airport', 'HQ1', 'HQ2', 'Dispatch'];
  $sectors = ['MCT', 'SLL', 'DXB', 'DOH', 'JED', 'RUH', 'BOM', 'DEL', 'LHR', 'BKK'];
  $aircraftTypes = ['A330', 'B737', 'B787', 'A320', 'B737 MAX', 'B787-9'];
  $flightPhases = ['Pre-Flight', 'Towing', 'Pushback', 'Taxi-out', 'Take-off', 'RTO', 'Climb - Initial', 'Climb', 'Cruise', 'Descent', 'Holding', 'Approach', 'Landing'];
@endphp
<section class="view active-view">
  <div class="page-title">
    <div>
      <p class="eyebrow">Report preview before submit</p>
      <h1>{{ $defaultTitle }}</h1>
    </div>
    <a class="secondary-button" href="{{ route('reporting.index') }}">Change report</a>
  </div>

  <div class="workflow-ribbon"><span class="active">Draft</span><span>Preview</span><span>Submitted</span><span>HSE Review</span><span>Investigation</span><span>CAPA</span><span>Closed</span></div>

  <div class="form-layout dor-form-layout">
    <form class="panel form-panel" method="POST" action="{{ route('occurrences.store') }}" id="dorReportForm">
      @csrf
      <input type="hidden" name="report_key" value="{{ $selectedKey }}">
      <h2>Occurrence header</h2>
      <div class="form-grid">
        <label>Report type
          <select name="type" id="observationType" required>
            @foreach ($reportTypes as $key => $report)
              <option value="{{ $report['type'] }}" @selected($report['type'] === $defaultType)>{{ $report['title'] }}</option>
            @endforeach
          </select>
        </label>
        <label>Title<input name="event_title" data-preview="eventTitle" placeholder="Short event title" required></label>
        <label>Reported by<input name="reported_by" list="userPicker" data-preview="reportedBy" value="{{ auth()->user()->name }}" placeholder="Search user by %text%" required></label>
        <label>Event date<input type="date" name="event_date" data-preview="eventDate" value="{{ now()->toDateString() }}"></label>
        <label>Location<input name="location" list="locationPicker" data-preview="location" placeholder="Search location by %text%" required></label>
        <label>Area / fleet<input name="area_fleet" list="areaFleetPicker" data-preview="areaFleet" placeholder="Search area or fleet by %text%"></label>
        <label>Exact location<input name="exact_location" data-preview="exactLocation" placeholder="Example: OCC desk, bay, ramp, aircraft stand"></label>
        <label>Confidential report<select name="confidential"><option value="0" @selected(! $defaultConfidential)>No</option><option value="1" @selected($defaultConfidential)>Yes</option></select></label>
        <label>MOR required<select name="mor"><option value="0">No</option><option value="1">Yes</option></select></label>
      </div>

      <datalist id="userPicker">
        @foreach ($users as $user)
          <option value="{{ $user->name }} - {{ $user->email }}"></option>
        @endforeach
      </datalist>
      <datalist id="locationPicker">
        @foreach ($locations as $location)<option value="{{ $location->name }}"></option>@endforeach
        @foreach (['OCC', 'Ramp', 'Station', 'Aircraft stand', 'Head Office', 'Hangar'] as $location)<option value="{{ $location }}"></option>@endforeach
      </datalist>
      <datalist id="areaFleetPicker">
        @foreach ($areas as $area)<option value="{{ $area }}"></option>@endforeach
      </datalist>
      <datalist id="sectorPicker">
        @foreach ($sectors as $sector)<option value="{{ $sector }}"></option>@endforeach
      </datalist>
      <datalist id="aircraftTypePicker">
        @foreach ($aircraftTypes as $aircraftType)<option value="{{ $aircraftType }}"></option>@endforeach
      </datalist>

      <fieldset class="checkbox-grid dor-checkboxes">
        <legend>Type of event</legend>
        @foreach (['Flight Planning', 'Performance', 'Compliance / Regulatory', 'Fuel', 'Communication', 'Decision Making', 'Weather', 'System / Automation', 'Irregular / Abnormal Ops', 'Weight Balance', 'Human Factors'] as $category)
          <label><input type="checkbox" name="event_categories[]" value="{{ $category }}"> {{ $category }}</label>
        @endforeach
      </fieldset>

      <div class="conditional-box visible">
        <h3>Commander voyage details</h3>
        <div class="form-grid">
          <label>Pilot name<input name="pilot_name" list="userPicker" placeholder="Search pilot by %text%"></label>
          <label>Sector to<input name="sector_to" list="sectorPicker" placeholder="Select sector"></label>
          <label>Sector diverted<input name="sector_diverted" list="sectorPicker" placeholder="Select sector if diverted"></label>
        </div>
        <fieldset class="checkbox-grid dor-checkboxes compact-checks">
          <legend>Flight phase</legend>
          @foreach ($flightPhases as $phase)
            <label><input type="checkbox" name="event_categories[]" value="Flight phase: {{ $phase }}"> {{ $phase }}</label>
          @endforeach
        </fieldset>
      </div>

      <div id="flightFields" class="conditional-box visible">
        <h3>Aircraft and flight details</h3>
        <div class="form-grid">
          <label>A/C type<input name="aircraft_type" list="aircraftTypePicker" placeholder="Search aircraft type"></label>
          <label>A/C registration<input name="aircraft_registration" placeholder="A4O-..."></label>
          <label>Flight number<input name="flight_number" placeholder="WY123"></label>
          <label>Time of occurrence (UTC)<input type="time" name="time_of_occurrence"></label>
          <label>Flight cancelled<select name="flight_cancelled"><option value="0">No</option><option value="1">Yes</option></select></label>
        </div>
      </div>

      <h2>Personnel involved</h2>
      <div class="form-grid">
        <label>Staff 1<input name="personnel_involved[staff_1]" list="userPicker" placeholder="Search user by %text%"></label>
        <label>Staff 1 license/permit<input name="personnel_involved[staff_1_license]" placeholder="License / permit no."></label>
        <label>Staff 2<input name="personnel_involved[staff_2]" list="userPicker" placeholder="Search user by %text%"></label>
        <label>Staff 2 license/permit<input name="personnel_involved[staff_2_license]" placeholder="License / permit no."></label>
      </div>

      <label>Summary of occurrence<textarea name="description" rows="5" data-preview="summary" required placeholder="Describe the occurrence clearly."></textarea></label>
      <label>Details of flight plan<textarea name="flight_plan_details" rows="4" placeholder="Flight plan, dispatch, weather, fuel, ATC, or operational details."></textarea></label>

      <fieldset class="checkbox-grid">
        <legend>Action taken</legend>
        @foreach (['Informed supervisor', 'Issued revised flight plan', 'Informed flight crew', 'Attached supporting documents'] as $action)
          <label><input type="checkbox" name="action_taken[]" value="{{ $action }}"> {{ $action }}</label>
        @endforeach
      </fieldset>

      <label>Immediate corrective action<textarea name="immediate_corrective_action" rows="4" placeholder="Immediate action taken to control the issue."></textarea></label>
      <label>Feedback to reporter<textarea name="feedback_to_reporter" rows="3" placeholder="Office use / feedback to reporter."></textarea></label>

      <div class="button-row"><button class="primary-button">Submit to workflow</button><a class="secondary-button" href="{{ route('reporting.index') }}">Cancel</a></div>
    </form>

    <aside class="panel report-preview-panel">
      <h2>Report preview</h2>
      <div class="preview-sheet">
        <div><span>Type</span><strong>{{ $defaultTitle }}</strong></div>
        <div><span>Event title</span><strong data-preview-output="eventTitle">Not entered</strong></div>
        <div><span>Area / fleet</span><strong data-preview-output="areaFleet">Not entered</strong></div>
        <div><span>Date</span><strong data-preview-output="eventDate">{{ now()->toDateString() }}</strong></div>
        <div><span>Location</span><strong data-preview-output="location">Not entered</strong></div>
        <div><span>Exact location</span><strong data-preview-output="exactLocation">Not entered</strong></div>
        <div><span>Reported by</span><strong data-preview-output="reportedBy">{{ auth()->user()->name }}</strong></div>
        <div class="preview-wide"><span>Summary</span><p data-preview-output="summary">Complete the summary to preview the report before submission.</p></div>
      </div>
      <ul class="check-list"><li>Fill details</li><li>Attach supporting documents later</li><li>Preview before submission</li><li>Workflow starts at HSE Review</li></ul>
    </aside>
  </div>
</section>
@endsection
