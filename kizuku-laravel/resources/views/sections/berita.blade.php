<!-- ═══ BERITA TERKINI ═══ -->
<section id="berita" class="section-pad">
  <div class="container">
    <div class="sec-head reveal" style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:36px;">
      <div>
        <div class="sec-tag">📰 Berita Terkini</div>
        <h2 class="sec-h2" style="margin-bottom:6px;">Update &amp; Informasi<br>Terbaru Kizuku</h2>
        <p class="sec-p">Tetap update dengan berita, info program, dan kisah sukses alumni kami.</p>
      </div>
      @auth
        <a class="btn btn-outline" href="{{ route('admin.berita.index') }}" style="flex-shrink:0;">+ Kelola Berita</a>
      @endauth
    </div>

    <div class="berita-featured reveal">
      @if($beritas->count() > 0)
        {{-- Featured berita --}}
        @php $featured = $beritas->first(); @endphp
        <div class="berita-card b-featured">
          <div class="berita-img-placeholder mix-bg"><span style="position:relative;z-index:1;">{{ $featured->emoji }}</span></div>
          <div class="berita-body">
            <div class="berita-meta">
              <span class="b-kategori {{ $featured->kategori }}">{{ \App\Helpers\KategoriHelper::label($featured->kategori) }}</span>
              <span class="b-date">{{ $featured->created_at->format('d M Y') }}</span>
            </div>
            <h4>{{ $featured->judul }}</h4>
            <p>{{ $featured->isi }}</p>
          </div>
        </div>

        {{-- Side berita --}}
        <div class="berita-small-grid">
          @foreach($beritas->skip(1)->take(3) as $berita)
            <div class="berita-card b-small">
              <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}" style="aspect-ratio:16/5;font-size:24px;">
                <span style="position:relative;z-index:1;">{{ $berita->emoji }}</span>
              </div>
              <div class="berita-body">
                <div class="berita-meta">
                  <span class="b-kategori {{ $berita->kategori }}">{{ \App\Helpers\KategoriHelper::label($berita->kategori) }}</span>
                  <span class="b-date">{{ $berita->created_at->format('d M Y') }}</span>
                </div>
                <h4>{{ $berita->judul }}</h4>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p style="color:var(--muted);text-align:center;padding:40px;">Belum ada berita.</p>
      @endif
    </div>

    {{-- Grid row --}}
    @if($beritas->count() > 4)
      <div class="berita-grid-row reveal" style="margin-top:18px;">
        @foreach($beritas->skip(4)->take(3) as $berita)
          <div class="berita-card">
            <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-info'=>'','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}">
              <span style="position:relative;z-index:1;">{{ $berita->emoji }}</span>
            </div>
            <div class="berita-body">
              <div class="berita-meta">
                <span class="b-kategori {{ $berita->kategori }}">{{ \App\Helpers\KategoriHelper::label($berita->kategori) }}</span>
                <span class="b-date">{{ $berita->created_at->format('d M Y') }}</span>
              </div>
              <h4>{{ $berita->judul }}</h4>
              <p>{{ $berita->isi }}</p>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
