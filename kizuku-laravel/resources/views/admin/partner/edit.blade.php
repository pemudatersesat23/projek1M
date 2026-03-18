@extends('layouts.admin')
@section('admin-title', 'Edit Kampus Partner')

@section('admin-content')
  <div class="mb-6">
    <a href="{{ route('admin.partner-campus.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 w-max mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali
    </a>
    <h3 class="text-lg font-bold text-slate-800">Edit Kampus Partner</h3>
    <p class="text-sm text-slate-500 mt-1">Perbarui data atau ganti logo kampus partner.</p>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.partner-campus.update', $partnerCampus) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      <div class="mb-5">
        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nama Kampus *</label>
        <input type="text" name="name" required value="{{ old('name', $partnerCampus->getTranslation('name', 'id', false) ?: $partnerCampus->name) }}"
               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        <p class="text-xs text-slate-400 mt-2">Nama akan diterjemahkan ke bahasa Jepang secara otomatis.</p>
        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
      </div>

      <div class="mb-5">
        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Deskripsi Kampus *</label>
        <textarea name="description" rows="3" required
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">{{ old('description', $partnerCampus->getTranslation('description', 'id', false)) }}</textarea>
        <p class="text-xs text-slate-400 mt-2">Maksimal 3-4 baris. Deskripsi akan diterjemahkan ke bahasa Jepang secara otomatis.</p>
        @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Logo Kampus (Saat Ini)</label>
          <div class="mb-3 p-3 bg-slate-50 rounded-lg border border-slate-200 w-max">
              <img src="{{ asset($partnerCampus->logo) }}" alt="Logo {{ $partnerCampus->name }}" class="h-24 object-contain">
          </div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Ganti Logo (Opsional)</label>
          <input type="file" name="logo" accept="image/*"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
          <p class="text-xs text-slate-400 mt-2"><b>Harus Rasio 1:1</b> (Kotak, misal: 400x400px). Format: JPG, PNG. Max: 2MB.</p>
          @error('logo') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Gambar Banner (Saat Ini)</label>
          <div class="mb-3 p-1 bg-slate-50 rounded-lg border border-slate-200 w-max overflow-hidden">
              @if($partnerCampus->banner)
                <img src="{{ asset($partnerCampus->banner) }}" alt="Banner {{ $partnerCampus->name }}" class="h-24 w-auto object-cover rounded">
              @else
                <div class="h-24 w-40 flex items-center justify-center bg-slate-200 rounded text-slate-400 text-xs text-center p-2">Belum ada banner</div>
              @endif
          </div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Ganti Banner (Opsional)</label>
          <input type="file" name="banner" accept="image/*"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
          <p class="text-xs text-slate-400 mt-2"><b>Harus Rasio 16:9</b> (Landscape, misal: 800x450px). Format: JPG, PNG. Max: 2MB.</p>
          @error('banner') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
      </div>

      <hr class="my-6 border-slate-100">

      <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
      </button>
    </form>
  </div>
@endsection
