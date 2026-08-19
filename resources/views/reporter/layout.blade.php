<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'QMS Reporter')</title>
  <link rel="stylesheet" href="{{ asset('qms-assets/style.css') }}">
  <link rel="stylesheet" href="{{ asset('qms-assets/phase2.css') }}">
</head>
<body class="reporter-body">
  <header class="reporter-topbar">
    <a href="{{ route('reporter.home') }}" aria-label="Back to reporter home">‹</a>
    <strong>@yield('screen-title', 'QMS Reporter')</strong>
    <span>{{ auth()->user()?->name ?? 'Guest' }}</span>
  </header>

  <main class="reporter-shell">
    @if (session('status'))<div class="server-flash">{{ session('status') }}</div>@endif
    @yield('content')
  </main>

  <nav class="reporter-bottom-nav" aria-label="Reporter navigation">
    <a href="{{ route('reporter.home') }}">Home</a>
    <a href="{{ route('reporter.home') }}">Report</a>
    @auth
      <a href="{{ route('reporter.my-reports') }}">My reports</a>
      <a href="{{ route('reporter.notifications') }}">Notifications</a>
    @else
      <span>My reports</span>
      <span>Notifications</span>
    @endauth
  </nav>
</body>
</html>
