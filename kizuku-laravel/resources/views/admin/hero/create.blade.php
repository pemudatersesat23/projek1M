@extends('layouts.admin')
@section('admin-title', 'Tambah Banner Hero')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Tambah Banner</h3>
      <p class="text-sm text-slate-500 mt-1">Buat konten hero baru untuk home page.</p>
    </div>
    <a href="{{ route('admin.hero-sections.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <form action="{{ route('admin.hero-sections.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Upload Gambar Background *</label>
          <input type="file" name="image" accept="image/*" required
                 class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
          <p class="text-[10px] text-slate-400 mt-1 italic">Rekomendasi ukuran: 1920x1080px (Max 5MB)</p>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Urutan Slide (Angka)</label>
          <input type="number" name="sort_order" required value="{{ old('sort_order', 0) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
          <p class="text-[10px] text-slate-400 mt-1 italic">Contoh: 1, 2, 3...</p>
        </div>

        <div class="flex items-end pb-2">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary transition-all cursor-pointer">
            <span class="text-sm font-bold text-slate-700">Aktifkan Gambar Ini</span>
          </label>
        </div>
      </div>

      <div class="mt-8 pt-6 border-t border-slate-100 flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-all flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">upload</span> Simpan & Upload
        </button>
      </div>
    </form>
  </div>
@endsection
