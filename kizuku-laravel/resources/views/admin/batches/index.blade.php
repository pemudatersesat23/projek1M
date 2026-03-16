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
          @php
            $statusClasses = [
              'akan_dibuka' => 'bg-amber-100 text-amber-700',
              'dibuka'      => 'bg-emerald-100 text-emerald-700',
              'ditutup'     => 'bg-red-100 text-red-700',
              'berjalan'    => 'bg-blue-100 text-blue-700',
              'selesai'     => 'bg-slate-100 text-slate-600',
            ];
          @endphp
          @forelse($batches as $b)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4">
              <div class="font-bold text-slate-800">{{ $b->name }}</div>
              <div class="text-[10px] text-slate-400 uppercase tracking-tight">ID: #{{ $b->id }}</div>
            </td>
            <td class="px-6 py-4">
              <span class="text-sm font-medium text-slate-700 px-2.5 py-1 bg-slate-100 rounded-lg">
                {{ $b->program->name }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600">
              @if($b->registration_start)
                {{ $b->registration_start->format('d M') }} - {{ $b->registration_end->format('d M Y') }}
              @else
                -
              @endif
            </td>
            <td class="px-6 py-4 text-sm text-slate-600">
              {{ $b->class_start ? $b->class_start->format('d/m/Y') : '-' }}
            </td>
            <td class="px-6 py-4">
              <span class="px-2.5 py-1 rounded-full {{ $statusClasses[$b->status] ?? 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wide">
                {{ str_replace('_', ' ', $b->status) }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.batches.edit', $b) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Edit">
                  <span class="material-symbols-outlined">edit</span>
                </a>
                <form action="{{ route('admin.batches.destroy', $b) }}" method="POST" onsubmit="return confirm('Hapus batch ini?')">
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
  </div>
@endsection
