@extends('layouts.admin')
@section('admin-title', 'Batch Pendaftaran')

@section('admin-content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h3 class="text-slate-800 font-bold text-2xl">Gelombang Pendaftaran</h3>
      <p class="text-slate-500 text-sm">Atur periode dan kuota pendaftaran batch program</p>
    </div>
    <a href="{{ route('admin.batches.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
      <span class="material-symbols-outlined text-lg">add</span>
      Buka Batch Baru
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">Nama Batch</th>
            <th class="px-6 py-4">Program</th>
            <th class="px-6 py-4">Periode Daftar</th>
            <th class="px-6 py-4">Mulai Kelas</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($batches as $batch)
              <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800">{{ $batch->nama_batch }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $batch->program->nama_program }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                  {{ $batch->tanggal_buka?->format('d/m/Y') }} - {{ $batch->tanggal_tutup?->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-bold rounded-full 
                    @if($batch->status === 'dibuka') bg-green-100 text-green-600 
                    @elseif($batch->status === 'akan_dibuka') bg-blue-100 text-blue-600 
                    @else bg-slate-100 text-slate-500 @endif">
                    {{ str_replace('_', ' ', ucfirst($batch->status)) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $batch->applicants()->count() }} / {{ $batch->kuota ?? '∞' }}</td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.batches.edit', $batch) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Edit">
                  <span class="material-symbols-outlined">edit</span>
                </a>
                <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST" onsubmit="return confirm('Hapus batch ini?')">
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
                <span class="material-symbols-outlined text-4xl text-slate-200">layers_clear</span>
                <p class="text-slate-400 text-sm">Belum ada data batch pendaftaran.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($batches->hasPages())
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
      {{ $batches->links() }}
    </div>
    @endif
  </div>
@endsection
