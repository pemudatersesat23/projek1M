<!-- ═══ BERANDA / HERO ═══ -->
<section id="beranda" style="position: relative; padding-top: 68px; overflow: hidden; min-height: 100vh; display: flex; align-items: center;">
  
  <!-- Swiper Background -->
  <div class="swiper heroBgSwiper" style="position: absolute; inset: 0; z-index: -2;">
    <div class="swiper-wrapper">
      @forelse($heroSections->sortBy('sort_order') as $hero)
        <div class="swiper-slide">
          <div class="slide-bg" style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(11, 18, 32, .75) 0%, rgba(15, 76, 129, .55) 50%, rgba(11, 18, 32, .7) 100%), url('{{ $hero->image_path ? asset('storage/'.$hero->image_path) : asset('image/backgorund hero section.jpeg') }}') center/cover no-repeat;"></div>
        </div>
      @empty
        <div class="swiper-slide">
          <div class="slide-bg" style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(11, 18, 32, .75) 0%, rgba(15, 76, 129, .55) 50%, rgba(11, 18, 32, .7) 100%), url('{{ asset('image/backgorund hero section.jpeg') }}') center/cover no-repeat;"></div>
        </div>
      @endforelse
    </div>
  </div>

  <div class="hero-bg" style="z-index: -1;">
    <div class="hero-shape s1"></div>
    <div class="hero-shape s2"></div>
    <div class="hero-shape s3"></div>
  </div>

  <div class="hero-grid" style="z-index: -1;"></div>

  <div class="hero-inner" style="z-index: 2;">
    <div class="hero-left">
        <div class="hero-pill">
          <div class="hero-pill-dot"></div>
          {!! __('messages.home.hero_pill') !!}
        </div>
        <h1 class="hero-h1">
          {!! __('messages.hero.title') !!}
        </h1>
        <p class="hero-sub">
          {{ __('messages.hero.subtitle') }}
        </p>
        <div class="hero-btns">
          <a class="btn btn-primary btn-lg" href="#program">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            {{ __('messages.home.mc_title') === 'Program Tersedia' ? 'Lihat Program' : 'プログラムを見る' }}
          </a>
          <a class="btn btn-outline btn-lg" href="#kontak">{{ __('messages.nav.home') === 'Beranda' ? 'Konsultasi Gratis' : '無料相談' }}</a>
        </div>
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
          <div class="mc-prog"><span class="mc-dot" style="background:var(--red)"></span>{{ __('messages.home.mc_progs.tg') }}<span class="count">{{ __('messages.home.mc_progs.open') }}</span></div>
          <div class="mc-prog"><span class="mc-dot" style="background:var(--blue)"></span>{{ __('messages.home.mc_progs.eng') }}<span class="count">{{ __('messages.home.mc_progs.open') }}</span></div>
          <div class="mc-prog"><span class="mc-dot" style="background:#111"></span>{{ __('messages.home.mc_progs.lang') }}<span class="count">{{ __('messages.home.mc_progs.open') }}</span></div>
          <div class="mc-prog"><span class="mc-dot" style="background:linear-gradient(135deg,var(--red),var(--cyan))"></span>{{ __('messages.home.mc_progs.ret') }}<span class="count">{{ __('messages.home.mc_progs.open') }}</span></div>
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
          <div style="font-size:13px;font-weight:800;">{{ __('messages.home.hero_labels.go_japan') }}</div>
          <div style="font-size:11px;color:var(--muted);">{{ __('messages.home.hero_labels.official_program') }}</div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroDuration = {{ \App\Models\Setting::get('hero_slider_duration', 5) * 1000 }};
    const heroBgSwiper = new Swiper('.heroBgSwiper', {
        slidesPerView: 1,
        effect: 'fade',
        loop: true,
        autoplay: {
            delay: heroDuration,
            disableOnInteraction: false,
        },
        speed: 1000,
        allowTouchMove: false, // Prevent swiping the background manually
    });
});
</script>
@endpush

<!-- ═══ STATS ═══ -->
@php
    $statsActive = \App\Models\Setting::get('stats_active', 1);
@endphp
@if($statsActive == 1)
<div class="stats-strip">
  <div class="stats-inner container">
    @php
        if (!function_exists('splitStat')) {
            function splitStat($val) {
                preg_match('/^(\d+)(.*)$/', $val, $matches);
                if (count($matches) == 3) {
                    return '<div class="stat-num">' . $matches[1] . '<span>' . $matches[2] . '</span></div>';
                }
                return '<div class="stat-num">' . $val . '</div>';
            }
        }
    @endphp
    <div class="stat-item">
        {!! splitStat(\App\Models\Setting::get('stats_alumni', '1000+')) !!}
        <div class="stat-lbl">{{ __('messages.home.stats.alumni') }}</div>
    </div>
    <div class="stat-item">
        {!! splitStat(\App\Models\Setting::get('stats_success', '98%')) !!}
        <div class="stat-lbl">{{ __('messages.home.stats.success') }}</div>
    </div>
    <div class="stat-item">
        {!! splitStat(\App\Models\Setting::get('stats_years', '10+')) !!}
        <div class="stat-lbl">{{ __('messages.home.stats.years') }}</div>
    </div>
    <div class="stat-item">
        {!! splitStat(\App\Models\Setting::get('stats_programs', '4')) !!}
        <div class="stat-lbl">{{ __('messages.home.stats.programs') }}</div>
    </div>
  </div>
</div>
@endif
