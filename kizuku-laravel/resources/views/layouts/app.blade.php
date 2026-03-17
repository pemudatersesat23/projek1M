<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
  <style>
    .lang-switcher-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-right: 20px;
        background: #f1f5f9;
        padding: 6px 16px;
        border-radius: 99px;
    }
    .lang-toggle-link {
        font-size: 13px;
        font-weight: 800;
        color: #64748b;
        transition: all 0.3s;
        text-decoration: none;
    }
    .lang-toggle-link:hover, .lang-toggle-link.active {
        color: var(--detail-primary);
    }
    .lang-toggle-link.active {
        text-decoration: underline;
        text-underline-offset: 4px;
    }
  </style>
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
        <li><a href="{{ url('/') }}#beranda" class="active">{{ __('messages.nav.home') }}</a></li>
        <li><a href="{{ url('/') }}#program">{{ __('messages.nav.program') }}</a></li>
        <li><a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a></li>
        <li><a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a></li>
        <li><a href="{{ url('/') }}#kontak">{{ __('messages.nav.kontak') }}</a></li>
      </ul>
      <div class="nav-cta">
        <div class="lang-switcher-wrapper">
          <a href="{{ route('lang.switch', 'jp') }}" class="lang-toggle-link {{ app()->getLocale() == 'jp' ? 'active' : '' }}">JP</a>
          <span class="text-slate-300">|</span>
          <a href="{{ route('lang.switch', 'id') }}" class="lang-toggle-link {{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
        </div>
        <a class="btn btn-outline" href="{{ url('/') }}#kontak">{{ __('messages.nav.konsultasi') }}</a>
        <a class="btn btn-primary" href="{{ url('/') }}#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Daftar Sekarang' : '今すぐ登録' }}</a>
      </div>
      <button class="hamburger" id="hambtn" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobmenu">
      <a href="{{ url('/') }}#beranda">{{ __('messages.nav.home') }}</a>
      <a href="{{ url('/') }}#program">{{ __('messages.nav.program') }}</a>
      <a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a>
      <a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a>
      <a href="{{ url('/') }}#kontak">{{ __('messages.nav.kontak') }}</a>
      <div class="mob-cta">
        <div class="lang-switcher-wrapper flex justify-center gap-4 mb-4">
          <a href="{{ route('lang.switch', 'jp') }}" class="text-sm font-bold {{ app()->getLocale() == 'jp' ? 'text-primary' : 'text-slate-400' }}">JAPANESE</a>
          <a href="{{ route('lang.switch', 'id') }}" class="text-sm font-bold {{ app()->getLocale() == 'id' ? 'text-primary' : 'text-slate-400' }}">INDONESIA</a>
        </div>
        <a class="btn btn-outline" href="{{ url('/') }}#kontak">{{ __('messages.nav.konsultasi') }}</a>
        <a class="btn btn-primary" href="{{ url('/') }}#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Daftar Sekarang' : '今すぐ登録' }}</a>
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
          <p class="ft-desc">{{ __('messages.nav.home') === 'Beranda' ? 'Lembaga pelatihan kerja terpercaya yang mempersiapkan generasi Indonesia untuk bersaing dan berkarier di Jepang.' : 'インドネシアの次世代が日本で競争し、キャリアを築くための信頼できる職業訓練機関です。' }}</p>
          <div class="ft-socials">
            <a class="ft-soc" href="#" aria-label="Instagram">📸</a>
            <a class="ft-soc" href="#" aria-label="TikTok">🎵</a>
            <a class="ft-soc" href="#" aria-label="YouTube">▶️</a>
            <a class="ft-soc" href="#" aria-label="WhatsApp">💬</a>
          </div>
        </div>
        <div class="ft-col">
          <h5>{{ __('messages.nav.program') }}</h5>
          <ul>
            <li><a href="{{ route('pages.tokutei') }}">Tokutei Ginou (TG)</a></li>
            <li><a href="{{ route('pages.engineer') }}">Engineering</a></li>
            <li><a href="{{ route('pages.kursus') }}">Kelas Bahasa Jepang</a></li>
            <li><a href="{{ route('pages.magang') }}">Returnee / Ex Jepang</a></li>
          </ul>
        </div>
        <div class="ft-col">
          <h5>{{ __('messages.nav.home') === 'Beranda' ? 'Navigasi' : 'ナビゲーション' }}</h5>
          <ul>
            <li><a href="{{ url('/') }}#beranda">{{ __('messages.nav.home') }}</a></li>
            <li><a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a></li>
            <li><a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a></li>
            <li><a href="{{ url('/') }}#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Daftar Sekarang' : '今すぐ登録' }}</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} LPK Kizuku International Academy. {{ __('messages.nav.home') === 'Beranda' ? 'All rights reserved.' : '全著作権所有。' }}</p>
        <p>{{ __('messages.nav.home') === 'Beranda' ? 'Dibuat dengan ❤️ untuk masa depan Indonesia di Jepang' : 'インドネシアの日本での未来のために ❤️ を込めて作られました' }}</p>
      </div>
    </div>
  </footer>

  <!-- FAB Admin -->
  @auth
    <a class="btn btn-primary admin-fab" href="{{ route('admin.dashboard') }}">⚙️ Admin Panel</a>
  @endauth

  <script src="{{ asset('js/navbar.js') }}"></script>
  <script src="{{ asset('js/lang-toggle.js') }}"></script>
  @stack('scripts')

</body>
</html>
