@extends('layouts.admin')
@section('admin-title', $schema->exists ? 'Edit Schema' : 'Tambah Schema Baru')

@section('admin-content')
  <div class="mb-8">
    <a href="{{ route('admin.program-schemas.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
    </a>
    <h3 class="text-slate-800 font-bold text-2xl">{{ $schema->exists ? 'Edit Skema: ' . $schema->nama_skema : 'Buat Skema Baru' }}</h3>
  </div>

  <form action="{{ $schema->exists ? route('admin.program-schemas.update', $schema) : route('admin.program-schemas.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    @if($schema->exists) @method('PUT') @endif

    <div class="lg:col-span-2 space-y-6">
      
      {{-- Handling Validation Errors --}}
      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
          <div class="flex">
            <div class="flex-shrink-0">
              <span class="material-symbols-outlined text-red-500">error</span>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan saat menyimpan:</h3>
              <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      {{-- Informasi Utama --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">info</span> Informasi Utama
        </h4>
        <div class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Program <span class="text-accent-red">*</span></label>
              <select name="program_id" id="schema_program_id" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
                <option value="">-- Pilih Program --</option>
                @foreach($programs as $p)
                  <option value="{{ $p->id }}" {{ old('program_id', $schema->program_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama_program }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Batch (Opsional)</label>
              <select name="batch_id" id="schema_batch_id" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
                <option value="">-- Berlaku Semua Batch --</option>
                @foreach($batches as $b)
                  <option value="{{ $b->id }}" data-program-id="{{ $b->program_id }}" {{ old('batch_id', $schema->batch_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->nama_batch }} ({{ $b->program?->nama_program ?? 'Program tidak tersedia' }})</option>
                @endforeach
              </select>
              <p class="text-xs text-slate-400 mt-1">Kosongkan jika berlaku umum untuk program tersebut.</p>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Skema <span class="text-accent-red">*</span></label>
            <input type="text" name="nama_skema" value="{{ old('nama_skema', $schema->nama_skema ?? '') }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Jalur Beasiswa Penuh">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug (Opsional)</label>
            <input type="text" name="slug" value="{{ old('slug', $schema->slug ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 bg-slate-50" placeholder="Kosongkan untuk generate otomatis">
            <p class="text-xs text-slate-400 mt-1">Hanya ubah jika Anda mengerti SEO. Unik per program.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Skema</label>
            <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Deskripsi lengkap skema...">{{ old('deskripsi', $schema->deskripsi ?? '') }}</textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Persyaratan Khusus</label>
            <textarea name="persyaratan" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Gunakan tanda hubung (-) untuk list...">{{ old('persyaratan', $schema->persyaratan ?? '') }}</textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Skema <span class="text-accent-red">*</span></label>
              <select name="tipe" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
                <option value="reguler" {{ old('tipe', $schema->tipe ?? '') === 'reguler' ? 'selected' : '' }}>Reguler (Normal)</option>
                <option value="beasiswa" {{ old('tipe', $schema->tipe ?? '') === 'beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                <option value="scholar_partnership" {{ old('tipe', $schema->tipe ?? '') === 'scholar_partnership' ? 'selected' : '' }}>Scholarship Partnership</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Harga / Biaya Pendaftaran</label>
              <input type="number" step="0.01" name="harga" value="{{ old('harga', $schema->harga ?? 0) }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: 5000000">
              <p class="text-xs text-slate-400 mt-1">Isi 0 jika gratis.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      {{-- Pengaturan --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
        <h4 class="font-bold text-slate-800 mb-6">Pengaturan</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status Skema</label>
            <select name="status" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              <option value="aktif" {{ old('status', $schema->status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil)</option>
              <option value="nonaktif" {{ old('status', $schema->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Urutan Tampil (Sort Order)</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $schema->sort_order ?? 0) }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
            <p class="text-xs text-slate-400 mt-1">Angka lebih kecil tampil lebih dulu (0, 1, 2...)</p>
          </div>
          <div class="pt-4 flex gap-2">
            <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined">save</span>
              Simpan
            </button>
            <a href="{{ route('admin.program-schemas.index') }}" class="py-3 px-4 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center">
              Batal
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const programSelect = document.getElementById('schema_program_id');
      const batchSelect = document.getElementById('schema_batch_id');

      function filterBatches() {
        const programId = programSelect.value;
        let selectedStillVisible = !batchSelect.value;

        batchSelect.querySelectorAll('option[data-program-id]').forEach(option => {
          const visible = !programId || option.dataset.programId === programId;
          option.hidden = !visible;
          option.disabled = !visible;

          if (visible && option.selected) {
            selectedStillVisible = true;
          }
        });

        if (!selectedStillVisible) {
          batchSelect.value = '';
        }
      }

      programSelect.addEventListener('change', filterBatches);
      filterBatches();
    });
  </script>
@endsection
