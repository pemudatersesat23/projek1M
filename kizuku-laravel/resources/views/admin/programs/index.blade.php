@extends('layouts.admin')
@section('admin-title', 'Manajemen Program')

@section('admin-content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h3 class="text-slate-800 font-bold text-2xl">{{ __('messages.nav.program') }}</h3>
      <p class="text-slate-500 text-sm">Kelola Program Pelatihan</p>
    </div>
    <a href="{{ route('admin.programs.create') }}" class="px-4 py-2 bg-primary text-white font-bold rounded-lg shadow hover:bg-primary/90 flex items-center gap-2">
      <span class="material-symbols-outlined text-sm">add</span> Tambah Program
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">Urutan</th>
            <th class="px-6 py-4">Program & Slug</th>
            <th class="px-6 py-4">Status & Fitur</th>
            <th class="px-6 py-4 text-center">Batch</th>
            <th class="px-6 py-4 text-center">Skema</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($programs as $program)
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-bold">
                  {{ $program->sort_order }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span class="text-sm font-bold text-slate-800">
                      {{ $program->getTranslation('nama_program', app()->getLocale()) ?: $program->nama_program }}
                    </span>
                    <span class="text-xs text-slate-400 font-mono">{{ $program->slug }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col gap-1 items-start">
                    <span class="px-2 py-1 text-xs font-bold rounded-full {{ $program->status === 'aktif' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                      {{ ucfirst($program->status) }}
                    </span>
                    @if($program->is_featured)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-700">Unggulan</span>
                    @endif
                    @if($program->has_schema)
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-blue-100 text-blue-700">Has Schema</span>
                    @endif
                  </div>
                </td>
                <td class="px-6 py-4 text-center text-sm font-bold text-slate-700">
                  {{ $program->batches_count }}
                </td>
                <td class="px-6 py-4 text-center text-sm font-bold text-slate-700">
                  {{ $program->program_schemas_count }}
                </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.programs.edit', $program) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Edit">
                  <span class="material-symbols-outlined">edit</span>
                </a>
                <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" data-confirm="Hapus program ini secara logikal (Soft Delete)?" data-confirm-type="warning" data-confirm-text="Ya, hapus">
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
    @if($programs->hasPages())
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
      {{ $programs->links() }}
    </div>
    @endif
  </div>
@endsection
