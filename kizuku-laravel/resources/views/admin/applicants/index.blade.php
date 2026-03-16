@extends('layouts.admin')
@section('admin-title', 'Daftar Pendaftar')

@section('admin-content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h3 class="text-slate-800 font-bold text-2xl">Daftar Pendaftar</h3>
      <p class="text-slate-500 text-sm">Kelola dan review calon peserta program</p>
    </div>
  </div>

  {{-- Filter Section --}}
  <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-8">
    <form action="{{ route('admin.applicants.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Program</label>
        <select name="program_id" class="w-full rounded-lg border-slate-200 text-sm">
          <option value="">Semua Program</option>
          @foreach($programs as $p)
            <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_program }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Batch</label>
        <select name="batch_id" class="w-full rounded-lg border-slate-200 text-sm">
          <option value="">Semua Batch</option>
          @foreach($batches as $b)
            <option value="{{ $b->id }}" {{ request('batch_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_batch }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
          <option value="">Semua Status</option>
          <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
          <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
          <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview</option>
          <option value="lolos" {{ request('status') == 'lolos' ? 'selected' : '' }}>Lolos</option>
          <option value="tidak_lolos" {{ request('status') == 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
        </select>
      </div>
      <div class="flex items-end">
        <button type="submit" class="w-full py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-700 transition-colors">
          Filter
        </button>
      </div>
    </form>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">Nama Pendaftar</th>
            <th class="px-6 py-4">Program / Batch</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Tgl. Daftar</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @php
            $statusClass = [
              'baru' => 'bg-blue-100 text-blue-700',
              'review' => 'bg-amber-100 text-amber-700',
              'lolos' => 'bg-emerald-100 text-emerald-700',
              'tidak_lolos' => 'bg-red-100 text-red-700',
              'interview' => 'bg-primary/10 text-primary',
            ];
          @endphp
          @forelse($applicants as $a)
          <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-800">{{ $a->nama }}</span>
                <span class="text-xs text-slate-400">{{ $a->email }}</span>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-col">
                <span class="text-sm font-medium text-slate-700">{{ $a->program->nama_program }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $a->batch->nama_batch }}</span>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase {{ $statusClass[$a->status_seleksi] ?? 'bg-slate-100 text-slate-500' }}">
                {{ str_replace('_', ' ', $a->status_seleksi) }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-slate-500">
              {{ $a->created_at->format('d/m/Y H:i') }}
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.applicants.show', $a) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Detail">
                  <span class="material-symbols-outlined">visibility</span>
                </a>
                <form action="{{ route('admin.applicants.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus data pendaftar ini?')">
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
            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada pendaftar.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($applicants->hasPages())
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
      {{ $applicants->links() }}
    </div>
    @endif
  </div>
@endsection
