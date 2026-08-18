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
  <div class="app-shell">
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-mark">Q</div>
        <div><strong>QMS</strong><span>qms.ysaidea.com</span></div>
      </div>
      <nav class="nav-list">
        <a class="nav-item {{ request()->routeIs('qms.*') ? 'active' : '' }}" href="{{ route('qms.dashboard') }}">Dashboard</a>
        <a class="nav-item {{ request()->routeIs('my-work.*') ? 'active' : '' }}" href="{{ route('my-work.index') }}">My Work</a>
        <a class="nav-item {{ request()->routeIs('intelligence.*') ? 'active' : '' }}" href="{{ route('intelligence.index') }}">Intelligence</a>
        <a class="nav-item {{ request()->routeIs('reporting.*') ? 'active' : '' }}" href="{{ route('reporting.index') }}">Reporting</a>
        <a class="nav-item {{ request()->routeIs('incidents.*') ? 'active' : '' }}" href="{{ route('incidents.index') }}">Incidents</a>
        <a class="nav-item {{ request()->routeIs('occurrences.*') ? 'active' : '' }}" href="{{ route('occurrences.index') }}">Occurrences</a>
        <a class="nav-item {{ request()->routeIs('actions.*') ? 'active' : '' }}" href="{{ route('actions.index') }}">CAPA / Actions</a>
        <a class="nav-item {{ request()->routeIs('investigations.*') ? 'active' : '' }}" href="{{ route('investigations.index') }}">Investigations</a>
        <a class="nav-item {{ request()->routeIs('audits.*') ? 'active' : '' }}" href="{{ route('audits.index') }}">Audits</a>
        <a class="nav-item {{ request()->routeIs('risks.*') ? 'active' : '' }}" href="{{ route('risks.index') }}">Risks</a>
        <a class="nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('documents.index') }}">Documents</a>
        <a class="nav-item {{ request()->routeIs('compliance.*') ? 'active' : '' }}" href="{{ route('compliance.index') }}">Compliance</a>
        <a class="nav-item {{ request()->routeIs('objectives.*') ? 'active' : '' }}" href="{{ route('objectives.index') }}">Objectives / SPI</a>
        <a class="nav-item {{ request()->routeIs('management-reviews.*') ? 'active' : '' }}" href="{{ route('management-reviews.index') }}">Management Review</a>
        <a class="nav-item {{ request()->routeIs('training.*') ? 'active' : '' }}" href="{{ route('training.index') }}">Training</a>
        <a class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">Suppliers</a>
        <a class="nav-item {{ request()->routeIs('public-reports.*') ? 'active' : '' }}" href="{{ route('public-reports.index') }}">Public Intake</a>
        <a class="nav-item {{ request()->routeIs('platform.*') ? 'active' : '' }}" href="{{ route('platform.index') }}">Platform Config</a>
        <a class="nav-item {{ request()->routeIs('ai.*') ? 'active' : '' }}" href="{{ route('ai.index') }}">Controlled AI</a>
        <a class="nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">Notifications</a>
        <a class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.index') }}">Admin Center</a>
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
          <a class="secondary-button" href="{{ route('notifications.index', ['status' => 'unread']) }}">Inbox</a>
          <a class="primary-button" href="{{ route('reporting.index') }}">New report</a>
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
