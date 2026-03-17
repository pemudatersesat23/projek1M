@extends('layouts.app')
@section('title', 'Semua Kampus Partner | LPK Kizuku International Academy')

@section('content')
<!-- Hero Section for Inner Page -->
<section class="bg-gradient-to-b from-slate-900 to-slate-800 pt-32 pb-20 relative overflow-hidden">
  <div class="absolute inset-0 z-0">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/10 rounded-full blur-[100px]"></div>
  </div>
  <div class="container relative z-10 text-center">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-white text-sm font-semibold tracking-wide mb-6">
      <span class="dynamic-lang" data-id="✦ Mitra Kami" data-jp="✦ パートナー">✦ Mitra Kami</span>
    </div>
    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 dynamic-lang" data-id="Semua Kampus Partner" data-jp="すべての提携キャンパス">Semua Kampus Partner</h1>
    <p class="text-slate-300 max-w-2xl mx-auto text-lg dynamic-lang" data-id="Daftar lengkap institusi pendidikan yang berkolaborasi dengan LPK Kizuku International Academy." data-jp="LPK Kizukuインターナショナルアカデミーと連携している教育機関の全リスト。">Daftar lengkap institusi pendidikan yang berkolaborasi dengan LPK Kizuku International Academy.</p>
  </div>
</section>

<!-- Content Section -->
<section class="py-20 bg-slate-50">
  <div class="container">
    <div class="mb-10">
      <a href="{{ url('/') }}#kampus-partner" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary transition-colors font-medium dynamic-lang" data-id="Kembali ke Beranda" data-jp="ホームに戻る">
        <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Beranda
      </a>
    </div>

    <div class="kampus-grid reveal" style="padding: 0; max-width: none;">
      @forelse($campuses as $campus)
        <div class="kampus-card">
          <!-- Banner -->
          <div class="kampus-banner">
            @if($campus->banner)
              <img src="{{ asset($campus->banner) }}" alt="Banner {{ $campus->name }}">
            @else
              <div class="kampus-banner-empty">No Banner</div>
            @endif
          </div>
          
          <!-- Overlapping Logo -->
          <div class="kampus-logo-wrapper">
            <img src="{{ asset($campus->logo) }}" alt="{{ $campus->name }}">
          </div>

          <!-- Content -->
          <div class="kampus-content">
            <h3 class="kampus-name dynamic-lang" data-id="{{ $campus->getTranslation('name', 'id', false) ?: $campus->name }}" data-jp="{{ $campus->getTranslation('name', 'jp', false) ?: $campus->name }}">{{ $campus->getTranslation('name', 'id', false) ?: $campus->name }}</h3>
            
            <div class="kampus-divider"></div>
            
            <p class="kampus-desc dynamic-lang" data-id="{{ $campus->getTranslation('description', 'id', false) ?: 'Belum ada deskripsi.' }}" data-jp="{{ $campus->getTranslation('description', 'jp', false) ?: '説明はありません。' }}">{{ $campus->getTranslation('description', 'id', false) ?: 'Belum ada deskripsi.' }}</p>
          </div>
        </div>
      @empty
        <div class="col-span-full py-20 text-center">
          <div class="inline-flex w-16 h-16 rounded-full bg-slate-100 items-center justify-center mb-4">
            <span class="material-symbols-outlined text-3xl text-slate-400">school</span>
          </div>
          <h3 class="font-bold text-slate-700 text-lg mb-2">Belum Ada Kampus</h3>
          <p class="text-slate-500">Daftar kampus partner belum tersedia saat ini.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
