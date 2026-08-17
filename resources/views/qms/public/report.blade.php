<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Public Safety Report - QMS</title>
  <link rel="stylesheet" href="{{ asset('qms-assets/style.css') }}">
  <link rel="stylesheet" href="{{ asset('qms-assets/phase2.css') }}">
</head>
<body class="login-body">
  <div class="login-shell">
    <section class="login-hero"><p class="eyebrow">Public reporting portal</p><h1>Submit safety or quality concern</h1><p>This intake supports voluntary, confidential, and anonymous reporting. Reports are routed to QMS screening.</p></section>
    <form class="login-card" method="POST" action="{{ route('portal.report.store') }}">
      @csrf
      <h2>Report details</h2>
      @if (session('status'))<div class="server-flash">{{ session('status') }}</div>@endif
      <label>Category<select name="category" required><option>Safety concern</option><option>Quality concern</option><option>HSE concern</option><option>Supplier concern</option><option>Confidential safety report</option></select></label>
      <label>Location<input name="location" placeholder="Location or department"></label>
      <label>Reporter name<input name="reporter_name" placeholder="Optional if anonymous"></label>
      <label>Contact<input name="reporter_contact" placeholder="Email or phone, optional"></label>
      <label class="inline-check"><input type="checkbox" name="anonymous" value="1"> Anonymous report</label>
      <label class="inline-check"><input type="checkbox" name="confidential" value="1"> Confidential report</label>
      <label>Description<textarea name="description" rows="6" required placeholder="Describe what happened or what may happen."></textarea></label>
      <button class="primary-button full">Submit report</button>
    </form>
  </div>
</body>
</html>
