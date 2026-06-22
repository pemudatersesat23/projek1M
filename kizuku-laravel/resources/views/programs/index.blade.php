@extends('layouts.app')

@section('title', __('messages.nav.program') . ' — LPK Kizuku International Academy')

@push('styles')
<style>
    .prog-hero {
        padding: 160px 0 100px;
        background: linear-gradient(to bottom, #f8fafc, white);
        text-align: center;
    }
    .prog-tag {
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
    .prog-h1 {
        font-size: 48px;
        font-weight: 950;
        color: #0f1c23;
        margin-bottom: 24px;
        letter-spacing: -1.5px;
    }
    .prog-subtitle {
        font-size: 18px;
        color: #64748b;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .programs-grid-section {
        padding-bottom: 120px;
    }

    .card-wrap {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    @media (max-width: 1100px) {
        .card-wrap { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .card-wrap { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<header class="prog-hero">
    <div class="container">
        <div class="reveal">
            <div class="prog-tag">{{ __('messages.home.program_tag') }}</div>
            <h1 class="prog-h1">{!! __('messages.home.program_h2') !!}</h1>
            <p class="prog-subtitle">{{ __('messages.home.program_p') }}</p>
        </div>
    </div>
</header>

<section class="programs-grid-section">
    <div class="container">
        <div class="card-wrap">
            @php $cardClasses = ['red', 'blue', 'dark', 'mix']; @endphp
            @foreach($programs as $index => $p)
                <article class="prog-card {{ $cardClasses[$index % 4] }} reveal" style="height: 100%; display: flex; flex-direction: column;">
                    <div class="prog-glow"></div>
                    <div class="prog-badge">
                        <span class="bdot"></span>
                        {{ $p->nama_program }}
                    </div>
                    @if($p->thumbnail_path)
                        <img src="{{ asset('storage/' . $p->thumbnail_path) }}" alt="{{ $p->nama_program }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;" loading="lazy">
                    @endif
                    <h3 style="font-size: 22px; font-weight: 900; margin: 20px 0 15px;">{{ $p->nama_program }}</h3>
                    <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 20px;">
                        {{ Str::limit($p->deskripsi, 120) }}
                    </p>
                    
                    <ul class="feat-list" style="margin-bottom: 30px; flex-grow: 1;">
                        @php 
                            $benefits = array_filter(explode("\n", str_replace('-', '', $p->benefit)));
                        @endphp
                        @foreach(array_slice($benefits, 0, 4) as $b)
                            <li>{{ trim($b) }}</li>
                        @endforeach
                    </ul>

                    <div class="prog-footer" style="padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 8px; width: 100%; align-items: stretch;">
                        @php 
                            $activeBatch = $p->batches->where('status', 'dibuka')->first(); 
                            $upcomingBatch = $p->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first();
                        @endphp

                        @if($activeBatch)
                            <a class="btn btn-{{ $cardClasses[$index % 4] }}" href="{{ route('programs.show', $p->slug) }}" style="text-align: center; justify-content: center;">
                                {{ __('messages.program.batch.enroll') }} {{ $activeBatch->nama_batch }}
                            </a>
                        @elseif($upcomingBatch)
                            <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}" style="text-align: center; justify-content: center;">
                                {{ __('messages.program.batch.see_schedule') }}
                            </a>
                        @else
                            <a class="btn btn-outline" href="{{ route('programs.show', $p->slug) }}" style="text-align: center; justify-content: center;">
                                {{ __('messages.program.batch.details') }}
                            </a>
                        @endif

                        @if($p->brosur)
                            <a class="btn btn-outline" href="{{ asset('storage/' . $p->brosur) }}" target="_blank" style="text-align: center; justify-content: center; display: inline-flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined notranslate" translate="no" style="font-size: 16px;">download</span> Brosur Program
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('active');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>
@endpush
