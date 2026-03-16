<!-- ═══ PROGRAM ═══ -->
<section id="program" class="section-pad">
  <div class="container">
    <div class="sec-head reveal">
      <div class="sec-tag">✦ Program Kami</div>
      <h2 class="sec-h2">Pilih Jalur yang Tepat<br>Untuk Kariermu</h2>
      <p class="sec-p">Dari pemula hingga profesional, kami siapkan jalur pelatihan terstruktur yang mengantarkan kamu ke Jepang.</p>
    </div>
    <div class="prog-grid">
      @php
        $programs = \App\Models\Program::where('status', 'aktif')->with(['batches' => function($q) {
          $q->where('status', 'dibuka');
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
            $benefits = array_filter(explode("\n", str_replace('-', '', $p->benefit)));
          @endphp
          @foreach(array_slice($benefits, 0, 4) as $b)
            <li>{{ trim($b) }}</li>
          @endforeach
        </ul>
        <div class="prog-footer">
          @php $activeBatch = $p->batches->first(); @endphp
          @if($activeBatch)
            <a class="btn btn-{{ $cardClasses[$index % 4] }}" href="{{ route('programs.show', $p->slug) }}">Daftar {{ $activeBatch->nama_batch }}</a>
          @else
            <a class="btn btn-primary" href="{{ route('programs.show', $p->slug) }}">Lihat Detail</a>
          @endif
          <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}">Detail</a>
          @if($activeBatch)
            <span class="prog-note">⚡ Batch dibuka</span>
          @else
            <span class="prog-note">✦ Info pendaftaran</span>
          @endif
        </div>
      </article>
      @endforeach

      @if($programs->isEmpty())
        <div class="reveal" style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 24px; border: 1px dashed #cbd5e1;">
            <p class="text-slate-500">Belum ada program yang aktif saat ini.</p>
        </div>
      @endif
    </div>
  </div>
</section>
