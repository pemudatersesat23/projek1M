<!-- ═══ FASILITAS KAMI ═══ -->
<section id="fasilitas" class="section-pad" style="background-color: #f8f9fa;">
  <div class="container relative">
    <div class="sec-head reveal" style="text-align:center;max-width:560px;margin:0 auto 44px;">
      <div class="sec-tag dynamic-lang" data-id="{{ __('messages.home.fasilitas_tag', [], 'id') }}" data-jp="{{ __('messages.home.fasilitas_tag', [], 'jp') }}">{{ __('messages.home.fasilitas_tag') }}</div>
      <h2 class="sec-h2 dynamic-lang" data-id="{{ __('messages.home.fasilitas_h2', [], 'id') }}" data-jp="{{ __('messages.home.fasilitas_h2', [], 'jp') }}" style="font-size:32px;font-weight:700;color:var(--dark, #1a1a2e);margin-bottom:16px;">{{ __('messages.home.fasilitas_h2') }}</h2>
      <p class="sec-p dynamic-lang" data-id="{{ __('messages.home.fasilitas_p', [], 'id') }}" data-jp="{{ __('messages.home.fasilitas_p', [], 'jp') }}" style="margin:0 auto;color:#64748b;">{{ __('messages.home.fasilitas_p') }}</p>
    </div>

    <!-- Slider Wrapper -->
    <div class="fasilitas-wrapper reveal" style="position:relative;margin:0 auto;max-width: 1200px;">
      
      <!-- Nav Buttons -->
      <button id="fasilitas-prev" class="fasilitas-nav-btn" aria-label="Previous">
        <span class="material-symbols-outlined">chevron_left</span>
      </button>
      <button id="fasilitas-next" class="fasilitas-nav-btn" aria-label="Next">
        <span class="material-symbols-outlined">chevron_right</span>
      </button>

      <!-- Carousel -->
      <div class="fasilitas-carousel" id="fasilitas-carousel">
        @if($fasilitas->count() > 0)
          @foreach($fasilitas as $item)
          <div class="fasilitas-item">
            <div class="fasilitas-img-wrap">
              @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama }}">
              @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f1f5f9;">
                  <span class="material-symbols-outlined" style="font-size:40px;color:#94a3b8;">image</span>
                </div>
              @endif
            </div>
            <div class="fasilitas-info">
              <h4 class="dynamic-lang" data-id="{{ $item->getTranslation('nama', 'id') }}" data-jp="{{ $item->getTranslation('nama', 'jp') }}" style="margin:0;font-size:16px;font-weight:600;color:#1e293b;">{{ $item->getTranslation('nama', app()->getLocale()) }}</h4>
            </div>
          </div>
          @endforeach
        @else
          <div class="dynamic-lang" data-id="{{ __('messages.home.fasilitas_empty', [], 'id') }}" data-jp="{{ __('messages.home.fasilitas_empty', [], 'jp') }}" style="width:100%;padding:60px 0;text-align:center;color:#94a3b8;font-size:15px;">
            <span class="material-symbols-outlined" style="font-size:40px;display:block;margin-bottom:10px;">apartment</span>
            {{ __('messages.home.fasilitas_empty') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

<style>
  .fasilitas-wrapper {
    padding: 0 50px; /* Space for nav buttons */
  }
  .fasilitas-carousel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 24px;
    padding-bottom: 20px;
    
    /* Hide scrollbar for Chrome, Safari and Opera */
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
  }
  .fasilitas-carousel::-webkit-scrollbar {
    display: none;
  }
  
  .fasilitas-item {
    /* 4 items visible: 100% / 4 minus the gap space distributed */
    flex: 0 0 calc(25% - 18px);
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .fasilitas-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -5px rgba(0,0,0,0.1);
  }
  .fasilitas-img-wrap {
    width: 100%;
    height: 200px;
    position: relative;
    overflow: hidden;
  }
  .fasilitas-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .fasilitas-item:hover .fasilitas-img-wrap img {
    transform: scale(1.05);
  }
  .fasilitas-info {
    padding: 16px 20px;
    background: #fff;
    border-top: 1px solid #f1f5f9;
  }
  
  .fasilitas-nav-btn {
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
  .fasilitas-nav-btn:hover {
    background: var(--red, #f53003);
    color: #fff;
  }
  #fasilitas-prev { left: 0; }
  #fasilitas-next { right: 0; }

  @media (max-width: 1024px) {
    .fasilitas-item { flex: 0 0 calc(33.333% - 16px); }
  }
  @media (max-width: 768px) {
    .fasilitas-item { flex: 0 0 calc(50% - 12px); }
  }
  @media (max-width: 480px) {
    .fasilitas-item { flex: 0 0 calc(100%); }
    .fasilitas-wrapper { padding: 0 40px; }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.getElementById('fasilitas-carousel');
  const btnPrev = document.getElementById('fasilitas-prev');
  const btnNext = document.getElementById('fasilitas-next');
  
  if (!carousel || !btnPrev || !btnNext) return;

  let autoScrollInterval;
  const scrollAmount = carousel.offsetWidth / 2; // scroll by half wrapper
  const scrollPace = 3000; // ms

  function scrollNext() {
    // If we've reached the end, loop back to the start smoothly or instantly
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

  // Start auto scroll
  function startAutoScroll() {
    autoScrollInterval = setInterval(scrollNext, scrollPace);
  }

  // Stop auto scroll when manual interaction occurs
  function resetAutoScroll() {
    clearInterval(autoScrollInterval);
    startAutoScroll();
  }

  // Button Listeners
  btnNext.addEventListener('click', () => {
    scrollNext();
    resetAutoScroll();
  });

  btnPrev.addEventListener('click', () => {
    scrollPrev();
    resetAutoScroll();
  });

  // Pause auto scroll on hover
  carousel.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
  carousel.addEventListener('mouseleave', startAutoScroll);

  // Initialize
  startAutoScroll();
});
</script>
