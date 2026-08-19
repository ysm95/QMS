@extends('reporter.layout')

@section('title', $reportType->title)
@section('screen-title', $reportType->title)

@section('content')
  <form class="reporter-form" method="POST" action="{{ route('reporter.store', $reportType->report_type_key) }}">
    @csrf
    <input type="hidden" name="form_version" value="{{ $reportType->form_version }}">

    <section class="reporter-form-section">
      <p class="eyebrow">{{ $reportType->module }} · Version {{ $reportType->form_version }}</p>
      <h1>{{ $reportType->title }}</h1>
      <p>{{ $reportType->description }}</p>
    </section>

    @if ($errors->any())
      <div class="form-error">Please review the highlighted fields and submit again.</div>
    @endif

    <label>Title
      <input name="title" value="{{ old('title') }}" placeholder="Short summary">
      @error('title')<small>{{ $message }}</small>@enderror
    </label>

    <label>Location
      <input name="location" value="{{ old('location') }}" placeholder="Station, department, area, or route">
      @error('location')<small>{{ $message }}</small>@enderror
    </label>

    @guest
      <label>Reporter name
        <input name="reporter_name" value="{{ old('reporter_name') }}" placeholder="Optional if anonymous">
      </label>
      <label>Contact
        <input name="reporter_contact" value="{{ old('reporter_contact') }}" placeholder="Email or phone, optional">
      </label>
    @endguest

    @if ($reportType->supports_anonymous)
      <label class="inline-check"><input type="checkbox" name="anonymous" value="1" @checked(old('anonymous'))> Submit anonymously</label>
    @endif

    <label class="inline-check"><input type="checkbox" name="confidential" value="1" @checked(old('confidential') || $reportType->report_type_key === 'confidential-safety')> Treat as confidential</label>

    <label>Description
      <textarea name="description" rows="8" required placeholder="Describe what happened, what may happen, or what needs review.">{{ old('description') }}</textarea>
      @error('description')<small>{{ $message }}</small>@enderror
    </label>

    <button class="primary-button full">Submit report</button>
  </form>
@endsection
