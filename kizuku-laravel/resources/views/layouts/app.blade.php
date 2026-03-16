<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LPK Kizuku International Academy')</title>
  <link rel="icon" type="image/png" href="{{ asset('image/logo tab broswer.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

  <!-- CSS per Section -->
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sections.css') }}">
  <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
  <link rel="stylesheet" href="{{ asset('css/berita.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('css/lang-toggle.css') }}">
  @stack('styles')
</head>
<body>

  <!-- ═══ NAVBAR ADMIN ═══ -->
  @auth
    @include('layouts.navigation')
  @endauth

  <!-- ═══ NAVBAR PUBLIC ═══ -->
  @guest
    <nav id="navbar">
      <a class="nav-brand" href="{{ url('/') }}#beranda">
        <img src="{{ asset('image/logo kiuzuku utama.png') }}" alt="LPK Kizuku International Academy" class="nav-logo-img">
      </a>
      <ul class="nav-links">
        <li><a href="{{ url('/') }}#beranda" class="active">Beranda</a></li>
        <li><a href="{{ url('/') }}#program">Program</a></li>
        <li><a href="{{ url('/') }}#kampus-partner">Kampus Partner</a></li>
        <li><a href="{{ url('/') }}#testimoni">Testimoni</a></li>
        <li><a href="{{ url('/') }}#kontak">Kontak</a></li>
      </ul>
      <div class="nav-cta">
        <button class="lang-toggle" id="langToggleDesktop" aria-label="Ganti Bahasa">
          <div class="brush-bg"></div>
          <div class="lang-label lang-label-jp">
            <span class="lang-char">日本語</span>
          </div>
          <div class="lang-divider"></div>
          <div class="lang-label lang-label-id active">
            <span class="lang-char">ID</span>
          </div>
          <div class="hanko">語</div>
        </button>
        <a class="btn btn-outline" href="{{ url('/') }}#kontak">Konsultasi</a>
        <a class="btn btn-primary" href="{{ url('/') }}#kontak">Daftar Sekarang</a>
      </div>
      <button class="hamburger" id="hambtn" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobmenu">
      <a href="{{ url('/') }}#beranda">Beranda</a>
      <a href="{{ url('/') }}#program">Program</a>
      <a href="{{ url('/') }}#kampus-partner">Kampus Partner</a>
      <a href="{{ url('/') }}#testimoni">Testimoni</a>
      <a href="{{ url('/') }}#kontak">Kontak</a>
      <div class="mob-cta">
        <button class="lang-toggle lang-toggle-mobile" aria-label="Ganti Bahasa">
          <div class="brush-bg"></div>
          <div class="lang-label lang-label-jp">
            <span class="lang-char">日本語</span>
          </div>
          <div class="lang-divider"></div>
          <div class="lang-label lang-label-id active">
            <span class="lang-char">ID</span>
          </div>
          <div class="hanko">語</div>
        </button>
        <a class="btn btn-outline" href="{{ url('/') }}#kontak">Konsultasi</a>
        <a class="btn btn-primary" href="{{ url('/') }}#kontak">Daftar Sekarang</a>
      </div>
    </div>
  @endguest

  @yield('content')

  <!-- ═══ FOOTER ═══ -->
  <footer>
    <div class="container">
      <div class="footer-inner">
        <div class="ft-brand">
          <div class="ft-nav-logo">
            <img src="{{ asset('image/logo kiuzuku utama.png') }}" alt="LPK Kizuku International Academy" class="ft-logo-img">
          </div>
          <p class="ft-desc">Lembaga pelatihan kerja terpercaya yang mempersiapkan generasi Indonesia untuk bersaing dan berkarier di Jepang.</p>
          <div class="ft-socials">
            <a class="ft-soc" href="#" aria-label="Instagram">📸</a>
            <a class="ft-soc" href="#" aria-label="TikTok">🎵</a>
            <a class="ft-soc" href="#" aria-label="YouTube">▶️</a>
            <a class="ft-soc" href="#" aria-label="WhatsApp">💬</a>
          </div>
        </div>
        <div class="ft-col">
          <h5>Program</h5>
          <ul>
            <li><a href="{{ url('/') }}#program">Tokutei Ginou (TG)</a></li>
            <li><a href="{{ url('/') }}#program">Engineering</a></li>
            <li><a href="{{ url('/') }}#program">Kelas Bahasa Jepang</a></li>
            <li><a href="{{ url('/') }}#program">Returnee / Ex Jepang</a></li>
          </ul>
        </div>
        <div class="ft-col">
          <h5>Navigasi</h5>
          <ul>
            <li><a href="{{ url('/') }}#beranda">Beranda</a></li>
            <li><a href="{{ url('/') }}#kampus-partner">Kampus Partner</a></li>
            <li><a href="{{ url('/') }}#testimoni">Testimoni</a></li>
            <li><a href="{{ url('/') }}#kontak">Daftar Sekarang</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} LPK Kizuku International Academy. All rights reserved.</p>
        <p>Dibuat dengan ❤️ untuk masa depan Indonesia di Jepang</p>
      </div>
    </div>
  </footer>

  <!-- FAB Admin -->
  @auth
    <a class="btn btn-primary admin-fab" href="{{ route('admin.siswa.index') }}">⚙️ Admin Panel</a>
  @endauth

  <script src="{{ asset('js/navbar.js') }}"></script>
  <script src="{{ asset('js/lang-toggle.js') }}"></script>
  @stack('scripts')

</body>
</html>
