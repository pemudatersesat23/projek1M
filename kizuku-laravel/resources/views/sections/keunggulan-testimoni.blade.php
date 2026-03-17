<!-- ═══ KAMPUS PARTNER ═══ -->
<section id="kampus-partner" class="section-pad">
  <div class="container">
    <div class="sec-head reveal" style="text-align:center;max-width:560px;margin:0 auto 44px;">
      <div class="sec-tag">{{ __('messages.home.partner_tag') }}</div>
      <h2 class="sec-h2">{{ __('messages.home.partner_h2') }}</h2>
      <p class="sec-p" style="margin:0 auto;">{{ __('messages.home.partner_p') }}</p>
    </div>
    <div class="kampus-grid reveal">
      @forelse($campuses as $campus)
        <div class="kampus-card">
          <!-- Banner -->
          <div class="kampus-banner">
            @if($campus->banner)
              <img src="{{ asset($campus->banner) }}" alt="Banner {{ $campus->name }}">
            @else
              <div class="kampus-banner-empty">No Banner</div>
            @endif
          </div>
          
          <!-- Overlapping Logo -->
          <div class="kampus-logo-wrapper">
            <img src="{{ asset($campus->logo) }}" alt="{{ $campus->name }}">
          </div>

          <!-- Content -->
          <div class="kampus-content">
            <h4 class="kampus-name">
                @if(app()->getLocale() == 'jp' && $campus->getTranslation('name', 'jp', false))
                    {{ $campus->getTranslation('name', 'jp') }}
                @else
                    {{ $campus->getTranslation('name', 'id') ?: $campus->name }}
                @endif
            </h4>
            
            <div class="kampus-divider"></div>
            
            <p class="kampus-desc">
                @if(app()->getLocale() == 'jp' && $campus->getTranslation('description', 'jp', false))
                    {{ $campus->getTranslation('description', 'jp') }}
                @else
                    {{ $campus->getTranslation('description', 'id') ?: 'Belum ada deskripsi.' }}
                @endif
            </p>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center text-slate-500 py-8" style="grid-column: 1 / -1;">
          {{ __('messages.home.partner_empty') }}
        </div>
      @endforelse
    </div>
    
    @if(\App\Models\PartnerCampus::count() > 4)
    <div class="text-center mt-10 reveal">
      <a href="{{ route('kampus-partner.all') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm transition-colors">
        {{ __('messages.nav.home') === 'Beranda' ? 'Lihat Semua Kampus Partner' : 'すべての提携キャンパスを見る' }}
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
    @endif
  </div>
</section>

<!-- ═══ TESTIMONI ═══ -->
<section id="testimoni" class="section-pad">
  <div class="container">
    <div class="sec-head reveal" style="text-align:center;max-width:560px;margin:0 auto 44px;">
      <div class="sec-tag">{{ __('messages.home.testi_tag') }}</div>
      <h2 class="sec-h2">{!! __('messages.home.testi_h2') !!}</h2>
      <p class="sec-p" style="margin:0 auto;">{{ __('messages.home.testi_p') }}</p>
    </div>
    <div class="testi-grid">
      @forelse($testimonials as $testi)
        <div class="testi-card reveal">
          <div class="stars">
            @for($i=1; $i<=$testi->stars; $i++)
              <span>★</span>
            @endfor
          </div>
          <p class="testi-text">"{{ $testi->content }}"</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,var(--red),#ff5e58)">
              @if($testi->avatar_path)
                <img src="{{ asset('storage/' . $testi->avatar_path) }}" class="w-full h-full object-cover rounded-full">
              @else
                {{ substr($testi->name, 0, 1) }}
              @endif
            </div>
            <div>
              <div class="testi-name">{{ $testi->name }}</div>
              <div class="testi-role">{{ $testi->role }}</div>
            </div>
          </div>
        </div>
      @empty
        <div class="testi-card reveal reveal-d1">
          <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
          <p class="testi-text">"Dulu saya nol bahasa Jepang. Setelah 6 bulan di Kizuku, saya lulus N4 dan langsung diterima di perusahaan manufaktur di Aichi."</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,var(--red),#ff5e58)">R</div>
            <div><div class="testi-name">Rizki Pratama</div><div class="testi-role">Alumni TG · Bekerja di Aichi, Jepang</div></div>
          </div>
        </div>
        <div class="testi-card reveal reveal-d2">
          <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
          <p class="testi-text">"Program Engineering Kizuku sangat fokus dan relevan. Saya dapat kerja di bidang konstruksi di Osaka hanya 3 bulan setelah pelatihan."</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,var(--blue),var(--cyan))">A</div>
            <div><div class="testi-name">Andi Setiawan</div><div class="testi-role">Alumni Engineering · Osaka, Jepang</div></div>
          </div>
        </div>
        <div class="testi-card reveal reveal-d3">
          <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
          <p class="testi-text">"Saya returnee yang sempat bingung mau lanjut kemana. Kizuku bantu saya upgrade bahasa dari N4 ke N3 dan matching dengan perusahaan baru yang lebih baik."</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,#1a1a2e,#555)">S</div>
            <div><div class="testi-name">Siti Nurhaliza</div><div class="testi-role">Alumni Returnee · Tokyo, Jepang</div></div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>
