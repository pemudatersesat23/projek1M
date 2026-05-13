@extends('layouts.admin')
@section('admin-title', 'Manajemen Program Schema')

@section('admin-content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h3 class="text-slate-800 font-bold text-2xl">Program Schemas</h3>
      <p class="text-slate-500 text-sm">Kelola skema pendaftaran seperti Beasiswa atau Reguler</p>
    </div>
    <a href="{{ route('admin.program-schemas.create') }}" class="px-4 py-2 bg-primary text-white font-bold rounded-lg shadow hover:bg-primary/90 flex items-center gap-2">
      <span class="material-symbols-outlined text-sm">add</span> Tambah Skema
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">Urutan</th>
            <th class="px-6 py-4">Nama Skema</th>
            <th class="px-6 py-4">Program & Batch</th>
            <th class="px-6 py-4">Tipe & Harga</th>
            <th class="px-6 py-4 text-center">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($schemas as $schema)
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-bold">
                  {{ $schema->sort_order }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="text-sm font-bold text-slate-800">
                      {{ $schema->getTranslation('nama_skema', app()->getLocale()) ?: $schema->nama_skema }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">{{ $schema->slug }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="text-sm text-slate-700">{{ $schema->program?->nama_program ?? 'Program tidak tersedia' }}</span>
                    @if($schema->batch)
                        <span class="text-xs text-blue-600">Khusus: {{ $schema->batch->nama_batch }}</span>
                    @else
                        <span class="text-xs text-slate-400">Semua Batch</span>
                    @endif
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  <div class="flex flex-col gap-1 items-start">
                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-indigo-50 text-indigo-700">
                        {{ strtoupper(str_replace('_', ' ', $schema->tipe)) }}
                    </span>
                    <span class="font-bold text-slate-700">{{ $schema->formattedPrice() }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span class="px-2 py-1 text-xs font-bold rounded-full {{ $schema->status === 'aktif' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                    {{ ucfirst($schema->status) }}
                  </span>
                </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.program-schemas.edit', $schema) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Edit">
                  <span class="material-symbols-outlined">edit</span>
                </a>
                <form action="{{ route('admin.program-schemas.destroy', $schema) }}" method="POST" onsubmit="return confirm('Hapus skema ini secara logikal (Soft Delete)?')">
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
                <p class="text-slate-400 text-sm">Belum ada data skema program.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($schemas->hasPages())
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
      {{ $schemas->links() }}
    </div>
    @endif
  </div>
@endsection
