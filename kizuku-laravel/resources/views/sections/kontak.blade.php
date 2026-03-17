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

      <div class="form-card reveal reveal-d2">
        <h3>{{ __('messages.home.kontak_form_title') }}</h3>
        <p class="fc-sub">{{ __('messages.home.kontak_form_sub') }}</p>

        @if(session('success'))
          <div style="padding:12px 16px;border-radius:12px;background:rgba(16,185,129,.12);color:#059669;font-weight:700;font-size:13.5px;margin-bottom:16px;">
            ✅ {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST">
          @csrf
          <div class="form-row">
            <div class="form-row two">
              <div class="field">
                <label>{{ __('messages.form.name') }}</label>
                <input type="text" name="nama" placeholder="{{ __('messages.nav.home') === 'Beranda' ? 'Nama kamu' : 'お名前' }}" required value="{{ old('nama') }}">
              </div>
              <div class="field">
                <label>{{ __('messages.form.phone') }}</label>
                <input type="text" name="wa" placeholder="08xx-xxxx-xxxx" required value="{{ old('wa') }}">
              </div>
            </div>
            <div class="field">
              <label>{{ __('messages.form.email') }}</label>
              <input type="email" name="email" placeholder="{{ __('messages.nav.home') === 'Beranda' ? 'nama@email.com' : 'name@email.com' }}" value="{{ old('email') }}">
            </div>
            <div class="field">
              <label>{{ __('messages.nav.program') }}</label>
              <select name="program">
                <option value="">{{ __('messages.home.kontak_form_program_ph') }}</option>
                <option {{ old('program')=='Tokutei Ginou (TG / SSW)' ? 'selected' : '' }}>Tokutei Ginou (TG / SSW)</option>
                <option {{ old('program')=='Engineering' ? 'selected' : '' }}>Engineering</option>
                <option {{ old('program')=='Kelas Bahasa Jepang' ? 'selected' : '' }}>Kelas Bahasa Jepang</option>
                <option {{ old('program')=='Returnee / Ex Jepang' ? 'selected' : '' }}>Returnee / Ex Jepang</option>
              </select>
            </div>
            <div class="field">
              <label>{{ __('messages.nav.home') === 'Beranda' ? 'Pesan / Pertanyaan' : 'メッセージ / ご質問' }}</label>
              <textarea name="catatan" placeholder="{{ __('messages.nav.home') === 'Beranda' ? 'Tuliskan pertanyaan atau informasi tambahan kamu di sini...' : 'ご質問や追加情報をこちらにお書きください...' }}">{{ old('catatan') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary form-submit">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              {{ __('messages.home.kontak_form_submit') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
