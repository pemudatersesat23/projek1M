@extends('layouts.app')

@section('title', $berita->judul . ' - ' . config('app.name'))

@push('styles')
<style>
  .berita-detail-header {
    padding: 60px 0 20px;
    background: var(--light-bg);
  }
  .berita-detail-img {
    width: 100%;
    margin-bottom: 20px;
    border-radius: 12px;
    overflow: hidden;
    background: #eee;
  }
  .berita-detail-img img {
    width: 100%;
    height: auto;
    display: block;
    max-height: 500px;
    object-fit: cover;
  }
  .berita-detail-content {
    font-size: 16px;
    line-height: 1.8;
    color: var(--text-color);
  }
  .berita-meta-info {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    color: var(--muted);
    font-size: 14px;
    align-items: center;
  }
  .berita-meta-info span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
</style>
@endpush

@section('content')
<main>
  <header class="berita-detail-header">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
      <a href="{{ route('home') }}#berita" style="display:inline-block; margin-bottom: 15px; color: var(--primary); text-decoration: none; font-weight: bold;">
        &larr; Kembali ke Beranda
      </a>
      
      <div style="margin-bottom: 12px;">
        <span style="display:inline-block; background: var(--primary); color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
          {{ \App\Helpers\KategoriHelper::label($berita->kategori) }}
        </span>
      </div>

      <h1 style="font-size: 32px; font-weight: 800; color: var(--heading-color); margin-bottom: 0px; line-height: 1.3;">
        {{ $berita->judul }}
      </h1>
    </div>
  </header>

  <section style="padding: 20px 0 40px 0;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
      @if($berita->image)
        <div class="berita-detail-img">
          <img src="{{ asset('storage/' . $berita->image) }}" alt="{{ $berita->judul }}">
        </div>
      @endif

      <div class="berita-meta-info">
        <span><span class="material-symbols-outlined" style="font-size: 18px;">calendar_month</span> {{ $berita->created_at->format('d M Y') }}</span>
        @if($berita->lokasi)
          <span><span class="material-symbols-outlined" style="font-size: 18px;">location_on</span> {{ $berita->lokasi }}</span>
        @endif
      </div>

      <div class="berita-detail-content">
        {!! nl2br(e($berita->isi)) !!}
      </div>
    </div>
  </section>
</main>
@endsection
