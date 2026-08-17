@extends('qms.layout', ['title' => 'Controlled AI - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title">
    <div><p class="eyebrow">Governed enterprise AI</p><h1>Controlled AI</h1></div>
    <span class="status-pill {{ $activeProvider ? 'success' : 'warning' }}">{{ $activeProvider ? 'Enabled provider approved' : 'AI blocked until approved' }}</span>
  </div>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Approved provider control</h2><span class="status-pill">Paid secured only</span></div>
      <div class="table-panel"><table><thead><tr><th>Provider</th><th>Model</th><th>Training scope</th><th>Security</th><th>Status</th></tr></thead><tbody>
        @foreach ($providers as $provider)
          <tr>
            <td>{{ $provider->name }}</td>
            <td>{{ $provider->model_name }}</td>
            <td>{{ $provider->training_scope }}</td>
            <td>{{ $provider->security_tier }}</td>
            <td><span class="status-pill {{ $provider->is_approved && $provider->is_enabled ? 'success' : 'warning' }}">{{ $provider->is_approved && $provider->is_enabled ? 'Approved / enabled' : 'Blocked' }}</span></td>
          </tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel">
      <h2>Controls</h2>
      <ul class="check-list">
        <li>No free/public AI provider</li>
        <li>Paid secured enterprise provider only</li>
        <li>Entity-trained approved knowledge only</li>
        <li>No customer-data training without contract</li>
        <li>Audit log for every AI request</li>
        <li>Human approval required</li>
      </ul>
    </article>
  </div>

  <div class="record-layout">
    <article class="panel">
      <h2>Request controlled AI review</h2>
      <form method="POST" action="{{ route('ai.request-review') }}">
        @csrf
        <label>Module<select name="module"><option>Occurrence</option><option>Investigation</option><option>CAPA</option><option>Audit</option><option>Risk</option><option>Document</option></select></label>
        <label>Source reference<input name="source_reference" placeholder="Example: QMS-2026-00435"></label>
        <label>Prompt summary<textarea name="prompt_summary" rows="5" required placeholder="Summarize the review request. Do not paste sensitive personal data here."></textarea></label>
        <button class="primary-button full">Record AI request</button>
      </form>
    </article>

    <article class="panel wide">
      <h2>AI interaction audit</h2>
      <div class="table-panel"><table><thead><tr><th>Date</th><th>Module</th><th>Source</th><th>Status</th><th>Response</th></tr></thead><tbody>
        @foreach ($interactions as $interaction)
          <tr><td>{{ $interaction->created_at->format('Y-m-d H:i') }}</td><td>{{ $interaction->module }}</td><td>{{ $interaction->source_reference ?? 'None' }}</td><td>{{ $interaction->status }}</td><td>{{ $interaction->response_summary }}</td></tr>
        @endforeach
      </tbody></table></div>
      <div class="pager">{{ $interactions->links() }}</div>
    </article>
  </div>
</section>
@endsection
