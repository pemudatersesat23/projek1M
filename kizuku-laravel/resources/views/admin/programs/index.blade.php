@extends('layouts.admin')
@section('admin-title', 'Manajemen Program')

@section('admin-content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h3 class="text-slate-800 font-bold text-2xl">Daftar Program</h3>
      <p class="text-slate-500 text-sm">Kelola informasi program pelatihan KIZUKU</p>
    </div>
    <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
      <span class="material-symbols-outlined text-lg">add</span>
      Tambah Program
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">Nama Program</th>
            <th class="px-6 py-4">Durasi</th>
            <th class="px-6 py-4">Biaya</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Batch Aktif</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($programs as $p)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4">
              <div class="font-bold text-slate-800">{{ $p->name }}</div>
              <div class="text-xs text-slate-400">/programs/{{ $p->slug }}</div>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $p->duration ?? '-' }}</td>
            <td class="px-6 py-4 text-sm font-medium text-primary">{{ $p->cost ?? '-' }}</td>
            <td class="px-6 py-4">
              <span class="px-2.5 py-1 rounded-full {{ $p->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wide">
                {{ $p->status }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600">
              {{ $p->batches->where('status', 'dibuka')->count() }} Batch Dibuka
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.programs.edit', $p) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Edit">
                  <span class="material-symbols-outlined">edit</span>
                </a>
                <form action="{{ route('admin.programs.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus program ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-2 text-slate-400 hover:text-accent-red transition-colors hover:bg-red-50 rounded-lg" title="Hapus">
                    <span class="material-symbols-outlined">delete</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-6 py-12 text-center">
              <div class="flex flex-col items-center gap-2">
                <span class="material-symbols-outlined text-4xl text-slate-200">folder_open</span>
                <p class="text-slate-400 text-sm">Belum ada data program.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
