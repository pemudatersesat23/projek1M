@extends('layouts.app')

@section('title', $program->nama_program . ' — LPK Kizuku International Academy')

@push('styles')
<style>
  :root {
    --detail-primary: #0067a3;
    --detail-accent: #E31E24;
  }
  .program-detail-hero {
    padding: 140px 0 80px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    position: relative;
    overflow: hidden;
  }
  .pd-hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }
  .pd-tag {
    display: inline-flex;
    padding: 6px 16px;
    background: white;
    border-radius: 99px;
    font-size: 13px;
    font-weight: 700;
    color: var(--detail-primary);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 24px;
    border: 1px solid rgba(0,0,0,0.05);
  }
  .pd-h1 {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.1;
    color: #0f1c23;
    margin-bottom: 24px;
  }
  .pd-p {
    font-size: 18px;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 32px;
  }
  .pd-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }
  .pd-stat-card {
    background: white;
    padding: 20px;
    border-radius: 20px;
    border: 1px solid rgba(0,103,163,0.1);
  }
  .pd-stat-val {
    display: block;
    font-size: 20px;
    font-weight: 800;
    color: var(--detail-primary);
  }
  .pd-stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .pd-content-section {
    padding: 80px 0;
  }
  .pd-main-grid {
    display: grid;
    grid-template-columns: 1.8fr 1.2fr;
    gap: 60px;
  }
  .pd-section-h3 {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 30px;
    color: #0f1c23;
  }
  .pd-benefit-list {
    list-style: none;
    padding: 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }
  .pd-benefit-item {
    display: flex;
    gap: 12px;
    font-weight: 600;
    color: #334155;
  }
  .pd-check {
    color: #10b981;
    font-weight: 900;
  }
  
  .pd-faq-item {
    background: white;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 16px;
  }
  .pd-faq-q {
    font-size: 18px;
    font-weight: 700;
    color: #0f1c23;
    margin-bottom: 8px;
    display: block;
  }
  .pd-faq-a {
    color: #64748b;
    line-height: 1.6;
  }

  .batch-card {
    background: white;
    border: 2px solid var(--detail-primary);
    border-radius: 24px;
    padding: 32px;
    position: sticky;
    top: 100px;
    box-shadow: 0 20px 40px rgba(0,103,163,0.08);
  }
  .batch-status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 8px;
    background: #ecfdf5;
    color: #059669;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 16px;
  }
  .batch-name {
    font-size: 24px;
    font-weight: 800;
    display: block;
    margin-bottom: 8px;
  }
  .batch-dates {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 24px 0;
  }
  .batch-date-item {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    border-bottom: 1px dashed #e2e8f0;
    padding-bottom: 8px;
  }
  .batch-date-label { color: #94a3b8; font-weight: 600; }
  .batch-date-val { color: #0f1c23; font-weight: 700; }

  .pendaftaran-form-box {
    margin-top: 32px;
    padding-top: 32px;
    border-top: 1px solid #f1f5f9;
  }

  .form-group-custom {
    margin-bottom: 16px;
  }
  .form-group-custom label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
  }
  .form-group-custom input, .form-group-custom select, .form-group-custom textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
  }
  .form-group-custom input:focus {
    border-color: var(--detail-primary);
    box-shadow: 0 0 0 4px rgba(0,103,163,0.1);
    outline: none;
  }
  
  @media (max-width: 1023px) {
    .pd-hero-grid, .pd-main-grid { grid-template-columns: 1fr; gap: 40px; }
    .pd-h1 { font-size: 36px; }
    .pd-stats { grid-template-columns: 1fr; }
    .program-detail-hero { padding-top: 100px; }
  }
</style>
@endpush

