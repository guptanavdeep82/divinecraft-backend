<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin login · DivineKraft</title>
  <style>
    body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #5a1420; color: #2a1a14; font-family: system-ui, sans-serif; }
    form { width: min(420px, calc(100% - 32px)); background: #fbf7f0; border-radius: 20px; padding: 28px; display: grid; gap: 12px; }
    h1 { font-family: Georgia, serif; font-weight: 500; margin: 0 0 6px; }
    label { display: grid; gap: 6px; font-size: 13px; font-weight: 600; }
    input { border: 1px solid #cdbca4; border-radius: 10px; padding: 12px; font: inherit; }
    button { background: #7a1e2c; color: #fff8ee; border: 0; border-radius: 999px; padding: 12px; font: inherit; font-weight: 600; cursor: pointer; }
    .error { color: #9e1b32; margin: 0; }
  </style>
</head>
<body>
  <form method="post" action="{{ route('admin.login.submit') }}">
    @csrf
    <h1>DivineKraft admin</h1>
    <p>Sign in to edit poshaks, banners and enquiries.</p>
    @error('email') <p class="error">{{ $message }}</p> @enderror
    <label>Email <input type="email" name="email" value="{{ old('email') }}" required autofocus></label>
    <label>Password <input type="password" name="password" required></label>
    <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
    <button type="submit">Sign in</button>
  </form>
</body>
</html>
