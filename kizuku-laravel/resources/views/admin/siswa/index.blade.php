@extends('layouts.admin')
@section('admin-title', 'Data Siswa')

@section('admin-content')
  {{-- Stats --}}
  <div class="db-stats-row">
    <div class="db-stat"><div class="db-stat-num blue">{{ $stats['total'] }}</div><div class="db-stat-lbl">Total Siswa</div></div>
    <div class="db-stat"><div class="db-stat-num green">{{ $stats['aktif'] }}</div><div class="db-stat-lbl">Aktif Belajar</div></div>
    <div class="db-stat"><div class="db-stat-num red">{{ $stats['berangkat'] }}</div><div class="db-stat-lbl">Sudah Berangkat</div></div>
    <div class="db-stat"><div class="db-stat-num orange">{{ $stats['proses'] }}</div><div class="db-stat-lbl">Dalam Proses</div></div>
  </div>

  {{-- Toolbar --}}
  <form method="GET" action="{{ route('admin.siswa.index') }}" class="db-toolbar">
    <input class="db-search" name="search" type="text" placeholder="🔍  Cari nama, kota..." value="{{ request('search') }}">
    <select class="db-filter" name="program" onchange="this.form.submit()">
      <option value="">Semua Program</option>
      @foreach(['Tokutei Ginou (TG)','Engineering','Kelas Bahasa Jepang','Returnee / Ex Jepang'] as $prog)
        <option {{ request('program')==$prog ? 'selected' : '' }}>{{ $prog }}</option>
      @endforeach
    </select>
    <select class="db-filter" name="status" onchange="this.form.submit()">
      <option value="">Semua Status</option>
      @foreach(['Aktif','Proses','Berangkat','Lulus'] as $stat)
        <option {{ request('status')==$stat ? 'selected' : '' }}>{{ $stat }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-primary" style="padding:9px 16px;font-size:12px;">🔍 Cari</button>
  </form>

  {{-- Table --}}
  <div class="db-table-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Nama</th><th>Program</th><th>No. WA</th><th>Kota</th><th>Tgl. Daftar</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($siswas as $i => $s)
          @php
            $colors = ['linear-gradient(135deg,#E10600,#ff5e58)','linear-gradient(135deg,#0F4C81,#1FA2C9)','linear-gradient(135deg,#1a1a2e,#555)','linear-gradient(135deg,#10b981,#059669)','linear-gradient(135deg,#f59e0b,#d97706)'];
            $statusMap = ['Aktif'=>'aktif','Lulus'=>'lulus','Proses'=>'proses','Berangkat'=>'berangkat'];
          @endphp
          <tr>
            <td style="color:var(--muted);font-size:12px;">{{ $i+1 }}</td>
            <td class="td-name"><span class="td-avatar" style="background:{{ $colors[$i%5] }}">{{ mb_substr($s->nama,0,1) }}</span>{{ $s->nama }}</td>
            <td style="font-size:12.5px;max-width:140px;">{{ $s->program }}</td>
            <td style="font-size:12.5px;">{{ $s->wa }}</td>
            <td style="font-size:12.5px;">{{ $s->kota }}</td>
            <td style="font-size:12px;color:var(--muted);">{{ $s->created_at->format('d/m/Y') }}</td>
            <td><span class="status-badge {{ $statusMap[$s->status] ?? 'aktif' }}"><span class="status-dot"></span>{{ $s->status }}</span></td>
            <td>
              <a href="{{ route('admin.siswa.edit', $s) }}" class="tb-action">✏️ Edit</a>
              <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf @method('DELETE')
                <button type="submit" class="tb-action danger">🗑</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:30px;">Belum ada data siswa.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
