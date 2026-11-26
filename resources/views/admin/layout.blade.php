<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin') · DivineKraft</title>
  <style>
    :root {
      --ink: #24160f;
      --maroon: #7a1e2c;
      --deep: #3d1018;
      --deep-2: #541621;
      --cream: #f6f1ea;
      --paper: #fffdf9;
      --line: #eadfce;
      --muted: #6d5c52;
      --gold: #e7c57a;
      --ok: #1f6b45;
      --danger: #9e1b32;
    }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: "Segoe UI", system-ui, sans-serif; background: var(--cream); color: var(--ink); }
    a { color: var(--maroon); }
    .shell { display: grid; grid-template-columns: 248px 1fr; min-height: 100vh; }
    aside { background: linear-gradient(180deg, var(--deep) 0%, var(--deep-2) 100%); color: #fff8ee; padding: 22px 16px 18px; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; }
    .brand { display: flex; flex-direction: column; gap: 4px; padding: 4px 10px 18px; border-bottom: 1px solid rgba(255,248,238,.12); margin-bottom: 14px; }
    .brand strong { letter-spacing: .18em; font-size: 13px; font-weight: 700; }
    .brand span { color: rgba(255,248,238,.62); font-size: 12px; letter-spacing: .02em; }
    nav { display: grid; gap: 16px; overflow: auto; padding-right: 4px; }
    nav section { display: grid; gap: 2px; }
    nav p { margin: 0 10px 4px; font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,248,238,.45); }
    nav a { display: block; color: #fff8ee; text-decoration: none; padding: 8px 10px; border-radius: 10px; font-size: 14px; }
    nav a:hover { background: rgba(255,248,238,.08); }
    nav a.active { background: rgba(231,197,122,.16); color: var(--gold); }
    .side-foot { margin-top: auto; padding-top: 16px; display: grid; gap: 8px; }
    .side-foot a, .side-foot button { display: block; width: 100%; text-align: left; background: transparent; color: #fff8ee; border: 1px solid rgba(255,248,238,.18); border-radius: 10px; padding: 9px 10px; font: inherit; font-size: 14px; text-decoration: none; cursor: pointer; }
    .side-foot button { color: var(--gold); }
    main { padding: 28px 32px 48px; min-width: 0; }
    h1 { font-family: Georgia, "Times New Roman", serif; font-weight: 500; font-size: 34px; letter-spacing: -0.02em; margin: 0; }
    h2 { font-family: Georgia, "Times New Roman", serif; font-weight: 500; }
    .flash { background: #eef6f1; border: 1px solid #b7d4c4; color: #174832; padding: 12px 14px; border-radius: 12px; margin-bottom: 16px; }
    .cards { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    .card, table, form.panel { background: var(--paper); border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 1px 2px rgba(36,22,15,.04); }
    .card { padding: 16px 18px; }
    .card span { display: block; color: var(--muted); font-size: 13px; margin-bottom: 8px; }
    .card b { display: block; font-size: 28px; font-weight: 650; letter-spacing: -0.03em; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 14px; background: var(--paper); border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 1px 2px rgba(36,22,15,.04); overflow: hidden; }
    th, td { text-align: left; padding: 12px 14px; border-bottom: 1px solid var(--line); vertical-align: middle; }
    tr:last-child td { border-bottom: 0; }
    th { font-size: 11px; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); background: #fbf7f1; }
    tbody tr:hover td { background: #fffaf4; }
    td small, .muted { color: var(--muted); }
    img.thumb { width: 64px; height: 72px; object-fit: contain; background: #fff; border: 1px solid var(--line); border-radius: 10px; display: block; }
    .row { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 18px; }
    .btn, button.btn { display: inline-flex; align-items: center; justify-content: center; background: var(--maroon); color: #fff8ee; text-decoration: none; border: 0; border-radius: 999px; padding: 10px 16px; font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn:hover { background: #641825; }
    .btn.ghost { background: transparent; color: var(--maroon); border: 1px solid #d7b4ae; }
    .btn.ghost:hover { background: #fff; }
    td .actions { display: flex; gap: 12px; align-items: center; justify-content: flex-end; }
    td form { display: inline; margin: 0; }
    button.link { background: none; border: 0; padding: 0; color: var(--danger); font: inherit; cursor: pointer; }
    form.panel { padding: 22px; display: grid; gap: 14px; max-width: 860px; }
    label { display: grid; gap: 6px; font-size: 13px; font-weight: 650; color: #3c2c24; }
    label:has(> input[type="checkbox"]) { display: flex; align-items: center; gap: 8px; font-weight: 550; }
    label input[type="checkbox"] { width: auto; }
    input, textarea, select { font: inherit; font-weight: 450; color: var(--ink); border: 1px solid #d9cbb8; border-radius: 10px; padding: 10px 12px; background: #fff; width: 100%; }
    input:focus, textarea:focus, select { outline: 2px solid rgba(122,30,44,.18); border-color: #b9838c; }
    textarea { resize: vertical; }
    .checks { display: flex; flex-wrap: wrap; gap: 10px 16px; font-weight: 500; }
    .checks label, label.checks { display: flex; align-items: center; gap: 8px; font-weight: 550; }
    .checks input, label.checks input { width: auto; }
    .split { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .error { color: var(--danger); margin: 0; }
    .photo-field { display: grid; gap: 10px; }
    .preview { margin: 0; width: 180px; height: 210px; border: 1px solid var(--line); border-radius: 14px; background: #fff; display: grid; place-items: center; overflow: hidden; }
    .preview img { width: 100%; height: 100%; object-fit: contain; }
    .preview[hidden] { display: none; }
    .line { display: flex; align-items: center; gap: 12px; margin: 8px 0; }
    label.file { font-weight: 550; }
    @media (max-width: 1100px) { .cards { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 800px) {
      .shell, .cards, .split { grid-template-columns: 1fr; }
      aside { position: static; height: auto; }
      main { padding: 20px 16px 40px; }
    }
  </style>
</head>
<body>
  <div class="shell">
    <aside>
      <div class="brand">
        <strong>DIVINE KRAFT</strong>
        <span>Studio desk</span>
      </div>
      <nav>
        <section>
          <p>Shop</p>
          <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
          <a class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products') }}">Poshaks</a>
          <a class="{{ request()->routeIs('admin.banners*') ? 'active' : '' }}" href="{{ route('admin.banners') }}">Banners</a>
          <a class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">Categories</a>
          <a class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders') }}">Orders</a>
          <a class="{{ request()->routeIs('admin.payments') ? 'active' : '' }}" href="{{ route('admin.payments') }}">Payments</a>
          <a class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">Report</a>
        </section>
        <section>
          <p>Content</p>
          <a class="{{ request()->routeIs('admin.blogs*') ? 'active' : '' }}" href="{{ route('admin.blogs') }}">Journal</a>
          <a class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}" href="{{ route('admin.reviews') }}">Reviews</a>
          <a class="{{ request()->routeIs('admin.videos*') ? 'active' : '' }}" href="{{ route('admin.videos') }}">Videos</a>
          <a class="{{ request()->routeIs('admin.pages*') ? 'active' : '' }}" href="{{ route('admin.pages') }}">Pages</a>
          <a class="{{ request()->routeIs('admin.faqs*') ? 'active' : '' }}" href="{{ route('admin.faqs') }}">FAQ</a>
        </section>
        <section>
          <p>Studio</p>
          <a class="{{ request()->routeIs('admin.enquiries*') ? 'active' : '' }}" href="{{ route('admin.enquiries') }}">Enquiries</a>
          <a class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">Customers</a>
          <a class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">Settings</a>
        </section>
      </nav>
      <div class="side-foot">
        <a href="{{ env('FRONTEND_URL', 'https://divinekraft.com') }}" target="_blank" rel="noopener">View shop</a>
        <form method="post" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit">Log out</button>
        </form>
      </div>
    </aside>
    <main>
      @if (session('status'))
        <div class="flash">{{ session('status') }}</div>
      @endif
      @yield('content')
    </main>
  </div>
  <script>
    document.querySelectorAll('[data-preview-input]').forEach((input) => {
      input.addEventListener('change', () => {
        const file = input.files && input.files[0];
        const frame = input.form && input.form.querySelector('[data-preview="' + input.dataset.previewInput + '"]');
        const img = frame && frame.querySelector('img');
        if (!file || !img) return;
        img.src = URL.createObjectURL(file);
        frame.hidden = false;
      });
    });
  </script>
</body>
</html>
