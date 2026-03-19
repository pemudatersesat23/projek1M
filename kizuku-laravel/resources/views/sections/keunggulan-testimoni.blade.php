<!-- ═══ KAMPUS PARTNER ═══ -->
<section id="kampus-partner" class="section-pad">
  <!-- Decorative Background Blobs -->
  <div style="position: absolute; top: 5%; right: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(15, 76, 129, 0.05), transparent 70%); filter: blur(60px); pointer-events: none; z-index: 1; animation: float 20s infinite ease-in-out;"></div>
  <div style="position: absolute; bottom: 5%; left: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(225, 6, 0, 0.03), transparent 70%); filter: blur(50px); pointer-events: none; z-index: 1; animation: float 15s infinite ease-in-out reverse;"></div>

  <div class="container" style="position: relative; z-index: 2;">
    <div class="sec-head reveal" style="text-align:center;max-width:600px;margin:0 auto 50px;">
      <div class="sec-tag" style="background: rgba(225, 6, 0, 0.08); color: var(--red); border-color: rgba(225, 6, 0, 0.1);">{{ __('messages.home.partner_tag') }}</div>
      <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--black), var(--red)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ __('messages.home.partner_h2') }}</h2>
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
        {{ __('messages.nav.see_all_partners') }}
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
    @endif
  </div>
</section>

<!-- ═══ TESTIMONI ═══ -->
<section id="testimoni" class="section-pad">
  <!-- Decorative Background Blobs -->
  <div style="position: absolute; top: -10%; left: -5%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(31, 162, 201, 0.05), transparent 70%); filter: blur(50px); pointer-events: none; z-index: 1; animation: float 18s infinite ease-in-out;"></div>
  <div style="position: absolute; bottom: -5%; right: -5%; width: 350px; height: 350px; background: radial-gradient(circle, rgba(15, 76, 129, 0.06), transparent 70%); filter: blur(60px); pointer-events: none; z-index: 1; animation: float 14s infinite ease-in-out reverse;"></div>

  <div class="container" style="position: relative; z-index: 2;">
    <div class="sec-head reveal" style="text-align:center;max-width:600px;margin:0 auto 50px;">
      <div class="sec-tag" style="background: rgba(31, 162, 201, 0.1); color: var(--cyan); border-color: rgba(31, 162, 201, 0.15);">{{ __('messages.home.testi_tag') }}</div>
      <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--blue), var(--cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{!! __('messages.home.testi_h2') !!}</h2>
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
          <p class="testi-text">"{{ $testi->getTranslation('content', app()->getLocale()) }}"</p>
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
              <div class="testi-role">{{ $testi->getTranslation('role', app()->getLocale()) }}</div>
            </div>
          </div>
        </div>
      @empty
        <div class="testi-card reveal reveal-d1">
          <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
          <p class="testi-text">"{{ __('messages.home.testi_items.rizki_content') }}"</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,var(--red),#ff5e58)">R</div>
            <div>
                <div class="testi-name">Rizki Pratama</div>
                <div class="testi-role">{{ __('messages.home.testi_items.rizki_role') }}</div>
            </div>
          </div>
        </div>
        <div class="testi-card reveal reveal-d2">
          <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
          <p class="testi-text">"{{ __('messages.home.testi_items.andi_content') }}"</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,var(--blue),var(--cyan))">A</div>
            <div>
                <div class="testi-name">Andi Setiawan</div>
                <div class="testi-role">{{ __('messages.home.testi_items.andi_role') }}</div>
            </div>
          </div>
        </div>
        <div class="testi-card reveal reveal-d3">
          <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
          <p class="testi-text">"{{ __('messages.home.testi_items.siti_content') }}"</p>
          <div class="testi-person">
            <div class="testi-avatar" style="background:linear-gradient(135deg,#1a1a2e,#555)">S</div>
            <div>
                <div class="testi-name">Siti Nurhaliza</div>
                <div class="testi-role">{{ __('messages.home.testi_items.siti_role') }}</div>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>
