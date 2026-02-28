@extends('layouts.admin')
@section('admin-title', 'Dashboard')

@section('admin-content')
  {{-- Top Stat Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
        <div class="p-2 bg-emerald-100 rounded-lg">
          <span class="material-symbols-outlined text-emerald-600">check_circle</span>
        </div>
      </div>
      <p class="text-slate-500 text-sm font-medium">Lunas Bayar</p>
      <h3 class="text-3xl font-bold mt-1">{{ $stats['lunas'] }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="p-2 bg-amber-100 rounded-lg">
          <span class="material-symbols-outlined text-amber-600">schedule</span>
        </div>
      </div>
      <p class="text-slate-500 text-sm font-medium">Pending Bayar</p>
      <h3 class="text-3xl font-bold mt-1">{{ $stats['pending'] }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex justify-between items-start mb-4">
        <div class="p-2 bg-blue-100 rounded-lg">
          <span class="material-symbols-outlined text-blue-600">new_releases</span>
        </div>
      </div>
      <p class="text-slate-500 text-sm font-medium">Baru Minggu Ini</p>
      <h3 class="text-3xl font-bold mt-1">{{ $stats['minggu_ini'] }}</h3>
    </div>
  </div>

  {{-- Charts Row --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- Distribusi Program --}}
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <h4 class="font-bold text-slate-800">Distribusi Program</h4>
      </div>
      <div class="space-y-5">
        @php
          $programColors = [
            'Engineering' => 'bg-primary',
            'Tokutei Ginou (TG)' => 'bg-accent-red',
            'Kelas Bahasa Jepang' => 'bg-amber-400',
            'Returnee / Ex Jepang' => 'bg-emerald-500',
          ];
        @endphp
        @foreach($programStats as $prog)
        <div>
          <div class="flex justify-between text-sm mb-1.5">
            <span class="text-slate-600 font-medium">{{ $prog['nama'] }}</span>
            <span class="text-slate-900 font-bold">{{ $prog['persen'] }}%</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="{{ $programColors[$prog['nama']] ?? 'bg-slate-400' }} h-full rounded-full transition-all duration-500" style="width: {{ $prog['persen'] }}%"></div>
          </div>
        </div>
        @endforeach

        @if(count($programStats) == 0)
          <p class="text-slate-400 text-sm text-center py-4">Belum ada data pendaftar.</p>
        @endif
      </div>
    </div>

    {{-- Status Pembayaran --}}
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
      <h4 class="font-bold text-slate-800 mb-6">Status Pembayaran</h4>
      <div class="flex items-center justify-around h-[200px]">
        <div class="relative w-40 h-40">
          @php
            $lunasPercent = $stats['total'] > 0 ? round(($stats['lunas'] / $stats['total']) * 100) : 0;
            $pendingPercent = $stats['total'] > 0 ? 100 - $lunasPercent : 0;
          @endphp
          <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
            <path class="stroke-slate-100 fill-none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-width="4"></path>
            <path class="stroke-primary fill-none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="{{ $lunasPercent }}, 100" stroke-linecap="round" stroke-width="4"></path>
            <path class="stroke-amber-400 fill-none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="{{ $pendingPercent }}, 100" stroke-dashoffset="-{{ $lunasPercent }}" stroke-linecap="round" stroke-width="4"></path>
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold">{{ $stats['total'] }}</span>
            <span class="text-[10px] uppercase tracking-wider text-slate-400">Total</span>
          </div>
        </div>
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-primary"></div>
            <div>
              <p class="text-xs text-slate-500">Lunas</p>
              <p class="text-sm font-bold">{{ $stats['lunas'] }} ({{ $lunasPercent }}%)</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-amber-400"></div>
            <div>
              <p class="text-xs text-slate-500">Pending</p>
              <p class="text-sm font-bold">{{ $stats['pending'] }} ({{ $pendingPercent }}%)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Laporan Bulanan --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <h4 class="font-bold text-slate-800">Laporan Bulanan</h4>
      <a href="{{ route('admin.siswa.index') }}" class="text-sm text-primary font-medium hover:underline flex items-center gap-1">
        Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">Bulan</th>
            <th class="px-6 py-4">Total Pendaftar</th>
            <th class="px-6 py-4">Lunas</th>
            <th class="px-6 py-4">Pending</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($laporanBulanan as $lap)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4 font-medium text-slate-700">{{ $lap['bulan'] }}</td>
            <td class="px-6 py-4">{{ $lap['total'] }}</td>
            <td class="px-6 py-4 text-emerald-600 font-semibold">{{ $lap['lunas'] }}</td>
            <td class="px-6 py-4 text-amber-600 font-semibold">{{ $lap['pending'] }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pendaftar Terbaru --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <h4 class="font-bold text-slate-800">Pendaftar Terbaru</h4>
      <a href="{{ route('admin.siswa.index') }}" class="text-sm text-primary font-medium hover:underline flex items-center gap-1">
        Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-3">Nama</th>
            <th class="px-6 py-3">Program</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Tgl. Daftar</th>
            <th class="px-6 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @php
            $avatarColors = ['bg-primary','bg-accent-red','bg-emerald-600','bg-amber-500','bg-violet-600'];
            $statusClass = [
              'Aktif' => 'bg-emerald-100 text-emerald-700',
              'Proses' => 'bg-amber-100 text-amber-700',
              'Berangkat' => 'bg-blue-100 text-blue-700',
              'Lulus' => 'bg-primary/10 text-primary',
            ];
          @endphp
          @foreach($siswasTerbaru as $i => $s)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-3">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 {{ $avatarColors[$i % 5] }} rounded-full flex items-center justify-center text-white text-xs font-bold">
                  {{ mb_substr($s->nama, 0, 1) }}
                </div>
                <span class="font-medium text-sm">{{ $s->nama }}</span>
              </div>
            </td>
            <td class="px-6 py-3 text-sm text-slate-600">{{ $s->program }}</td>
            <td class="px-6 py-3">
              <span class="px-2 py-1 rounded-full {{ $statusClass[$s->status] ?? 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wide">{{ $s->status }}</span>
            </td>
            <td class="px-6 py-3 text-sm text-slate-500">{{ $s->created_at->format('d/m/Y') }}</td>
            <td class="px-6 py-3">
              <a href="{{ route('admin.siswa.show', $s) }}" class="text-primary hover:underline text-sm font-medium">Detail</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
