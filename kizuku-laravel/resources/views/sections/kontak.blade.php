<!-- ═══ KONTAK ═══ -->
<section id="kontak" class="section-pad">
  <div class="container">
    <div class="kontak-grid">
      <div class="kontak-left reveal">
        <div class="sec-tag">✦ Hubungi Kami</div>
        <h2 class="sec-h2">Siap Mulai<br>Perjalananmu?</h2>
        <p>Tim kami siap membantu kamu memilih program terbaik, menjawab pertanyaan, dan memandu proses pendaftaran dari awal hingga akhir.</p>
        <div class="kontak-item"><div class="k-icon">📍</div><div><div class="k-label">Alamat</div><div class="k-val">Jl. Contoh No. 123, Kota Anda, Indonesia</div></div></div>
        <div class="kontak-item"><div class="k-icon">📱</div><div><div class="k-label">WhatsApp</div><div class="k-val">+62 812-XXXX-XXXX</div></div></div>
        <div class="kontak-item"><div class="k-icon">📧</div><div><div class="k-label">Email</div><div class="k-val">info@kizuku-academy.com</div></div></div>
        <div class="kontak-item"><div class="k-icon">⏰</div><div><div class="k-label">Jam Operasional</div><div class="k-val">Senin – Sabtu, 08.00 – 17.00 WIB</div></div></div>
        <div style="margin-top:24px;">
          <a class="btn btn-primary" href="https://wa.me/62812XXXXXXXX" target="_blank" style="margin-right:10px;">💬 Chat WhatsApp</a>
          <a class="btn btn-outline" href="mailto:info@kizuku-academy.com">✉️ Kirim Email</a>
        </div>
      </div>

      <div class="form-card reveal reveal-d2">
        <h3>Form Pendaftaran</h3>
        <p class="fc-sub">Isi form di bawah, kami akan menghubungi kamu dalam 1×24 jam.</p>

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
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Nama kamu" required value="{{ old('nama') }}">
              </div>
              <div class="field">
                <label>No. WhatsApp</label>
                <input type="text" name="wa" placeholder="08xx-xxxx-xxxx" required value="{{ old('wa') }}">
              </div>
            </div>
            <div class="field">
              <label>Email</label>
              <input type="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}">
            </div>
            <div class="field">
              <label>Program yang Diminati</label>
              <select name="program">
                <option value="">-- Pilih Program --</option>
                <option {{ old('program')=='Tokutei Ginou (TG / SSW)' ? 'selected' : '' }}>Tokutei Ginou (TG / SSW)</option>
                <option {{ old('program')=='Engineering' ? 'selected' : '' }}>Engineering</option>
                <option {{ old('program')=='Kelas Bahasa Jepang' ? 'selected' : '' }}>Kelas Bahasa Jepang</option>
                <option {{ old('program')=='Returnee / Ex Jepang' ? 'selected' : '' }}>Returnee / Ex Jepang</option>
              </select>
            </div>
            <div class="field">
              <label>Pesan / Pertanyaan</label>
              <textarea name="catatan" placeholder="Tuliskan pertanyaan atau informasi tambahan kamu di sini...">{{ old('catatan') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary form-submit">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              Kirim Pendaftaran
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
