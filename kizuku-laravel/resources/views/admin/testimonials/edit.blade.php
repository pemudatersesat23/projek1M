@extends('layouts.admin')
@section('admin-title', 'Edit Testimoni')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Edit Testimoni</h3>
      <p class="text-sm text-slate-500 mt-1">Ubah atau perbarui testimoni alumni.</p>
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6">
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="p-6">
      @csrf
      @method('PUT')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Nama Lengkap *</label>
          <input type="text" name="name" required value="{{ old('name', $testimonial->name) }}" placeholder="Contoh: Budi Santoso"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Jabatan / Keterangan *</label>
          <input type="text" name="role" required value="{{ old('role', $testimonial->role) }}" placeholder="Contoh: Alumni TG - Tokyo, Jepang"
                 class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Isi Testimoni *</label>
          <textarea name="content" rows="4" required placeholder="Ceritakan pengalaman sukses..."
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none transition-all">{{ old('content', $testimonial->content) }}</textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Bintang (1-5)</label>
          <select name="stars" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none cursor-pointer">
            @for($i=5; $i>=1; $i--)
              <option value="{{ $i }}" {{ old('stars', $testimonial->stars) == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
            @endfor
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Ganti Foto Peserta</label>
          <div class="flex items-center gap-4 mb-3">
             <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                @if($testimonial->avatar_path)
                  <img src="{{ asset('storage/' . $testimonial->avatar_path) }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold uppercase">{{ substr($testimonial->name, 0, 1) }}</div>
                @endif
             </div>
             <div class="text-[10px] text-slate-500 italic">Pilih file baru untuk mengganti avatar.</div>
          </div>
          <input type="file" name="avatar" accept="image/*"
                 class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
        </div>

        <div class="flex items-center pt-2">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ $testimonial->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary transition-all cursor-pointer">
            <span class="text-sm font-bold text-slate-700">Tampilkan di Halaman Depan</span>
          </label>
        </div>
      </div>

      <div class="mt-8 pt-6 border-t border-slate-100 flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-all flex items-center gap-2">
          <span class="material-symbols-outlined text-lg">save</span> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
@endsection
