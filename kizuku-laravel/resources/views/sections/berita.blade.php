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

    <div class="berita-featured reveal">
      @if($beritas->count() > 0)
        {{-- Featured berita --}}
        @php $featured = $beritas->first(); @endphp
<<<<<<< HEAD
        <div class="berita-card b-featured" style="cursor:pointer;" onclick="window.location='{{ route('berita.show', $featured->id) }}'">
          @if($featured->image)
            <div class="berita-img-placeholder mix-bg" style="padding:0; overflow:hidden;">
              <img src="{{ asset('storage/' . $featured->image) }}" style="width:100%; height:100%; object-fit:cover;" alt="Foto">
            </div>
          @else
            <div class="berita-img-placeholder mix-bg"><span style="position:relative;z-index:1; font-size:32px;" class="material-symbols-outlined">image</span></div>
          @endif
=======
        <div class="berita-card b-featured">
          <div class="berita-img-placeholder mix-bg">
            @if($featured->gambar)
              <img src="{{ asset('storage/' . $featured->gambar) }}" alt="{{ $featured->judul }}">
            @else
              <span style="position:relative;z-index:1;">{{ $featured->emoji }}</span>
            @endif
          </div>
>>>>>>> c1ac75645fc240b17f13b9836c2fe8124120ef96
          <div class="berita-body">
            <div class="berita-meta">
              <span class="b-kategori {{ $featured->kategori }}">{{ \App\Helpers\KategoriHelper::label($featured->kategori) }}</span>
              <span class="b-date">{{ $featured->created_at->format('d M Y') }}</span>
            </div>
            <h4>{{ $featured->judul }}</h4>
            <p>{!! Str::limit(strip_tags($featured->isi), 120) !!}</p>
            @if($featured->lokasi)
              <p style="font-size:12px; margin-top:8px; color:var(--muted);"><span class="material-symbols-outlined" style="font-size:14px; vertical-align:middle;">location_on</span> {{ $featured->lokasi }}</p>
            @endif
            <a href="{{ route('berita.show', $featured->id) }}" style="display:inline-block; margin-top:12px; font-weight:bold; color:var(--primary); font-size:14px;">Baca Selengkapnya &rarr;</a>
          </div>
        </div>

        {{-- Side berita --}}
        <div class="berita-small-grid">
          @foreach($beritas->skip(1)->take(3) as $berita)
<<<<<<< HEAD
            <div class="berita-card b-small" style="cursor:pointer;" onclick="window.location='{{ route('berita.show', $berita->id) }}'">
              @if($berita->image)
                <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}" style="aspect-ratio:16/5; padding:0; overflow:hidden;">
                  <img src="{{ asset('storage/' . $berita->image) }}" style="width:100%; height:100%; object-fit:cover;" alt="Foto">
                </div>
              @else
                <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}" style="aspect-ratio:16/5;font-size:24px;">
                  <span style="position:relative;z-index:1;" class="material-symbols-outlined">image</span>
                </div>
              @endif
=======
            <div class="berita-card b-small">
              <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}" style="aspect-ratio:16/5;font-size:24px;">
                @if($berita->gambar)
                  <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                @else
                  <span style="position:relative;z-index:1;">{{ $berita->emoji }}</span>
                @endif
              </div>
>>>>>>> c1ac75645fc240b17f13b9836c2fe8124120ef96
              <div class="berita-body">
                <div class="berita-meta">
                  <span class="b-kategori {{ $berita->kategori }}">{{ \App\Helpers\KategoriHelper::label($berita->kategori) }}</span>
                  <span class="b-date">{{ $berita->created_at->format('d M Y') }}</span>
                </div>
                <h4 style="margin-bottom:6px;">{{ $berita->judul }}</h4>
                <p style="font-size:13px; line-height:1.4; color:var(--muted);">{!! Str::limit(strip_tags($berita->isi), 80) !!}</p>
                <a href="{{ route('berita.show', $berita->id) }}" style="display:inline-block; margin-top:8px; font-weight:bold; color:var(--primary); font-size:12px;">Baca Selengkapnya</a>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p style="color:var(--muted);text-align:center;padding:40px;">{{ __('messages.home.program_empty') }}</p>
      @endif
    </div>

    {{-- Grid row --}}
    @if($beritas->count() > 4)
      <div class="berita-grid-row reveal" style="margin-top:18px;">
        @foreach($beritas->skip(4)->take(3) as $berita)
<<<<<<< HEAD
          <div class="berita-card" style="cursor:pointer;" onclick="window.location='{{ route('berita.show', $berita->id) }}'">
            @if($berita->image)
              <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-info'=>'','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}" style="padding:0; overflow:hidden;">
                <img src="{{ asset('storage/' . $berita->image) }}" style="width:100%; height:100%; object-fit:cover;" alt="Foto">
              </div>
            @else
              <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-info'=>'','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}">
                <span style="position:relative;z-index:1; font-size:32px;" class="material-symbols-outlined">image</span>
              </div>
            @endif
=======
          <div class="berita-card">
            <div class="berita-img-placeholder {{ ['kat-alumni'=>'red-bg','kat-info'=>'','kat-promo'=>'dark-bg'][$berita->kategori] ?? '' }}">
              @if($berita->gambar)
                <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
              @else
                <span style="position:relative;z-index:1;">{{ $berita->emoji }}</span>
              @endif
            </div>
>>>>>>> c1ac75645fc240b17f13b9836c2fe8124120ef96
            <div class="berita-body">
              <div class="berita-meta">
                <span class="b-kategori {{ $berita->kategori }}">{{ \App\Helpers\KategoriHelper::label($berita->kategori) }}</span>
                <span class="b-date">{{ $berita->created_at->format('d M Y') }}</span>
              </div>
              <h4>{{ $berita->judul }}</h4>
              <p>{!! Str::limit(strip_tags($berita->isi), 100) !!}</p>
              @if($berita->lokasi)
                <p style="font-size:12px; margin-top:8px; color:var(--muted);"><span class="material-symbols-outlined" style="font-size:14px; vertical-align:middle;">location_on</span> {{ $berita->lokasi }}</p>
              @endif
              <a href="{{ route('berita.show', $berita->id) }}" style="display:inline-block; margin-top:10px; font-weight:bold; color:var(--primary); font-size:13px;">Baca Selengkapnya</a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
