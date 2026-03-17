@extends('layouts.app')

@section('title', $program->nama_program . ' — LPK Kizuku International Academy')

@push('styles')
<style>
  :root {
    --detail-primary: #0067a3;
    --detail-accent: #E31E24;
    --glass-white: rgba(255, 255, 255, 0.82);
  }
  .program-detail-hero {
    padding: 160px 0 100px;
    background: radial-gradient(circle at 10% 20%, rgba(216, 241, 230, 0.46) 0.1%, rgba(233, 226, 226, 0.28) 90.1%);
    position: relative;
    overflow: hidden;
  }
  .program-detail-hero::before {
    content: '';
    position: absolute;
    top: -10%; right: -10%;
    width: 40%; height: 40%;
    background: radial-gradient(circle, rgba(0,103,163,0.05) 0%, transparent 70%);
    z-index: 0;
  }
  .pd-hero-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 80px;
    align-items: center;
    position: relative;
    z-index: 1;
  }
  .pd-tag {
    display: inline-flex;
    padding: 8px 18px;
    background: var(--glass-white);
    backdrop-filter: blur(10px);
    border-radius: 99px;
    font-size: 13px;
    font-weight: 800;
    color: var(--detail-primary);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 32px;
    border: 1px solid rgba(255,255,255,0.4);
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .pd-h1 {
    font-size: 56px;
    font-weight: 900;
    line-height: 1;
    color: #0f1c23;
    margin-bottom: 28px;
    letter-spacing: -1px;
  }
  .pd-p {
    font-size: 20px;
    color: #576871;
    line-height: 1.6;
    margin-bottom: 40px;
    max-width: 90%;
  }
  .pd-stats {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
  }
  .pd-stat-card {
    background: var(--glass-white);
    backdrop-filter: blur(12px);
    padding: 24px;
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,0.6);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    min-width: 160px;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }
  .pd-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    border-color: var(--detail-primary);
  }
  .pd-stat-val {
    display: block;
    font-size: 20px;
    font-weight: 900;
    color: var(--detail-primary);
    margin-bottom: 4px;
  }
  .pd-stat-label {
    font-size: 12px;
    font-weight: 700;
    color: #8da0a9;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }

  .pd-content-section {
    padding: 100px 0;
    background: white;
  }
  .pd-main-grid {
    display: grid;
    grid-template-columns: 1.8fr 1.2fr;
    gap: 80px;
  }
  .pd-section-h3 {
    font-size: 36px;
    font-weight: 900;
    margin-bottom: 40px;
    color: #0f1c23;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .pd-section-h3::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f1f5f9;
  }
  
  .focus-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 80px;
  }
  .focus-box {
    padding: 32px;
    border-radius: 28px;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    transition: all 0.3s;
  }
  .focus-box:hover {
    background: white;
    box-shadow: 0 20px 50px rgba(0,0,0,0.04);
    border-color: var(--detail-primary);
  }
  .focus-label {
    font-size: 13px;
    font-weight: 800;
    color: var(--detail-primary);
    text-transform: uppercase;
    margin-bottom: 16px;
    display: block;
  }
  .focus-text {
    font-size: 17px;
    font-weight: 600;
    color: #334155;
    line-height: 1.5;
  }

  .pd-benefit-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 80px;
  }
  .pd-benefit-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 24px;
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    font-weight: 700;
    color: #1e293b;
    transition: all 0.2s;
  }
  .pd-benefit-item:hover {
    border-color: #10b981;
    background: #f0fdf4;
  }
  .pd-check {
    width: 24px; height: 24px;
    background: #10b981;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center; justify-content: center;
    font-size: 12px;
  }
  
  /* Timeline Style */
  .selection-timeline {
    position: relative;
    padding-left: 20px;
    margin-bottom: 80px;
  }
  .selection-timeline::before {
    content: '';
    position: absolute;
    left: 48px; top: 0; bottom: 0;
    width: 2px;
    background: #f1f5f9;
  }
  .timeline-item {
    display: flex;
    gap: 24px;
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
  }
  .timeline-num {
    width: 56px; height: 56px;
    background: white;
    border: 2px solid #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center; justify-content: center;
    font-size: 20px;
    font-weight: 900;
    color: var(--detail-primary);
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    transition: all 0.3s;
  }
  .timeline-item:hover .timeline-num {
    background: var(--detail-primary);
    color: white;
    border-color: var(--detail-primary);
    transform: scale(1.1);
  }
  .timeline-content {
    padding-top: 14px;
    background: white;
    border-radius: 20px;
    flex: 1;
  }
  .timeline-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f1c23;
  }

  .pd-faq-item {
    background: #f8fafc;
    border: none;
    border-radius: 24px;
    padding: 32px;
    margin-bottom: 20px;
    transition: all 0.3s;
  }
  .pd-faq-item:hover {
    background: white;
    box-shadow: 0 20px 50px rgba(0,0,0,0.05);
    transform: translateY(-4px);
  }
  .pd-faq-q {
    font-size: 19px;
    font-weight: 800;
    color: #0f1c23;
    margin-bottom: 12px;
  }

  .batch-card {
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: 32px;
    padding: 40px;
    position: sticky;
    top: 100px;
    box-shadow: 0 30px 60px rgba(0,103,163,0.08);
  }
  .batch-status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1px;
    margin-bottom: 24px;
  }
  .status-active-badge {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
  }
  .batch-name {
    font-size: 28px;
    font-weight: 950;
    color: #0f1c23;
    letter-spacing: -0.5px;
  }
  
  .enroll-form {
    margin-top: 40px;
  }
  .form-group-custom label {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 10px;
  }
  .form-group-custom input, .form-group-custom select, .form-group-custom textarea {
    background: #f8fafc;
    border: 2px solid #f1f5f9;
    padding: 14px 18px;
    border-radius: 16px;
    font-weight: 600;
    color: #0f1c23;
  }
  .form-group-custom input:focus {
    background: white;
    border-color: var(--detail-primary);
  }

  .reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);
  }
  .reveal.active {
    opacity: 1;
    transform: translateY(0);
  }
  .reveal-d2 { transition-delay: 0.2s; }
  .reveal-d3 { transition-delay: 0.4s; }
}
</style>
@endpush

