@extends('layouts.admin')
@section('admin-title', 'Edit Siswa')

@section('admin-content')
  <div class="mb-6">
    <a href="{{ route('admin.siswa.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-primary transition-colors font-medium">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke Data Siswa
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
    <div class="px-6 py-4 border-b border-slate-200">
      <h4 class="font-bold text-slate-800">Edit Data Siswa</h4>
      <p class="text-sm text-slate-500 mt-1">Data akan tersimpan di database.</p>
    </div>

    @if($errors->any())
      <div class="mx-6 mt-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
        <span class="material-symbols-outlined text-accent-red">error</span>
        <span class="text-sm font-medium text-accent-red">{{ $errors->first() }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" class="p-6">
      @csrf @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nama Lengkap *</label>
          <input type="text" name="nama" required value="{{ old('nama', $siswa->nama) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">No. WhatsApp *</label>
          <input type="text" name="wa" required value="{{ old('wa', $siswa->wa) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Email</label>
          <input type="email" name="email" value="{{ old('email', $siswa->email) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $siswa->tgl_lahir ? $siswa->tgl_lahir->format('Y-m-d') : '') }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Kota Asal *</label>
          <input type="text" name="kota" required value="{{ old('kota', $siswa->kota) }}"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Program *</label>
          <select name="program" id="programSelect" required onchange="toggleExtraFields()"
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="">-- Pilih --</option>
            @foreach(['Tokutei Ginou (TG)','Engineering','Kelas Bahasa Jepang','Returnee / Ex Jepang'] as $prog)
              <option {{ old('program', $siswa->program)==$prog ? 'selected' : '' }}>{{ $prog }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Status</label>
          <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            @foreach(['Aktif'=>'Aktif','Proses'=>'Dalam Proses','Berangkat'=>'Sudah Berangkat','Lulus'=>'Lulus'] as $val => $label)
              <option value="{{ $val }}" {{ old('status', $siswa->status)==$val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Pendidikan</label>
          <select name="pendidikan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            @foreach(['SMA/SMK','D3','S1','S2'] as $pend)
              <option {{ old('pendidikan', $siswa->pendidikan)==$pend ? 'selected' : '' }}>{{ $pend }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Dynamic Extra Fields --}}
      @php $extra = $siswa->extra_fields ? json_decode($siswa->extra_fields, true) : []; @endphp

      <div id="fields-engineering" class="hidden mb-6 p-5 rounded-xl border-2 border-dashed border-primary/30 bg-primary/5">
        <h5 class="text-sm font-bold text-primary mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">engineering</span> Data Khusus — Engineering
        </h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Jurusan</label>
            <select name="extra_jurusan" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
              <option value="">-- Pilih --</option>
              @foreach(['Teknik Mesin','Teknik Elektro','Teknik Sipil','Teknik Industri','Teknik Informatika','Lainnya'] as $jur)
                <option {{ old('extra_jurusan', $extra['jurusan'] ?? '')==$jur ? 'selected' : '' }}>{{ $jur }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">IPK</label>
            <input type="number" step="0.01" min="0" max="4" name="extra_ipk" placeholder="3.45"
                   value="{{ old('extra_ipk', $extra['ipk'] ?? '') }}"
                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Skill Software</label>
            <input type="text" name="extra_skill_software" placeholder="AutoCAD, SolidWorks..."
                   value="{{ old('extra_skill_software', $extra['skill_software'] ?? '') }}"
                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
        </div>
      </div>

      <div id="fields-tg" class="hidden mb-6 p-5 rounded-xl border-2 border-dashed border-accent-red/30 bg-red-50">
        <h5 class="text-sm font-bold text-accent-red mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">translate</span> Data Khusus — Tokutei Ginou
        </h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Level Bahasa</label>
            <select name="extra_level_bahasa" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
              <option value="">-- Pilih --</option>
              @foreach(['N5','N4','N3','N2','N1'] as $lv)
                <option {{ old('extra_level_bahasa', $extra['level_bahasa'] ?? '')==$lv ? 'selected' : '' }}>{{ $lv }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Sertifikat Skill</label>
            <input type="text" name="extra_sertifikat_skill" placeholder="SSW 1, Careworker..."
                   value="{{ old('extra_sertifikat_skill', $extra['sertifikat_skill'] ?? '') }}"
                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Bidang Keahlian</label>
            <select name="extra_bidang" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
              <option value="">-- Pilih --</option>
              @foreach(['Manufaktur','Konstruksi','Pertanian','Perikanan','Food Service','Keperawatan','Lainnya'] as $bid)
                <option {{ old('extra_bidang', $extra['bidang'] ?? '')==$bid ? 'selected' : '' }}>{{ $bid }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div id="fields-returnee" class="hidden mb-6 p-5 rounded-xl border-2 border-dashed border-emerald-400/40 bg-emerald-50">
        <h5 class="text-sm font-bold text-emerald-700 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">flight_land</span> Data Khusus — Returnee / Ex Jepang
        </h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Perusahaan di Jepang</label>
            <input type="text" name="extra_perusahaan" placeholder="Nama perusahaan..."
                   value="{{ old('extra_perusahaan', $extra['perusahaan'] ?? '') }}"
                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Lama Kontrak</label>
            <input type="text" name="extra_lama_kontrak" placeholder="Contoh: 3 tahun"
                   value="{{ old('extra_lama_kontrak', $extra['lama_kontrak'] ?? '') }}"
                   class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
          </div>
        </div>
      </div>

      <div class="mb-6">
        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Catatan</label>
        <textarea name="catatan" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-y">{{ old('catatan', $siswa->catatan) }}</textarea>
      </div>

      <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">check_circle</span> Update Data
        </button>
        <a href="{{ route('admin.siswa.index') }}" class="px-6 py-2.5 border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
          Batal
        </a>
      </div>
    </form>
  </div>
@endsection

@section('admin-scripts')
<script>
  function toggleExtraFields() {
    const program = document.getElementById('programSelect').value;
    document.getElementById('fields-engineering').classList.add('hidden');
    document.getElementById('fields-tg').classList.add('hidden');
    document.getElementById('fields-returnee').classList.add('hidden');
    if (program === 'Engineering') document.getElementById('fields-engineering').classList.remove('hidden');
    else if (program === 'Tokutei Ginou (TG)') document.getElementById('fields-tg').classList.remove('hidden');
    else if (program === 'Returnee / Ex Jepang') document.getElementById('fields-returnee').classList.remove('hidden');
  }
  document.addEventListener('DOMContentLoaded', toggleExtraFields);
</script>
@endsection
