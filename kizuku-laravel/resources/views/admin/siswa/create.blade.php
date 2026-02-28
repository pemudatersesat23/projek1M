@extends('layouts.admin')
@section('admin-title', 'Tambah Siswa')

@section('admin-content')
  <h3 style="font-size:17px;font-weight:800;margin-bottom:4px;">{{ isset($siswa) ? 'Edit Data Siswa' : 'Tambah Data Siswa Baru' }}</h3>
  <p style="color:var(--muted);font-size:13.5px;margin-bottom:22px;">Data akan tersimpan di database.</p>

  @if($errors->any())
    <div style="padding:12px 16px;border-radius:12px;background:rgba(225,6,0,.10);color:var(--red);font-weight:700;font-size:13.5px;margin-bottom:16px;">
      ⚠️ {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ isset($siswa) ? route('admin.siswa.update', $siswa) : route('admin.siswa.store') }}">
    @csrf
    @if(isset($siswa)) @method('PUT') @endif

    <div class="add-form-grid">
      <div class="add-field">
        <label>Nama Lengkap *</label>
        <input type="text" name="nama" placeholder="Nama lengkap siswa" required value="{{ old('nama', $siswa->nama ?? '') }}">
      </div>
      <div class="add-field">
        <label>No. WhatsApp *</label>
        <input type="text" name="wa" placeholder="08xx-xxxx-xxxx" required value="{{ old('wa', $siswa->wa ?? '') }}">
      </div>
      <div class="add-field">
        <label>Email</label>
        <input type="email" name="email" placeholder="email@gmail.com" value="{{ old('email', $siswa->email ?? '') }}">
      </div>
      <div class="add-field">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', isset($siswa) && $siswa->tgl_lahir ? $siswa->tgl_lahir->format('Y-m-d') : '') }}">
      </div>
      <div class="add-field">
        <label>Kota Asal *</label>
        <input type="text" name="kota" placeholder="Contoh: Surabaya" required value="{{ old('kota', $siswa->kota ?? '') }}">
      </div>
      <div class="add-field">
        <label>Program *</label>
        <select name="program" required>
          <option value="">-- Pilih Program --</option>
          @foreach(['Tokutei Ginou (TG)','Engineering','Kelas Bahasa Jepang','Returnee / Ex Jepang'] as $prog)
            <option {{ old('program', $siswa->program ?? '')==$prog ? 'selected' : '' }}>{{ $prog }}</option>
          @endforeach
        </select>
      </div>
      <div class="add-field">
        <label>Status</label>
        <select name="status">
          @foreach(['Aktif'=>'Aktif','Proses'=>'Dalam Proses','Berangkat'=>'Sudah Berangkat','Lulus'=>'Lulus'] as $val => $label)
            <option value="{{ $val }}" {{ old('status', $siswa->status ?? 'Aktif')==$val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="add-field">
        <label>Pendidikan Terakhir</label>
        <select name="pendidikan">
          @foreach(['SMA/SMK','D3','S1','S2'] as $pend)
            <option {{ old('pendidikan', $siswa->pendidikan ?? 'SMA/SMK')==$pend ? 'selected' : '' }}>{{ $pend }}</option>
          @endforeach
        </select>
      </div>
      <div class="add-field span2">
        <label>Catatan</label>
        <textarea name="catatan" placeholder="Catatan tambahan..." rows="3">{{ old('catatan', $siswa->catatan ?? '') }}</textarea>
      </div>
    </div>

    <div class="add-submit-row">
      <button type="submit" class="btn btn-primary btn-lg">✅ {{ isset($siswa) ? 'Update Data' : 'Simpan Data Siswa' }}</button>
      <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline btn-lg">← Kembali</a>
    </div>
  </form>
@endsection
