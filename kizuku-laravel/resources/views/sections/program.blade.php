<!-- ═══ PROGRAM ═══ -->
{{--
  $featuredPrograms dipersiapkan oleh HomeController::index().
  Sudah ada fallback di controller: jika featured kosong, ambil semua aktif.
  Blade ini hanya render — tidak ada query DB di sini.
--}}
<section id="program" class="section-pad">
  <div class="container" style="position: relative; z-index: 2;">
    <div class="sec-head reveal">
      <div class="sec-tag" style="background: rgba(225, 6, 0, 0.08); color: var(--red); border-color: rgba(225, 6, 0, 0.1);">{{ __('messages.home.program_tag') }}</div>
      <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--black), var(--red)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{!! __('messages.home.program_h2') !!}</h2>
      <p class="sec-p">{{ __('messages.home.program_p') }}</p>
    </div>

    <!-- Slider Wrapper -->
    <div class="program-wrapper reveal" style="position:relative;margin:0 auto;max-width: 1200px;">

      <!-- Nav Buttons -->
      <button id="program-prev" class="program-nav-btn" aria-label="Previous">
        <span class="material-symbols-outlined">chevron_left</span>
      </button>
      <button id="program-next" class="program-nav-btn" aria-label="Next">
        <span class="material-symbols-outlined">chevron_right</span>
      </button>

      <!-- Carousel -->
      <div class="program-carousel" id="program-carousel">
        @php $cardClasses = ['red', 'blue', 'dark', 'mix']; @endphp

        @foreach($featuredPrograms as $index => $p)
          <article class="prog-card {{ $cardClasses[$index % 4] }} reveal-d{{ ($index % 4) + 1 }}">
            <div class="prog-glow" aria-hidden="true"></div>

            <div class="prog-badge">
              <span class="bdot"></span>
              <span>
                {{ $p->registrationStatusLabel() }}
              </span>
            </div>
            
            @if($p->thumbnail_path)
              <img src="{{ asset('storage/' . $p->thumbnail_path) }}" alt="{{ $p->nama_program }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;" loading="lazy">
            @endif

            <h3 class="dynamic-lang"
                data-id="{{ $p->getTranslation('nama_program', 'id') }}"
                data-jp="{{ $p->getTranslation('nama_program', 'jp') }}">
              {{ $p->nama_program }}
            </h3>

            <p class="dynamic-lang"
               data-id="{{ Str::limit($p->getTranslation('deskripsi', 'id'), 120) }}"
               data-jp="{{ Str::limit($p->getTranslation('deskripsi', 'jp'), 120) }}">
              {{ Str::limit($p->deskripsi, 120) }}
            </p>

            {{-- $p->benefitItems diparsing oleh Program::getBenefitItemsAttribute() --}}
            <ul class="feat-list">
              @foreach(array_slice($p->benefitItems, 0, 4) as $b)
                <li>{{ $b }}</li>
              @endforeach
            </ul>

            <div class="prog-footer">
              <a class="btn btn-{{ $cardClasses[$index % 4] }}" href="{{ route('programs.show', $p->slug) }}">
                {{ __('messages.program.batch.details') }}
              </a>
              @if($p->isRegistrationOpen())
                <span class="prog-note" style="color: #10b981;">✅ Pendaftaran Dibuka</span>
              @else
                <span class="prog-note">✦ {{ __('messages.program.batch.enroll_info') }}</span>
              @endif
            </div>
          </article>
        @endforeach

        @if($featuredPrograms->isEmpty())
          <div style="flex: 0 0 100%; text-align: center; padding: 40px; background: white; border-radius: 24px; border: 1px dashed #cbd5e1;">
            <p class="text-slate-500">{{ __('messages.home.program_empty') }}</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <style>
  .program-wrapper {
    padding: 0 50px;
  }
  .program-carousel {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 30px;
    padding-bottom: 30px;
    padding-top: 15px;
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
  .program-carousel::-webkit-scrollbar {
    display: none;
  }
  .program-carousel .prog-card {
    flex: 0 0 calc(33.333% - 20px);
  }
  .program-nav-btn {
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
  .program-nav-btn:hover {
    background: var(--red, #f53003);
    color: #fff;
  }
  #program-prev { left: 0; }
  #program-next { right: 0; }

  @media (max-width: 1024px) {
    .program-carousel .prog-card { flex: 0 0 calc(50% - 15px); }
  }
  @media (max-width: 768px) {
    .program-carousel .prog-card { flex: 0 0 calc(100%); }
    .program-wrapper { padding: 0 40px; }
  }
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('program-carousel');
    const btnPrev  = document.getElementById('program-prev');
    const btnNext  = document.getElementById('program-next');

    if (!carousel || !btnPrev || !btnNext) return;

    let autoScrollInterval;
    const scrollPace = 4000;

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

    function startAutoScroll() { autoScrollInterval = setInterval(scrollNext, scrollPace); }
    function resetAutoScroll()  { clearInterval(autoScrollInterval); startAutoScroll(); }

    btnNext.addEventListener('click', () => { scrollNext(); resetAutoScroll(); });
    btnPrev.addEventListener('click', () => { scrollPrev(); resetAutoScroll(); });
    carousel.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
    carousel.addEventListener('mouseleave', startAutoScroll);

    startAutoScroll();
  });
  </script>

  <!-- Decorative Background Blobs -->
  <div style="position: absolute; top: 10%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(225, 6, 0, 0.03), transparent 70%); filter: blur(40px); pointer-events: none; z-index: 1; animation: float 12s infinite ease-in-out;"></div>
  <div style="position: absolute; bottom: 10%; right: -5%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(15, 76, 129, 0.04), transparent 70%); filter: blur(50px); pointer-events: none; z-index: 1; animation: float 15s infinite ease-in-out reverse;"></div>
</section>
