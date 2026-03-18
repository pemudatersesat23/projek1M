@extends('layouts.app')

@section('title', $berita->judul . ' - ' . config('app.name'))

@push('styles')
<style>
  .berita-detail-wrap {
    max-width: 820px;
    margin: 0 auto;
    padding: 0 20px;
  }
  .berita-detail-breadcrumb {
    padding: 28px 0 0;
  }
  .berita-detail-breadcrumb a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--primary);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    background: var(--light-bg, #f5f7fa);
    border: 1px solid var(--border-color, #e2e8f0);
    transition: background 0.2s;
  }
  .berita-detail-breadcrumb a:hover {
    background: #e9eef5;
  }
  .berita-detail-title-block {
    padding: 22px 0 18px;
  }
  .berita-detail-title-block .b-kategori-pill {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 12px;
  }
  .berita-detail-title-block h1 {
    font-size: clamp(22px, 4vw, 34px);
    font-weight: 800;
    color: var(--heading-color);
    line-height: 1.3;
    margin: 0;
  }
  .berita-detail-img {
    width: 100%;
    border-radius: 14px;
    overflow: hidden;
    background: #eee;
    margin-bottom: 0;
  }
  .berita-detail-img img {
    width: 100%;
    max-height: 480px;
    object-fit: cover;
    display: block;
  }
  .berita-detail-meta {
    display: flex;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
    padding: 14px 0 24px;
    color: var(--muted);
    font-size: 14px;
    border-bottom: 1px solid var(--border-color, #e2e8f0);
    margin-bottom: 24px;
  }
  .berita-detail-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .berita-detail-content {
    font-size: 16px;
    line-height: 1.85;
    color: var(--text-color);
    padding-bottom: 60px;
  }
</style>
@endpush

@section('content')
<main style="padding-top: 68px;">
  <div class="berita-detail-wrap">
    {{-- Tombol Kembali --}}
    <div class="berita-detail-breadcrumb">
      <a href="{{ route('home') }}#berita">
        <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
        Kembali ke Beranda
      </a>
    </div>

    {{-- Judul & Kategori --}}
    <div class="berita-detail-title-block">
      <span class="b-kategori-pill">{{ \App\Helpers\KategoriHelper::label($berita->kategori) }}</span>
      <h1>{{ $berita->judul }}</h1>
    </div>

    {{-- Foto --}}
    @if($berita->image)
      <div class="berita-detail-img">
        <img src="{{ asset('storage/' . $berita->image) }}" alt="{{ $berita->judul }}">
      </div>
    @endif

    {{-- Meta: Tanggal & Lokasi --}}
    <div class="berita-detail-meta">
      <span>
        <span class="material-symbols-outlined" style="font-size:18px;">calendar_month</span>
        {{ $berita->created_at->format('d M Y') }}
      </span>
      @if($berita->lokasi)
        <span>
          <span class="material-symbols-outlined" style="font-size:18px;">location_on</span>
          {{ $berita->lokasi }}
        </span>
      @endif
    </div>

    {{-- Isi Berita --}}
    <div class="berita-detail-content">
      {!! nl2br(e($berita->isi)) !!}
    </div>
  </div>
</main>
@endsection
