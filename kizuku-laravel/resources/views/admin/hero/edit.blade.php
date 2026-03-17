@extends('layouts.admin')
@section('admin-title', 'Edit Banner Hero')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Edit Banner</h3>
      <p class="text-sm text-slate-500 mt-1">Sesuaikan pengaturan banner hero pilihan Anda.</p>
    </div>
    <a href="{{ route('admin.hero-sections.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <form action="{{ route('admin.hero-sections.update', $heroSection) }}" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf
      @method('PUT')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Judul Banner *</label>
          <input type="text" name="title" required value="{{ old('title', $heroSection->title) }}" placeholder="Wujudkan Karier Impian di Jepang"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Sub-Judul / Deskripsi</label>
          <textarea name="subtitle" rows="3" placeholder="Penjelasan singkat..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">{{ old('subtitle', $heroSection->subtitle) }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Tombol Utama (Text)</label>
          <input type="text" name="btn_primary_text" required value="{{ old('btn_primary_text', $heroSection->btn_primary_text) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Tombol Utama (Link)</label>
          <input type="text" name="btn_primary_link" required value="{{ old('btn_primary_link', $heroSection->btn_primary_link) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Tombol Kedua (Text)</label>
          <input type="text" name="btn_secondary_text" required value="{{ old('btn_secondary_text', $heroSection->btn_secondary_text) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Tombol Kedua (Link)</label>
          <input type="text" name="btn_secondary_link" required value="{{ old('btn_secondary_link', $heroSection->btn_secondary_link) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Ganti Background Banner</label>
          <div class="flex items-center gap-4 mb-3">
             <div class="w-24 h-16 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                @if($heroSection->image_path)
                  <img src="{{ asset('storage/' . $heroSection->image_path) }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <span class="material-symbols-outlined">image</span>
                  </div>
                @endif
             </div>
             <div class="text-[10px] text-slate-500 italic">Pilih file baru untuk mengganti gambar saat ini.</div>
          </div>
          <input type="file" name="image" accept="image/*"
                 class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
        </div>

        <div class="flex items-end pb-2">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ $heroSection->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary transition-all cursor-pointer">
            <span class="text-sm font-bold text-slate-700">Aktifkan Banner Ini</span>
          </label>
        </div>
      </div>

      <div class="mt-8 pt-6 border-t border-slate-100 flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-all flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
@endsection
