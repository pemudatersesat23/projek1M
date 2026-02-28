@extends('layouts.admin')
@section('admin-title', 'Kelola Berita')

@section('admin-content')
  <h3 style="font-size:17px;font-weight:800;margin-bottom:4px;">Kelola Berita &amp; Pengumuman</h3>
  <p style="color:var(--muted);font-size:13.5px;margin-bottom:20px;">Tambah atau hapus berita yang tampil di halaman publik.</p>

  {{-- Form tambah berita --}}
  <div style="background:#fff;border-radius:16px;padding:20px;border:1px solid rgba(17,17,17,.07);margin-bottom:22px;">
    <h4 style="font-size:14px;font-weight:800;margin-bottom:14px;">+ Tambah Berita Baru</h4>

    @if($errors->any())
      <div style="padding:10px 14px;border-radius:10px;background:rgba(225,6,0,.10);color:var(--red);font-weight:700;font-size:13px;margin-bottom:14px;">
        ⚠️ {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.berita.store') }}">
      @csrf
      <div class="add-form-grid">
        <div class="add-field span2">
          <label>Judul Berita *</label>
          <input type="text" name="judul" placeholder="Judul berita..." required value="{{ old('judul') }}">
        </div>
        <div class="add-field">
          <label>Kategori</label>
          <select name="kategori">
            <option value="kat-info" {{ old('kategori')=='kat-info' ? 'selected' : '' }}>Info Program</option>
            <option value="kat-alumni" {{ old('kategori')=='kat-alumni' ? 'selected' : '' }}>Alumni</option>
            <option value="kat-promo" {{ old('kategori')=='kat-promo' ? 'selected' : '' }}>Promo</option>
            <option value="kat-tips" {{ old('kategori')=='kat-tips' ? 'selected' : '' }}>Tips</option>
          </select>
        </div>
        <div class="add-field">
          <label>Emoji / Ikon</label>
          <input type="text" name="emoji" placeholder="🎌" maxlength="4" value="{{ old('emoji', '📢') }}">
        </div>
        <div class="add-field span2">
          <label>Isi Singkat</label>
          <textarea name="isi" placeholder="Ringkasan berita..." rows="2">{{ old('isi') }}</textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-top:14px;">📰 Publish Berita</button>
    </form>
  </div>

  {{-- List berita --}}
  <div class="news-admin-grid">
    @forelse($beritas as $n)
      <div class="news-item-admin">
        <div class="news-emo">{{ $n->emoji }}</div>
        <div style="flex:1;">
          <h5>{{ $n->judul }}</h5>
          <p>{{ $n->isi }}</p>
          <div class="news-item-actions">
            <span class="b-kategori {{ $n->kategori }}" style="font-size:10px;padding:2px 8px;">{{ \App\Helpers\KategoriHelper::label($n->kategori) }}</span>
            <form method="POST" action="{{ route('admin.berita.destroy', $n) }}" style="display:inline;" onsubmit="return confirm('Hapus berita ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="tb-action danger">🗑 Hapus</button>
            </form>
            <span style="font-size:11px;color:var(--muted);padding:4px 6px;">{{ $n->created_at->format('d/m/Y') }}</span>
          </div>
        </div>
      </div>
    @empty
      <p style="color:var(--muted)">Belum ada berita.</p>
    @endforelse
  </div>
@endsection
