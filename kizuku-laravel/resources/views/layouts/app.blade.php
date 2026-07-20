<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LPK Kizuku International Academy — Pelatihan Kerja ke Jepang Terpercaya')</title>

  {{-- SEO Meta Tags --}}
  <meta name="description" content="@yield('meta_description', 'LPK Kizuku International Academy adalah lembaga pelatihan kerja ke Jepang terpercaya di Makassar. Program Tokutei Ginou, Engineering, magang Jepang, dan kursus bahasa Jepang intensif.')">
  <meta name="keywords" content="@yield('meta_keywords', 'kerja ke jepang, lpk makassar, tokutei ginou, magang jepang, kursus bahasa jepang makassar, kizuku academy, pelatihan kerja jepang, lowongan kerja jepang, belajar bahasa jepang, lpk sulawesi selatan')">
  <meta name="author" content="LPK Kizuku International Academy">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">

  {{-- Open Graph (Facebook, LinkedIn, WhatsApp) --}}
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="@yield('title', 'LPK Kizuku International Academy — Pelatihan Kerja ke Jepang Terpercaya')">
  <meta property="og:description" content="@yield('meta_description', 'LPK Kizuku International Academy adalah lembaga pelatihan kerja ke Jepang terpercaya di Makassar. Program Tokutei Ginou, Engineering, magang Jepang, dan kursus bahasa Jepang intensif.')">
  <meta property="og:image" content="@yield('og_image', asset('image/logo kiuzuku utama.png'))">
  <meta property="og:site_name" content="LPK Kizuku International Academy">
  <meta property="og:locale" content="id_ID">

  {{-- Twitter Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('title', 'LPK Kizuku International Academy — Pelatihan Kerja ke Jepang Terpercaya')">
  <meta name="twitter:description" content="@yield('meta_description', 'LPK Kizuku International Academy adalah lembaga pelatihan kerja ke Jepang terpercaya di Makassar. Program Tokutei Ginou, Engineering, magang Jepang, dan kursus bahasa Jepang intensif.')">
  <meta name="twitter:image" content="@yield('og_image', asset('image/logo kiuzuku utama.png'))">

  {{-- JSON-LD Structured Data --}}
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "name": "LPK Kizuku International Academy",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('image/logo kiuzuku utama.png') }}",
    "description": "Lembaga pelatihan kerja ke Jepang terpercaya di Makassar yang menyediakan program Tokutei Ginou, Engineering, dan kursus bahasa Jepang intensif.",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "{{ $appSettings['office_address'] ?? 'Jl. Bontotangnga, Paccinongang' }}",
      "addressLocality": "Kabupaten Gowa",
      "addressRegion": "Sulawesi Selatan",
      "postalCode": "90233",
      "addressCountry": "ID"
    },
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "+{{ $appSettings['whatsapp_number'] ?? '6282261888851' }}",
      "contactType": "customer service",
      "availableLanguage": ["Indonesian", "Japanese"]
    },
    "sameAs": [
      "{{ $appSettings['facebook_url'] ?? '' }}",
      "{{ $appSettings['instagram_link'] ?? 'https://www.instagram.com/kizuku.academy' }}",
      "{{ $appSettings['tiktok_link'] ?? 'https://www.tiktok.com/@kizuku.academy' }}"
    ]
  }
  </script>
  @stack('seo')

  <link rel="icon" type="image/png" href="{{ asset('image/favicon-square.png') }}">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

  <!-- CSS per Section -->
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}?v=1.0.1">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sections.css') }}">
  <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
  <link rel="stylesheet" href="{{ asset('css/berita.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
  <!-- Swiper.js for Sliders -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <style>
    /* Hide Google Translate top bar & tooltips */
    .skiptranslate, iframe.goog-te-banner-frame, .goog-te-banner-frame, .goog-te-balloon-frame {
        display: none !important;
    }
    body {
        top: 0px !important;
    }
    .goog-tooltip, .goog-tooltip:hover {
        display: none !important;
    }
    .goog-text-highlight {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

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

    /* WhatsApp Modal Styles */
    .wa-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .wa-modal-backdrop.active {
        opacity: 1;
        visibility: visible;
    }
    .wa-modal-card {
        background: white;
        width: 100%;
        max-width: 440px;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        transform: translateY(20px);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .wa-modal-backdrop.active .wa-modal-card {
        transform: translateY(0);
    }
    .wa-modal-header {
        background: linear-gradient(135deg, #25D366, #128C7E);
        padding: 24px;
        color: white;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .wa-modal-header .wa-icon {
        font-size: 32px;
    }
    .wa-modal-body {
        padding: 28px;
    }
    .wa-form-group {
        margin-bottom: 20px;
    }
    .wa-form-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .wa-input, .wa-select, .wa-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        font-size: 15px;
        background: #f8fafc;
        transition: all 0.3s;
        outline: none;
    }
    .wa-input:focus, .wa-select:focus, .wa-textarea:focus {
        border-color: #25D366;
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.1);
    }
    .wa-btn-submit {
        width: 100%;
        padding: 14px;
        background: #25D366;
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
    }
    .wa-btn-submit:hover {
        background: #128C7E;
        transform: translateY(-2px);
    }
    .wa-modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        color: rgba(255,255,255,0.8);
        cursor: pointer;
        transition: all 0.3s;
    }
    .wa-modal-close:hover {
        color: white;
        transform: scale(1.1);
    }

    /* Premium Flag Switcher Styles */
    .lang-switcher-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(241, 245, 249, 0.9);
        padding: 4px 8px;
        border-radius: 99px;
        border: 1px solid rgba(0,0,0,0.08);
        margin-right: 15px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }
    .lang-btn {
        background: none;
        border: none;
        padding: 4px 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 99px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0.45;
        scale: 0.95;
        text-decoration: none;
    }
    .lang-btn svg {
        border-radius: 2px;
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s;
        display: block;
    }
    .lang-btn:hover {
        opacity: 0.85;
        scale: 1.05;
    }
    .lang-btn.active {
        opacity: 1;
        scale: 1.1;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.04);
    }
    .lang-btn.active svg {
        border-color: rgba(0,0,0,0.15);
    }
    .lang-divider {
        width: 1px;
        height: 14px;
        background: rgba(0,0,0,0.1);
    }
  </style>
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
      <!-- Language Switcher desktop -->
      <div class="lang-switcher-buttons">
          <a href="javascript:changeLanguage('id')" class="lang-btn lang-btn-id" title="Bahasa Indonesia">
              <svg width="20" height="14" viewBox="0 0 3 2"><rect fill="#ED2939" width="3" height="1"/><rect fill="#fff" y="1" width="3" height="1"/></svg>
          </a>
          <span class="lang-divider"></span>
          <a href="javascript:changeLanguage('ja')" class="lang-btn lang-btn-jp" title="日本語">
              <svg width="20" height="14" viewBox="0 0 3 2"><rect fill="#fff" width="3" height="2"/><circle fill="#bc002d" cx="1.5" cy="1" r="0.6"/></svg>
          </a>
      </div>

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

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobmenu">
    <div class="mob-links">
      <a href="{{ url('/') }}#beranda">{{ __('messages.nav.home') }}</a>
      <a href="{{ route('programs.index') }}">{{ __('messages.nav.program') }}</a>
      <a href="{{ route('pages.alur') }}">{{ __('messages.nav.alur') }}</a>
      
      <div class="mob-dropdown-title">{{ __('messages.nav.about') }}</div>
      <a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a>
      <a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a>
      <a href="{{ url('/') }}#galeri">{{ __('messages.nav.gallery') }}</a>
      
      <div class="mob-dropdown-title">{{ __('messages.nav.info') }}</div>
      <a href="{{ url('/') }}#berita">{{ __('messages.nav.berita') }}</a>
      <a href="{{ route('pages.faq') }}">{{ __('messages.nav.faq') }}</a>
      <a href="{{ url('/') }}#kontak">{{ __('messages.nav.kontak') }}</a>
    </div>

    @auth
      <div class="mob-auth-section" style="padding: 20px 0; border-top: 1px solid rgba(0,0,0,0.05); margin-top: 20px;">
        <a href="{{ route('profile.edit') }}" class="mob-link">⚙️ {{ __('messages.auth.profile') }}</a>
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}" class="mob-link admin">🔐 {{ __('messages.auth.dashboard') }}</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
          @csrf
          <button type="submit" class="mob-link logout" style="width: 100%; text-align: left; background: none; border: none; padding: 12px 0; font-family: inherit; font-size: 16px; cursor: pointer;">🚪 {{ __('messages.auth.logout') }}</button>
        </form>
      </div>
    @endauth

    <!-- Language Switcher mobile -->
    <div style="padding: 10px 20px;">
      <div class="lang-switcher-buttons" style="display: inline-flex;">
          <a href="javascript:changeLanguage('id')" class="lang-btn lang-btn-id" title="Bahasa Indonesia">
              <svg width="20" height="14" viewBox="0 0 3 2"><rect fill="#ED2939" width="3" height="1"/><rect fill="#fff" y="1" width="3" height="1"/></svg>
          </a>
          <span class="lang-divider"></span>
          <a href="javascript:changeLanguage('ja')" class="lang-btn lang-btn-jp" title="日本語">
              <svg width="20" height="14" viewBox="0 0 3 2"><rect fill="#fff" width="3" height="2"/><circle fill="#bc002d" cx="1.5" cy="1" r="0.6"/></svg>
          </a>
      </div>
    </div>

    <div class="mob-cta">

      @guest
        <a class="btn btn-outline" href="{{ url('/') }}#kontak">{{ __('messages.nav.konsultasi') }}</a>
        <a class="btn btn-primary" href="{{ url('/') }}#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Daftar Sekarang' : '今すぐ登録' }}</a>
      @endguest
    </div>
  </div>

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
            {{-- Instagram --}}
            <a class="ft-soc" href="{{ $appSettings['instagram_link'] ?? 'https://www.instagram.com/kizuku.academy' }}" target="_blank" aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">
                <defs>
                  <linearGradient id="ig-grad" x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#f09433"/>
                    <stop offset="25%" style="stop-color:#e6683c"/>
                    <stop offset="50%" style="stop-color:#dc2743"/>
                    <stop offset="75%" style="stop-color:#cc2366"/>
                    <stop offset="100%" style="stop-color:#bc1888"/>
                  </linearGradient>
                </defs>
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="url(#ig-grad)" stroke-width="2" fill="none"/>
                <circle cx="12" cy="12" r="4.5" stroke="url(#ig-grad)" stroke-width="2" fill="none"/>
                <circle cx="17.5" cy="6.5" r="1.2" fill="url(#ig-grad)"/>
              </svg>
            </a>
            {{-- TikTok --}}
            <a class="ft-soc" href="{{ $appSettings['tiktok_link'] ?? 'https://www.tiktok.com/@kizuku.academy' }}" target="_blank" aria-label="TikTok">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.95a8.18 8.18 0 0 0 4.78 1.53V7.04a4.85 4.85 0 0 1-1.01-.35z"/>
              </svg>
            </a>
            {{-- Facebook --}}
            <a class="ft-soc" href="{{ $appSettings['facebook_url'] ?? 'https://www.facebook.com/share/1BHHcLbuLP/?mibextid=wwXIfr' }}" target="_blank" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="#1877F2">
                <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073c0 6.027 4.388 11.024 10.125 11.927v-8.437H7.078v-3.49h3.047V9.413c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796v8.437C19.612 23.097 24 18.1 24 12.073z"/>
              </svg>
            </a>
            {{-- WhatsApp --}}
            <a class="ft-soc" href="https://wa.me/{{ $appSettings['whatsapp_number'] }}" target="_blank" aria-label="WhatsApp">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="#25D366">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
              </svg>
            </a>
          </div>
        </div>
        <div class="ft-col">
          <h5>{{ __('messages.nav.program') }}</h5>
          <ul>
            <li><span>Tokutei Ginou (TG)</span></li>
            <li><span>Engineering</span></li>
            <li><span>Kelas Bahasa Jepang</span></li>
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

  <!-- WhatsApp Modal -->
  <div id="waModal" class="wa-modal-backdrop">
    <div class="wa-modal-card">
        <div class="wa-modal-header">
            <span class="material-symbols-outlined wa-icon">forum</span>
            <div>
                <h4 style="margin:0; font-size:18px; font-weight:800;">Tanya Admin Kizuku</h4>
                <p style="margin:0; font-size:13px; opacity:0.9;">Fast response via WhatsApp</p>
            </div>
            <span class="material-symbols-outlined wa-modal-close" onclick="closeWaModal()">close</span>
        </div>
        <div class="wa-modal-body">
            <div class="wa-form-group">
                <label class="wa-form-label">Nama Lengkap</label>
                <input type="text" id="wa_name" class="wa-input" placeholder="Masukkan nama Anda...">
            </div>
            <div class="wa-form-group">
                <label class="wa-form-label">Program yang diminati</label>
                <select id="wa_program" class="wa-select">
                    <option value="">-- Pilih Program --</option>
                    @foreach($publicPrograms as $prog)
                        <option value="{{ $prog->getTranslation('nama_program', app()->getLocale()) ?: $prog->getTranslation('nama_program', 'id') }}">
                            {{ $prog->getTranslation('nama_program', app()->getLocale()) ?: $prog->getTranslation('nama_program', 'id') }}
                        </option>
                    @endforeach
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="wa-form-group">
                <label class="wa-form-label">Pertanyaan</label>
                <textarea id="wa_question" class="wa-textarea" rows="3" placeholder="Tuliskan pertanyaan Anda di sini..."></textarea>
            </div>
            <button class="wa-btn-submit" onclick="sendToWhatsApp()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.025 3.284l-.548 2.003 2.051-.538c.952.517 1.912.838 3.24.838 3.182 0 5.768-2.586 5.768-5.766-.001-3.182-2.587-5.767-5.768-5.767zm3.334 8.169c-.145.412-.848.749-1.121.799-.247.045-.568.082-1.611-.337-.899-.362-1.474-1.272-1.519-1.332-.045-.061-.363-.483-.363-.928s.233-.664.316-.755c.083-.091.182-.114.242-.114.061 0 .121.001.174.004.057.003.134-.021.21-.205.076-.182.261-.635.32-.751.059-.116.1-.252.009-.434-.092-.182-.182-.363-.265-.544-.083-.182-.174-.37-.253-.559-.079-.189-.161-.137-.229-.141-1.042-.058-2.029.743-2.029 1.838 0 .424.16.83.475 1.144.57 1.258 1.159 1.956 2.569 2.583 1.258.559 1.766.444 2.454.341.688-.104 1.353-.541 1.543-1.041.192-.5.192-.929.135-1.042-.057-.113-.208-.182-.435-.296z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.891.527 3.66 1.442 5.174L2 22l4.985-1.314C8.428 21.558 10.151 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18c-1.63 0-3.155-.445-4.471-1.222l-2.822.744.757-2.766A7.944 7.944 0 014 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/></svg>
                Chat Admin Sekarang
            </button>
        </div>
    </div>
  </div>

  @include('components.app-alerts')

  <script src="{{ asset('js/navbar.js') }}"></script>
  <script src="{{ asset('js/lang-toggle.js') }}"></script>
  <script>
    // Initialize Swiper Sliders
    // Setiap inisialisasi dilindungi null check agar tidak error di halaman yang tidak memiliki element slider.
    document.addEventListener('DOMContentLoaded', function() {

      if (document.querySelector('.testimonialSwiper')) {
        new Swiper('.testimonialSwiper', {
          slidesPerView: 1,
          spaceBetween: 20,
          loop: true,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.testimonialSwiper .swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.testimonialSwiper .swiper-button-next',
            prevEl: '.testimonialSwiper .swiper-button-prev',
          },
          breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
          }
        });
      }

      if (document.querySelector('.gallerySwiper')) {
        new Swiper('.gallerySwiper', {
          slidesPerView: 1,
          spaceBetween: 20,
          loop: true,
          autoplay: {
            delay: 3000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.gallerySwiper .swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.gallerySwiper .swiper-button-next',
            prevEl: '.gallerySwiper .swiper-button-prev',
          },
          breakpoints: {
            640: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1024: { slidesPerView: 4 },
          }
        });
      }

      if (document.querySelector('.heroSwiper')) {
        new Swiper('.heroSwiper', {
          slidesPerView: 1,
          spaceBetween: 0,
          loop: true,
          effect: 'fade',
          fadeEffect: {
            crossFade: true
          },
          autoplay: {
            delay: 7000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.heroSwiper .swiper-pagination',
            clickable: true,
          },
        });
      }

      if (document.querySelector('.kampusPartnerSwiper')) {
        new Swiper('.kampusPartnerSwiper', {
          slidesPerView: 1,
          spaceBetween: 20,
          loop: true,
          autoplay: {
            delay: 4000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.kampusPartnerSwiper .swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.kampusPartnerSwiper .swiper-button-next',
            prevEl: '.kampusPartnerSwiper .swiper-button-prev',
          },
          breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
          }
        });
      }

    });

    const waModal = document.getElementById('waModal');
    
    function openWaModal() {
        waModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeWaModal() {
        waModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Capture all WA button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.open-wa-modal') || (e.target.closest('a') && e.target.closest('a').href.includes('wa.me'))) {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                openWaModal();
            }
        }
    });

    function sendToWhatsApp() {
        const name = document.getElementById('wa_name').value;
        const program = document.getElementById('wa_program').value;
        const question = document.getElementById('wa_question').value;

        if (!name || !program || !question) {
            window.KizukuAlert.alert('Mohon isi semua data agar admin dapat membantu Anda lebih baik.', {
              type: 'warning',
              title: 'Data Belum Lengkap',
            });
            return;
        }

        const phone = '{{ $appSettings["whatsapp_number"] }}';
        const template = `Halo Admin, saya *${name}*. Saya ingin bertanya tentang program *${program}*.\n\nPertanyaan saya:\n${question}`;
        const url = `https://wa.me/${phone}?text=${encodeURIComponent(template)}`;
        
        window.open(url, '_blank');
        closeWaModal();
    }

    // Close on backdrop click
    waModal.addEventListener('click', function(e) {
        if (e.target === waModal) closeWaModal();
    });
  </script>
  
  <!-- Google Translate Element (Hidden) -->
  <div id="google_translate_element" style="display:none;"></div>
  
  <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'id',
        includedLanguages: 'id,ja',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
      }, 'google_translate_element');
    }
  </script>
  <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

  <script type="text/javascript">
    function getCookie(name) {
      var value = "; " + document.cookie;
      var parts = value.split("; " + name + "=");
      if (parts.length === 2) return parts.pop().split(";").shift();
      return null;
    }

    function changeLanguage(langCode) {
      var domain = window.location.hostname;
      document.cookie = "googtrans=/id/" + langCode + "; path=/";
      document.cookie = "googtrans=/id/" + langCode + "; path=/; domain=" + domain;
      document.cookie = "googtrans=/id/" + langCode + "; path=/; domain=." + domain;
      
      var select = document.querySelector('select.goog-te-combo');
      if (select) {
        select.value = langCode;
        select.dispatchEvent(new Event('change'));
      }
      location.reload();
    }

    document.addEventListener("DOMContentLoaded", function() {
      // Prevent Google Translate from translating material icons, swiper buttons, and lang buttons
      document.querySelectorAll('.material-symbols-outlined, .swiper-button-next, .swiper-button-prev, [class*="swiper-button"], .lang-btn, .lang-switcher-buttons').forEach(function(el) {
        el.classList.add('notranslate');
        el.setAttribute('translate', 'no');
      });

      // Cek seluruh cookie googtrans yang aktif di browser (menghindari bug duplikasi cookie domain di production)
      var isJp = document.cookie.includes('googtrans=/id/ja');

      var btnIds = document.querySelectorAll('.lang-btn-id');
      var btnJps = document.querySelectorAll('.lang-btn-jp');

      if (isJp) {
        btnIds.forEach(function(el) { el.classList.remove('active'); });
        btnJps.forEach(function(el) { el.classList.add('active'); });
      } else {
        btnIds.forEach(function(el) { el.classList.add('active'); });
        btnJps.forEach(function(el) { el.classList.remove('active'); });
      }
    });
  </script>
  @stack('scripts')

</body>
</html>
