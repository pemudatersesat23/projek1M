@extends('layouts.admin')
@section('admin-title', 'Edit Foto Galeri')

@section('admin-content')
  <div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
      <a href="{{ route('admin.galleries.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
      </a>
      <h3 class="text-lg font-bold text-slate-800">Edit Foto Galeri</h3>
    </div>

    @if($errors->any())
      <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-3">
        <span class="material-symbols-outlined text-accent-red">error</span>
        <span class="text-sm font-medium text-accent-red">{{ $errors->first() }}</span>
      </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" class="p-8" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Foto Saat Ini</label>
          <div class="relative group">
            <input type="file" name="image" id="imageInput" accept="image/*"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
            <div id="imagePlaceholder" class="w-full aspect-video rounded-xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-3 group-hover:bg-slate-100 group-hover:border-primary/30 transition-all overflow-hidden">
              @if($gallery->image)
                <img id="imagePreview" src="{{ asset('storage/' . $gallery->image) }}" class="absolute inset-0 w-full h-full object-cover rounded-xl shadow-inner">
              @else
                <span class="material-symbols-outlined text-4xl text-slate-300">add_photo_alternate</span>
                <p class="text-xs font-medium text-slate-500">Klik untuk mengganti foto</p>
                <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-cover rounded-xl shadow-inner">
              @endif
            </div>
          </div>
          <p class="text-[11px] text-slate-400 mt-2">Biarkan kosong jika tidak ingin mengganti foto. Format: JPG, PNG, WEBP (Maks 5MB).</p>
        </div>

        <div class="space-y-6">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Judul / Keterangan (Opsional)</label>
            <input type="text" name="title" placeholder="Misal: Sesi Pelatihan Bahasa" value="{{ old('title', $gallery->title) }}"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Urutan Tampil</label>
              <input type="number" name="order" value="{{ old('order', $gallery->order) }}"
                     class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
            </div>
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Status</label>
              <div class="flex items-center gap-3 mt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" name="is_active" value="1" {{ $gallery->is_active ? 'checked' : '' }} class="sr-only peer">
                  <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                  <span class="ml-3 text-sm font-medium text-slate-700">Aktif</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-8 border-slate-100">

        <div class="flex items-center justify-end gap-3">
          <a href="{{ route('admin.galleries.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</a>
          <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('imageInput').onchange = function() {
      const [file] = this.files;
      if (file) {
        const preview = document.getElementById('imagePreview');
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
      }
    };
  </script>
@endsection
