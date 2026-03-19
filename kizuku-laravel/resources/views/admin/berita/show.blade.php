@extends('layouts.admin')
@section('admin-title', 'Detail Berita')

@section('admin-content')
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Detail Berita</h3>
      <p class="text-sm text-slate-500 mt-1">Melihat keseluruhan konten berita yang dipilih.</p>
    </div>
    <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
    @if($berita->image)
      <div class="w-full h-64 md:h-96 bg-slate-100">
        <img src="{{ asset('storage/' . $berita->image) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
      </div>
    @endif
    
    <div class="p-8">
      <div class="flex flex-wrap items-center gap-3 mb-4">
        @php
            $kategoriLabel = [
              'kat-info' => ['label' => __("messages.home.categories.kat-info"), 'class' => 'bg-primary/10 text-primary'],
              'kat-alumni' => ['label' => __("messages.home.categories.kat-alumni"), 'class' => 'bg-emerald-100 text-emerald-700'],
              'kat-promo' => ['label' => __("messages.home.categories.kat-promo"), 'class' => 'bg-accent-red/10 text-accent-red'],
              'kat-tips' => ['label' => __("messages.home.categories.kat-tips"), 'class' => 'bg-amber-100 text-amber-700'],
            ];
            $kat = $kategoriLabel[$berita->kategori] ?? ['label' => 'Info', 'class' => 'bg-slate-100 text-slate-600'];
        @endphp
        <span class="px-3 py-1 rounded-full {{ $kat['class'] }} text-xs font-bold uppercase tracking-wide">{{ $kat['label'] }}</span>
        <span class="text-sm text-slate-500 flex items-center gap-1">
          <span class="material-symbols-outlined text-sm">calendar_month</span> {{ $berita->created_at->format('d M Y') }}
        </span>
        @if($berita->lokasi)
          <span class="text-sm text-slate-500 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">location_on</span> {{ $berita->lokasi }}
          </span>
        @endif
        @if(($berita->status_publish ?? 'published') === 'published')
          <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-wide flex items-center gap-1">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Published
          </span>
        @else
          <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wide flex items-center gap-1">
            <span class="w-2 h-2 bg-slate-400 rounded-full"></span> Draft
          </span>
        @endif
      </div>

      <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-6">{{ $berita->judul }}</h1>

      <div class="prose prose-slate max-w-none prose-img:rounded-xl">
        {!! nl2br(e($berita->isi)) !!}
      </div>
    </div>
  </div>
@endsection
