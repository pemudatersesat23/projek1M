<!-- ═══ BERITA TERKINI ═══ -->
<section id="berita" class="section-pad">
  <div class="container">
    <div class="sec-head reveal" style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:36px;">
      <div>
        <div class="sec-tag">{{ __('messages.home.berita_tag') }}</div>
        <h2 class="sec-h2" style="margin-bottom:6px;">{!! __('messages.home.berita_h2') !!}</h2>
        <p class="sec-p">{{ __('messages.home.berita_p') }}</p>
      </div>
      @auth
        <a class="btn btn-outline" href="{{ route('admin.berita.index') }}" style="flex-shrink:0;">{{ __('messages.nav.home') === 'Beranda' ? '+ Kelola Berita' : '+ ニュース管理' }}</a>
      @endauth
    </div>

    <div class="berita-slider-container">
      @if($beritas->count() > 0)
        <!-- Slider Wrapper -->
        <div class="berita-wrapper reveal" style="position:relative;margin:0 auto;max-width: 1200px;">
          
          <!-- Nav Buttons -->
          <button id="berita-prev" class="berita-nav-btn" aria-label="Previous">
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <button id="berita-next" class="berita-nav-btn" aria-label="Next">
            <span class="material-symbols-outlined">chevron_right</span>
          </button>

          <!-- Carousel -->
          <div class="berita-carousel" id="berita-carousel">
            @foreach($beritas as $berita)
              <div class="berita-item">
                <div class="berita-card" style="height: 100%; margin: 0; box-shadow: none; display: flex; flex-direction: column; cursor: pointer;" onclick="window.location='{{ route('berita.show', $berita->id) }}'">
                  <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-info'=>'','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}" style="height: 200px; width: 100%; position: relative; overflow: hidden; flex-shrink: 0;">
                    @if($berita->gambar)
                      <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->getTranslation('judul', app()->getLocale()) ?: $berita->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                      <span style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index:1; font-size:3rem;">{{ $berita->emoji }}</span>
                    @endif
                  </div>
                  <div class="berita-body" style="flex-grow: 1; display: flex; flex-direction: column;">
                    <div class="berita-meta" style="margin-bottom: 8px;">
                      <span class="b-kategori {{ $berita->kategori }}">{{ \App\Helpers\KategoriHelper::label($berita->kategori) }}</span>
                      <span class="b-date">{{ $berita->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #1e293b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $berita->getTranslation('judul', app()->getLocale()) ?: $berita->judul }}</h4>
                    <p style="margin: 0; font-size: 14px; color: #64748b; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $berita->getTranslation('isi', app()->getLocale()) ?: $berita->isi }}</p>
                    <a href="{{ route('berita.show', $berita->id) }}" style="display:inline-block; margin-top:auto; padding-top:10px; font-weight:bold; color:var(--primary); font-size:13px;">Baca Selengkapnya &rarr;</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @else
        <p style="color:var(--muted);text-align:center;padding:40px;">{{ __('messages.home.program_empty') }}</p>
      @endif
    </div>
  </div>
</section>

<style>
  .berita-wrapper {
    padding: 0 50px;
  }
  .berita-carousel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 24px;
    padding-bottom: 20px;
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
  }
  .berita-carousel::-webkit-scrollbar {
    display: none;
  }
  
  .berita-item {
    flex: 0 0 calc(25% - 18px);
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .berita-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -5px rgba(0,0,0,0.1);
  }
  
  .berita-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #fff;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    color: var(--dark, #1a1a2e);
    transition: background 0.2s, color 0.2s;
  }
  .berita-nav-btn:hover {
    background: var(--red, #f53003);
    color: #fff;
  }
  #berita-prev { left: 0; }
  #berita-next { right: 0; }

  @media (max-width: 1024px) {
    .berita-item { flex: 0 0 calc(33.333% - 16px); }
  }
  @media (max-width: 768px) {
    .berita-item { flex: 0 0 calc(50% - 12px); }
    .berita-wrapper { padding: 0 40px; }
  }
  @media (max-width: 480px) {
    .berita-item { flex: 0 0 calc(100%); }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('berita-carousel');
  const btnPrev = document.getElementById('berita-prev');
  const btnNext = document.getElementById('berita-next');
  
  if (!carousel || !btnPrev || !btnNext) return;

  let autoScrollInterval;
  const scrollPace = 3000; // ms

  function scrollNext() {
    if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 10) {
      carousel.scrollTo({ left: 0, behavior: 'smooth' });
    } else {
      carousel.scrollBy({ left: carousel.clientWidth / 2, behavior: 'smooth' });
    }
  }

  function scrollPrev() {
    if (carousel.scrollLeft <= 0) {
      carousel.scrollTo({ left: carousel.scrollWidth, behavior: 'smooth' });
    } else {
      carousel.scrollBy({ left: -(carousel.clientWidth / 2), behavior: 'smooth' });
    }
  }

  function startAutoScroll() {
    autoScrollInterval = setInterval(scrollNext, scrollPace);
  }

  function resetAutoScroll() {
    clearInterval(autoScrollInterval);
    startAutoScroll();
  }

  btnNext.addEventListener('click', () => {
    scrollNext();
    resetAutoScroll();
  });

  btnPrev.addEventListener('click', () => {
    scrollPrev();
    resetAutoScroll();
  });

  carousel.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
  carousel.addEventListener('mouseleave', startAutoScroll);

  startAutoScroll();
});
</script>