@section('content')
  {{-- Hero Section --}}
  <header class="program-detail-hero">
    <div class="container">
      <div class="pd-hero-grid">
        <div class="reveal">
          <div class="pd-tag">✦ Program Pelatihan Eksklusif</div>
          <h1 class="pd-h1">{{ $program->nama_program }}</h1>
          <p class="pd-p">{{ $program->deskripsi }}</p>
          <div class="pd-stats">
            <div class="pd-stat-card">
              <span class="pd-stat-label">Durasi Pelatihan</span>
              <span class="pd-stat-val">{{ $program->durasi }}</span>
            </div>
            <div class="pd-stat-card">
              <span class="pd-stat-label">Investasi Program</span>
              <span class="pd-stat-val">{{ $program->biaya }}</span>
            </div>
            <div class="pd-stat-card">
              <span class="pd-stat-label">Status Batch</span>
              <span class="pd-stat-val" style="color: #059669;">Tersedia</span>
            </div>
          </div>
        </div>
        <div class="reveal reveal-d2">
          <div style="position: relative;">
            <div style="position: absolute; top: -20px; left: -20px; right: 20px; bottom: 20px; background: var(--detail-primary); opacity: 0.1; border-radius: 40px; transform: rotate(-2deg); z-index: -1;"></div>
            @if($program->thumbnail_path)
              <img src="{{ asset($program->thumbnail_path) }}" alt="{{ $program->nama_program }}" style="width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:32px; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
            @else
              <div style="width:100%; aspect-ratio:4/3; background: linear-gradient(135deg, var(--detail-primary) 0%, #004d7a 100%); border-radius:32px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:white; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
                <div style="font-size: 64px; font-weight: 900; margin-bottom: 10px; opacity: 0.2;">KIZUKU</div>
                <div style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">International Academy</div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="pd-content-section">
    <div class="container">
      <div class="pd-main-grid">
        {{-- Left Content: Detailed Information --}}
        <div class="pd-left-content">
          {{-- Focus & Target Section --}}
          <div class="focus-grid reveal">
            <div class="focus-box">
              <span class="focus-label">Target Peserta</span>
              <p class="focus-text">{{ $program->target_peserta }}</p>
            </div>
            <div class="focus-box">
              <span class="focus-label">Materi Pelatihan</span>
              <p class="focus-text">{{ $program->materi }}</p>
            </div>
          </div>

          <h3 class="pd-section-h3">Apa yang Kamu Dapatkan?</h3>
          <div class="pd-benefit-list reveal">
            @php 
              $benefits = array_filter(explode("\n", str_replace(['-', '✓'], '', $program->benefit)));
            @endphp
            @foreach($benefits as $b)
              <div class="pd-benefit-item">
                <span class="pd-check">✓</span>
                {{ trim($b) }}
              </div>
            @endforeach
          </div>

          <h3 class="pd-section-h3">Alur Seleksi</h3>
          <div class="selection-timeline reveal">
            @php 
              $steps = array_filter(explode("\n", str_replace(['-', '>'], '', $program->alur_seleksi)));
            @endphp
            @foreach($steps as $index => $step)
              <div class="timeline-item">
                <div class="timeline-num">{{ $index + 1 }}</div>
                <div class="timeline-content">
                  <div class="timeline-title">{{ trim($step) }}</div>
                </div>
              </div>
            @endforeach
          </div>

          @if($program->faq)
          <h3 class="pd-section-h3">FAQ (Sering Ditanyakan)</h3>
          <div class="reveal">
            @foreach($program->faq as $faq)
              <div class="pd-faq-item">
                <h5 class="pd-faq-q">{{ $faq['q'] }}</h5>
                <p class="pd-faq-a">{{ $faq['a'] }}</p>
              </div>
            @endforeach
          </div>
          @endif

          {{-- Batch History --}}
          <h3 class="pd-section-h3">Riwayat Gelombang</h3>
          <div class="reveal" style="margin-bottom:40px;">
            <div class="space-y-4">
              @foreach($batchHistory as $history)
                <div style="padding:20px 28px; background:#f8fafc; border-radius:20px; display:flex; justify-content:space-between; align-items:center; border:1px solid #f1f5f9; transition: all 0.3s; cursor: default;" onmouseover="this.style.borderColor='var(--detail-primary)'; this.style.background='white'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.02)'" onmouseout="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                  <div style="display:flex; align-items:center; gap:20px;">
                    <div style="width:10px; height:10px; border-radius:50%; background:{{ $history->status === 'dibuka' ? '#10b981' : '#cbd5e1' }}"></div>
                    <div>
                      <span style="font-weight:900; color:#0f1c23; font-size:16px;">{{ $history->nama_batch }}</span>
                      <div style="font-size:12px; color:#94a3b8; font-weight:700; margin-top:2px;">{{ $history->tanggal_mulai?->format('F Y') }}</div>
                    </div>
                  </div>
                  @php
                    $statusColor = match($history->status) {
                        'dibuka' => ['bg' => '#ecfdf5', 'text' => '#059669', 'label' => 'Dibuka'],
                        'akan_dibuka' => ['bg' => '#fef3c7', 'text' => '#d97706', 'label' => 'Akan Datang'],
                        'selesai' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => 'Selesai'],
                        default => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => $history->status]
                    };
                  @endphp
                  <span style="padding:6px 16px; border-radius:99px; background:{{ $statusColor['bg'] }}; color:{{ $statusColor['text'] }}; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:1px;">
                    {{ $statusColor['label'] }}
                  </span>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Right Content: Batch Card & Form --}}
        <div>
          <div class="batch-card reveal reveal-d2">
            @if($activeBatch)
              <div class="batch-status-badge status-active-badge">PENDAFTARAN DIBUKA</div>
              <span class="batch-name">{{ $activeBatch->nama_batch }}</span>
              
              <div class="batch-dates" style="margin: 32px 0; display: flex; flex-direction: column; gap: 16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
                  <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Pendaftaran</span>
                  <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_buka?->format('d M') }} – {{ $activeBatch->tanggal_tutup?->format('d M Y') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
                  <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Mulai Kelas</span>
                  <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_mulai?->format('d M Y') }}</span>
                </div>
                @if($activeBatch->kuota)
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f0fdf4; padding:16px 20px; border-radius:16px; border:1px solid #dcfce7;">
                  <span style="font-size:12px; font-weight:800; color:#059669; text-transform:uppercase;">Sisa Kuota</span>
                  <span style="font-weight:900; color:#059669;">{{ $activeBatch->kuota }} Peserta</span>
                </div>
                @endif
              </div>
              
              <div class="enroll-form">
                <h4 style="font-size:20px; font-weight:900; margin-bottom:24px; color:#0f1c23; letter-spacing:-0.5px;">Formulir Pendaftaran</h4>
                
                @if(session('success'))
                  <div style="padding:16px; border-radius:16px; background:#ecfdf5; color:#059669; font-weight:800; font-size:14px; margin-bottom:24px; border:1px solid #dcfce7;">
                    ✅ {{ session('success') }}
                  </div>
                @endif

                <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                  @csrf
                  <input type="hidden" name="program_id" value="{{ $program->id }}">
                  <input type="hidden" name="batch_id" value="{{ $activeBatch->id }}">
                  
                  <div class="form-group-custom">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Sesuai KTP" required>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div class="form-group-custom">
                      <label>Jenis Kelamin</label>
                      <select name="jenis_kelamin" required>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                      </select>
                    </div>
                    <div class="form-group-custom">
                      <label>Pendidikan Terakhir</label>
                      <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" placeholder="SMK / S1 Teknik" required>
                    </div>
                  </div>

                  @if(Str::contains(Str::lower($program->nama_program), 'engineer'))
                  <div class="form-group-custom">
                    <label>Jurusan & IPK (Program Engineer)</label>
                    <input type="text" name="jurusan_ipk" value="{{ old('jurusan_ipk') }}" placeholder="S1 Mesin / IPK 3.25">
                  </div>
                  @endif

                  <div class="grid grid-cols-2 gap-4">
                    <div class="form-group-custom">
                      <label>Tempat Lahir</label>
                      <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Pekalongan" required>
                    </div>
                    <div class="form-group-custom">
                      <label>Tanggal Lahir</label>
                      <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                    </div>
                  </div>

                  <div class="form-group-custom">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat domisili saat ini" required>{{ old('alamat') }}</textarea>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div class="form-group-custom">
                      <label>WhatsApp</label>
                      <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxx" required>
                    </div>
                    <div class="form-group-custom">
                      <label>Email</label>
                      <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>
                  </div>

                  <div class="form-group-custom">
                    <label>Pengalaman Kerja (Jika ada)</label>
                    <textarea name="pengalaman_kerja" rows="2" placeholder="2 Thn sebagai Mekanik">{{ old('pengalaman_kerja') }}</textarea>
                  </div>

                  {{-- Document Uploads --}}
                  <div style="margin-top:32px; padding:28px; background:#f8fafc; border-radius:24px; border:1px solid #f1f5f9;">
                    <h5 style="font-size:12px; font-weight:900; margin-bottom:20px; color:var(--detail-primary); letter-spacing:1px;">UNGGAH DOKUMEN (MAX 5MB)</h5>
                    <div class="space-y-5">
                      @foreach(['ktp'=>'Foto KTP', 'kk'=>'Kartu Keluarga', 'foto'=>'Pas Foto', 'ijazah'=>'Ijazah Terakhir'] as $name => $label)
                      <div class="form-group-custom">
                        <label style="font-size:11px;">{{ $label }} <span style="color:var(--detail-accent)">*</span></label>
                        <input type="file" name="{{ $name }}" accept="application/pdf,image/*" required style="padding:10px; background:white; font-size:12px; border-style:dashed;">
                      </div>
                      @endforeach
                      <div class="form-group-custom">
                        <label style="font-size:11px;">Sertifikat / Pendukung</label>
                        <input type="file" name="sertifikat" accept="application/pdf,image/*" style="padding:10px; background:white; font-size:12px; border-style:dashed;">
                      </div>
                    </div>
                  </div>

                  @if ($errors->any())
                    <div style="padding:16px; background:#fef2f2; border:1px solid #fecaca; border-radius:16px; color:#991b1b; font-size:12px;">
                      <ul class="list-disc pl-4 font-bold">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  <button type="submit" class="btn btn-primary" style="width:100%; padding:18px; border-radius:16px; justify-content:center; display:flex; font-weight:900; font-size:16px; box-shadow: 0 10px 25px rgba(0,103,163,0.2);">
                    Daftar Sekarang
                  </button>
                </form>
              </div>
            @elseif($nextBatch)
              <div class="batch-status-badge" style="background:rgba(217, 119, 6, 0.1); color:#d97706;">AKAN DATANG</div>
              <span class="batch-name">{{ $nextBatch->nama_batch }}</span>
              <p class="text-slate-500 text-sm mb-6 mt-4 font-semibold leading-relaxed">Pendaftaran untuk batch ini akan dibuka pada {{ $nextBatch->tanggal_buka?->format('d F Y') }}.</p>
              <a href="https://wa.me/62812XXXXXXXX" class="btn btn-outline" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">Ingatkan Saya di WA</a>
            @else
              <div class="batch-status-badge" style="background:#f1f5f9; color:#64748b;">BELUM TERSEDIA</div>
              <span class="batch-name">Jadwal Belum Rilis</span>
              <p class="text-slate-500 text-sm mb-6 mt-4 font-semibold leading-relaxed">Saat ini belum ada batch yang dibuka. Silahkan hubungi admin untuk info terbaru.</p>
              <a href="https://wa.me/62812XXXXXXXX" class="btn btn-primary" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">Tanya Admin WA</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>

  {{-- Reuse existing sections --}}
  @include('sections.kontak')

@endsection

@push('scripts')
<script>
  // Simple scroll reveal
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
@endpush
