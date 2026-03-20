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
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

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
  <!-- Swiper.js for Sliders -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
      <li><a href="{{ url('/') }}#program">{{ __('messages.nav.program') }}</a></li>
      <li><a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a></li>
      <li><a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a></li>
      <li><a href="{{ url('/') }}#galeri">{{ app()->getLocale() == 'id' ? 'Galeri' : (app()->getLocale() == 'jp' ? 'ギャラリー' : 'Gallery') }}</a></li>
      <li><a href="{{ url('/') }}#kontak">{{ __('messages.nav.kontak') }}</a></li>
    </ul>

    <div class="nav-cta">
      <div class="lang-switcher-wrapper">
        <a href="{{ route('lang.switch', 'jp') }}" class="lang-toggle-link {{ (app()->getLocale() == 'jp' || app()->getLocale() == 'ja') ? 'active' : '' }}">JP</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('lang.switch', 'id') }}" class="lang-toggle-link {{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
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
      <a href="{{ url('/') }}#program">{{ __('messages.nav.program') }}</a>
      <a href="{{ url('/') }}#kampus-partner">{{ __('messages.nav.partner') }}</a>
      <a href="{{ url('/') }}#testimoni">{{ __('messages.nav.testimoni') }}</a>
      <a href="{{ url('/') }}#galeri">{{ app()->getLocale() == 'id' ? 'Galeri' : (app()->getLocale() == 'jp' ? 'ギャラリー' : 'Gallery') }}</a>
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

    <div class="mob-cta">
      <div class="lang-switcher-wrapper flex justify-center gap-4 mb-4">
        <a href="{{ route('lang.switch', 'jp') }}" class="text-sm font-bold {{ app()->getLocale() == 'jp' ? 'text-primary' : 'text-slate-400' }}">JAPANESE</a>
        <a href="{{ route('lang.switch', 'id') }}" class="text-sm font-bold {{ app()->getLocale() == 'id' ? 'text-primary' : 'text-slate-400' }}">INDONESIA</a>
      </div>
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
            <a class="ft-soc" href="#" aria-label="Instagram">📸</a>
            <a class="ft-soc" href="#" aria-label="TikTok">🎵</a>
            <a class="ft-soc" href="#" aria-label="YouTube">▶️</a>
            <a class="ft-soc" href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', '6281217549529') }}" aria-label="WhatsApp">💬</a>
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
                    <option value="Tokutei Ginou (TG)">Tokutei Ginou (TG)</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Kelas Bahasa Jepang">Kelas Bahasa Jepang</option>
                    <option value="Magang / Returnee">Magang / Returnee</option>
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

  <script src="{{ asset('js/navbar.js') }}"></script>
  <script src="{{ asset('js/lang-toggle.js') }}"></script>
  <script>
    // Initialize Swiper Sliders
    document.addEventListener('DOMContentLoaded', function() {
      const testimonialSwiper = new Swiper('.testimonialSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          640: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
        }
      });

      const gallerySwiper = new Swiper('.gallerySwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          640: { slidesPerView: 2 },
          768: { slidesPerView: 3 },
          1024: { slidesPerView: 4 },
        }
      });

      const kampusPartnerSwiper = new Swiper('.kampusPartnerSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          640: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
        }
      });
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
            alert('Mohon isi semua data agar admin dapat membantu Anda lebih baik.');
            return;
        }

        const phone = '{{ \App\Models\Setting::get("whatsapp_number", "6281217549529") }}';
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
  @stack('scripts')

</body>
</html>
