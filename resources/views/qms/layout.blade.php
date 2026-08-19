<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'QMS' }}</title>
  <link rel="stylesheet" href="{{ asset('qms-assets/style.css') }}">
  <link rel="stylesheet" href="{{ asset('qms-assets/phase2.css') }}">
  <script defer src="{{ asset('qms-assets/studio.js') }}"></script>
</head>
<body>
  @php
    $role = auth()->user()->qms_role ?? 'QMS User';
    $canAdmin = in_array($role, ['Super Admin', 'Quality Admin', 'Safety Admin'], true);
    $navItems = [
      ['label' => 'Home', 'route' => 'qms.dashboard', 'active' => ['qms.*']],
      ['label' => 'My Work', 'route' => 'my-work.index', 'active' => ['my-work.*']],
      ['label' => 'Reports', 'route' => 'reporting.index', 'active' => ['reporting.*', 'public-reports.*'], 'roles' => ['Super Admin', 'Safety Admin', 'Quality Admin', 'HSE Admin']],
      ['label' => 'Safety', 'route' => 'incidents.index', 'active' => ['incidents.*', 'occurrences.*', 'actions.*', 'investigations.*', 'risks.*'], 'roles' => ['Super Admin', 'Safety Admin', 'HSE Admin', 'Action User']],
      ['label' => 'Quality', 'route' => 'documents.index', 'active' => ['documents.*', 'objectives.*', 'training.*', 'suppliers.*'], 'roles' => ['Super Admin', 'Quality Admin']],
      ['label' => 'Assurance', 'route' => 'audits.index', 'active' => ['audits.*', 'compliance.*', 'management-reviews.*'], 'roles' => ['Super Admin', 'Quality Admin', 'Safety Admin']],
      ['label' => 'Analytics', 'route' => 'intelligence.index', 'active' => ['intelligence.*', 'search.*'], 'roles' => ['Super Admin', 'Quality Admin', 'Safety Admin', 'HSE Admin']],
      ['label' => 'Administration', 'route' => 'admin.index', 'active' => ['admin.*', 'platform.*', 'ai.*'], 'admin' => true],
    ];
    $secondaryNav = [
      'Reports' => [
        ['Reporting workspace', 'reporting.index'],
        ['Public intake', 'public-reports.index'],
      ],
      'Safety' => [
        ['Incidents', 'incidents.index'],
        ['Occurrences', 'occurrences.index'],
        ['Actions', 'actions.index'],
        ['Investigations', 'investigations.index'],
        ['Risks', 'risks.index'],
      ],
      'Quality' => [
        ['Documents', 'documents.index'],
        ['Objectives', 'objectives.index'],
        ['Training', 'training.index'],
        ['Suppliers', 'suppliers.index'],
      ],
      'Assurance' => [
        ['Audits', 'audits.index'],
        ['Compliance', 'compliance.index'],
        ['Management review', 'management-reviews.index'],
      ],
      'Administration' => [
        ['Control center', 'admin.index'],
        ['Configuration', 'platform.index'],
        ['Controlled AI', 'ai.index'],
      ],
    ];
  @endphp
  <div class="app-shell">
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-mark">Q</div>
        <div><strong>QMS</strong><span>qms.ysaidea.com</span></div>
      </div>
      <nav class="nav-list">
        @foreach ($navItems as $item)
          @continue(($item['admin'] ?? false) && ! $canAdmin)
          @continue(isset($item['roles']) && ! in_array($role, $item['roles'], true))
          @php $isActive = collect($item['active'])->contains(fn ($pattern) => request()->routeIs($pattern)); @endphp
          <a class="nav-item {{ $isActive ? 'active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
          @if ($isActive && isset($secondaryNav[$item['label']]))
            <div class="nav-sublist">
              @foreach ($secondaryNav[$item['label']] as [$label, $route])
                <a class="{{ request()->routeIs(str_replace('.index', '.*', $route)) ? 'active' : '' }}" href="{{ route($route) }}">{{ $label }}</a>
              @endforeach
            </div>
          @endif
        @endforeach
      </nav>
      <div class="sidebar-footer">
        <span>{{ auth()->user()->qms_role ?? 'QMS User' }}</span>
        <strong>{{ auth()->user()->name ?? 'Guest' }}</strong>
      </div>
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="search">
          <span>Search</span>
          <form action="{{ route('search.index') }}" method="GET">
            <input name="q" type="search" placeholder="Reports, actions, audits, risks..." value="{{ request('q', request('search')) }}">
          </form>
        </div>
        <div class="topbar-actions">
          <a class="secondary-button" href="{{ route('notifications.index', ['status' => 'unread']) }}">Notifications</a>
          <a class="primary-button" href="{{ route('reporting.index') }}">Report</a>
          <form method="POST" action="{{ route('logout') }}">@csrf<button class="secondary-button">Logout</button></form>
        </div>
      </header>

      @if (session('status'))
        <div class="server-flash">{{ session('status') }}</div>
      @endif

      @yield('content')
    </main>
  </div>
</body>
</html>
