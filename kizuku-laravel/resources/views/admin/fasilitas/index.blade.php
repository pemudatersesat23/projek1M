@extends('layouts.admin')
@section('admin-title', 'Kelola Fasilitas')

@section('admin-content')
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Kelola Fasilitas</h3>
      <p class="text-sm text-slate-500 mt-1">Tambah atau kelola fasilitas yang tampil di halaman publik.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-5 p-4 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center gap-3">
      <span class="material-symbols-outlined text-emerald-600">check_circle</span>
      <span class="text-sm font-medium text-emerald-700">{{ session('success') }}</span>
    </div>
  @endif

  {{-- Add Form --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">add_circle</span> Tambah Fasilitas Baru
      </h4>
    </div>

    @if($errors->any())
      <div class="mx-6 mt-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
        <span class="material-symbols-outlined text-accent-red">error</span>
        <span class="text-sm font-medium text-accent-red">{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.fasilitas.store') }}" class="p-6" enctype="multipart/form-data">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nama Fasilitas *</label>
          <input type="text" name="nama" placeholder="Contoh: Ruang Kelas Modern" required value="{{ old('nama') }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Urutan Tampil</label>
          <input type="number" name="urutan" placeholder="0" value="{{ old('urutan', 0) }}" min="0"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          <p class="text-[11px] text-slate-400 mt-1">Angka lebih kecil tampil lebih awal</p>
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Foto Fasilitas</label>
          <input type="file" name="image" accept="image/*"
                 class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none cursor-pointer">
          <p class="text-[11px] text-slate-500 mt-1">Format: JPG, PNG, GIF (Maks 5MB)</p>
        </div>
      </div>
      <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">add_photo_alternate</span> Tambah Fasilitas
      </button>
    </form>
  </div>

  {{-- Fasilitas List --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($fasilitas as $item)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
      <div class="w-full h-40 bg-slate-100 overflow-hidden">
        @if($item->image)
          <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
          <div class="w-full h-full flex items-center justify-center text-slate-300">
            <span class="material-symbols-outlined text-5xl">image</span>
          </div>
        @endif
      </div>
      <div class="p-4">
        <h5 class="font-bold text-sm text-slate-800 mb-1">{{ $item->nama }}</h5>
        <p class="text-[11px] text-slate-400 mb-3">Urutan: {{ $item->urutan }}</p>
        <div class="flex items-center gap-2">
          <a href="{{ route('admin.fasilitas.edit', $item) }}" class="flex-1 text-center px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-center gap-1">
            <span class="material-symbols-outlined text-sm">edit</span> Edit
          </a>
          <button type="button"
                  onclick="if(confirm('Hapus fasilitas ini?')) document.getElementById('del-{{ $item->id }}').submit();"
                  class="flex-1 px-3 py-1.5 border border-red-200 text-accent-red rounded-lg text-xs font-medium hover:bg-red-50 transition-colors flex items-center justify-center gap-1">
            <span class="material-symbols-outlined text-sm">delete</span> Hapus
          </button>
        </div>
        <form id="del-{{ $item->id }}" method="POST" action="{{ route('admin.fasilitas.destroy', $item) }}">
          @csrf @method('DELETE')
        </form>
      </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center text-slate-400">
      <span class="material-symbols-outlined text-4xl mb-2 block">apartment</span>
      Belum ada fasilitas. Tambahkan fasilitas pertama Anda di atas.
    </div>
    @endforelse
  </div>
@endsection
