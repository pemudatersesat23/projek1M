{{--
  components/program/detail/content.blade.php
  Props: $program, $activeBatch, $nextBatch, $batchHistory
--}}
@php
  $tgFields = config('programs.tg_fields');
  $dynamicSections = $program->relationLoaded('activeSections')
    ? $program->activeSections
    : $program->activeSections()->get();
@endphp

<main class="pd-content-section">
  <div class="container">
    <div class="pd-main-grid">

      {{-- ── Kiri: Konten Detail ── --}}
      <div class="pd-left-content">

        @if($dynamicSections->isNotEmpty())
          @foreach($dynamicSections as $section)
            @include('components.program.detail.section', ['section' => $section])
          @endforeach
        @else
        {{-- Focus & Target --}}
        <div class="focus-grid reveal">
          <div class="focus-box">
            <span class="focus-label">{{ __('messages.program.labels.target') }}</span>
            <p class="focus-text">{{ $program->getTranslation('target_peserta', app()->getLocale()) }}</p>
          </div>
          <div class="focus-box">
            <span class="focus-label">{{ __('messages.program.labels.materi') }}</span>
            <p class="focus-text">{{ $program->getTranslation('materi', app()->getLocale()) }}</p>
          </div>
          @if($program->focus)
            <div class="focus-box">
              <span class="focus-label">{{ __('messages.program.labels.focus') }}</span>
              <p class="focus-text">{{ $program->getTranslation('focus', app()->getLocale()) }}</p>
            </div>
          @endif
          @if($program->output)
            <div class="focus-box">
              <span class="focus-label">{{ __('messages.program.labels.output') }}</span>
              <p class="focus-text">{{ $program->getTranslation('output', app()->getLocale()) }}</p>
            </div>
          @endif
        </div>

        {{-- Tokutei Ginou: 10 Bidang Pekerjaan (config-driven, no hardcoded slug) --}}
        @if(array_key_exists($program->slug, config('programs.tg_fields_by_slug', [])))
          <h3 class="pd-section-h3" style="margin-top: 40px;">{{ config('programs.tg_fields_by_slug.' . $program->slug . '.section_title', '10 Bidang Pekerjaan') }}</h3>
          <p style="color: var(--slate-500); margin-bottom: 24px; font-weight: 500; font-size: 16px; line-height: 1.6;">
            {{ config('programs.tg_fields_by_slug.' . $program->slug . '.section_desc', '') }}
          </p>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 48px;">
            @foreach($tgFields as $index => $field)
              <div class="tg-field-card" style="display: flex; align-items: center; gap: 16px; padding: 16px; border-radius: 16px; border: 1px solid #f1f5f9; background: white; transition: all 0.3s; cursor: default;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--primary, #1e40af), #3b82f6); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(59,130,246,0.25);">
                  <span style="font-size: 16px; font-weight: 900; color: white; line-height: 1;">{{ $loop->iteration }}</span>
                </div>
                <div>
                  <span style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px;">Bidang {{ $loop->iteration }}</span>
                  <span style="display: block; font-size: 14px; font-weight: 800; color: #0f1c23;">{{ $field['display'] }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        <h3 class="pd-section-h3">{{ __('messages.program.benefit') }}</h3>
        <div class="pd-benefit-list reveal">
          @foreach($program->benefitItems as $b)
            <div class="pd-benefit-item">
              <div class="pd-check">
                <span class="material-symbols-outlined" style="font-size: 16px; font-weight: 800;">check</span>
              </div>
              {{ $b }}
            </div>
          @endforeach
        </div>

        {{-- Alur Seleksi --}}
        <h3 class="pd-section-h3">{{ __('messages.program.selection') }}</h3>
        <div class="selection-timeline reveal">
          @foreach($program->alurSeleksiItems as $index => $step)
            <div class="timeline-item">
              <div class="timeline-num">{{ $index + 1 }}</div>
              <div class="timeline-content">
                <div class="timeline-title">{{ $step }}</div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- FAQ --}}
        @if($program->faq)
          <h3 class="pd-section-h3">{{ __('messages.program.faq') }}</h3>
          <div class="reveal">
            @php
              $faqs = $program->getTranslation('faq', app()->getLocale()) ?: $program->faq;
            @endphp
            @foreach($faqs as $faq)
              <div class="pd-faq-item">
                <h5 class="pd-faq-q">{{ $faq['q'] ?? '' }}</h5>
                <p class="pd-faq-a">{{ $faq['a'] ?? '' }}</p>
              </div>
            @endforeach
          </div>
        @endif
        @endif

        {{-- Batch History --}}
        <h3 class="pd-section-h3">{{ __('messages.program.batch_history') }}</h3>
        <div class="reveal" style="margin-bottom:40px;">
          <div class="space-y-4">
            @foreach($batchHistory as $history)
              @php
                $colors = config('programs.batch_status_colors');
                $sc = $colors[$history->status] ?? $colors['_default'];
                $label = __('messages.program.status_labels.' . $history->status) ?: ucfirst($history->status);
              @endphp
              <div style="padding:20px 28px; background:#f8fafc; border-radius:20px; display:flex; justify-content:space-between; align-items:center; border:1px solid #f1f5f9; transition: all 0.3s; cursor: default;"
                   onmouseover="this.style.borderColor='var(--detail-primary)'; this.style.background='white'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.02)'"
                   onmouseout="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                <div style="display:flex; align-items:center; gap:20px;">
                  <div style="width:10px; height:10px; border-radius:50%; background:{{ $history->status === 'dibuka' ? '#10b981' : '#cbd5e1' }}"></div>
                  <div>
                    <span style="font-weight:900; color:#0f1c23; font-size:16px;">{{ $history->nama_batch }}</span>
                    <div style="font-size:12px; color:#94a3b8; font-weight:700; margin-top:2px;">{{ $history->tanggal_mulai?->format('F Y') }}</div>
                  </div>
                </div>
                <span style="padding:6px 16px; border-radius:99px; background:{{ $sc['bg'] }}; color:{{ $sc['text'] }}; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:1px;">
                  {{ $label }}
                </span>
              </div>
            @endforeach
          </div>
        </div>

      </div>

      {{-- ── Kanan: Batch Card ── --}}
      <div>
        @include('components.program.detail.batch-section', [
          'activeBatch' => $activeBatch,
          'nextBatch'   => $nextBatch,
        ])
      </div>

    </div>
  </div>
</main>
