<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin login · DivineKraft</title>
  <style>
    body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: linear-gradient(180deg, #3d1018, #541621); color: #24160f; font-family: "Segoe UI", system-ui, sans-serif; }
    form { width: min(420px, calc(100% - 32px)); background: #fffdf9; border-radius: 20px; padding: 28px; display: grid; gap: 12px; box-shadow: 0 18px 50px rgba(0,0,0,.22); }
    .mark { letter-spacing: .18em; font-size: 12px; font-weight: 700; color: #7a1e2c; }
    h1 { font-family: Georgia, serif; font-weight: 500; margin: 0; font-size: 32px; }
    p { margin: 0 0 6px; color: #6d5c52; }
    label { display: grid; gap: 6px; font-size: 13px; font-weight: 650; }
    input { border: 1px solid #d9cbb8; border-radius: 10px; padding: 12px; font: inherit; }
    label.row { display: flex; align-items: center; gap: 8px; font-weight: 550; }
    label.row input { width: auto; }
    button { background: #7a1e2c; color: #fff8ee; border: 0; border-radius: 999px; padding: 12px; font: inherit; font-weight: 650; cursor: pointer; }
    .error { color: #9e1b32; margin: 0; }
  </style>
</head>
<body>
  <form method="post" action="{{ route('admin.login.submit') }}">
    @csrf
    <div class="mark">DIVINE KRAFT</div>
    <h1>Studio desk</h1>
    <p>Sign in to edit poshaks, banners and orders.</p>
    @error('email') <p class="error">{{ $message }}</p> @enderror
    <label>Email <input type="email" name="email" value="{{ old('email') }}" required autofocus></label>
    <label>Password <input type="password" name="password" required></label>
    <label class="row"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
    <button type="submit">Sign in</button>
  </form>
</body>
</html>
