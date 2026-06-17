@extends('layouts.admin')
@section('admin-title', 'Kelola Galeri')

@section('admin-content')
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Kelola Galeri Foto</h3>
      <p class="text-sm text-slate-500 mt-1">Tambah atau kelola foto yang tampil di slider galeri halaman utama.</p>
    </div>
    <a href="{{ route('admin.galleries.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2 w-fit">
      <span class="material-symbols-outlined text-lg">add_circle</span> Tambah Foto
    </a>
  </div>

  {{-- Gallery List --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($galleries as $g)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
      <div class="aspect-video bg-slate-100 relative overflow-hidden">
        <img src="{{ asset('storage/' . $g->image) }}" alt="Gallery" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
          <a href="{{ route('admin.galleries.edit', $g) }}" class="w-10 h-10 bg-white text-slate-800 rounded-full flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
            <span class="material-symbols-outlined text-lg">edit</span>
          </a>
          <form method="POST" action="{{ route('admin.galleries.destroy', $g) }}" data-confirm="Hapus foto ini?" data-confirm-type="warning" data-confirm-text="Ya, hapus">
            @csrf @method('DELETE')
            <button type="submit" class="w-10 h-10 bg-white text-accent-red rounded-full flex items-center justify-center hover:bg-accent-red hover:text-white transition-colors">
              <span class="material-symbols-outlined text-lg">delete</span>
            </button>
          </form>
        </div>
        @if(!$g->is_active)
          <div class="absolute top-2 left-2 px-2 py-0.5 bg-slate-800/80 text-white text-[10px] font-bold rounded uppercase tracking-wider">Draft / Inactive</div>
        @endif
      </div>
      <div class="p-4">
        <div class="flex items-center justify-between mb-1">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Urutan: {{ $g->order }}</span>
          <span class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full {{ $g->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            <span class="text-[10px] font-medium text-slate-500">{{ $g->is_active ? 'Aktif' : 'Nonaktif' }}</span>
          </span>
        </div>
        <p class="text-sm font-semibold text-slate-800 truncate">{{ $g->title ?: 'Tanpa Judul' }}</p>
      </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center text-slate-400">
      <span class="material-symbols-outlined text-4xl mb-2 block">image</span>
      Belum ada foto galeri.
    </div>
    @endforelse
  </div>

  <div class="mt-8">
    {{ $galleries->links() }}
  </div>
@endsection
