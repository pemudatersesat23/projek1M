<!-- ═══ BERANDA / HERO ═══ -->
<section id="beranda">
  <div class="hero-bg">
    <div class="hero-shape s1"></div>
    <div class="hero-shape s2"></div>
    <div class="hero-shape s3"></div>
  </div>
  <div class="hero-grid"></div>

  <div class="hero-inner">
    <div class="hero-left">
      @php $hero = $heroSections->first(); @endphp
      @if($hero)
        <div class="hero-pill">
          <div class="hero-pill-dot"></div>
          {!! __('messages.home.hero_pill') !!}
        </div>
        <h1 class="hero-h1">
          {!! nl2br(e($hero->title)) !!}
        </h1>
        <p class="hero-sub">
          {{ $hero->subtitle }}
        </p>
        <div class="hero-btns">
          <a class="btn btn-primary btn-lg" href="{{ $hero->btn_primary_link }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            {{ $hero->btn_primary_text }}
          </a>
          <a class="btn btn-outline btn-lg" href="{{ $hero->btn_secondary_link }}">{{ $hero->btn_secondary_text }}</a>
        </div>
      @else
        <div class="hero-pill">
          <div class="hero-pill-dot"></div>
          {!! __('messages.home.hero_pill') !!}
        </div>
        <h1 class="hero-h1">
          Wujudkan Karier<br>
          <span class="line-accent">Impian di Jepang</span><br>
          Bersama Kami
        </h1>
        <p class="hero-sub">
          LPK Kizuku International Academy hadir untuk mempersiapkan kamu dengan pelatihan bahasa, budaya, dan skill kerja terbaik menuju Jepang.
        </p>
        <div class="hero-btns">
          <a class="btn btn-primary btn-lg" href="#program">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            {{ __('messages.home.mc_title') === 'Program Tersedia' ? 'Lihat Program' : 'プログラムを見る' }}
          </a>
          <a class="btn btn-outline btn-lg" href="#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Konsultasi Gratis' : '無料相談' }}</a>
        </div>
      @endif
      <div class="hero-trust">
        <div class="trust-avatars"><span>R</span><span>A</span><span>S</span></div>
        <div class="trust-text">
          <strong>{{ __('messages.home.trust_text') }}</strong>
          {{ __('messages.home.trust_sub') }}
        </div>
      </div>
    </div>

    <div class="hero-right">
      <div class="hero-card main-card">
        <div class="mc-header">
          <img src="{{ asset('image/logo tab broswer.png') }}" alt="Kizuku Logo" class="mc-logo-img">
          <div>
            <div class="mc-title">Kizuku International Academy</div>
            <div class="mc-sub">{{ __('messages.home.mc_title') }}</div>
          </div>
        </div>
        <div class="mc-prog-list">
          <div class="mc-prog"><span class="mc-dot" style="background:var(--red)"></span>Tokutei Ginou (TG)<span class="count">Open</span></div>
          <div class="mc-prog"><span class="mc-dot" style="background:var(--blue)"></span>Engineering<span class="count">Open</span></div>
          <div class="mc-prog"><span class="mc-dot" style="background:#111"></span>Kelas Bahasa Jepang<span class="count">Open</span></div>
          <div class="mc-prog"><span class="mc-dot" style="background:linear-gradient(135deg,var(--red),var(--cyan))"></span>Returnee / Ex Jepang<span class="count">Open</span></div>
        </div>
      </div>
      <div class="hero-card hero-badge-1">
        <div class="hb-icon" style="background:rgba(225,6,0,.10);font-size:20px;">🎌</div>
        <div class="hb-num" style="background:linear-gradient(135deg,var(--red),#ff5e58);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">98%</div>
        <div class="hb-label">{{ __('messages.home.stats.success') }}</div>
      </div>
      <div class="hero-card hero-badge-2">
        <div class="hb-icon" style="background:rgba(15,76,129,.10);font-size:20px;">🏆</div>
        <div class="hb-num" style="background:linear-gradient(135deg,var(--blue),var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">10+</div>
        <div class="hb-label">{{ __('messages.home.stats.years') }}</div>
      </div>
      <div class="jp-flag">
        <span class="flag">🇯🇵</span>
        <div>
          <div style="font-size:13px;font-weight:800;">{{ __('messages.nav.home') === 'Beranda' ? 'Berangkat ke Jepang' : '日本へ出発' }}</div>
          <div style="font-size:11px;color:var(--muted);">{{ __('messages.nav.home') === 'Beranda' ? 'Program resmi SSW/TG' : '公式SSW/TGプログラム' }}</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ STATS ═══ -->
<div class="stats-strip">
  <div class="stats-inner container">
    <div class="stat-item"><div class="stat-num">1000<span>+</span></div><div class="stat-lbl">{{ __('messages.home.stats.alumni') }}</div></div>
    <div class="stat-item"><div class="stat-num">98<span>%</span></div><div class="stat-lbl">{{ __('messages.home.stats.success') }}</div></div>
    <div class="stat-item"><div class="stat-num">10<span>+</span></div><div class="stat-lbl">{{ __('messages.home.stats.years') }}</div></div>
    <div class="stat-item"><div class="stat-num">4</div><div class="stat-lbl">{{ __('messages.home.stats.programs') }}</div></div>
  </div>
</div>