@section('content')
  {{-- Hero Section --}}
  <header class="program-detail-hero">
    <div class="container">
      <div class="pd-hero-grid">
        <div class="reveal">
          <div class="pd-tag">✦ Program Pelatihan</div>
          <h1 class="pd-h1">{{ $program->nama_program }}</h1>
          <p class="pd-p">{{ $program->deskripsi }}</p>
          <div class="pd-stats">
            <div class="pd-stat-card">
              <span class="pd-stat-label">Durasi</span>
              <span class="pd-stat-val">{{ $program->durasi }}</span>
            </div>
            <div class="pd-stat-card">
              <span class="pd-stat-label">Biaya</span>
              <span class="pd-stat-val">{{ $program->biaya }}</span>
            </div>
            <div class="pd-stat-card">
              <span class="pd-stat-label">Status</span>
              <span class="pd-stat-val" style="color: #059669;">Tersedia</span>
            </div>
          </div>
        </div>
        <div class="reveal reveal-d2">
          @if($program->thumbnail_path)
            <img src="{{ asset($program->thumbnail_path) }}" alt="{{ $program->nama_program }}" style="width:100%; border-radius:32px; box-shadow:0 30px 60px rgba(0,0,0,0.1);">
          @else
            <div style="width:100%; aspect-ratio:4/3; background:var(--detail-primary); border-radius:32px; display:flex; align-items:center; justify-content:center; color:white; font-size:48px; font-weight:800;">
              KIZUKU
            </div>
          @endif
        </div>
      </div>
    </div>
  </header>

  <main class="pd-content-section">
    <div class="container">
      <div class="pd-main-grid">
        {{-- Left Content --}}
        <div class="reveal">
          <h3 class="pd-section-h3">Apa yang Kamu Dapatkan?</h3>
          <div class="pd-benefit-list" style="margin-bottom:60px;">
            @php 
              $benefits = array_filter(explode("\n", str_replace('-', '', $program->benefit)));
            @endphp
            @foreach($benefits as $b)
              <div class="pd-benefit-item">
                <span class="pd-check">✓</span>
                {{ trim($b) }}
              </div>
            @endforeach
          </div>

          <h3 class="pd-section-h3">Alur Seleksi</h3>
          <p class="pd-p" style="margin-bottom:60px; white-space: pre-line;">{{ $program->alur_seleksi }}</p>

          @if($program->faq)
          <h3 class="pd-section-h3">FAQ (Sering Ditanyakan)</h3>
          <div class="pd-faq-list">
            @foreach($program->faq as $faq)
              <div class="pd-faq-item">
                <span class="pd-faq-q">{{ $faq['q'] }}</span>
                <p class="pd-faq-a">{{ $faq['a'] }}</p>
              </div>
            @endforeach
          </div>
          @endif
        </div>

        {{-- Right: Batch & Form --}}
        <div>
          <div class="batch-card reveal reveal-d2">
            @if($activeBatch)
              <div class="batch-status-badge">PENDAFTARAN DIBUKA</div>
              <span class="batch-name">{{ $activeBatch->nama_batch }}</span>
              <div class="batch-dates">
                <div class="batch-date-item">
                  <span class="batch-date-label">Pendaftaran</span>
                  <span class="batch-date-val">{{ $activeBatch->tanggal_buka?->format('d M') }} – {{ $activeBatch->tanggal_tutup?->format('d M Y') }}</span>
                </div>
                <div class="batch-date-item">
                  <span class="batch-date-label">Mulai Kelas</span>
                  <span class="batch-date-val">{{ $activeBatch->tanggal_mulai?->format('d M Y') }}</span>
                </div>
                @if($activeBatch->kuota)
                <div class="batch-date-item">
                  <span class="batch-date-label">Sisa Kuota</span>
                  <span class="batch-date-val">{{ $activeBatch->kuota }} Peserta</span>
                </div>
                @endif
              </div>
              
              <div class="pendaftaran-form-box">
                <h4 style="font-weight:800; margin-bottom:16px;">Formulir Pendaftaran</h4>
                
                @if(session('success'))
                  <div style="padding:12px 16px; border-radius:12px; background:#ecfdf5; color:#059669; font-weight:700; font-size:13px; margin-bottom:16px;">
                    ✅ {{ session('success') }}
                  </div>
                @endif

                <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                      <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" placeholder="Contoh: SMK Teknik" required>
                    </div>
                  </div>

                  <div class="grid grid-cols-2 gap-4">
                    <div class="form-group-custom">
                      <label>Tempat Lahir</label>
                      <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta" required>
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
                      <label>No. HP / WhatsApp</label>
                      <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxx" required>
                    </div>
                    <div class="form-group-custom">
                      <label>Email</label>
                      <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>
                  </div>

                  <div class="form-group-custom">
                    <label>Pengalaman Kerja (Opsional)</label>
                    <textarea name="pengalaman" rows="2" placeholder="Sebutkan jika ada...">{{ old('pengalaman') }}</textarea>
                  </div>

                  <div class="form-group-custom">
                    <label>Motivasi Bergabung</label>
                    <textarea name="motivasi" rows="2" placeholder="Apa tujuan kamu ikut program ini?">{{ old('motivasi') }}</textarea>
                  </div>

                  {{-- Document Uploads --}}
                  <div style="margin-top:24px; padding:20px; background:#f8fafc; border-radius:16px;">
                    <h5 style="font-size:14px; font-weight:800; margin-bottom:16px; color:var(--detail-primary);">UPLOAD DOKUMEN (PDF atau Gambar, Max 5MB)</h5>
                    <div class="space-y-4">
                      <div class="form-group-custom">
                        <label>Foto KTP <span style="color:red">*</span></label>
                        <input type="file" name="ktp" accept="application/pdf,image/*" required style="padding:8px; background:white;">
                      </div>
                      <div class="form-group-custom">
                        <label>Kartu Keluarga (KK) <span style="color:red">*</span></label>
                        <input type="file" name="kk" accept="application/pdf,image/*" required style="padding:8px; background:white;">
                      </div>
                      <div class="form-group-custom">
                        <label>Pas Foto Terbaru <span style="color:red">*</span></label>
                        <input type="file" name="foto" accept="image/*" required style="padding:8px; background:white;">
                      </div>
                      <div class="form-group-custom">
                        <label>Ijazah Terakhir <span style="color:red">*</span></label>
                        <input type="file" name="ijazah" accept="application/pdf,image/*" required style="padding:8px; background:white;">
                      </div>
                      <div class="form-group-custom">
                        <label>Sertifikat Pendukung (Opsional)</label>
                        <input type="file" name="sertifikat" accept="application/pdf,image/*" style="padding:8px; background:white;">
                      </div>
                    </div>
                  </div>

                  @if ($errors->any())
                    <div style="padding:12px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; color:#991b1b; font-size:12px;">
                      <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  <button type="submit" class="btn btn-primary" style="width:100%; padding:14px; border-radius:12px; justify-content:center; display:flex;">
                    Kirim Pendaftaran
                  </button>
                </form>
              </div>
            @elseif($nextBatch)
              <div class="batch-status-badge" style="background:#fef3c7; color:#d97706;">AKAN DATANG</div>
              <span class="batch-name">{{ $nextBatch->nama_batch }}</span>
              <p class="text-slate-500 text-sm mb-4">Batch ini akan dibuka pada {{ $nextBatch->tanggal_buka?->format('d F Y') }}.</p>
              <a href="https://wa.me/62812XXXXXXXX" class="btn btn-outline" style="width:100%; justify-content:center; display:flex;">Ingatkan Saya di WA</a>
            @else
              <div class="batch-status-badge" style="background:#f1f5f9; color:#64748b;">BELUM TERSEDIA</div>
              <span class="batch-name">Jadwal Belum Rilis</span>
              <p class="text-slate-500 text-sm mb-4">Saat ini belum ada batch yang dibuka. Silakan hubungi CS untuk informasi lebih lanjut.</p>
              <a href="https://wa.me/62812XXXXXXXX" class="btn btn-primary" style="width:100%; justify-content:center; display:flex;">Hubungi WhatsApp</a>
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
