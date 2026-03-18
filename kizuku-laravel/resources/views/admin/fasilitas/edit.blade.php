@extends('layouts.admin')
@section('admin-title', 'Edit Fasilitas')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Edit Fasilitas</h3>
      <p class="text-sm text-slate-500 mt-1">Perbarui informasi fasilitas.</p>
    </div>
    <a href="{{ route('admin.fasilitas.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">edit_note</span> Perbarui Fasilitas
      </h4>
    </div>

    @if($errors->any())
      <div class="mx-6 mt-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
        <span class="material-symbols-outlined text-accent-red">error</span>
        <span class="text-sm font-medium text-accent-red">{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.fasilitas.update', $fasilitas) }}" class="p-6" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nama Fasilitas *</label>
          <input type="text" name="nama" required value="{{ old('nama', $fasilitas->nama) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Urutan Tampil</label>
          <input type="number" name="urutan" value="{{ old('urutan', $fasilitas->urutan) }}" min="0"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Foto Fasilitas</label>
          @if($fasilitas->image)
            <div class="mb-3">
              <img src="{{ asset('storage/' . $fasilitas->image) }}" alt="Foto" class="h-28 w-auto rounded-lg border object-cover">
            </div>
          @endif
          <input type="file" name="image" accept="image/*"
                 class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none cursor-pointer">
          <p class="text-[11px] text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG (Maks 5MB)</p>
        </div>
      </div>
      <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
      </button>
    </form>
  </div>
@endsection
