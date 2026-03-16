<!-- ═══ KAMPUS PARTNER ═══ -->
<section id="kampus-partner" class="section-pad">
  <div class="container">
    <div class="sec-head reveal" style="text-align:center;max-width:560px;margin:0 auto 44px;">
      <div class="sec-tag" data-id="✦ Mitra Kami" data-jp="✦ パートナー">✦ Mitra Kami</div>
      <h2 class="sec-h2" data-id="Kampus Partner" data-jp="提携キャンパス">Kampus Partner</h2>
      <p class="sec-p" style="margin:0 auto;" data-id="Kami bekerja sama dengan berbagai insitusi pendidikan terkemuka untuk memberikan pendidikan terbaik." data-jp="私たちは最高の教育を提供するために有名な教育機関と提携しています。">Kami bekerja sama dengan berbagai insitusi pendidikan terkemuka untuk memberikan pendidikan terbaik.</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 px-4 md:px-0 max-w-5xl mx-auto">
      @forelse($campuses as $campus)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition-shadow flex flex-col items-center justify-center text-center reveal">
          <div class="w-full h-24 flex items-center justify-center p-2 mb-4">
            <img src="{{ asset($campus->logo) }}" alt="{{ $campus->name }}" class="max-w-full max-h-full object-contain">
          </div>
          <h4 class="font-semibold text-sm text-slate-800">{{ $campus->name }}</h4>
        </div>
      @empty
        <div class="col-span-full text-center text-slate-500 py-8" data-id="Belum ada kampus partner." data-jp="提携キャンパスはまだありません。">
          Belum ada kampus partner.
        </div>
      @endforelse
    </div>
    
    @if(\App\Models\PartnerCampus::count() > 4)
    <div class="text-center mt-10 reveal">
      <a href="{{ route('kampus-partner.all') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm transition-colors" data-id="Lihat Semua Kampus Partner" data-jp="すべての提携キャンパスを見る">
        Lihat Semua Kampus Partner
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
      <div class="sec-tag">✦ Testimoni</div>
      <h2 class="sec-h2">Cerita Sukses<br>Alumni Kizuku</h2>
      <p class="sec-p" style="margin:0 auto;">Bukan kami yang bilang — dengarlah langsung dari mereka yang sudah membuktikannya.</p>
    </div>
    <div class="testi-grid">
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
    </div>
  </div>
</section>
