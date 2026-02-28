<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin — @yield('admin-title', 'Panel') | LPK Kizuku</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <style>
    body { font-family:'Sora',sans-serif; background:var(--gray); color:var(--black); margin:0; }
    .admin-shell { min-height:100vh; display:flex; flex-direction:column; }
    .admin-header {
      background:linear-gradient(135deg,var(--blue),#0a3560);
      padding:18px 28px;
      display:flex; align-items:center; justify-content:space-between;
      color:#fff; flex-shrink:0;
    }
    .admin-header h1 { font-size:17px; font-weight:800; margin:0; }
    .admin-header p  { font-size:12px; color:rgba(255,255,255,.6); margin:2px 0 0; }
    .admin-header-right { display:flex; align-items:center; gap:10px; }
    .admin-nav {
      background:#fff;
      border-bottom:1px solid rgba(17,17,17,.08);
      padding:0 28px;
      display:flex; gap:4px;
    }
    .admin-nav a {
      padding:12px 18px;
      text-decoration:none;
      font-size:13px; font-weight:700;
      color:var(--muted);
      border-bottom:2px solid transparent;
      transition:color .18s, border-color .18s;
    }
    .admin-nav a:hover { color:var(--black); }
    .admin-nav a.active { color:var(--blue); border-bottom-color:var(--blue); }
    .admin-content { flex:1; padding:24px 28px; max-width:1200px; width:100%; margin:0 auto; }
    .alert-success {
      padding:12px 16px; border-radius:12px;
      background:rgba(16,185,129,.12); color:#059669;
      font-weight:700; font-size:13.5px; margin-bottom:20px;
    }
  </style>
</head>
<body>
  <div class="admin-shell">
    <div class="admin-header">
      <div>
        <h1>⚙️ Panel Admin — LPK Kizuku</h1>
        <p>Kelola data siswa, pendaftaran, dan konten berita</p>
      </div>
      <div class="admin-header-right">
        <a class="btn btn-outline-white" href="{{ url('/') }}" style="font-size:12px;padding:7px 14px;">🏠 Ke Website</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
          @csrf
          <button type="submit" class="btn btn-outline-white" style="font-size:12px;padding:7px 14px;">🚪 Logout</button>
        </form>
      </div>
    </div>

    <div class="admin-nav">
      <a href="{{ route('admin.siswa.index') }}" class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">📋 Data Siswa</a>
      <a href="{{ route('admin.siswa.create') }}" class="{{ request()->routeIs('admin.siswa.create') ? 'active' : '' }}">➕ Tambah Siswa</a>
      <a href="{{ route('admin.berita.index') }}" class="{{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">📰 Kelola Berita</a>
    </div>

    <div class="admin-content">
      @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
      @endif
      @yield('admin-content')
    </div>
  </div>
</body>
</html>
