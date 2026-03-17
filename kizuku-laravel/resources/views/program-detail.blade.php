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

  .registration-section {
    padding: 120px 0;
    background: #f8fafc;
    position: relative;
    overflow: hidden;
  }
  .registration-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(to right, transparent, rgba(0,103,163,0.1), transparent);
  }
  .form-card {
    background: white;
    border-radius: 40px;
    padding: 60px;
    box-shadow: 0 40px 100px rgba(0,103,163,0.06);
    border: 1px solid rgba(0,103,163,0.05);
    max-width: 900px;
    margin: 0 auto;
  }
  .form-header {
    text-align: center;
    margin-bottom: 60px;
  }
  .form-title {
    font-size: 40px;
    font-weight: 950;
    color: #0f1c23;
    margin-bottom: 16px;
    letter-spacing: -1px;
  }
  .form-subtitle {
    font-size: 16px;
    color: #64748b;
    font-weight: 600;
  }
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
  }
  .form-full {
    grid-column: span 2;
  }
  .input-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--detail-primary);
    margin-bottom: 12px;
  }
  .premium-input {
    width: 100%;
    background: #f8fafc;
    border: 2px solid #f1f5f9;
    padding: 18px 24px;
    border-radius: 20px;
    font-weight: 700;
    color: #0f1c23;
    transition: all 0.3s;
    font-size: 15px;
  }
  .premium-input:focus {
    background: white;
    border-color: var(--detail-primary);
    box-shadow: 0 10px 30px rgba(0,103,163,0.08);
    outline: none;
  }
  .premium-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230067a3'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 24px center;
    background-size: 18px;
  }
  .upload-zone {
    position: relative;
    padding: 40px 24px;
    border: 2px dashed #e2e8f0;
    border-radius: 32px;
    background: #f8fafc;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
  }
  .upload-zone:hover {
    border-color: var(--detail-primary);
    background: white;
    box-shadow: 0 20px 40px rgba(0,103,163,0.08);
    transform: translateY(-2px);
  }
  .upload-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
  }
  .upload-icon {
    width: 64px;
    height: 64px;
    background: white;
    color: var(--detail-primary);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    transition: all 0.3s;
    border: 1px solid #f1f5f9;
  }
  .upload-zone:hover .upload-icon {
    background: var(--detail-primary);
    color: white;
    transform: scale(1.1) rotate(5deg);
  }
  .upload-text {
    font-size: 14px;
    font-weight: 800;
    color: #334155;
    transition: all 0.3s;
  }
  .file-name-display {
    padding: 8px 16px;
    background: #f1f5f9;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    color: var(--detail-primary);
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: none;
    animation: fadeIn 0.3s ease;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .file-selected {
    border-style: solid;
    border-color: #10b981;
    background: #f0fdf4;
  }
  .file-selected .upload-icon {
    background: #10b981;
    color: white;
    border-color: #10b981;
  }
  .file-selected .file-name-display {
    display: block;
  }
  .file-selected .upload-text {
    color: #059669;
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
          <div class="pd-tag">{{ __('messages.hero.badge') }}</div>
          <h1 class="pd-h1">{{ $program->nama_program }}</h1>
          <p class="pd-p">{{ $program->deskripsi }}</p>
          <div class="pd-stats">
            <div class="pd-stat-card">
              <span class="pd-stat-label">{{ __('messages.program.duration') }}</span>
              <span class="pd-stat-val">{{ $program->durasi }}</span>
            </div>
            <div class="pd-stat-card">
              <span class="pd-stat-label">{{ __('messages.program.investment') }}</span>
              <span class="pd-stat-val">{{ $program->biaya }}</span>
            </div>
            <div class="pd-stat-card">
              <span class="pd-stat-label">{{ __('messages.program.status') }}</span>
              <span class="pd-stat-val" style="color: #059669;">{{ __('messages.program.available') }}</span>
            </div>
          </div>
        </div>
        <div class="reveal reveal-d2">
          <div style="position: relative;">
            <div style="position: absolute; top: -20px; left: -20px; right: 20px; bottom: 20px; background: var(--detail-primary); opacity: 0.1; border-radius: 40px; transform: rotate(-2deg); z-index: -1;"></div>
            @if($program->video_url)
              <div class="video-container" style="border-radius:32px; overflow:hidden; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
                <iframe width="100%" height="315" src="{{ $program->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
            @elseif($program->thumbnail_path)
              <img src="{{ asset('storage/' . $program->thumbnail_path) }}" alt="{{ $program->nama_program }}" style="width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:32px; box-shadow:0 30px 60px rgba(0,0,0,0.12); border: 8px solid white;">
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
              <span class="focus-label">{{ __('messages.program.labels.target') }}</span>
              <p class="focus-text">{{ $program->getTranslation('target_peserta', app()->getLocale()) }}</p>
            </div>
            <div class="focus-box">
              <span class="focus-label">{{ __('messages.program.labels.materi') }}</span>
              <p class="focus-text">{{ $program->getTranslation('materi', app()->getLocale()) }}</p>
            </div>
            @if($program->focus)
            <div class="focus-box">
              <span class="focus-label">{{ __('messages.program.labels.focus') }}</span>
              <p class="focus-text">{{ $program->getTranslation('focus', app()->getLocale()) }}</p>
            </div>
            @endif
            @if($program->output)
            <div class="focus-box">
              <span class="focus-label">{{ __('messages.program.labels.output') }}</span>
              <p class="focus-text">{{ $program->getTranslation('output', app()->getLocale()) }}</p>
            </div>
            @endif
          </div>

          <h3 class="pd-section-h3">{{ __('messages.program.benefit') }}</h3>
          <div class="pd-benefit-list reveal">
            @php 
              $benefits = array_filter(explode("\n", str_replace(['-', '✓'], '', $program->getTranslation('benefit', app()->getLocale()))));
            @endphp
            @foreach($benefits as $b)
              <div class="pd-benefit-item">
                <span class="pd-check">✓</span>
                {{ trim($b) }}
              </div>
            @endforeach
          </div>

          <h3 class="pd-section-h3">{{ __('messages.program.selection') }}</h3>
          <div class="selection-timeline reveal">
            @php 
              $steps = array_filter(explode("\n", str_replace(['-', '>'], '', $program->getTranslation('alur_seleksi', app()->getLocale()))));
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
          <h3 class="pd-section-h3">{{ __('messages.program.faq') }}</h3>
          <div class="reveal">
            @php 
              $faqs = $program->getTranslation('faq', app()->getLocale()) ?: $program->faq;
            @endphp
            @foreach($faqs as $faq)
              <div class="pd-faq-item">
                <h5 class="pd-faq-q">{{ $faq['q'] ?? '' }}</h5>
                <p class="pd-faq-a">{{ $faq['a'] ?? '' }}</p>
              </div>
            @endforeach
          </div>
          @endif

          {{-- Batch History --}}
          <h3 class="pd-section-h3">{{ __('messages.program.batch_history') }}</h3>
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
                        'dibuka' => ['bg' => '#ecfdf5', 'text' => '#059669', 'label' => __('messages.program.status_labels.dibuka')],
                        'akan_dibuka' => ['bg' => '#fef3c7', 'text' => '#d97706', 'label' => __('messages.program.status_labels.akan_dibuka')],
                        'selesai' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => __('messages.program.status_labels.selesai')],
                        default => ['bg' => '#f1f5f9', 'text' => '#64748b', 'label' => __('messages.program.status_labels.tutup')]
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

        {{-- Right Content: Batch Summary Card --}}
        <div>
          <div class="batch-card reveal reveal-d2">
            @if($activeBatch)
              <div class="batch-status-badge status-active-badge">{{ __('messages.program.enroll_open') }}</div>
              <span class="batch-name">{{ $activeBatch->nama_batch }}</span>
              
              <div class="batch-dates" style="margin: 32px 0; display: flex; flex-direction: column; gap: 16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
                  <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ __('messages.program.batch.pendaftaran') }}</span>
                  <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_buka?->format('d M') }} – {{ $activeBatch->tanggal_tutup?->format('d M Y') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
                  <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ __('messages.program.batch.mulai') }}</span>
                  <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_mulai?->format('d M Y') }}</span>
                </div>
                @if($activeBatch->kuota)
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f0fdf4; padding:16px 20px; border-radius:16px; border:1px solid #dcfce7;">
                  <span style="font-size:12px; font-weight:800; color:#059669; text-transform:uppercase;">{{ __('messages.program.batch.kuota') }}</span>
                  <span style="font-weight:900; color:#059669;">{{ $activeBatch->kuota ?? 'Fleksibel' }} {{ __('messages.program.batch.peserta') }}</span>
                </div>
                @if($activeBatch->tanggal_estimasi_selesai)
                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
                  <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ __('messages.program.batch.estimasi') }}</span>
                  <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_estimasi_selesai->format('d M Y') }}</span>
                </div>
                @endif
                @endif
              </div>
              
              <div class="action-buttons">
                @if($activeBatch->cta_type === 'whatsapp')
                  <a href="{{ $activeBatch->whatsapp_link ?? 'https://wa.me/6281212345678' }}" target="_blank" class="btn btn-primary" style="width:100%; padding:18px; border-radius:16px; justify-content:center; display:flex; font-weight:900; font-size:16px; background:#25d366; border-color:#25d366; box-shadow: 0 10px 25px rgba(37,211,102,0.2);">
                    <svg style="width:20px; height:20px; margin-right:10px;" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    {{ __('messages.program.batch.wa_btn') }}
                  </a>
                @else
                  <a href="#registration-section" class="btn btn-primary" style="width:100%; padding:18px; border-radius:16px; justify-content:center; display:flex; font-weight:900; font-size:16px; box-shadow: 0 10px 25px rgba(0,103,163,0.2);">
                    {{ __('messages.program.batch.enroll_btn') }}
                  </a>
                @endif
              </div>

            @elseif($nextBatch)
              <div class="batch-status-badge" style="background:rgba(217, 119, 6, 0.1); color:#d97706;">{{ __('messages.program.enroll_soon') }}</div>
              <span class="batch-name">{{ $nextBatch->nama_batch }}</span>
              <p class="text-slate-500 text-sm mb-6 mt-4 font-semibold leading-relaxed">{{ __('messages.nav.home') === 'Beranda' ? "Pendaftaran untuk batch ini akan dibuka pada {$nextBatch->tanggal_buka?->format('d F Y')}." : "このバッチの登録は {$nextBatch->tanggal_buka?->format('Y年m月d日')} に開始されます。" }}</p>
              <a href="https://wa.me/6281212345678" target="_blank" class="btn btn-outline" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">{{ __('messages.nav.kontak') }}</a>
            @else
              <div class="batch-status-badge" style="background:#f1f5f9; color:#64748b;">{{ __('messages.program.enroll_closed') }}</div>
              <span class="batch-name">{{ __('messages.program.no_schedule') }}</span>
              <p class="text-slate-500 text-sm mb-6 mt-4 font-semibold leading-relaxed">{{ __('messages.program.no_schedule_p') }}</p>
              <a href="https://wa.me/6281212345678" target="_blank" class="btn btn-primary" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">{{ __('messages.program.ask_admin') }}</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>

  {{-- Registration Form Section (Bottom) --}}
  @if($activeBatch && $activeBatch->cta_type !== 'whatsapp')
  <section id="registration-section" class="registration-section reveal">
    <div class="container">
      <div class="form-card">
        <div class="form-header">
          <h2 class="form-title">{{ __('messages.form.title') }}</h2>
          <p class="form-subtitle">{{ __('messages.form.subtitle', ['batch' => $activeBatch->nama_batch]) }}</p>
        </div>

        @if(session('success'))
          <div style="padding:24px; border-radius:32px; background:#ecfdf5; color:#059669; font-weight:800; font-size:15px; margin-bottom:40px; border:1px solid #dcfce7; text-align:center;">
            ✅ {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
          @csrf
          <input type="hidden" name="program_id" value="{{ $program->id }}">
          <input type="hidden" name="batch_id" value="{{ $activeBatch->id }}">
          
          {{-- Informasi Dasar --}}
          <div class="form-group-custom form-full">
            <span class="input-label"><span class="material-symbols-outlined text-[16px]">person</span> {{ __('messages.form.name') }}</span>
            <input type="text" name="nama" value="{{ old('nama') }}" class="premium-input" placeholder="{{ __('messages.form.placeholders.ktp') }}" required>
          </div>

          <div class="form-group-custom">
            <span class="input-label">{{ __('messages.form.gender') }}</span>
            <select name="jenis_kelamin" class="premium-input premium-select" required>
              <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>{{ __('messages.nav.home') === 'Beranda' ? 'Laki-laki' : '男性' }}</option>
              <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>{{ __('messages.nav.home') === 'Beranda' ? 'Perempuan' : '女性' }}</option>
            </select>
          </div>

          <div class="form-group-custom">
            <span class="input-label">{{ __('messages.form.education') }}</span>
            <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" class="premium-input" placeholder="{{ __('messages.form.placeholders.pendidikan') }}" required>
          </div>

          {{-- Dynamic Groups --}}
          @if(Str::contains(Str::lower($program->nama_program), ['magang', 'tokutei']))
            <div class="form-full grid grid-cols-2 gap-8 p-8 bg-slate-50 rounded-[32px] border border-slate-100">
              <div class="col-span-2 flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-primary">straighten</span>
                <span class="text-[12px] font-black text-primary uppercase tracking-widest">{{ __('messages.form.physical') }}</span>
              </div>
              <div class="form-group-custom">
                <label class="input-label">{{ __('messages.form.height') }} (cm)</label>
                <input type="number" name="tinggi_badan" value="{{ old('tinggi_badan') }}" class="premium-input" placeholder="165" required>
              </div>
              <div class="form-group-custom">
                <label class="input-label">{{ __('messages.form.weight') }} (kg)</label>
                <input type="number" name="berat_badan" value="{{ old('berat_badan') }}" class="premium-input" placeholder="60" required>
              </div>
              <div class="form-group-custom col-span-2">
                <label class="input-label">{{ __('messages.form.eyes') }}</label>
                <input type="text" name="kondisi_mata" value="{{ old('kondisi_mata') }}" class="premium-input" placeholder="Normal / Minus 1.5" required>
              </div>
              <div class="col-span-2 flex gap-10">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative flex items-center">
                    <input type="checkbox" name="tato" value="1" {{ old('tato') ? 'checked' : '' }} class="w-6 h-6 rounded-lg text-primary focus:ring-primary/20 transition-all border-slate-300">
                  </div>
                  <span class="text-sm font-bold text-slate-600 group-hover:text-primary">{{ __('messages.form.tattoo') }}</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative flex items-center">
                    <input type="checkbox" name="merokok" value="1" {{ old('merokok') ? 'checked' : '' }} class="w-6 h-6 rounded-lg text-primary focus:ring-primary/20 transition-all border-slate-300">
                  </div>
                  <span class="text-sm font-bold text-slate-600 group-hover:text-primary">{{ __('messages.form.smoke') }}</span>
                </label>
              </div>
            </div>
          @endif

          @if(Str::contains(Str::lower($program->nama_program), 'tokutei'))
            <div class="form-full grid grid-cols-1 gap-8 p-8 bg-blue-50/50 rounded-[32px] border border-blue-100">
              <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-blue-600">verified</span>
                <span class="text-[12px] font-black text-blue-600 uppercase tracking-widest">{{ __('messages.form.ssw') }}</span>
              </div>
              <div class="form-group-custom">
                <label class="input-label">{{ __('messages.form.placeholders.ssw_field') }}</label>
                <input type="text" name="bidang_ssw" value="{{ old('bidang_ssw') }}" class="premium-input" placeholder="Food Service / Kaigo" required>
              </div>
              <div class="form-group-custom">
                <label class="input-label">{{ __('messages.form.placeholders.jp_level') }}</label>
                <input type="text" name="level_bahasa_jepang" value="{{ old('level_bahasa_jepang') }}" class="premium-input" placeholder="N4 / JFT A2" required>
              </div>
            </div>
          @endif

          @if(Str::contains(Str::lower($program->nama_program), 'engineer'))
            <div class="form-full grid grid-cols-2 gap-8 p-8 bg-emerald-50/30 rounded-[32px] border border-emerald-100">
              <div class="col-span-2 flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-emerald-600">school</span>
                <span class="text-[12px] font-black text-emerald-600 uppercase tracking-widest">{{ __('messages.form.academic') }}</span>
              </div>
              <div class="form-group-custom">
                <label class="input-label">{{ __('messages.form.placeholders.ipk') }}</label>
                <input type="text" name="ipk" value="{{ old('ipk') }}" class="premium-input" placeholder="3.50" required>
              </div>
              <div class="form-group-custom">
                <label class="input-label">{{ __('messages.form.placeholders.major') }}</label>
                <input type="text" name="jurusan_ipk" value="{{ old('jurusan_ipk') }}" class="premium-input" placeholder="S1 Teknik Informatika" required>
              </div>
            </div>
          @endif

          @if(Str::contains(Str::lower($program->nama_program), 'kursus'))
            <div class="form-full p-8 bg-amber-50/30 rounded-[32px] border border-amber-100">
              <span class="input-label">{{ __('messages.form.shift') }}</span>
              <select name="shift_kursus" class="premium-input premium-select" required>
                <option value="pagi">{{ __('messages.form.placeholders.shift_pagi') }}</option>
                <option value="siang">{{ __('messages.form.placeholders.shift_siang') }}</option>
                <option value="malam">{{ __('messages.form.placeholders.shift_malam') }}</option>
              </select>
            </div>
          @endif

          <div class="form-group-custom">
            <span class="input-label">{{ __('messages.form.pob') }}</span>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="premium-input" placeholder="{{ __('messages.form.placeholders.pob') }}" required>
          </div>

          <div class="form-group-custom">
            <span class="input-label">{{ __('messages.form.dob') }}</span>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="premium-input" required>
          </div>

          <div class="form-group-custom form-full">
            <span class="input-label">{{ __('messages.form.address') }}</span>
            <textarea name="alamat" rows="3" class="premium-input" placeholder="{{ __('messages.form.placeholders.address') }}" required>{{ old('alamat') }}</textarea>
          </div>

          <div class="form-group-custom">
            <span class="input-label">{{ __('messages.form.phone') }}</span>
            <input type="text" name="phone" value="{{ old('phone') }}" class="premium-input" placeholder="08xxxxxxxx" required>
          </div>

          <div class="form-group-custom">
            <span class="input-label">{{ __('messages.form.email') }}</span>
            <input type="email" name="email" value="{{ old('email') }}" class="premium-input" placeholder="nama@email.com" required>
          </div>

          <div class="form-group-custom form-full">
            <span class="input-label">{{ __('messages.form.experience') }}</span>
            <textarea name="pengalaman_kerja" rows="2" class="premium-input" placeholder="{{ __('messages.form.placeholders.experience') }}">{{ old('pengalaman_kerja') }}</textarea>
          </div>

          {{-- Upload Grid --}}
          <div class="form-full pt-10 mt-6 border-t border-slate-100">
            <h5 class="text-[12px] font-black text-slate-400 uppercase tracking-[2px] mb-8 text-center">{{ __('messages.form.upload') }} (Max 5MB)</h5>
            <div class="grid grid-cols-2 gap-6">
              @foreach(['ktp'=>'KTP', 'kk'=>'KK', 'foto'=>'Pas Foto', 'ijazah'=>'Ijazah'] as $name => $label)
                <div class="form-group-custom">
                  <label class="text-[10px] font-extrabold text-slate-500 uppercase ml-2 mb-2 block">{{ $label }} <span class="text-red-500">*</span></label>
                  <div class="upload-zone" id="zone-{{ $name }}">
                    <input type="file" name="{{ $name }}" onchange="updateFileName(this, 'zone-{{ $name }}')" required>
                    <div class="upload-icon">
                      <span class="material-symbols-outlined">cloud_upload</span>
                    </div>
                    <div class="upload-text">{{ __('messages.form.upload_placeholder') }} {{ $label }}</div>
                    <div class="file-name-display"></div>
                  </div>
                </div>
              @endforeach
              <div class="form-group-custom col-span-2">
                <label class="text-[10px] font-extrabold text-slate-500 uppercase ml-2 mb-2 block">Sertifikat / Tambahan</label>
                <div class="upload-zone" id="zone-sertifikat">
                  <input type="file" name="sertifikat" onchange="updateFileName(this, 'zone-sertifikat')">
                  <div class="upload-icon">
                    <span class="material-symbols-outlined">history_edu</span>
                  </div>
                  <div class="upload-text">{{ __('messages.form.upload_placeholder') }} Sertifikat</div>
                  <div class="file-name-display"></div>
                </div>
              </div>
            </div>
          </div>

          @if ($errors->any())
            <div class="form-full p-6 bg-red-50 border border-red-100 rounded-3xl text-red-600 text-sm font-bold">
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="form-full pt-8">
            <button type="submit" class="btn btn-primary w-full py-6 rounded-[24px] text-lg font-black shadow-2xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
              {{ __('messages.nav.home') === 'Beranda' ? 'Kirim Pendaftaran' : '登録を送信' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
  @endif

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

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });

  // File Upload Preview
  function updateFileName(input, zoneId) {
    const zone = document.getElementById(zoneId);
    const fileNameDisplay = zone.querySelector('.file-name-display');
    const uploadText = zone.querySelector('.upload-text');
    const selectedText = "{{ __('messages.form.file_selected') }}";
    const placeholderText = "{{ __('messages.form.upload_placeholder') }}";
    
    if (input.files && input.files[0]) {
      const fileName = input.files[0].name;
      fileNameDisplay.textContent = fileName;
      zone.classList.add('file-selected');
      uploadText.textContent = selectedText;
    } else {
      zone.classList.remove('file-selected');
      // Extract original label from context if needed, but simple revert is fine
      uploadText.textContent = placeholderText;
    }
  }
</script>
@endpush
