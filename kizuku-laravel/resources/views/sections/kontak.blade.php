<!-- ═══ KONTAK ═══ -->
<section id="kontak" class="section-pad">
  <div class="container">
    <div class="kontak-grid">
      <div class="kontak-left reveal">
        <div class="sec-tag" style="background: rgba(15, 76, 129, 0.08); color: var(--blue); border-color: rgba(15, 76, 129, 0.1);">{{ __('messages.home.kontak_tag') }}</div>
        <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--black), var(--blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{!! __('messages.home.kontak_h2') !!}</h2>
        <p>{{ __('messages.home.kontak_p') }}</p>
        <div class="kontak-item"><div class="k-icon">📍</div><div><div class="k-label">{{ __('messages.home.kontak_labels.address') }}</div><div class="k-val">{{ $appSettings['office_address'] }}</div></div></div>
        <div class="kontak-item"><div class="k-icon">📱</div><div><div class="k-label">{{ __('messages.home.kontak_labels.phone') }}</div><div class="k-val">+{{ $appSettings['whatsapp_number'] }}</div></div></div>
        <div class="kontak-item"><div class="k-icon">📧</div><div><div class="k-label">{{ __('messages.home.kontak_labels.email') }}</div><div class="k-val">{{ $appSettings['admin_email'] }}</div></div></div>
        <div class="kontak-item">
          <div class="k-icon" style="display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #E1306C;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
          </div>
          <div>
            <div class="k-label">Instagram</div>
            <div class="k-val"><a href="https://instagram.com/kizuku_academy" target="_blank" style="color:inherit; text-decoration:none;">@kizuku_academy</a></div>
          </div>
        </div>
        <div class="kontak-item">
          <div class="k-icon" style="display:flex; align-items:center; justify-content:center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #000000;"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
          </div>
          <div>
            <div class="k-label">TikTok</div>
            <div class="k-val"><a href="https://tiktok.com/@kizuku_academy" target="_blank" style="color:inherit; text-decoration:none;">@kizuku_academy</a></div>
          </div>
        </div>
        <div class="kontak-item"><div class="k-icon">⏰</div><div><div class="k-label">{{ __('messages.home.kontak_labels.hours') }}</div><div class="k-val">{{ $appSettings['office_hours'] }}</div></div></div>
        <div style="margin-top:24px;">
          <a class="btn btn-primary" href="https://wa.me/{{ $appSettings['whatsapp_number'] }}" target="_blank" style="margin-right:10px;">{{ __('messages.home.kontak_btn_wa') }}</a>
          <a class="btn btn-outline" href="mailto:{{ $appSettings['admin_email'] }}">{{ __('messages.home.kontak_btn_email') }}</a>
        </div>
      </div>

      <div class="form-card reveal reveal-d2" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
        <div style="padding: 24px 28px 16px; border-bottom: 1px solid rgba(0,0,0,0.06);">
          <h3 style="margin:0 0 4px;">{{ __('messages.home.kontak_loc_h3') }}</h3>
          <p class="fc-sub" style="margin:0;">{{ __('messages.nav.home') === 'Beranda' ? 'Kunjungi kantor LPK Kizuku International Academy langsung.' : 'LPK Kizuku International Academyのオフィスに直接お越しください。' }}</p>
        </div>
        <div style="flex:1; min-height: 360px; position: relative;">
          <iframe
            src="https://maps.google.com/maps?q=-5.190963382983311,119.47090066650802&t=&z=16&ie=UTF8&iwloc=&output=embed"
            width="100%"
            height="100%"
            style="border:0; display:block; min-height:360px;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi LPK Kizuku International Academy"
          ></iframe>
        </div>
        <div style="padding: 16px 28px; border-top: 1px solid rgba(0,0,0,0.06);">
          <a href="https://maps.google.com/?q=-5.190963382983311,119.47090066650802" target="_blank" class="btn btn-primary" style="width:100%; text-align:center; display:block;">
            🗺️ {{ __('messages.nav.home') === 'Beranda' ? 'Buka di Google Maps' : 'Google Mapsで開く' }}
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
