<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin') · DivineKraft</title>
  <style>
    :root { --ink:#2a1a14; --maroon:#7a1e2c; --deep:#5a1420; --cream:#fbf7f0; --line:#e6d9c6; --muted:#5e4e44; }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: Georgia, "Times New Roman", serif; background: var(--cream); color: var(--ink); }
    a { color: var(--maroon); }
    .shell { display: grid; grid-template-columns: 220px 1fr; min-height: 100vh; }
    aside { background: var(--deep); color: #fff8ee; padding: 24px 18px; }
    aside strong { display: block; letter-spacing: .14em; font-size: 13px; margin-bottom: 22px; }
    aside a { display: block; color: #fff8ee; text-decoration: none; padding: 10px 0; font-family: system-ui, sans-serif; font-size: 14px; }
    aside a:hover { color: #f3d27a; }
    aside form { margin-top: 24px; }
    aside button { background: transparent; color: #f3d27a; border: 0; padding: 0; font: inherit; cursor: pointer; }
    main { padding: 28px; }
    h1 { font-weight: 500; font-size: 36px; margin: 0 0 18px; }
    .flash { background: #eef5f0; border: 1px solid #b7d4c4; padding: 12px 14px; border-radius: 12px; margin-bottom: 16px; font-family: system-ui, sans-serif; }
    .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
    .card, table, form.panel { background: #fff; border: 1px solid var(--line); border-radius: 16px; }
    .card { padding: 18px; }
    .card b { display: block; font-size: 32px; }
    table { width: 100%; border-collapse: collapse; font-family: system-ui, sans-serif; font-size: 14px; overflow: hidden; }
    th, td { text-align: left; padding: 12px 14px; border-bottom: 1px solid var(--line); vertical-align: middle; }
    th { font-size: 12px; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); }
    img.thumb { width: 54px; height: 54px; object-fit: contain; background: #fff; border-radius: 8px; }
    .row { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; }
    .btn, button.btn { display: inline-flex; align-items: center; background: var(--maroon); color: #fff8ee; text-decoration: none; border: 0; border-radius: 999px; padding: 10px 16px; font-family: system-ui, sans-serif; font-size: 14px; cursor: pointer; }
    .btn.ghost { background: transparent; color: var(--maroon); border: 1px solid var(--maroon); }
    form.panel { padding: 20px; display: grid; gap: 12px; max-width: 760px; font-family: system-ui, sans-serif; }
    label { display: grid; gap: 6px; font-size: 13px; font-weight: 600; }
    input, textarea, select { font: inherit; border: 1px solid #cdbca4; border-radius: 10px; padding: 10px 12px; background: #fff; }
    .checks { display: flex; flex-wrap: wrap; gap: 10px 16px; font-weight: 500; }
    .checks label { display: flex; align-items: center; gap: 6px; }
    .split { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .error { color: #9e1b32; font-family: system-ui, sans-serif; }
    @media (max-width: 800px) { .shell, .cards, .split { grid-template-columns: 1fr; } }
  </style>
</head>
<body>
  <div class="shell">
    <aside>
      <strong>DIVINE KRAFT</strong>
      <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      <a href="{{ route('admin.products') }}">Poshaks</a>
      <a href="{{ route('admin.banners') }}">Banners</a>
      <a href="{{ route('admin.categories') }}">Categories</a>
      <a href="{{ route('admin.orders') }}">Orders</a>
      <a href="{{ route('admin.payments') }}">Payments</a>
      <a href="{{ route('admin.users') }}">Users</a>
      <a href="{{ route('admin.blogs') }}">Journal</a>
      <a href="{{ route('admin.reviews') }}">Reviews</a>
      <a href="{{ route('admin.videos') }}">Videos</a>
      <a href="{{ route('admin.pages') }}">Pages</a>
      <a href="{{ route('admin.faqs') }}">FAQ</a>
      <a href="{{ route('admin.reports') }}">Report</a>
      <a href="{{ route('admin.enquiries') }}">Enquiries</a>
      <a href="{{ route('admin.settings') }}">Settings</a>
      <a href="{{ env('FRONTEND_URL', 'http://localhost:3010') }}" target="_blank" rel="noopener">View shop</a>
      <form method="post" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Log out</button>
      </form>
    </aside>
    <main>
      @if (session('status'))
        <div class="flash">{{ session('status') }}</div>
      @endif
      @yield('content')
    </main>
  </div>
</body>
</html>
