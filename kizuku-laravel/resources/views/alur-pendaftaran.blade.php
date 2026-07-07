@extends('layouts.app')

@section('title', __('messages.nav.alur') . ' — LPK Kizuku International Academy')
@section('meta_description', 'Pahami alur pendaftaran dan proses seleksi kerja ke Jepang di LPK Kizuku International Academy. Mulai dari pendaftaran, pelatihan bahasa, hingga keberangkatan ke Jepang.')

@push('styles')
<style>
    .alur-hero {
        padding: 160px 0 100px;
        background: radial-gradient(circle at 10% 20%, rgba(216, 241, 230, 0.46) 0.1%, rgba(233, 226, 226, 0.28) 90.1%);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .alur-hero::before {
        content: '';
        position: absolute;
        top: -10%; right: -10%;
        width: 40%; height: 40%;
        background: radial-gradient(circle, rgba(0,103,163,0.05) 0%, transparent 70%);
        z-index: 0;
    }
    .alur-tag {
        display: inline-flex;
        padding: 8px 18px;
        background: rgba(225, 6, 0, 0.05);
        border-radius: 99px;
        font-size: 13px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 24px;
        border: 1px solid rgba(225, 6, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .alur-h1 {
        font-size: 48px;
        font-weight: 900;
        color: #0f1c23;
        line-height: 1.1;
        margin-bottom: 24px;
        letter-spacing: -1px;
    }
    .alur-p {
        font-size: 18px;
        color: #64748b;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .process-section {
        padding: 100px 0;
        background: white;
    }
    .timeline {
        position: relative;
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #f1f5f9;
        transform: translateX(-50%);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 80px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    .timeline-item:nth-child(even) {
        justify-content: flex-start;
    }
    .timeline-dot {
        position: absolute;
        left: 50%;
        top: 0;
        width: 56px;
        height: 56px;
        background: white;
        border: 2px solid #f1f5f9;
        border-radius: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
        transition: all 0.3s;
    }
    .timeline-item:hover .timeline-dot {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateX(-50%) scale(1.1);
    }
    .timeline-content {
        width: 42%;
        padding: 40px;
        background: #f8fafc;
        border-radius: 32px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
    }
    .timeline-item:hover .timeline-content {
        background: white;
        box-shadow: 0 30px 60px rgba(0,0,0,0.06);
        border-color: rgba(225, 6, 0, 0.1);
        transform: translateY(-10px);
    }
    .timeline-step {
        font-size: 12px;
        font-weight: 950;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 16px;
        display: block;
    }
    .timeline-title {
        font-size: 24px;
        font-weight: 900;
        color: #0f1c23;
        margin-bottom: 20px;
    }
    .timeline-desc {
        font-size: 15px;
        color: #64748b;
        line-height: 1.7;
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        top: 28px;
        left: 42%;
        width: 8%;
        height: 2px;
        background: #f1f5f9;
    }
    .timeline-item:nth-child(even)::after {
        left: auto;
        right: 42%;
    }

    .cta-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        text-align: center;
        color: white;
        border-radius: 60px;
        margin-bottom: 100px;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66-3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-46-45c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm54 24c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM58 69c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM4 60c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm59 32c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm29-20c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-62-39c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm34 37c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-3-47c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .timeline::before { left: 40px; }
        .timeline-dot { left: 40px; transform: translateX(-50%); }
        .timeline-item { justify-content: flex-start !important; padding-left: 80px; }
        .timeline-content { width: 100%; }
        .timeline-item::after { display: none; }
    }
</style>
@endpush

@section('content')
<header class="alur-hero">
    <div class="container">
        <div class="reveal">
            <div class="alur-tag">{{ \App\Models\Setting::get('alur_tag', '✦ PROSES SELEKSI') }}</div>
            <h1 class="alur-h1">{!! \App\Models\Setting::get('alur_title', 'Alur Pendaftaran & <br><span class="text-primary">Keberangkatan</span>') !!}</h1>
            <p class="alur-p">{{ \App\Models\Setting::get('alur_subtitle', 'Pahami setiap langkah perjalanannmu menuju karier sukses di Jepang bersama LPK Kizuku.') }}</p>
        </div>
    </div>
</header>

<section class="process-section">
    <div class="container">
        <div class="timeline">
            @foreach ($steps as $index => $step)
                <div class="timeline-item reveal">
                    <div class="timeline-dot">
                        <span class="material-symbols-outlined">{{ $step->icon }}</span>
                    </div>
                    <div class="timeline-content">
                        <span class="timeline-step">Step {{ sprintf('%02d', $index + 1) }}</span>
                        <h3 class="timeline-title">{{ $step->getTranslation('title', app()->getLocale(), false) ?: $step->getTranslation('title', 'id') }}</h3>
                        <p class="timeline-desc">{{ $step->getTranslation('description', app()->getLocale(), false) ?: $step->getTranslation('description', 'id') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cta-section reveal">
            <div style="position: relative; z-index: 2;">
                <h2 style="font-size: 36px; font-weight: 900; margin-bottom: 16px;">{{ \App\Models\Setting::get('alur_cta_title', 'Mulai Langkah Pertama Anda Hari Ini') }}</h2>
                <p style="opacity: 0.8; margin-bottom: 40px; font-size: 18px;">{{ __('messages.home.program_p') }}</p>
                <a href="{{ route('programs.index') }}" class="btn btn-primary" style="padding: 18px 40px; font-size: 16px; font-weight: 900;">
                    {{ \App\Models\Setting::get('alur_cta_btn', 'Pilih Program & Daftar') }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>
@endpush
