@extends('layouts.admin')
@section('admin-title', 'Edit Berita')

@section('admin-content')
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Edit Berita</h3>
      <p class="text-sm text-slate-500 mt-1">Ubah informasi berita yang sudah ada.</p>
    </div>
    <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
    </a>
  </div>

  {{-- Edit Form --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">edit_note</span> Perbarui Berita
      </h4>
    </div>

    @if($errors->any())
      <div class="mx-6 mt-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
        <span class="material-symbols-outlined text-accent-red">error</span>
        <span class="text-sm font-medium text-accent-red">{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.berita.update', $berita) }}" class="p-6">
      @csrf
      @method('PUT')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Judul Berita *</label>
          <input type="text" name="judul" placeholder="Judul berita..." required value="{{ old('judul', $berita->judul) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Kategori</label>
          <select name="kategori" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="kat-info" {{ old('kategori', $berita->kategori)=='kat-info' ? 'selected' : '' }}>Info Program</option>
            <option value="kat-alumni" {{ old('kategori', $berita->kategori)=='kat-alumni' ? 'selected' : '' }}>Alumni</option>
            <option value="kat-promo" {{ old('kategori', $berita->kategori)=='kat-promo' ? 'selected' : '' }}>Promo</option>
            <option value="kat-tips" {{ old('kategori', $berita->kategori)=='kat-tips' ? 'selected' : '' }}>Tips</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Emoji / Ikon</label>
          <input type="text" name="emoji" placeholder="📢" maxlength="4" value="{{ old('emoji', $berita->emoji) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Isi Berita</label>
          <textarea name="isi" placeholder="Ringkasan berita..." rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-y">{{ old('isi', $berita->isi) }}</textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Status Publikasi</label>
          <div class="flex items-center gap-4 mt-1">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status_publish" value="published" {{ old('status_publish', $berita->status_publish) === 'published' ? 'checked' : '' }} class="text-primary focus:ring-primary">
              <span class="text-sm font-medium text-slate-700">Published</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="status_publish" value="draft" {{ old('status_publish', $berita->status_publish) === 'draft' ? 'checked' : '' }} class="text-slate-400 focus:ring-primary">
              <span class="text-sm font-medium text-slate-700">Draft</span>
            </label>
          </div>
        </div>
      </div>
      <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
      </button>
    </form>
  </div>
@endsection
