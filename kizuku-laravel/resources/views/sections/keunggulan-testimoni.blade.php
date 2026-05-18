<!-- ═══ KAMPUS PARTNER ═══ -->
<section id="kampus-partner" class="section-pad">
  <!-- Decorative Background Blobs -->
  <div style="position: absolute; top: 5%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(15, 76, 129, 0.05), transparent 70%); filter: blur(60px); pointer-events: none; z-index: 1; animation: float 20s infinite ease-in-out;"></div>
  <div style="position: absolute; bottom: 5%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(225, 6, 0, 0.03), transparent 70%); filter: blur(50px); pointer-events: none; z-index: 1; animation: float 15s infinite ease-in-out reverse;"></div>

  <div class="container" style="position: relative; z-index: 2;">
    <div class="sec-head reveal" style="text-align:center;max-width:600px;margin:0 auto 50px;">
      <div class="sec-tag" style="background: rgba(225, 6, 0, 0.08); color: var(--red); border-color: rgba(225, 6, 0, 0.1);">{{ __('messages.home.partner_tag') }}</div>
      <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--black), var(--red)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ __('messages.home.partner_h2') }}</h2>
      <p class="sec-p" style="margin:0 auto;">{{ __('messages.home.partner_p') }}</p>
    </div>
    <div class="swiper kampusPartnerSwiper reveal">
      <div class="swiper-wrapper">
        @forelse($campuses as $campus)
          <div class="swiper-slide">
            <div class="kampus-card" style="height: 100%; margin-bottom: 0;">
              <!-- Banner -->
              <div class="kampus-banner">
                @if($campus->banner)
                  <img src="{{ Storage::url($campus->banner) }}" alt="Banner {{ $campus->name }}">
                @else
                  <div class="kampus-banner-empty">No Banner</div>
                @endif
              </div>
              
              <!-- Overlapping Logo -->
              <div class="kampus-logo-wrapper">
                <img src="{{ Storage::url($campus->logo) }}" alt="{{ $campus->name }}">
              </div>

              <!-- Content -->
              <div class="kampus-content">
                <h4 class="kampus-name">
                    @if(app()->getLocale() == 'jp' && $campus->getTranslation('name', 'jp', false))
                        {{ $campus->getTranslation('name', 'jp') }}
                    @else
                        {{ $campus->getTranslation('name', 'id') ?: $campus->name }}
                    @endif
                </h4>
                
                <div class="kampus-divider"></div>
                
                <p class="kampus-desc">
                    @if(app()->getLocale() == 'jp' && $campus->getTranslation('description', 'jp', false))
                        {{ $campus->getTranslation('description', 'jp') }}
                    @else
                        {{ $campus->getTranslation('description', 'id') ?: 'Belum ada deskripsi.' }}
                    @endif
                </p>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center text-slate-500 py-8 w-full">
            {{ __('messages.home.partner_empty') }}
          </div>
        @endforelse
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination !-bottom-2"></div>
      <!-- Add Navigation -->
      <div class="swiper-button-next !text-primary !-right-2 md:!right-4 after:!text-xl"></div>
      <div class="swiper-button-prev !text-primary !-left-2 md:!left-4 after:!text-xl"></div>
    </div>
  </div>
</section>

<style>
  .kampusPartnerSwiper {
    padding: 20px 10px 50px !important;
  }
  .kampusPartnerSwiper .swiper-slide {
    height: auto;
    display: flex;
  }
  .kampus-card {
    transition: transform 0.3s ease;
  }
  .kampus-card:hover {
    transform: translateY(-5px);
  }
</style>

<!-- ═══ TESTIMONI ═══ -->
<section id="testimoni" class="section-pad">
  <!-- Decorative Background Blobs -->
  <div style="position: absolute; top: -10%; left: -5%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(31, 162, 201, 0.05), transparent 70%); filter: blur(50px); pointer-events: none; z-index: 1; animation: float 18s infinite ease-in-out;"></div>
  <div style="position: absolute; bottom: -5%; right: -5%; width: 350px; height: 350px; background: radial-gradient(circle, rgba(15, 76, 129, 0.06), transparent 70%); filter: blur(60px); pointer-events: none; z-index: 1; animation: float 14s infinite ease-in-out reverse;"></div>

  <div class="container" style="position: relative; z-index: 2;">
    <div class="sec-head reveal" style="text-align:center;max-width:600px;margin:0 auto 50px;">
      <div class="sec-tag" style="background: rgba(31, 162, 201, 0.1); color: var(--cyan); border-color: rgba(31, 162, 201, 0.15);">{{ __('messages.home.testi_tag') }}</div>
      <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--blue), var(--cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{!! __('messages.home.testi_h2') !!}</h2>
      <p class="sec-p" style="margin:0 auto;">{{ __('messages.home.testi_p') }}</p>
    </div>
    <div class="swiper testimonialSwiper reveal">
      <div class="swiper-wrapper">
        @forelse($testimonials as $testi)
          <div class="swiper-slide">
            <div class="testi-card" style="height: 100%; margin-bottom: 0;">
              <div class="stars">
                @for($i=1; $i<=$testi->stars; $i++)
                  <span>★</span>
                @endfor
              </div>
              <p class="testi-text">"{{ $testi->getTranslation('content', app()->getLocale()) }}"</p>
              <div class="testi-person">
                <div class="testi-avatar" style="background:linear-gradient(135deg,var(--red),#ff5e58)">
                  @if($testi->avatar_path)
                    <img src="{{ asset('storage/' . $testi->avatar_path) }}" class="w-full h-full object-cover rounded-full">
                  @else
                    {{ substr($testi->name, 0, 1) }}
                  @endif
                </div>
                <div>
                  <div class="testi-name">{{ $testi->name }}</div>
                  <div class="testi-role">{{ $testi->getTranslation('role', app()->getLocale()) }}</div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center text-slate-500 py-12 w-full">
            <p>{{ __('messages.home.testi_empty') ?: 'Belum ada testimoni alumni.' }}</p>
          </div>
        @endforelse
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination !-bottom-2"></div>
      <!-- Add Navigation -->
      <div class="swiper-button-next !text-primary !-right-2 md:!right-4 after:!text-xl"></div>
      <div class="swiper-button-prev !text-primary !-left-2 md:!left-4 after:!text-xl"></div>
    </div>
  </div>
</section>

<style>
  .testimonialSwiper {
    padding: 20px 10px 50px !important;
  }
  .testimonialSwiper .swiper-slide {
    height: auto;
    display: flex;
  }
  .testi-card {
    width: 100%;
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.03);
    transition: all 0.3s;
  }
</style>
