<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register — CDRRMD</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--red:#b60b0b;--bg:#f3f4f5;--card:#fff;--black:#000;--muted:#9aa0a6;--google:#e6e6e6;--error:#d9534f;--logo:140px;--card-w:420px}
    html,body{height:100%;margin:0;font-family:Poppins,system-ui,Segoe UI,Arial,sans-serif;background:var(--bg);color:var(--black)}
    .top-bar{height:64px;background:var(--red)}
    .page{min-height:calc(100vh - 64px);display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding:48px 20px}
    .page-head{text-align:center;margin-top:8px}
    .logo{width:var(--logo);height:var(--logo);object-fit:contain;display:block;margin:0 auto}
    .site-title{font-size:28px;margin:12px 0 24px;font-weight:700}
    .card{width:100%;max-width:var(--card-w);background:var(--card);border-radius:12px;padding:28px;box-shadow:0 8px 24px rgba(14,20,30,0.06)}
    h2{font-size:18px;margin:0 0 16px;font-weight:600}
    .label{display:block;margin-bottom:12px;font-size:13px}
    input[type=text],input[type=email],input[type=password]{width:100%;height:44px;padding:10px 12px;border-radius:12px;border:1px solid rgba(0,0,0,0.12);font-size:14px;outline:none;box-sizing:border-box}
    input:focus{border:1.5px solid var(--black);box-shadow:inset 0 1px 0 rgba(0,0,0,0.02)}
    .field-error{color:var(--error);font-size:13px;margin-top:6px}
    .muted{color:var(--muted);font-size:13px;margin:10px 0}
    .btn{display:inline-flex;align-items:center;justify-content:center;width:100%;height:44px;border-radius:8px;border:none;cursor:pointer;text-decoration:none}
    .btn.primary{background:var(--black);color:#fff;margin-top:8px}
    .divider{text-align:center;color:var(--muted);margin:12px 0}
    .btn.google{background:var(--google);color:#000;padding-left:12px}
    .google-icon{width:18px;height:18px;margin-right:8px}
    .small{font-size:13px}
    .card form p, .card form .muted{margin-top:12px}
    @media (max-width:480px){:root{--card-w:92%}.site-title{font-size:22px}}
  </style>
</head>
<body>
  <div class="top-bar" aria-hidden="true"></div>
  <main class="page">
    <header class="page-head">
      <img src="{{ asset('images/cdrrmd-logo.png') }}" alt="San Juan CDRRMD logo" class="logo">
      <h1 class="site-title">CDRRMD - Admin &amp; Training</h1>
    </header>

    <section class="card" role="form" aria-labelledby="register-heading">
      <h2 id="register-heading">Create an account</h2>

      <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <label class="label">Full name
          <input type="text" name="name" value="{{ old('name') }}" required aria-required="true" aria-describedby="name-error">
        </label>
        @error('name')<div id="name-error" class="field-error">{{ $message }}</div>@enderror

        <label class="label">Email
          <input type="email" name="email" value="{{ old('email') }}" required aria-required="true" aria-describedby="email-error">
        </label>
        @error('email')<div id="email-error" class="field-error">{{ $message }}</div>@enderror

        <label class="label">Password
          <input type="password" name="password" required aria-required="true" aria-describedby="password-error">
        </label>
        @error('password')<div id="password-error" class="field-error">{{ $message }}</div>@enderror

        <label class="label">Confirm Password
          <input type="password" name="password_confirmation" required aria-required="true">
        </label>

        <div class="muted">By registering, your account will require superadmin approval before access.</div>

        <button type="submit" class="btn primary">Register</button>

        <div class="divider">or</div>

        <a href="{{ route('auth.google') }}" class="btn google" aria-label="Continue with Google">
          <img src="{{ asset('images/google-icon.svg') }}" alt="Google" class="google-icon"> Continue with Google
        </a>

        <p class="muted small">Already have an account? <a href="{{ route('login') }}">Login</a></p>
      </form>
    </section>
  
    @if(session('registered'))
      <div id="registered-modal" style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.4)">
        <div style="background:#fff;padding:24px;border-radius:12px;max-width:480px;width:90%;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,0.12)">
          <h3 style="margin-top:0">Registration received</h3>
          <p style="color:var(--muted)">Thank you — your account request has been submitted. Please wait for administrator approval. You will receive an email once your account is activated.</p>
          <button onclick="document.getElementById('registered-modal').style.display='none'" class="btn" style="margin-top:12px;background:#111;color:#fff;padding:10px 16px;border-radius:8px">Close</button>
        </div>
      </div>
    @endif
  </main>
</body>
</html>