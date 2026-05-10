@extends('layouts.admin')
@section('admin-title', 'Manage Hero Banners')

@section('admin-content')

{{-- Custom Delete Confirmation Modal --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:white; border-radius:16px; padding:32px; max-width:400px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
    <div style="width:56px; height:56px; background:#fee2e2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
      <span class="material-symbols-outlined" style="color:#dc2626; font-size:28px;">warning</span>
    </div>
    <h3 style="font-size:18px; font-weight:700; color:#111; margin-bottom:8px;">Hapus Gambar?</h3>
    <p style="font-size:14px; color:#6b7280; margin-bottom:24px;">Gambar ini akan dihapus secara permanen dan tidak bisa dikembalikan.</p>
    <div style="display:flex; gap:12px; justify-content:center;">
      <button onclick="closeDeleteModal()" style="padding:10px 24px; background:#f1f5f9; border:none; border-radius:8px; font-size:14px; font-weight:600; color:#475569; cursor:pointer;">Batal</button>
      <button onclick="submitDeleteForm()" style="padding:10px 24px; background:#dc2626; border:none; border-radius:8px; font-size:14px; font-weight:600; color:white; cursor:pointer;">Ya, Hapus</button>
    </div>
  </div>
</div>

<script>
  var _deleteForm = null;
  function openDeleteModal(formEl) {
    _deleteForm = formEl;
    var modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
  }
  function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    _deleteForm = null;
  }
  function submitDeleteForm() {
    if (_deleteForm) _deleteForm.submit();
  }
</script>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Banner Hero</h3>
      <p class="text-sm text-slate-500 mt-1">Kelola gambar background yang muncul di bagian paling atas beranda.</p>
    </div>
    <a href="{{ route('admin.hero-sections.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">add</span> Tambah Gambar
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
      {{ session('success') }}
    </div>
  @endif

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-slate-50 border-b border-slate-200">
          <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Preview Gambar</th>
          <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-center">Urutan Slide</th>
          <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-center">Status</th>
          <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-200">
        @forelse($heroSections as $hero)
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-6 py-4">
              <div class="w-32 h-20 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden">
                @if($hero->image_path)
                  <img src="{{ asset('storage/' . $hero->image_path) }}" class="w-full h-full object-cover" alt="Banner">
                @else
                  <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <span class="material-symbols-outlined text-3xl">image</span>
                  </div>
                @endif
              </div>
            </td>
            <td class="px-6 py-4 text-center">
              <span class="text-sm font-bold text-slate-700">Slide #{{ $hero->sort_order }}</span>
            </td>
            <td class="px-6 py-4 text-center">
              @if($hero->is_active)
                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full border border-green-200">AKTIF</span>
              @else
                <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold rounded-full border border-slate-200">DRAFT</span>
              @endif
            </td>
            <td class="px-6 py-4 text-center">
              <div class="flex items-center justify-center gap-3">
                {{-- EDIT BUTTON --}}
                <a href="{{ route('admin.hero-sections.edit', $hero->id) }}"
                   style="display:inline-flex; align-items:center; gap:4px; padding:6px 12px; background:#f1f5f9; border-radius:8px; font-size:13px; font-weight:600; color:#475569; text-decoration:none;"
                   onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                  <span class="material-symbols-outlined" style="font-size:16px;">edit</span> Edit
                </a>
                {{-- DELETE BUTTON (custom modal) --}}
                <form method="POST" action="{{ route('admin.hero-sections.destroy', $hero->id) }}"
                      id="delete-form-{{ $hero->id }}"
                      style="display:inline-block; margin:0;">
                  @csrf
                  @method('DELETE')
                  <button type="button"
                          onclick="openDeleteModal(document.getElementById('delete-form-{{ $hero->id }}'))"
                          style="display:inline-flex; align-items:center; gap:4px; padding:6px 12px; background:#fef2f2; border:none; border-radius:8px; font-size:13px; font-weight:600; color:#dc2626; cursor:pointer;"
                          onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <span class="material-symbols-outlined" style="font-size:16px;">delete</span> Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-16 text-center">
              <div class="text-slate-300 mb-3"><span class="material-symbols-outlined text-5xl">image_not_supported</span></div>
              <p class="text-slate-500 text-sm">Belum ada gambar banner. Klik "Tambah Gambar" untuk menambahkan.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
