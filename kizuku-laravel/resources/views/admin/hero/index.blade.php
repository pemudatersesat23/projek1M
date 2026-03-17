@extends('layouts.admin')
@section('admin-title', 'Manage Hero Banners')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Banner Hero</h3>
      <p class="text-sm text-slate-500 mt-1">Kelola konten utama yang muncul di bagian paling atas beranda.</p>
    </div>
    <a href="{{ route('admin.hero-sections.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">add</span> Tambah Banner
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Banner</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Konten</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-center">Status</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($heroSections as $hero)
            <tr class="hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4">
                <div class="w-24 h-16 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                  @if($hero->image_path)
                    <img src="{{ asset('storage/' . $hero->image_path) }}" class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                      <span class="material-symbols-outlined">image</span>
                    </div>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="font-bold text-slate-800 text-sm line-clamp-1">{{ $hero->title }}</div>
                <div class="text-slate-500 text-xs mt-1 line-clamp-1">{{ $hero->subtitle }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="flex justify-center">
                  @if($hero->is_active)
                    <span class="px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full border border-green-200">AKTIF</span>
                  @else
                    <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold rounded-full border border-slate-200">DRAFT</span>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.hero-sections.edit', $hero) }}" class="p-2 text-slate-400 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-lg">edit</span>
                  </a>
                  <form action="{{ route('admin.hero-sections.destroy', $hero) }}" method="POST" onsubmit="return confirm('Hapus banner ini?')">
                    @csrf @method('DELETE')
                    <button class="p-2 text-slate-400 hover:text-accent-red transition-colors">
                      <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-12 text-center">
                <div class="text-slate-300 mb-2"><span class="material-symbols-outlined text-4xl">drafts</span></div>
                <p class="text-slate-500 text-sm">Belum ada data banner hero.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
