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
      <span data-id="✦ Mitra Kami" data-jp="✦ パートナー">✦ Mitra Kami</span>
    </div>
    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6" data-id="Semua Kampus Partner" data-jp="すべての提携キャンパス">Semua Kampus Partner</h1>
    <p class="text-slate-300 max-w-2xl mx-auto text-lg" data-id="Daftar lengkap institusi pendidikan yang berkolaborasi dengan LPK Kizuku International Academy." data-jp="LPK Kizukuインターナショナルアカデミーと連携している教育機関の全リスト。">Daftar lengkap institusi pendidikan yang berkolaborasi dengan LPK Kizuku International Academy.</p>
  </div>
</section>

<!-- Content Section -->
<section class="py-20 bg-slate-50">
  <div class="container">
    <div class="mb-10">
      <a href="{{ url('/') }}#kampus-partner" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary transition-colors font-medium" data-id="Kembali ke Beranda" data-jp="ホームに戻る">
        <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Beranda
      </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
      @forelse($campuses as $campus)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center text-center">
          <div class="w-full h-32 flex items-center justify-center p-4 bg-slate-50 rounded-xl mb-6">
            <img src="{{ asset($campus->logo) }}" alt="{{ $campus->name }}" class="max-w-full max-h-full object-contain mix-blend-multiply">
          </div>
          <h3 class="font-bold text-slate-800 text-lg">{{ $campus->name }}</h3>
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
