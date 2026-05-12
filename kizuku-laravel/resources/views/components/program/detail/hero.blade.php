{{--
  components/program/detail/hero.blade.php
  Props: $program, $activeBatch, $nextBatch
--}}
@php
  if ($activeBatch) {
    $hsColor = '#059669';
    $hsText  = __('messages.program.status_labels.dibuka');
  } elseif ($nextBatch) {
    $hsColor = '#d97706';
    $hsText  = __('messages.program.status_labels.akan_dibuka');
  } else {
    $hsColor = '#64748b';
    $hsText  = __('messages.program.status_labels.tutup');
  }
@endphp

<header class="program-detail-hero">
  <div class="container">
    <div class="pd-hero-grid">

      {{-- Kiri: Judul + Stats --}}
      <div class="reveal">
        <div class="pd-tag">{{ __('messages.hero.badge') }}</div>
        <h1 class="pd-h1">{{ $program->nama_program }}</h1>
        <p class="pd-p">{{ $program->deskripsi }}</p>

        <div class="pd-stats">
          <div class="pd-stat-card">
            <span class="pd-stat-label">{{ __('messages.program.duration') }}</span>
            <span class="pd-stat-val">{{ $program->durasi }}</span>
          </div>
          <div class="pd-stat-card">
            <span class="pd-stat-label">{{ __('messages.program.investment') }}</span>
            <span class="pd-stat-val">{{ $program->biaya }}</span>
          </div>
          <div class="pd-stat-card">
            <span class="pd-stat-label">{{ __('messages.program.status') }}</span>
            <span class="pd-stat-val" style="color: {{ $hsColor }};">{{ $hsText }}</span>
          </div>
        </div>
      </div>

      {{-- Kanan: Video / Thumbnail / Placeholder --}}
      <div class="reveal reveal-d2">
        <div style="position: relative;">
          <div style="position: absolute; top: -20px; left: -20px; right: 20px; bottom: 20px; background: var(--detail-primary); opacity: 0.1; border-radius: 40px; transform: rotate(-2deg); z-index: -1;"></div>

          @if($program->video_url)
            <div class="video-container" style="border-radius:32px; overflow:hidden; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
              <iframe width="100%" height="315" src="{{ $program->video_url }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            </div>
          @elseif($program->thumbnail_path)
            <img src="{{ asset('storage/' . $program->thumbnail_path) }}"
                 alt="{{ $program->nama_program }}"
                 style="width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:32px; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
          @else
            <div style="width:100%; aspect-ratio:4/3; background: linear-gradient(135deg, var(--detail-primary) 0%, #004d7a 100%); border-radius:32px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:white; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
              <div style="font-size: 64px; font-weight: 900; margin-bottom: 10px; opacity: 0.2;">KIZUKU</div>
              <div style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">International Academy</div>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</header>
