@extends('layouts.admin')
@section('admin-title', isset($program) ? 'Edit Program' : 'Tambah Program Baru')

@section('admin-content')
  <div class="mb-8">
    <a href="{{ route('admin.programs.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
    </a>
    <h3 class="text-slate-800 font-bold text-2xl">{{ isset($program) ? 'Edit Program: ' . $program->nama_program : 'Buat Program Baru' }}</h3>
  </div>

  <form action="{{ isset($program) ? route('admin.programs.update', $program) : route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    @if(isset($program)) @method('PUT') @endif

    <div class="lg:col-span-2 space-y-6">
      {{-- Informasi Utama --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">info</span> Informasi Utama
        </h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Program <span class="text-accent-red">*</span></label>
            <input type="text" name="nama_program" value="{{ old('nama_program', $program->nama_program ?? '') }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Tokutei Ginou (TG)">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Program</label>
            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Deskripsi lengkap program...">{{ old('deskripsi', $program->deskripsi ?? '') }}</textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Durasi Belajar</label>
              <input type="text" name="durasi" value="{{ old('durasi', $program->durasi ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: 5-6 Bulan">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Biaya Estimasi</label>
              <input type="text" name="biaya" value="{{ old('biaya', $program->biaya ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Rp 5.000.000">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Video URL (YouTube Embed)</label>
            <input type="url" name="video_url" value="{{ old('video_url', $program->video_url ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="https://www.youtube.com/embed/...">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Thumbnail Program</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 focus:border-primary focus:ring-primary/20 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            @if(isset($program) && $program->thumbnail_path)
              <div class="mt-2">
                <img src="{{ asset('storage/' . $program->thumbnail_path) }}" alt="Thumbnail" class="h-20 w-auto object-cover rounded shadow-sm">
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Detail Konten --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">description</span> Detail Konten
        </h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Target Peserta</label>
            <textarea name="target_peserta" rows="2" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">{{ old('target_peserta', $program->target_peserta ?? '') }}</textarea>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Fokus Pelatihan</label>
              <textarea name="focus" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Bahasa Jepang, Skill Kerja...">{{ old('focus', $program->focus ?? '') }}</textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Output Program</label>
              <textarea name="output" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Siap kerja ke Jepang...">{{ old('output', $program->output ?? '') }}</textarea>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Benefit Program</label>
            <textarea name="benefit" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Gunakan tanda hubung (-) untuk list...">{{ old('benefit', $program->benefit ?? '') }}</textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Alur Seleksi</label>
            <textarea name="alur_seleksi" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">{{ old('alur_seleksi', $program->alur_seleksi ?? '') }}</textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Materi Utama</label>
            <textarea name="materi" rows="3" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Materi yang akan dipelajari...">{{ old('materi', $program->materi ?? '') }}</textarea>
          </div>
        </div>
      </div>

      {{-- FAQ Section --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <h4 class="font-bold text-slate-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">quiz</span> FAQ (Pertanyaan Umum)
          </h4>
          <button type="button" onclick="addFaq()" class="text-sm text-primary font-bold flex items-center gap-1 hover:underline">
            <span class="material-symbols-outlined text-sm">add_circle</span> Tambah FAQ
          </button>
        </div>
        <div id="faq-container" class="space-y-4">
          @php 
            $faqData = old('faq', $program->faq ?? []);
            if (is_string($faqData)) {
                $faqData = json_decode($faqData, true);
            }
            $faqs = is_array($faqData) ? $faqData : [];
          @endphp
          @foreach($faqs as $index => $item)
          <div class="faq-item p-4 bg-slate-50 rounded-lg border border-slate-100 relative group">
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
              <span class="material-symbols-outlined text-xs">close</span>
            </button>
            <div class="space-y-3">
              <input type="text" name="faq[{{$index}}][q]" value="{{ $item['q'] ?? '' }}" class="w-full text-sm font-bold bg-transparent border-0 border-b border-slate-200 focus:ring-0 px-0" placeholder="Pertanyaan">
              <textarea name="faq[{{$index}}][a]" rows="2" class="w-full text-sm bg-transparent border-0 focus:ring-0 px-0" placeholder="Jawaban">{{ $item['a'] ?? '' }}</textarea>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="space-y-6">
      {{-- Pengaturan --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
        <h4 class="font-bold text-slate-800 mb-6">Pengaturan</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
            <select name="status" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              <option value="aktif" {{ old('status', $program->status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil)</option>
              <option value="nonaktif" {{ old('status', $program->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
            </select>
          </div>
          <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
            <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $program->is_featured ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary/20 border-slate-300">
            <label for="is_featured" class="text-sm font-bold text-slate-700">Tampilkan di Beranda</label>
          </div>
          <div class="pt-4">
            <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined">save</span>
              Simpan Program
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <template id="faq-template">
    <div class="faq-item p-4 bg-slate-50 rounded-lg border border-slate-100 relative group">
      <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
        <span class="material-symbols-outlined text-xs">close</span>
      </button>
      <div class="space-y-3">
        <input type="text" name="faq[INDEX][q]" class="w-full text-sm font-bold bg-transparent border-0 border-b border-slate-200 focus:ring-0 px-0" placeholder="Pertanyaan">
        <textarea name="faq[INDEX][a]" rows="2" class="w-full text-sm bg-transparent border-0 focus:ring-0 px-0" placeholder="Jawaban"></textarea>
      </div>
    </div>
  </template>

  <script>
    let faqCount = {{ count($faqs) }};
    function addFaq() {
      const container = document.getElementById('faq-container');
      const template = document.getElementById('faq-template').innerHTML;
      const html = template.replace(/INDEX/g, faqCount++);
      container.insertAdjacentHTML('beforeend', html);
    }
    
    // Auto-expand textareas
    document.querySelectorAll('textarea').forEach(el => {
      el.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
      });
    });
  </script>
@endsection
