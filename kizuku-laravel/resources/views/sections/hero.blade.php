<!-- ═══ BERANDA / HERO ═══ -->
<section id="beranda" style="position:relative; min-height:100vh; overflow:hidden; background:#0b1220;">

  <!-- Background Slider (absolutely positioned, fills the section) -->
  <div class="swiper heroSwiper" style="position:absolute; inset:0; width:100%; height:100%; z-index:0;">
    <div class="swiper-wrapper">
      @forelse($heroSections->sortBy('sort_order') as $hero)
        <div class="swiper-slide hero-slide" style="background-image: url('{{ $hero->image_path ? asset('storage/' . $hero->image_path) : asset('image/backgorund hero section.jpeg') }}'); background-size:cover; background-position:center; background-repeat:no-repeat; width:100%; height:100vh;">
          <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(11,18,32,0.72) 0%, rgba(15,76,129,0.42) 50%, rgba(11,18,32,0.65) 100%); z-index:1;"></div>
        </div>
      @empty
        <div class="swiper-slide hero-slide" style="background-image: url('{{ asset('image/backgorund hero section.jpeg') }}'); background-size:cover; background-position:center; background-repeat:no-repeat; width:100%; height:100vh;">
          <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(11,18,32,0.72) 0%, rgba(15,76,129,0.42) 50%, rgba(11,18,32,0.65) 100%); z-index:1;"></div>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Decorative grid overlay -->
  <div class="hero-grid" style="position:absolute; inset:0; z-index:1; pointer-events:none;"></div>

  <!-- Static Content (left-aligned, above slider) -->
  <div style="position:relative; z-index:10; padding: 140px max(48px, 8vw) 120px; max-width:860px; display:flex; flex-direction:column; align-items:flex-start; text-align:left; min-height:100vh; justify-content:center;">
    
    <div class="hero-pill">
      <div class="hero-pill-dot"></div>
      {!! __('messages.home.hero_pill') !!}
    </div>

    <h1 class="hero-h1">{!! __('messages.hero.title') !!}</h1>
    <p class="hero-sub">{{ __('messages.hero.subtitle') }}</p>
    
    <div class="hero-btns">
      <a class="btn btn-primary btn-lg" href="#program">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        {{ __('messages.nav.home') === 'Beranda' ? 'Lihat Program' : 'プログラムを見る' }}
      </a>
      <a class="btn btn-outline btn-lg" href="#kontak">
        {{ __('messages.nav.home') === 'Beranda' ? 'Konsultasi Gratis' : '無料相談' }}
      </a>
    </div>
    
    <div class="hero-trust">
      <div class="trust-avatars"><span>R</span><span>A</span><span>S</span></div>
      <div class="trust-text">
        <strong>{{ __('messages.home.trust_text') }}</strong>
        {{ __('messages.home.trust_sub') }}
      </div>
    </div>
  </div>

  <!-- Decorative shapes -->
  <div class="hero-bg" style="position:absolute; inset:0; pointer-events:none; overflow:hidden; z-index:0;">
    <div class="hero-shape s1"></div>
    <div class="hero-shape s2"></div>
    <div class="hero-shape s3"></div>
  </div>

</section>

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
