@extends('layouts.admin')
@section('admin-title', 'Data Siswa')

@section('admin-content')
  {{-- Stats Row --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-primary/10 rounded-lg">
          <span class="material-symbols-outlined text-primary text-xl">groups</span>
        </div>
        <div>
          <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
          <p class="text-xs text-slate-500 font-medium">Total Siswa</p>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-emerald-100 rounded-lg">
          <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
        </div>
        <div>
          <p class="text-2xl font-bold">{{ $stats['aktif'] }}</p>
          <p class="text-xs text-slate-500 font-medium">Aktif Belajar</p>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-blue-100 rounded-lg">
          <span class="material-symbols-outlined text-blue-600 text-xl">flight_takeoff</span>
        </div>
        <div>
          <p class="text-2xl font-bold">{{ $stats['berangkat'] }}</p>
          <p class="text-xs text-slate-500 font-medium">Sudah Berangkat</p>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-amber-100 rounded-lg">
          <span class="material-symbols-outlined text-amber-600 text-xl">pending</span>
        </div>
        <div>
          <p class="text-2xl font-bold">{{ $stats['proses'] }}</p>
          <p class="text-xs text-slate-500 font-medium">Dalam Proses</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Toolbar --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <span class="material-symbols-outlined text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 text-xl">search</span>
        <input name="search" type="text" placeholder="Cari nama, kota..." value="{{ request('search') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
      </div>
      <select name="program" onchange="this.form.submit()" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer">
        <option value="">Semua Program</option>
        @foreach(['Tokutei Ginou (TG)','Engineering','Kelas Bahasa Jepang','Returnee / Ex Jepang'] as $prog)
          <option {{ request('program')==$prog ? 'selected' : '' }}>{{ $prog }}</option>
        @endforeach
      </select>
      <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer">
        <option value="">Semua Status</option>
        @foreach(['Aktif','Proses','Berangkat','Lulus'] as $stat)
          <option {{ request('status')==$stat ? 'selected' : '' }}>{{ $stat }}</option>
        @endforeach
      </select>
      <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">search</span> Cari
      </button>
    </form>
  </div>

  {{-- Table --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <h4 class="font-bold text-slate-800">Daftar Siswa</h4>
      <a href="{{ route('admin.siswa.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">person_add</span> Tambah Siswa
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4">#</th>
            <th class="px-6 py-4">Nama</th>
            <th class="px-6 py-4">Program</th>
            <th class="px-6 py-4">No. WA</th>
            <th class="px-6 py-4">Kota</th>
            <th class="px-6 py-4">Tgl. Daftar</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Bayar</th>
            <th class="px-6 py-4">Aksi</th>
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
            $paymentClass = [
              'Lunas' => 'bg-emerald-100 text-emerald-700',
              'Pending' => 'bg-amber-100 text-amber-700',
            ];
          @endphp
          @forelse($siswas as $i => $s)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4 text-xs text-slate-400">{{ $i + 1 }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 {{ $avatarColors[$i % 5] }} rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                  {{ mb_substr($s->nama, 0, 1) }}
                </div>
                <span class="font-medium text-sm">{{ $s->nama }}</span>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600 max-w-[140px]">{{ $s->program }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $s->wa }}</td>
            <td class="px-6 py-4 text-sm text-slate-600">{{ $s->kota }}</td>
            <td class="px-6 py-4 text-xs text-slate-400">{{ $s->created_at->format('d/m/Y') }}</td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 rounded-full {{ $statusClass[$s->status] ?? 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wide">{{ $s->status }}</span>
            </td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 rounded-full {{ $paymentClass[$s->payment_status ?? 'Pending'] ?? 'bg-amber-100 text-amber-700' }} text-[10px] font-bold uppercase tracking-wide">{{ $s->payment_status ?? 'Pending' }}</span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-1">
                <a href="{{ route('admin.siswa.show', $s) }}" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-primary transition-colors" title="Detail">
                  <span class="material-symbols-outlined text-lg">visibility</span>
                </a>
                <a href="{{ route('admin.siswa.edit', $s) }}" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-primary transition-colors" title="Edit">
                  <span class="material-symbols-outlined text-lg">edit</span>
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->wa) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-emerald-50 text-slate-500 hover:text-emerald-600 transition-colors" title="WhatsApp">
                  <span class="material-symbols-outlined text-lg">chat</span>
                </a>
                <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus data {{ $s->nama }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-500 hover:text-accent-red transition-colors" title="Hapus">
                    <span class="material-symbols-outlined text-lg">delete</span>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
              <span class="material-symbols-outlined text-4xl mb-2 block">person_off</span>
              Belum ada data siswa.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
