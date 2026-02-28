@extends('layouts.admin')
@section('admin-title', 'Detail Siswa')

@section('admin-content')
  {{-- Back Button --}}
  <div class="mb-6">
    <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-primary transition-colors font-medium">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Data Siswa
    </a>
  </div>

  {{-- Profile Header --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
      @php
        $avatarColors = ['bg-primary','bg-accent-red','bg-emerald-600','bg-amber-500','bg-violet-600'];
        $color = $avatarColors[$siswa->id % 5];
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
      <div class="w-16 h-16 {{ $color }} rounded-full flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
        {{ mb_substr($siswa->nama, 0, 1) }}
      </div>
      <div class="flex-1">
        <h3 class="text-xl font-bold text-slate-800">{{ $siswa->nama }}</h3>
        <p class="text-sm text-slate-500 mt-1">Terdaftar: {{ $siswa->created_at->format('d F Y') }}</p>
        <div class="flex flex-wrap gap-2 mt-3">
          <span class="px-3 py-1 rounded-full {{ $statusClass[$siswa->status] ?? 'bg-slate-100 text-slate-600' }} text-xs font-bold uppercase tracking-wide">{{ $siswa->status }}</span>
          <span class="px-3 py-1 rounded-full {{ $paymentClass[$siswa->payment_status ?? 'Pending'] ?? 'bg-amber-100 text-amber-700' }} text-xs font-bold uppercase tracking-wide flex items-center gap-1">
            <span class="material-symbols-outlined text-xs">payments</span>
            {{ $siswa->payment_status ?? 'Pending' }}
          </span>
        </div>
      </div>
      <div class="flex flex-wrap gap-2 sm:ml-auto">
        <a href="{{ route('admin.siswa.edit', $siswa) }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">edit</span> Edit
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siswa->wa) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">chat</span> WhatsApp
        </a>
        <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus data {{ $siswa->nama }}?')">
          @csrf @method('DELETE')
          <button type="submit" class="px-4 py-2 border border-red-200 text-accent-red rounded-lg text-sm font-medium hover:bg-red-50 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">delete</span> Hapus
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Data Cards --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Data Personal --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">person</span> Data Personal
        </h4>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">Nama Lengkap</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->nama }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">No. WhatsApp</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->wa }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">Email</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->email ?? '-' }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">Tanggal Lahir</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->tgl_lahir ? $siswa->tgl_lahir->format('d/m/Y') : '-' }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">Kota Asal</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->kota }}</span>
        </div>
        <div class="flex justify-between items-center py-2">
          <span class="text-sm text-slate-500">Pendidikan</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->pendidikan ?? '-' }}</span>
        </div>
      </div>
    </div>

    {{-- Data Program --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">school</span> Data Program
        </h4>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">Program</span>
          <span class="text-sm font-semibold text-slate-800">{{ $siswa->program }}</span>
        </div>

        @php $extra = $siswa->extra_fields ? json_decode($siswa->extra_fields, true) : []; @endphp

        @if($siswa->program === 'Engineering')
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-sm text-slate-500">Jurusan</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['jurusan'] ?? '-' }}</span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-sm text-slate-500">IPK</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['ipk'] ?? '-' }}</span>
          </div>
          <div class="flex justify-between items-center py-2">
            <span class="text-sm text-slate-500">Skill Software</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['skill_software'] ?? '-' }}</span>
          </div>
        @elseif($siswa->program === 'Tokutei Ginou (TG)')
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-sm text-slate-500">Level Bahasa</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['level_bahasa'] ?? '-' }}</span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-sm text-slate-500">Sertifikat Skill</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['sertifikat_skill'] ?? '-' }}</span>
          </div>
          <div class="flex justify-between items-center py-2">
            <span class="text-sm text-slate-500">Bidang Keahlian</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['bidang'] ?? '-' }}</span>
          </div>
        @elseif($siswa->program === 'Returnee / Ex Jepang')
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-sm text-slate-500">Perusahaan di Jepang</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['perusahaan'] ?? '-' }}</span>
          </div>
          <div class="flex justify-between items-center py-2">
            <span class="text-sm text-slate-500">Lama Kontrak</span>
            <span class="text-sm font-semibold text-slate-800">{{ $extra['lama_kontrak'] ?? '-' }}</span>
          </div>
        @endif

        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-sm text-slate-500">Status</span>
          <span class="px-2 py-1 rounded-full {{ $statusClass[$siswa->status] ?? 'bg-slate-100 text-slate-600' }} text-[10px] font-bold uppercase tracking-wide">{{ $siswa->status }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Catatan --}}
  @if($siswa->catatan)
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">sticky_note_2</span> Catatan
      </h4>
    </div>
    <div class="p-6">
      <p class="text-sm text-slate-600 leading-relaxed">{{ $siswa->catatan }}</p>
    </div>
  </div>
  @endif

  {{-- Payment Status --}}
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">payments</span> Status Pembayaran
      </h4>
    </div>
    <div class="p-6">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          @if(($siswa->payment_status ?? 'Pending') === 'Lunas')
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
              <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            </div>
            <div>
              <p class="text-sm font-semibold text-emerald-700">Lunas — Pembayaran Terverifikasi</p>
              <p class="text-xs text-slate-400">Diverifikasi oleh admin</p>
            </div>
          @else
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
              <span class="material-symbols-outlined text-amber-600">schedule</span>
            </div>
            <div>
              <p class="text-sm font-semibold text-amber-700">Pending — Menunggu Pembayaran</p>
              <p class="text-xs text-slate-400">Belum diverifikasi</p>
            </div>
          @endif
        </div>
        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}">
          @csrf @method('PUT')
          <input type="hidden" name="nama" value="{{ $siswa->nama }}">
          <input type="hidden" name="wa" value="{{ $siswa->wa }}">
          <input type="hidden" name="kota" value="{{ $siswa->kota }}">
          <input type="hidden" name="program" value="{{ $siswa->program }}">
          @if(($siswa->payment_status ?? 'Pending') === 'Pending')
            <input type="hidden" name="payment_status" value="Lunas">
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center gap-2">
              <span class="material-symbols-outlined text-lg">verified</span> Verifikasi Lunas
            </button>
          @else
            <input type="hidden" name="payment_status" value="Pending">
            <button type="submit" class="px-4 py-2 border border-amber-300 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-50 transition-colors flex items-center gap-2">
              <span class="material-symbols-outlined text-lg">undo</span> Set Pending
            </button>
          @endif
        </form>
      </div>
    </div>
  </div>
@endsection
