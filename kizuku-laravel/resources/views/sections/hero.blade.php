<!-- ═══ BERANDA / HERO ═══ -->
<section id="beranda" style="position: relative; padding-top: 68px; overflow: hidden; min-height: 100vh; display: flex; align-items: center;">
  
  <!-- Swiper Background -->
  <div class="swiper heroBgSwiper" style="position: absolute; inset: 0; width: 100%; height: 100%; z-index: 0;">
    <div class="swiper-wrapper">
      @forelse($heroSections->sortBy('sort_order') as $hero)
        <div class="swiper-slide" style="width: 100%; height: 100%;">
          <div class="slide-bg" style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(11, 18, 32, .75) 0%, rgba(15, 76, 129, .55) 50%, rgba(11, 18, 32, .7) 100%), url('{{ $hero->image_path ? asset('storage/'.$hero->image_path) : asset('image/backgorund hero section.jpeg') }}') center/cover no-repeat;"></div>
        </div>
      @empty
        <div class="swiper-slide" style="width: 100%; height: 100%;">
          <div class="slide-bg" style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(11, 18, 32, .75) 0%, rgba(15, 76, 129, .55) 50%, rgba(11, 18, 32, .7) 100%), url('{{ asset('image/backgorund hero section.jpeg') }}') center/cover no-repeat;"></div>
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
        {{ app()->getLocale() === 'jp' ? 'プログラムを見る' : 'Lihat Program' }}
      </a>
      <a class="btn btn-outline btn-lg" href="#kontak">
        {{ app()->getLocale() === 'jp' ? '無料相談' : 'Konsultasi Gratis' }}
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
{{-- $siteStats dipersiapkan oleh HomeController::index() — tidak ada query di sini --}}
@if($siteStats['active'])
<div class="stats-strip">
  <div class="stats-inner container">
    @foreach($siteStats['items'] as $stat)
      <div class="stat-item">
        {!! splitStat($stat['value']) !!}
        <div class="stat-lbl">{{ __($stat['label_key']) }}</div>
      </div>
    @endforeach
  </div>
</div>
@endif
