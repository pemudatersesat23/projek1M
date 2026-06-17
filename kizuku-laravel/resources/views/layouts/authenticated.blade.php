<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LPK Kizuku International Academy')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

  <!-- CSS per Section -->
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  @stack('styles')
</head>
<body>

  <!-- ═══ SHARED NAVBAR ═══ -->
  <nav id="navbar">
    <a class="nav-brand" href="{{ url('/') }}#beranda">
      <img src="{{ asset('image/logo kiuzuku utama.png') }}" alt="LPK Kizuku International Academy" class="nav-logo-img">
    </a>
    
    <ul class="nav-links">
      <li><a href="{{ url('/') }}#beranda" class="{{ Request::is('/') ? 'active' : '' }}">{{ __('messages.nav.home') }}</a></li>
      <li><a href="{{ route('programs.index') }}" class="{{ Request::routeIs('programs.index') ? 'active' : '' }}">{{ __('messages.nav.program') }}</a></li>
      <li><a href="{{ route('pages.alur') }}" class="{{ Request::routeIs('pages.alur') ? 'active' : '' }}">{{ __('messages.nav.alur') }}</a></li>
      <li class="dropdown">
        <span class="dropdown-toggle">{{ __('messages.nav.about') }} <span class="dropdown-arrow">▼</span></span>
        <ul class="dropdown-menu">
          <li><a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a></li>
          <li><a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a></li>
          <li><a href="{{ url('/') }}#galeri">{{ __('messages.nav.gallery') }}</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <span class="dropdown-toggle">{{ __('messages.nav.info') }} <span class="dropdown-arrow">▼</span></span>
        <ul class="dropdown-menu">
          <li><a href="{{ url('/') }}#berita">{{ __('messages.nav.berita') }}</a></li>
          <li><a href="{{ route('pages.faq') }}">{{ __('messages.nav.faq') }}</a></li>
          <li><a href="{{ url('/') }}#kontak">{{ __('messages.nav.kontak') }}</a></li>
        </ul>
      </li>
    </ul>

    <div class="nav-cta">


      @guest
        <a class="btn btn-outline" href="{{ url('/') }}#kontak">{{ __('messages.nav.konsultasi') }}</a>
        <a class="btn btn-primary" href="{{ url('/') }}#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Daftar Sekarang' : '今すぐ登録' }}</a>
      @endguest

      @auth
        <div class="nav-user-auth">
          @include('layouts.navigation-dropdown')
        </div>
      @endauth
    </div>

    <button class="hamburger" id="hambtn" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </nav>

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

    /* User Dropdown Styles */
    .nav-user-auth {
        position: relative;
        margin-left: 15px;
    }
    .user-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: rgba(225, 6, 0, 0.05);
        border: 1px solid rgba(225, 6, 0, 0.1);
        border-radius: 99px;
        color: var(--primary, #e10600);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .user-btn:hover, .user-btn.active {
        background: var(--primary, #e10600);
        color: white;
    }
    .dropdown-icon {
        width: 16px;
        height: 16px;
        transition: transform 0.3s;
    }
    .user-btn.active .dropdown-icon {
        transform: rotate(180deg);
    }
    .user-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 220px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s;
        z-index: 1000;
    }
    .user-menu.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .user-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        color: #444;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .user-menu-item:hover {
        background: #f8f9fa;
        color: var(--primary, #e10600);
    }
    .user-menu-item svg {
        width: 18px;
        height: 18px;
        opacity: 0.7;
    }
    .user-menu-item.admin {
        color: #c2410c;
    }
    .user-menu-item.admin:hover {
        background: #fff7ed;
    }
    .user-menu-item.logout {
        color: #dc2626;
        width: 100%;
        border: none;
        background: none;
        cursor: pointer;
        text-align: left;
    }
    .user-menu-item.logout:hover {
        background: #fef2f2;
    }
    .user-menu-form {
        margin: 0;
        padding: 0;
    }
  </style>

  <!-- ═══ MAIN CONTENT ═══ -->
  @yield('content')

  <!-- FAB Admin -->
  @auth
    <a class="btn btn-primary admin-fab" href="{{ route('admin.dashboard') }}">⚙️ Admin Panel</a>
  @endauth

  @include('components.app-alerts')

  <script src="{{ asset('js/navbar.js') }}"></script>
  @stack('scripts')

</body>
</html>
