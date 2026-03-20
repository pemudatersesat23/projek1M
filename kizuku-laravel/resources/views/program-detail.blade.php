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
    min-width: 24px; height: 24px;
    background: #10b981;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center; justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
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
    grid-template-columns: repeat(6, 1fr);
    gap: 20px;
  }
  .form-full {
    grid-column: span 6;
  }
  .form-half {
    grid-column: span 3;
  }
  .form-third {
    grid-column: span 2;
  }
  .form-section-label {
    grid-column: span 6;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 30px 0 10px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f1f5f9;
  }
  .form-section-label .material-icons {
    color: var(--detail-primary);
  }
  .form-section-label span.section-text {
    font-size: 14px;
    font-weight: 950;
    color: #0f1c23;
    text-transform: uppercase;
    letter-spacing: 1px;
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
    padding: 18px 16px;
    border: 2px dashed #e2e8f0;
    border-radius: 20px;
    background: #f8fafc;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    height: 100%;
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
    width: 44px;
    height: 44px;
    background: white;
    color: var(--detail-primary);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    transition: all 0.3s;
    border: 1px solid #f1f5f9;
  }
  .upload-zone:hover .upload-icon {
    background: var(--detail-primary);
    color: white;
    transform: scale(1.05) rotate(5deg);
  }
  .upload-text {
    font-size: 13px;
    font-weight: 800;
    color: #334155;
    transition: all 0.3s;
  }
  .file-name-display {
    padding: 6px 14px;
    background: #f1f5f9;
    border-radius: 99px;
    font-size: 10px;
    font-weight: 700;
    color: var(--detail-primary);
    max-width: 160px;
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
    background: #f0fdf4 !important;
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
    font-size: 11px;
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
            @php
              if ($activeBatch) {
                  $hsColor = '#059669'; // Green
                  $hsText = __('messages.program.status_labels.dibuka');
              } elseif ($nextBatch) {
                  $hsColor = '#d97706'; // Amber/Orange
                  $hsText = __('messages.program.status_labels.akan_dibuka');
              } else {
                  $hsColor = '#64748b'; // Slate/Gray
                  $hsText = __('messages.program.status_labels.tutup');
              }
            @endphp
            <div class="pd-stat-card">
              <span class="pd-stat-label">{{ __('messages.program.status') }}</span>
              <span class="pd-stat-val" style="color: {{ $hsColor }};">{{ $hsText }}</span>
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
                <div class="pd-check">
                  <span class="material-symbols-outlined" style="font-size: 16px; font-weight: 800;">check</span>
                </div>
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
              <p class="text-slate-500 text-sm mb-8 mt-4 font-semibold leading-relaxed">{{ __('messages.nav.home') === 'Beranda' ? "Pendaftaran untuk batch ini akan dibuka pada {$nextBatch->tanggal_buka?->format('d F Y')}." : "このバッチの登録は {$nextBatch->tanggal_buka?->format('Y年m月d日')} に開始されます。" }}</p>
              <a href="#kontak" class="btn btn-outline" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">{{ __('messages.nav.kontak') }}</a>
            @else
              <div class="batch-status-badge" style="background:#f1f5f9; color:#64748b;">{{ __('messages.program.enroll_closed') }}</div>
              <span class="batch-name">{{ __('messages.program.no_schedule') }}</span>
              <p class="text-slate-500 text-sm mb-8 mt-4 font-semibold leading-relaxed">{{ __('messages.program.no_schedule_p') }}</p>
              <a href="#kontak" class="btn btn-primary" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">{{ __('messages.program.ask_admin') }}</a>
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
          <h2 class="form-title">Formulir Pendaftaran Online</h2>
          <p class="form-subtitle">Pendaftaran Program {{ $program->nama_program }} - {{ $activeBatch->nama_batch }}</p>
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
          
          {{-- SECTION: DATA PRIBADI (Common for all) --}}
          <div class="form-section-label">
            <span class="material-symbols-outlined">person</span>
            <span class="section-text">Bagian 1: Data Pribadi</span>
          </div>

          <div class="form-group-custom form-full">
            <span class="input-label">Nama Lengkap (sesuai KTP/Paspor) *</span>
            <input type="text" name="nama" value="{{ old('nama') }}" class="premium-input" placeholder="Masukkan nama lengkap" required>
          </div>

          <div class="form-group-custom form-half">
            <span class="input-label">Jenis Kelamin *</span>
            <select name="jenis_kelamin" class="premium-input premium-select" required>
              <option value="" disabled selected>Pilih Jenis Kelamin</option>
              <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
              <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
          </div>

          <div class="form-group-custom form-half">
            <span class="input-label">Tempat & Tanggal Lahir *</span>
            <div class="flex gap-2">
              <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="premium-input w-2/3" placeholder="Contoh: Makassar" required>
              <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="premium-input w-1/3" required>
            </div>
          </div>

          <div class="form-group-custom form-full">
            <span class="input-label">Alamat Domisili Lengkap *</span>
            <textarea name="alamat" rows="2" class="premium-input" placeholder="Alamat lengkap saat ini" required>{{ old('alamat') }}</textarea>
          </div>

          <div class="form-group-custom form-half">
            <span class="input-label">Nomor HP / WhatsApp Aktif *</span>
            <input type="text" name="phone" value="{{ old('phone') }}" class="premium-input" placeholder="08XXXXXXXXXX" required>
          </div>

          <div class="form-group-custom form-half">
            <span class="input-label">Email Aktif *</span>
            <input type="email" name="email" value="{{ old('email') }}" class="premium-input" placeholder="nama@email.com" required>
          </div>

          {{-- Program Specific Fields (Bagian 2) --}}
          @if($program->slug === 'tokutei-ginou-tg')
            <div class="form-section-label">
              <span class="material-symbols-outlined">school</span>
              <span class="section-text">Bagian 2: Pendidikan & Keahlian</span>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Pendidikan Terakhir *</span>
              <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" class="premium-input" placeholder="SMA/D3/S1" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Jurusan / Program Studi *</span>
              <input type="text" name="additional_data[jurusan]" class="premium-input" placeholder="Masukkan jurusan" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Nama Sekolah/Universitas *</span>
              <input type="text" name="additional_data[universitas]" class="premium-input" placeholder="Nama sekolah/kampus" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Tahun Lulus *</span>
              <input type="number" name="additional_data[tahun_lulus]" class="premium-input" placeholder="Contoh: 2022" required>
            </div>
            <div class="form-group-custom form-full">
              <span class="input-label">Kemampuan Bahasa Jepang *</span>
              <input type="text" name="additional_data[level_bahasa]" class="premium-input" placeholder="Contoh: JLPT N4 / JFT-Basic A2" required>
            </div>
            <div class="form-full">
              <span class="input-label" style="display:block; margin-bottom:16px;">Bidang TG yang diminati *</span>
              <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                @php
                  $tgFields = [
                    'makanan' => '1. Pengolahan Makanan',
                    'pertanian' => '2. Pertanian',
                    'kaigo' => '3. Perawatan Lansia (Kaigo)',
                    'restoran' => '4. Restoran / Layanan Makanan',
                    'hotel' => '5. Perhotelan',
                    'konstruksi' => '6. Konstruksi',
                    'manufaktur' => '7. Manufaktur Mesin & Peralatan',
                    'otomotif' => '8. Otomotif',
                    'peternakan' => '9. Peternakan',
                    'cleaning' => '10. Pembersihan Gedung'
                  ];
                @endphp
                @foreach($tgFields as $key => $label)
                  <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: #f8fafc; border-radius: 16px; border: 1px solid #f1f5f9; cursor: pointer;">
                    <input type="checkbox" name="additional_data[bidang_tg][]" value="{{ $key }}" style="width: 20px; height: 20px; accent-color: var(--primary);">
                    <span style="font-size: 13px; font-weight: 700; color: #334155;">{{ $label }}</span>
                  </label>
                @endforeach
              </div>
            </div>
          @elseif($program->slug === 'engineer-jepang-gijinkoku')
            <div class="form-section-label">
              <span class="material-symbols-outlined">engineering</span>
              <span class="section-text">Bagian 2: Pendidikan & Keahlian</span>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Jurusan (Pilih Satu) *</span>
              <select name="additional_data[jurusan]" class="premium-input premium-select" required>
                <option value="Teknik Sipil">Teknik Sipil</option>
                <option value="Teknik Arsitektur">Teknik Arsitektur</option>
                <option value="Teknik Mesin Elektro">Teknik Mesin Elektro</option>
                <option value="Teknik Mesin">Teknik Mesin</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Lainya">Lainya</option>
              </select>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Nama Universitas *</span>
              <input type="text" name="additional_data[universitas]" class="premium-input" placeholder="Nama Kampus" required>
            </div>
            <div class="form-group-custom form-third">
              <span class="input-label">Tahun Lulus *</span>
              <input type="number" name="additional_data[tahun_lulus]" class="premium-input" required>
            </div>
            <div class="form-group-custom form-third">
              <span class="input-label">Level Bahasa Jepang *</span>
              <input type="text" name="additional_data[level_bahasa]" class="premium-input" placeholder="N3 / N2" required>
            </div>
            <div class="form-group-custom form-third">
              <span class="input-label">Pengalaman Kerja *</span>
              <input type="text" name="pengalaman_kerja" class="premium-input" placeholder="Contoh: 2 Tahun di PT X" required>
            </div>
          @elseif($program->slug === 'kenshusei-jishussei-magang-jepang')
            <div class="form-section-label">
              <span class="material-symbols-outlined">accessibility_new</span>
              <span class="section-text">Bagian 2: Pendidikan & Keahlian</span>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Tinggi & Berat Badan *</span>
              <input type="text" name="additional_data[tb_bb]" class="premium-input" placeholder="Contoh: 170cm / 60kg" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Status Pernikahan *</span>
              <select name="additional_data[status_pernikahan]" class="premium-input premium-select" required>
                <option value="single">Lajang</option>
                <option value="married">Menikah</option>
              </select>
            </div>
            <div class="form-group-custom form-third">
              <span class="input-label">Pendidikan Terakhir *</span>
              <input type="text" name="pendidikan" class="premium-input" required>
            </div>
            <div class="form-group-custom form-third">
              <span class="input-label">Jurusan (Jika ada)</span>
              <input type="text" name="additional_data[jurusan]" class="premium-input">
            </div>
            <div class="form-group-custom form-third">
              <span class="input-label">Tahun Lulus</span>
              <input type="number" name="additional_data[tahun_lulus]" class="premium-input">
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Bidang Magang diminati *</span>
              <input type="text" name="additional_data[bidang_magang]" class="premium-input" placeholder="Contoh: Konstruksi / Pertanian" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Level Bahasa Jepang *</span>
              <input type="text" name="additional_data[level_bahasa]" class="premium-input" placeholder="N5 / Belum belajar" required>
            </div>
          @elseif($program->slug === 'kursus-bahasa-jepang-offline')
            <div class="form-section-label">
              <span class="material-symbols-outlined">menu_book</span>
              <span class="section-text">Bagian 2: Pilihan Program</span>
            </div>
            <div class="form-full">
              <span class="input-label mb-4 block">Pilihan Kelas *</span>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach(['Kelas N5', 'N4', 'N3', 'Kaiwa', 'Persiapan JLPT', 'Persiapan TG', 'Persiapan Engineering'] as $kelas)
                  <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer hover:bg-white transition-all">
                    <input type="radio" name="additional_data[pilihan_kelas]" value="{{ $kelas }}" class="w-5 h-5 text-primary">
                    <span class="text-xs font-bold">{{ $kelas }}</span>
                  </label>
                @endforeach
              </div>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Sistem Kelas *</span>
              <select name="additional_data[sistem_belajar]" class="premium-input premium-select" required>
                <option value="online">Online</option>
                <option value="offline">Offline</option>
              </select>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Level saat ini *</span>
              <input type="text" name="additional_data[level_sekarang]" class="premium-input" placeholder="Contoh: Pemula / Dasar" required>
            </div>
            <div class="form-section-label">
              <span class="material-symbols-outlined">target</span>
              <span class="section-text">Bagian 3: Tujuan Belajar</span>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Tujuan mengikuti kursus *</span>
              <input type="text" name="additional_data[tujuan_kursus]" class="premium-input" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Target JLPT / Target Keberangkatan</span>
              <input type="text" name="additional_data[target_jlpt]" class="premium-input">
            </div>
          @elseif($program->slug === 'engineer-jepang-ex-internship')
            <div class="form-section-label">
              <span class="material-symbols-outlined">history_edu</span>
              <span class="section-text">Bagian 2: Pendidikan & Keahlian</span>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Jurusan / Program Studi *</span>
              <select name="additional_data[jurusan]" class="premium-input premium-select" required>
                <option value="Teknik Mesin">Teknik Mesin</option>
                <option value="Teknik Elektro">Teknik Elektro</option>
                <option value="Teknik Sipil">Teknik Sipil</option>
                <option value="Lainya">Lainya</option>
              </select>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Nama Sekolah/Universitas *</span>
              <input type="text" name="additional_data[universitas]" class="premium-input" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Tahun Lulus *</span>
              <input type="number" name="additional_data[tahun_lulus]" class="premium-input" required>
            </div>
            <div class="form-group-custom form-half">
              <span class="input-label">Kemampuan Bahasa Jepang *</span>
              <select name="additional_data[level_bahasa]" class="premium-input premium-select" required>
                <option value="Belum belajar">Belum belajar</option>
                <option value="Sedang belajar">Sedang belajar</option>
                <option value="JLPT N5">JLPT N5</option>
                <option value="JLPT N4">JLPT N4</option>
                <option value="JLPT N3 atau lebih">JLPT N3 atau lebih</option>
              </select>
            </div>
            <div class="form-group-custom form-full">
              <span class="input-label">Pengalaman Kerja (nama perusahaan, posisi, lama bekerja)</span>
              <textarea name="pengalaman_kerja" rows="2" class="premium-input"></textarea>
            </div>
          @endif

          @if($program->slug === 'tokutei-ginou-tg')
            <div class="form-group-custom form-full">
              <span class="input-label">Pengalaman Kerja *</span>
              <textarea name="pengalaman_kerja" rows="2" class="premium-input" placeholder="Contoh: 1 Tahun di Restoran X" required></textarea>
            </div>
          @endif

          {{-- SECTION: DOKUMEN --}}
          <div class="form-section-label">
            <span class="material-symbols-outlined">cloud_upload</span>
            <span class="section-text">Bagian Dokumen Unggahan</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 form-full">
            @php
              $docs = [];
              if($program->slug === 'tokutei-ginou-tg') {
                $docs = ['ktp'=>'KTP', 'kk'=>'KK', 'foto'=>'Pas Foto', 'ijazah'=>'Ijazah', 'sertifikat'=>'Sertifikat JLPT/Keterampilan', 'bukti_follow'=>'Bukti Follow IG & TikTok'];
              } elseif($program->slug === 'engineer-jepang-gijinkoku') {
                $docs = ['cv'=>'CV', 'ktp'=>'KTP', 'kk'=>'KK', 'foto'=>'Pas Foto', 'ijazah'=>'Ijazah', 'transkrip'=>'Transkrip Nilai', 'sertifikat'=>'Sertifikat JLPT/Keterampilan', 'bukti_follow'=>'Bukti Follow IG & TikTok'];
              } elseif($program->slug === 'kenshusei-jishussei-magang-jepang') {
                $docs = ['ktp'=>'KTP', 'kk'=>'KK', 'ijazah'=>'Ijazah', 'foto'=>'Pas Foto', 'sertifikat'=>'Sertifikat Bahasa (jika ada)', 'bukti_follow'=>'Bukti Follow IG & TikTok'];
              } elseif($program->slug === 'kursus-bahasa-jepang-offline') {
                $docs = ['bukti_follow'=>'Bukti Follow IG & TikTok'];
              } else {
                $docs = ['cv'=>'CV', 'ijazah'=>'Ijazah', 'transkrip'=>'Transkrip Nilai', 'sertifikat'=>'Sertifikat (jika ada)', 'bukti_follow'=>'Bukti Follow IG & TikTok'];
              }
            @endphp

            @foreach($docs as $name => $label)
              <div class="form-group-custom">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">{{ $label }} @if(!Str::contains($label, 'jika ada') && $name !== 'sertifikat') <span class="text-red-500">*</span> @endif</label>
                <div class="upload-zone" id="zone-{{ $name }}">
                  <input type="file" name="{{ $name }}" onchange="updateFileName(this, 'zone-{{ $name }}')" @if(!Str::contains($label, 'jika ada') && $name !== 'sertifikat') required @endif>
                  <div class="upload-icon">
                    <span class="material-symbols-outlined">@if($name=='foto')image @elseif($name=='cv'||$name=='transkrip'||$name=='ijazah')description @else cloud_upload @endif</span>
                  </div>
                  <div class="upload-text text-[11px] font-bold">{{ $label }}</div>
                  <div class="file-name-display text-[10px]"></div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- SECTION: INFORMASI TAMBAHAN --}}
          @if($program->slug !== 'kursus-bahasa-jepang-offline')
            <div class="form-section-label">
              <span class="material-symbols-outlined">info</span>
              <span class="section-text">Bagian Informasi Tambahan</span>
            </div>
            
            <div class="form-group-custom form-full">
              <span class="input-label">Motivasi bekerja di Jepang *</span>
              <textarea name="additional_data[motivasi]" rows="2" class="premium-input" placeholder="Apa motivasi utama Anda?" required></textarea>
            </div>

            @if($program->slug === 'tokutei-ginou-tg')
              <div class="form-group-custom form-half">
                <span class="input-label">Bersedia kontrak minimal 3 tahun? *</span>
                <select name="additional_data[kontrak_3_tahun]" class="premium-input premium-select" required>
                  <option value="ya">Ya, Bersedia</option>
                  <option value="tidak">Tidak</option>
                </select>
              </div>
              <div class="form-group-custom form-half">
                <span class="input-label">Pernah ikut magang Jepang sebelumnya? *</span>
                <select name="additional_data[pernah_magang]" class="premium-input premium-select" required>
                  <option value="tidak">Belum Pernah</option>
                  <option value="ya">Pernah</option>
                </select>
              </div>
            @elseif($program->slug === 'kenshusei-jishussei-magang-jepang')
              <div class="form-group-custom form-half">
                <span class="input-label">Bersedia pelatihan sebelum berangkat? *</span>
                <select name="additional_data[bersedia_pelatihan]" class="premium-input premium-select" required>
                  <option value="ya">Ya, Bersedia</option>
                  <option value="tidak">Tidak</option>
                </select>
              </div>
              <div class="form-group-custom form-half">
                <span class="input-label">Bersedia ditempatkan di seluruh Jepang? *</span>
                <select name="additional_data[bersedia_penempatan]" class="premium-input premium-select" required>
                  <option value="ya">Ya, Bersedia</option>
                  <option value="tidak">Tidak</option>
                </select>
              </div>
            @endif
          @endif

          {{-- SECTION: PERNYATAAN --}}
          <div class="form-section-label">
            <span class="material-symbols-outlined">verified_user</span>
            <span class="section-text">Bagian Pernyataan & Persetujuan</span>
          </div>

          <div class="form-full" style="display: flex; flex-direction: column; gap: 16px; padding: 32px; background: #f8fafc; border-radius: 32px; border: 1px solid #f1f5f9;">
            <label style="display: flex; align-items: flex-start; gap: 16px; cursor: pointer;">
              <input type="checkbox" name="additional_data[agreement_truth]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
              <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">Saya menyatakan bahwa seluruh data yang saya masukkan adalah benar dan dapat dipertanggungjawabkan. *</span>
            </label>

            @if($program->slug === 'tokutei-ginou-tg' || $program->slug === 'kenshusei-jishussei-magang-jepang')
              <label style="display: flex; align-items: flex-start; gap: 16px; cursor: pointer;">
                <input type="checkbox" name="additional_data[agreement_selection]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
                <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">Saya bersedia mengikuti seluruh tahapan seleksi dan pelatihan yang telah ditentukan. *</span>
              </label>
              <label style="display: flex; align-items: flex-start; gap: 16px; cursor: pointer;">
                <input type="checkbox" name="additional_data[agreement_makassar]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
                <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">Saya siap mengikuti seleksi offline (tatap muka) di Makassar jika diperlukan. *</span>
              </label>
            @endif

            @if($program->slug === 'kursus-bahasa-jepang-offline')
              <label style="display: flex; align-items: flex-start; gap: 16px; cursor: pointer;">
                <input type="checkbox" name="additional_data[agreement_rules]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
                <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">Saya bersedia mengikuti seluruh aturan kelas yang berlaku di LPK Kizuku International Academy. *</span>
              </label>
            @endif
          </div>

          <div class="form-full pt-10">
            <button type="submit" class="btn btn-primary w-full py-6 rounded-[32px] text-xl font-black shadow-3xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
              Kirim Pendaftaran Sekarang
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
