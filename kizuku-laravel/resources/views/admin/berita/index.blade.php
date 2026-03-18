@extends('layouts.admin')
@section('admin-title', 'Kelola Berita')

@section('admin-content')
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">{{ __('messages.nav.home') === 'Beranda' ? 'Kelola Berita & Pengumuman' : 'ニュースとアナウンスの管理' }}</h3>
      <p class="text-sm text-slate-500 mt-1">{{ __('messages.nav.home') === 'Beranda' ? 'Tambah atau kelola berita yang tampil di halaman publik.' : '公開ページに表示されるニュースを追加または管理します。' }}</p>
    </div>
  </div>

  {{-- Add Form --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">add_circle</span> {{ __('messages.nav.home') === 'Beranda' ? 'Tambah Berita Baru' : '新しいニュースを追加' }}
      </h4>
    </div>

    @if($errors->any())
      <div class="mx-6 mt-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
        <span class="material-symbols-outlined text-accent-red">error</span>
        <span class="text-sm font-medium text-accent-red">{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="p-6">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Judul Berita *</label>
          <input type="text" name="judul" placeholder="Judul berita..." required value="{{ old('judul') }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Kategori</label>
          <select name="kategori" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="kat-info" {{ old('kategori')=='kat-info' ? 'selected' : '' }}>{{ __("messages.home.categories.kat-info") }}</option>
            <option value="kat-alumni" {{ old('kategori')=='kat-alumni' ? 'selected' : '' }}>{{ __("messages.home.categories.kat-alumni") }}</option>
            <option value="kat-promo" {{ old('kategori')=='kat-promo' ? 'selected' : '' }}>{{ __("messages.home.categories.kat-promo") }}</option>
            <option value="kat-tips" {{ old('kategori')=='kat-tips' ? 'selected' : '' }}>{{ __("messages.home.categories.kat-tips") }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Upload Gambar</label>
          <input type="file" name="gambar" accept="image/*"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Isi Berita</label>
          <textarea name="isi" placeholder="Ringkasan berita..." rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-y">{{ old('isi') }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Status Publikasi</label>
          <div class="flex items-center gap-4 mt-1">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status_publish" value="published" checked class="text-primary focus:ring-primary">
              <span class="text-sm font-medium text-slate-700">Published</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status_publish" value="draft" class="text-slate-400 focus:ring-primary">
              <span class="text-sm font-medium text-slate-700">Draft</span>
            </label>
          </div>
        </div>
      </div>
      <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">newspaper</span> Publish Berita
      </button>
    </form>
  </div>

  {{-- Berita List --}}
  @php
    $kategoriLabel = [
      'kat-info' => ['label' => __("messages.home.categories.kat-info"), 'class' => 'bg-primary/10 text-primary'],
      'kat-alumni' => ['label' => __("messages.home.categories.kat-alumni"), 'class' => 'bg-emerald-100 text-emerald-700'],
      'kat-promo' => ['label' => __("messages.home.categories.kat-promo"), 'class' => 'bg-accent-red/10 text-accent-red'],
      'kat-tips' => ['label' => __("messages.home.categories.kat-tips"), 'class' => 'bg-amber-100 text-amber-700'],
    ];
  @endphp

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($beritas as $n)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition-shadow">
      <div class="flex gap-4">
        <div class="flex-shrink-0 w-12 h-12 bg-slate-50 rounded-xl overflow-hidden flex items-center justify-center border border-slate-100">
          @if($n->gambar)
            <img src="{{ asset('storage/' . $n->gambar) }}" alt="{{ $n->judul }}" class="w-full h-full object-cover">
          @else
            <span class="material-symbols-outlined text-slate-400">image</span>
          @endif
        </div>
        <div class="flex-1 min-w-0">
          <h5 class="font-bold text-sm text-slate-800 mb-1 truncate">{{ $n->getTranslation('judul', app()->getLocale()) ?: $n->judul }}</h5>
          <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $n->getTranslation('isi', app()->getLocale()) ?: $n->isi }}</p>

          <div class="flex flex-wrap items-center gap-2">
            @php $kat = $kategoriLabel[$n->kategori] ?? ['label' => 'Info', 'class' => 'bg-slate-100 text-slate-600']; @endphp
            <span class="px-2 py-0.5 rounded-full {{ $kat['class'] }} text-[10px] font-bold uppercase tracking-wide">{{ $kat['label'] }}</span>

            @if(($n->status_publish ?? 'published') === 'published')
              <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wide flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Published
              </span>
            @else
              <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wide flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Draft
              </span>
            @endif

            <span class="text-[11px] text-slate-400">{{ $n->created_at->format('d/m/Y') }}</span>
          </div>

          <div class="flex items-center gap-2 mt-3">
            <a href="{{ route('admin.berita.edit', $n) }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-50 transition-colors flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">edit</span> Edit
            </a>
            <form method="POST" action="{{ route('admin.berita.destroy', $n) }}" class="inline" onsubmit="return confirm('Hapus berita ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="px-3 py-1.5 border border-red-200 text-accent-red rounded-lg text-xs font-medium hover:bg-red-50 transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">delete</span> Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="md:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center text-slate-400">
      <span class="material-symbols-outlined text-4xl mb-2 block">article</span>
      Belum ada berita.
    </div>
    @endforelse
  </div>
@endsection
