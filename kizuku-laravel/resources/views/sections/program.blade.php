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
        <div class="prog-badge"><span class="bdot"></span>{{ $p->nama_program }}</div>
        <h3>{{ $p->nama_program }}</h3>
        <p>{{ Str::limit($p->deskripsi, 120) }}</p>
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
            <a class="btn btn-{{ $cardClasses[$index % 4] }}" href="{{ route('programs.show', $p->slug) }}">{{ __('messages.nav.home') === 'Beranda' ? 'Daftar' : '登録' }} {{ $activeBatch->nama_batch }}</a>
            <span class="prog-note">⚡ {{ __('messages.nav.home') === 'Beranda' ? 'Batch dibuka' : 'バッチ募集中' }}</span>
          @elseif($upcomingBatch)
            <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}">{{ __('messages.nav.home') === 'Beranda' ? 'Lihat Jadwal' : 'スケジュールを見る' }}</a>
            <span class="prog-note">📅 {{ __('messages.nav.home') === 'Beranda' ? 'Segera' : 'まもなく' }}: {{ $upcomingBatch->tanggal_buka->format('d M') }}</span>
          @else
            <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}">{{ __('messages.nav.home') === 'Beranda' ? 'Detail' : '詳細' }}</a>
            <span class="prog-note">✦ {{ __('messages.nav.home') === 'Beranda' ? 'Info pendaftaran' : '登録情報' }}</span>
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
