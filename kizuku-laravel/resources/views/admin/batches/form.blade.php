@extends('layouts.admin')
@section('admin-title', isset($batch) ? 'Edit Batch' : 'Buka Batch Baru')

@section('admin-content')
  <div class="mb-8">
    <a href="{{ route('admin.batches.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
    </a>
    <h3 class="text-slate-800 font-bold text-2xl">{{ isset($batch) ? 'Edit Batch: ' . $batch->nama_batch : 'Buka Gelombang Pendaftaran Baru' }}</h3>
  </div>

  <form action="{{ isset($batch) ? route('admin.batches.update', $batch) : route('admin.batches.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    @if(isset($batch)) @method('PUT') @endif

    <div class="lg:col-span-2 space-y-6">
      {{-- Informasi Batch --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">layers</span> Detail Batch
        </h4>
        <div class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Program <span class="text-accent-red">*</span></label>
              <select name="program_id" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
                <option value="">-- Pilih Program --</option>
                @foreach($programs as $p)
                  <option value="{{ $p->id }}" {{ old('program_id', $batch->program_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama_program }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama Batch <span class="text-accent-red">*</span></label>
              <input type="text" name="nama_batch" value="{{ old('nama_batch', $batch->nama_batch ?? '') }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Batch 1 - 2024">
            </div>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Kuota Peserta</label>
              <input type="number" name="kuota" value="{{ old('kuota', $batch->kuota ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: 30">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Link Form Eksternal (Opsional)</label>
              <input type="url" name="link_form" value="{{ old('link_form', $batch->link_form ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="https://forms.gle/...">
            </div>
          </div>
        </div>
      </div>

      {{-- Jadwal --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">calendar_month</span> Penjadwalan
        </h4>
        <div class="space-y-6">
          <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Masa Pendaftaran</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal Buka</label>
                <input type="date" name="tanggal_buka" value="{{ old('tanggal_buka', $batch->tanggal_buka?->format('Y-m-d') ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              </div>
              <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal Tutup</label>
                <input type="date" name="tanggal_tutup" value="{{ old('tanggal_tutup', $batch->tanggal_tutup?->format('Y-m-d') ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              </div>
            </div>
          </div>

          <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Estimasi Kelas</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal Mulai Kelas</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $batch->tanggal_mulai?->format('Y-m-d') ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              </div>
              <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Estimasi Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $batch->tanggal_selesai?->format('Y-m-d') ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      {{-- Status & Simpan --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
        <h4 class="font-bold text-slate-800 mb-6">Status Batch</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status Saat Ini</label>
            <select name="status" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              <option value="akan_dibuka" {{ old('status', $batch->status ?? '') === 'akan_dibuka' ? 'selected' : '' }}>Akan Dibuka</option>
              <option value="dibuka" {{ old('status', $batch->status ?? '') === 'dibuka' ? 'selected' : '' }}>Dibuka (Aktif)</option>
              <option value="ditutup" {{ old('status', $batch->status ?? '') === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
              <option value="berjalan" {{ old('status', $batch->status ?? '') === 'berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
              <option value="selesai" {{ old('status', $batch->status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
          </div>
          <div class="pt-4">
            <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined">save</span>
              Simpan Batch
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
