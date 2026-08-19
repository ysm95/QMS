@extends($layout, ['title' => 'Feedback - QMS'])

@section('title', 'Feedback - QMS')
@section('screen-title', 'Help and Feedback')

@section('content')
<section class="view active-view reporter-panel">
  <div class="page-title">
    <div><p class="eyebrow">Help</p><h1>Feedback</h1></div>
    <span class="status-pill">Separate from safety reporting</span>
  </div>

  <form class="config-form panel" method="POST" action="{{ route('feedback.store') }}">
    @csrf
    <h2>Send feedback</h2>
    <label>Type
      <select name="feedback_type" required>
        <option>Problem</option>
        <option>Improvement idea</option>
        <option>Content correction</option>
        <option>Accessibility issue</option>
      </select>
    </label>
    <label>Context
      <input name="context" value="{{ old('context', request('context')) }}" placeholder="Page, workflow, or task">
    </label>
    <label>Message
      <textarea name="message" rows="5" required placeholder="Tell the support team what needs attention."></textarea>
    </label>
    <button class="primary-button full">Send feedback</button>
  </form>

  <article class="panel wide">
    <div class="panel-header"><h2>Feedback history</h2><span class="status-pill">Support workflow</span></div>
    <ul class="timeline compact-list">
      @forelse ($feedbackItems as $item)
        <li><strong>{{ $item->reference }}</strong><span>{{ $item->feedback_type }} - {{ $item->status }}</span></li>
      @empty
        <li><strong>No feedback yet</strong><span>Feedback helps improve the product without creating a safety report.</span></li>
      @endforelse
    </ul>
    <div class="pager">{{ $feedbackItems->links() }}</div>
  </article>
</section>
@endsection
