@extends('layouts.admin')
@section('admin-title', 'Edit Siswa')

@section('admin-content')
  <h3 style="font-size:17px;font-weight:800;margin-bottom:4px;">Edit Data Siswa</h3>
  <p style="color:var(--muted);font-size:13.5px;margin-bottom:22px;">Data akan tersimpan di database.</p>

  @if($errors->any())
    <div style="padding:12px 16px;border-radius:12px;background:rgba(225,6,0,.10);color:var(--red);font-weight:700;font-size:13.5px;margin-bottom:16px;">
      ⚠️ {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}">
    @csrf @method('PUT')
    <div class="add-form-grid">
      <div class="add-field">
        <label>Nama Lengkap *</label>
        <input type="text" name="nama" required value="{{ old('nama', $siswa->nama) }}">
      </div>
      <div class="add-field">
        <label>No. WhatsApp *</label>
        <input type="text" name="wa" required value="{{ old('wa', $siswa->wa) }}">
      </div>
      <div class="add-field">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $siswa->email) }}">
      </div>
      <div class="add-field">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $siswa->tgl_lahir ? $siswa->tgl_lahir->format('Y-m-d') : '') }}">
      </div>
      <div class="add-field">
        <label>Kota Asal *</label>
        <input type="text" name="kota" required value="{{ old('kota', $siswa->kota) }}">
      </div>
      <div class="add-field">
        <label>Program *</label>
        <select name="program" required>
          <option value="">-- Pilih --</option>
          @foreach(['Tokutei Ginou (TG)','Engineering','Kelas Bahasa Jepang','Returnee / Ex Jepang'] as $prog)
            <option {{ old('program', $siswa->program)==$prog ? 'selected' : '' }}>{{ $prog }}</option>
          @endforeach
        </select>
      </div>
      <div class="add-field">
        <label>Status</label>
        <select name="status">
          @foreach(['Aktif'=>'Aktif','Proses'=>'Dalam Proses','Berangkat'=>'Sudah Berangkat','Lulus'=>'Lulus'] as $val => $label)
            <option value="{{ $val }}" {{ old('status', $siswa->status)==$val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="add-field">
        <label>Pendidikan</label>
        <select name="pendidikan">
          @foreach(['SMA/SMK','D3','S1','S2'] as $pend)
            <option {{ old('pendidikan', $siswa->pendidikan)==$pend ? 'selected' : '' }}>{{ $pend }}</option>
          @endforeach
        </select>
      </div>
      <div class="add-field span2">
        <label>Catatan</label>
        <textarea name="catatan" rows="3">{{ old('catatan', $siswa->catatan) }}</textarea>
      </div>
    </div>
    <div class="add-submit-row">
      <button type="submit" class="btn btn-primary btn-lg">✅ Update Data</button>
      <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline btn-lg">← Kembali</a>
    </div>
  </form>
@endsection
