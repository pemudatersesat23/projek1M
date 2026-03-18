<!-- ═══ KONTAK ═══ -->
<section id="kontak" class="section-pad">
  <div class="container">
    <div class="kontak-grid">
      <div class="kontak-left reveal">
        <div class="sec-tag">{{ __('messages.home.kontak_tag') }}</div>
        <h2 class="sec-h2">{!! __('messages.home.kontak_h2') !!}</h2>
        <p>{{ __('messages.home.kontak_p') }}</p>
        <div class="kontak-item"><div class="k-icon">📍</div><div><div class="k-label">{{ __('messages.home.kontak_labels.address') }}</div><div class="k-val">Jl. Contoh No. 123, Kota Anda, Indonesia</div></div></div>
        <div class="kontak-item"><div class="k-icon">📱</div><div><div class="k-label">{{ __('messages.home.kontak_labels.phone') }}</div><div class="k-val">+62 812-XXXX-XXXX</div></div></div>
        <div class="kontak-item"><div class="k-icon">📧</div><div><div class="k-label">{{ __('messages.home.kontak_labels.email') }}</div><div class="k-val">info@kizuku-academy.com</div></div></div>
        <div class="kontak-item"><div class="k-icon">⏰</div><div><div class="k-label">{{ __('messages.home.kontak_labels.hours') }}</div><div class="k-val">{{ __('messages.nav.home') === 'Beranda' ? 'Senin – Sabtu, 08.00 – 17.00 WIB' : '月曜日〜土曜日、08:00〜17:00 WIB' }}</div></div></div>
        <div style="margin-top:24px;">
          <a class="btn btn-primary" href="https://wa.me/62812XXXXXXXX" target="_blank" style="margin-right:10px;">{{ __('messages.home.kontak_btn_wa') }}</a>
          <a class="btn btn-outline" href="mailto:info@kizuku-academy.com">{{ __('messages.home.kontak_btn_email') }}</a>
        </div>
      </div>

      <div class="form-card reveal reveal-d2" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
        <div style="padding: 24px 28px 16px; border-bottom: 1px solid rgba(0,0,0,0.06);">
          <h3 style="margin:0 0 4px;">{{ __('messages.nav.home') === 'Beranda' ? '📍 Lokasi Kami' : '📍 私たちの場所' }}</h3>
          <p class="fc-sub" style="margin:0;">{{ __('messages.nav.home') === 'Beranda' ? 'Kunjungi kantor LPK Kizuku International Academy langsung.' : 'LPK Kizuku International Academyのオフィスに直接お越しください。' }}</p>
        </div>
        <div style="flex:1; min-height: 360px; position: relative;">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zLTbCsDEyJzAwLjAiUyAxMDbCsDQ4JzAwLjAiRQ!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid&q=LPK+Kizuku+International+Academy"
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
          <a href="https://maps.app.goo.gl/kizuku" target="_blank" class="btn btn-primary" style="width:100%; text-align:center; display:block;">
            🗺️ {{ __('messages.nav.home') === 'Beranda' ? 'Buka di Google Maps' : 'Google Mapsで開く' }}
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
