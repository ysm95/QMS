@extends('qms.layout', ['title' => $investigation->reference . ' - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Investigation workspace</p><h1>{{ $investigation->title }}</h1></div><span class="status-pill">{{ $investigation->status }}</span></div>
  <div class="record-layout">
    <article class="panel wide">
      <h2>Scope</h2><p>{{ $investigation->scope }}</p>
      <h2>Findings</h2><p>{{ $investigation->findings }}</p>
      <h2>Record notes</h2>
      <ul class="timeline">
        @forelse ($notes as $note)
          <li><strong>{{ $note->author }}</strong><span>{{ $note->visibility }} - {{ $note->body }}</span></li>
        @empty
          <li><strong>No notes</strong><span>Add interview notes, evidence notes, or analysis notes from the side panel.</span></li>
        @endforelse
      </ul>
      <h2>Audit trail</h2>
      <ul class="timeline">
        @forelse ($auditLogs as $log)
          <li><strong>{{ $log->action }}</strong><span>{{ $log->actor ?? 'System' }} - {{ $log->created_at->format('Y-m-d H:i') }}</span></li>
        @empty
          <li><strong>No audit entries</strong><span>Investigation changes will be recorded here.</span></li>
        @endforelse
      </ul>
    </article>
    <article class="panel">
      <h2>Analysis tools</h2><ul class="check-list"><li>5 Whys</li><li>Fishbone</li><li>Bow-tie</li><li>SHELL</li></ul>
      <h2>Update investigation</h2>
      <form method="POST" action="{{ route('investigations.update', $investigation) }}">
        @csrf @method('PATCH')
        <label>Status<select name="status">@foreach (['Open', 'Evidence gathering', 'Analysis', 'Review', 'Closed'] as $status)<option @selected($investigation->status === $status)>{{ $status }}</option>@endforeach</select></label>
        <label>Scope<textarea name="scope" rows="4">{{ $investigation->scope }}</textarea></label>
        <label>Findings<textarea name="findings" rows="5">{{ $investigation->findings }}</textarea></label>
        <button class="primary-button full">Update</button>
      </form>
      <h2>Add note</h2>
      <form method="POST" action="{{ route('investigations.notes.store', $investigation) }}">
        @csrf
        <label>Visibility<select name="visibility"><option>Internal</option><option>Investigation team</option><option>Confidential</option></select></label>
        <label>Note<textarea name="body" rows="4" required></textarea></label>
        <button class="secondary-button full">Add note</button>
      </form>
    </article>
  </div>
</section>
@endsection
