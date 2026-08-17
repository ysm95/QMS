@extends('qms.layout', ['title' => 'Platform Config - QMS'])

@section('content')
<section class="view active-view">
  <div class="page-title"><div><p class="eyebrow">Configurable platform</p><h1>Forms, workflows, and saved views</h1></div><span class="status-pill warning">Version controlled</span></div>

  <div class="content-grid">
    <article class="panel wide">
      <div class="panel-header"><h2>Form definitions</h2><span class="status-pill">Historical safe</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Module</th><th>Version</th><th>Status</th><th>Sections</th></tr></thead><tbody>
        @foreach ($forms as $form)
          <tr><td>{{ $form->code }}</td><td>{{ $form->name }}</td><td>{{ $form->module }}</td><td>v{{ $form->version }}</td><td><span class="status-pill">{{ $form->status }}</span></td><td>{{ implode(', ', $form->schema['sections'] ?? $form->schema['supports'] ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel wide">
      <div class="panel-header"><h2>Workflow definitions</h2><span class="status-pill">Stage controlled</span></div>
      <div class="table-panel"><table><thead><tr><th>Code</th><th>Name</th><th>Module</th><th>Version</th><th>Status</th><th>Stages</th></tr></thead><tbody>
        @foreach ($workflows as $workflow)
          <tr><td>{{ $workflow->code }}</td><td>{{ $workflow->name }}</td><td>{{ $workflow->module }}</td><td>v{{ $workflow->version }}</td><td><span class="status-pill">{{ $workflow->status }}</span></td><td>{{ implode(' > ', $workflow->stages ?? []) }}</td></tr>
        @endforeach
      </tbody></table></div>
    </article>

    <article class="panel">
      <h2>Saved views</h2>
      <ul class="timeline">
        @foreach ($views as $view)
          <li><strong>{{ $view->name }}</strong><span>{{ $view->module }} - {{ $view->shared ? 'Shared' : 'Private' }}</span></li>
        @endforeach
      </ul>
    </article>
  </div>
</section>
@endsection
