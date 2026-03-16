@extends('layouts.admin')
@section('admin-title', 'Dashboard')

@section('admin-content')
  {{-- Top Stat Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="p-2 bg-primary/10 rounded-lg">
          <span class="material-symbols-outlined text-primary">bar_chart</span>
        </div>
        @if($stats['growth_total'] != 0)
        <span class="{{ $stats['growth_total'] >= 0 ? 'text-emerald-500' : 'text-accent-red' }} text-xs font-bold flex items-center gap-1">
          <span class="material-symbols-outlined text-xs">{{ $stats['growth_total'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
          {{ $stats['growth_total'] > 0 ? '+' : '' }}{{ $stats['growth_total'] }}%
        </span>
        @endif
      </div>
      <p class="text-slate-500 text-sm font-medium">Total Pendaftar</p>
      <h3 class="text-3xl font-bold mt-1">{{ $stats['total'] }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="p-2 bg-blue-50 rounded-lg">
          <span class="material-symbols-outlined text-blue-500">assignment</span>
        </div>
      </div>
      <p class="text-slate-500 text-sm font-medium">Jumlah Program</p>
      <h3 class="text-3xl font-bold mt-1">{{ $stats['program'] }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="p-2 bg-amber-50 rounded-lg">
          <span class="material-symbols-outlined text-amber-500">layers</span>
        </div>
      </div>
      <p class="text-slate-500 text-sm font-medium">Batch Dibuka</p>
      <h3 class="text-3xl font-bold mt-1">{{ $stats['batch_aktif'] }}</h3>
    </div>
  </div>

  {{-- Status Row --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Lolos Seleksi</p>
      <h3 class="text-2xl font-bold text-emerald-600">{{ $stats['lolos'] }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Pendaftar Baru</p>
      <h3 class="text-2xl font-bold text-blue-600">{{ $stats['baru'] }}</h3>
    </div>
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Minggu Ini</p>
      <h3 class="text-2xl font-bold text-primary">{{ $stats['minggu_ini'] }}</h3>
    </div>
  </div>

  {{-- Charts Row --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- Distribusi Program --}}
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <h4 class="font-bold text-slate-800">Pendaftar per Program</h4>
      </div>
      <div class="space-y-5">
        @foreach($programStats as $prog)
        <div>
          <div class="flex justify-between text-sm mb-1.5">
            <span class="text-slate-600 font-medium">{{ $prog['nama'] }}</span>
            <span class="text-slate-900 font-bold">{{ $prog['jumlah'] }} Pendaftar</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $prog['persen'] }}%"></div>
          </div>
        </div>
        @endforeach

        @if(count($programStats) == 0)
          <p class="text-slate-400 text-sm text-center py-4">Belum ada data pendaftar.</p>
        @endif
      </div>
    </div>

    {{-- Status Seleksi --}}
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <h4 class="font-bold text-slate-800 mb-6">Status Seleksi Dasar</h4>
      <div class="flex items-center justify-around h-[200px]">
        <div class="relative w-40 h-40">
          @php
            $lolosPercent = $stats['total'] > 0 ? round(($stats['lolos'] / $stats['total']) * 100) : 0;
            $otherPercent = 100 - $lolosPercent;
          @endphp
          <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
            <path class="stroke-slate-100 fill-none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-width="4"></path>
            <path class="stroke-emerald-500 fill-none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="{{ $lolosPercent }}, 100" stroke-linecap="round" stroke-width="4"></path>
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold">{{ $stats['total'] }}</span>
            <span class="text-[10px] uppercase tracking-wider text-slate-400">Pendaftar</span>
          </div>
        </div>
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            <div>
              <p class="text-xs text-slate-500">Lolos</p>
              <p class="text-sm font-bold">{{ $stats['lolos'] }} ({{ $lolosPercent }}%)</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-slate-100"></div>
            <div>
              <p class="text-xs text-slate-500">Lainnya</p>
              <p class="text-sm font-bold">{{ $stats['total'] - $stats['lolos'] }} ({{ $otherPercent }}%)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Pendaftar Terbaru --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <h4 class="font-bold text-slate-800">Pendaftar Terbaru</h4>
      <a href="{{ route('admin.applicants.index') }}" class="text-sm text-primary font-medium hover:underline flex items-center gap-1">
        Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-3">Nama</th>
            <th class="px-6 py-3">Program / Batch</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Tgl. Daftar</th>
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
          @foreach($applicantsTerbaru as $a)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-3">
              <span class="font-medium text-sm">{{ $a->nama }}</span>
            </td>
            <td class="px-6 py-3 text-sm text-slate-600">
              {{ $a->program->nama_program }}<br>
              <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $a->batch->nama_batch }}</span>
            </td>
            <td class="px-6 py-3">
              <span class="px-2 py-1 rounded-full {{ $statusClass[$a->status_seleksi] ?? 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wide">{{ str_replace('_', ' ', $a->status_seleksi) }}</span>
            </td>
            <td class="px-6 py-3 text-sm text-slate-500">{{ $a->created_at->format('d/m/Y') }}</td>
          </tr>
          @endforeach

          @if($applicantsTerbaru->isEmpty())
          <tr>
            <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada pendaftar baru.</td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
@endsection
