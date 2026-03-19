<!-- ═══ PROGRAM ═══ -->
<section id="program" class="section-pad">
  <div class="container">
    <div class="sec-head reveal">
      <div class="sec-tag">{{ __('messages.home.program_tag') }}</div>
      <h2 class="sec-h2">{!! __('messages.home.program_h2') !!}</h2>
      <p class="sec-p">{{ __('messages.home.program_p') }}</p>
    </div>
    <div class="prog-grid">
      @php
        $programs = (isset($featuredPrograms) && $featuredPrograms->isNotEmpty()) ? $featuredPrograms : \App\Models\Program::where('status', 'aktif')->with(['batches' => function($q) {
          $q->whereIn('status', ['dibuka', 'akan_dibuka']);
        }])->get();
        
        $cardClasses = ['red', 'blue', 'dark', 'mix'];
      @endphp

      @foreach($programs as $index => $p)
      <article class="prog-card {{ $cardClasses[$index % 4] }} reveal reveal-d{{ ($index % 4) + 1 }}">
        <div class="prog-glow" aria-hidden="true"></div>
        <div class="prog-badge"><span class="bdot"></span><span class="dynamic-lang" data-id="{{ $p->getTranslation('nama_program', 'id') }}" data-jp="{{ $p->getTranslation('nama_program', 'jp') }}">{{ $p->nama_program }}</span></div>
        <h3 class="dynamic-lang" data-id="{{ $p->getTranslation('nama_program', 'id') }}" data-jp="{{ $p->getTranslation('nama_program', 'jp') }}">{{ $p->nama_program }}</h3>
        <p class="dynamic-lang" data-id="{{ Str::limit($p->getTranslation('deskripsi', 'id'), 120) }}" data-jp="{{ Str::limit($p->getTranslation('deskripsi', 'jp'), 120) }}">{{ Str::limit($p->deskripsi, 120) }}</p>
        <ul class="feat-list">
          @php 
            $rawBenefit = $p->benefit; // Automatically translated by HasTranslations
            $benefits = array_filter(explode("\n", str_replace('-', '', $rawBenefit)));
          @endphp
          @foreach(array_slice($benefits, 0, 4) as $b)
            <li>{{ trim($b) }}</li>
          @endforeach
        </ul>
        <div class="prog-footer">
          @php 
            $activeBatch = $p->batches->where('status', 'dibuka')->first(); 
            $upcomingBatch = $p->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first();
          @endphp

          @if($activeBatch)
            <a class="btn btn-{{ $cardClasses[$index % 4] }}" href="{{ route('programs.show', $p->slug) }}">{{ __('messages.program.batch.enroll') }} {{ $activeBatch->nama_batch }}</a>
            <span class="prog-note">⚡ {{ __('messages.program.batch_open') }}</span>
          @elseif($upcomingBatch)
            <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}">{{ __('messages.program.batch.see_schedule') }}</a>
            <span class="prog-note">📅 {{ __('messages.program.batch.coming_soon') }}: {{ $upcomingBatch->tanggal_buka->format('d M') }}</span>
          @else
            <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}">{{ __('messages.program.batch.details') }}</a>
            <span class="prog-note">✦ {{ __('messages.program.batch.enroll_info') }}</span>
          @endif
        </div>
      </article>
      @endforeach

      @if($programs->isEmpty())
        <div class="reveal" style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 24px; border: 1px dashed #cbd5e1;">
            <p class="text-slate-500">{{ __('messages.home.program_empty') }}</p>
        </div>
      @endif
    </div>
  </div>
</section>
