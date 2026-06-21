@extends('layouts.admin')
@section('admin-title', isset($program) ? 'Edit Program' : 'Tambah Program Baru')

@section('admin-content')
  @php
    $sectionTypes = \App\Models\ProgramSection::TYPES;
    $existingSections = isset($program)
      ? $program->sections->map(function ($section) {
          return [
            'type' => $section->type,
            'title' => $section->getTranslation('title', 'id', false) ?: '',
            'description' => $section->getTranslation('description', 'id', false) ?: '',
            'items' => $section->getTranslation('items', 'id', false) ?: [],
            'sort_order' => $section->sort_order,
            'is_active' => $section->is_active ? '1' : '0',
          ];
        })->values()->all()
      : [];
    $sectionsData = old('sections', $existingSections);
    $sectionsData = is_array($sectionsData) ? array_values($sectionsData) : [];
  @endphp

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
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Program <span class="text-accent-red">*</span></label>
            <input type="text" name="nama_program" value="{{ old('nama_program', $program->nama_program ?? '') }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Contoh: Tokutei Ginou (TG)">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug (Opsional)</label>
            <input type="text" name="slug" value="{{ old('slug', $program->slug ?? '') }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 bg-slate-50" placeholder="Kosongkan untuk generate otomatis">
            <p class="text-xs text-slate-400 mt-1">Hanya ubah jika Anda mengerti SEO. Biarkan kosong saat membuat baru.</p>
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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Thumbnail Program</label>
              <input type="file" name="thumbnail" accept="image/*" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 focus:border-primary focus:ring-primary/20 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
              @if(isset($program) && $program->thumbnail_path)
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $program->thumbnail_path) }}" alt="Thumbnail" class="h-20 w-auto object-cover rounded shadow-sm">
                </div>
              @endif
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">File Brosur (PDF/DOC)</label>
              <input type="file" name="brosur" accept=".pdf,.doc,.docx" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 focus:border-primary focus:ring-primary/20 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
              @if(isset($program) && $program->brosur)
                <div class="mt-2">
                  <a href="{{ asset('storage/' . $program->brosur) }}" target="_blank" class="text-primary text-sm hover:underline">Lihat Brosur Saat Ini</a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>



      {{-- Konten Halaman Dinamis --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
          <div>
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">view_agenda</span> Konten Halaman Dinamis
            </h4>
            <p class="text-xs text-slate-500 mt-1">Gunakan section ini untuk mengatur isi halaman detail program tanpa mengubah kode.</p>
          </div>
          <button type="button" onclick="addProgramSection()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Section
          </button>
        </div>

        <div id="program-sections-container" class="space-y-4">
          @forelse($sectionsData as $sectionIndex => $section)
            @php
              $sectionItems = is_array($section['items'] ?? null) ? array_values($section['items']) : [];
              $sectionType = $section['type'] ?? 'text';
            @endphp
            <div class="program-section-item rounded-xl border border-slate-200 bg-slate-50 overflow-hidden" data-section-index="{{ $sectionIndex }}" data-item-count="{{ count($sectionItems) }}">
              <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 p-4 bg-white border-b border-slate-200">
                <div class="flex items-center gap-3">
                  <span class="material-symbols-outlined text-primary">drag_indicator</span>
                  <div>
                    <p class="font-bold text-sm text-slate-800">Section #{{ $sectionIndex + 1 }}</p>
                    <p class="text-[11px] text-slate-400">Urutan bisa diatur lewat angka sort order.</p>
                  </div>
                </div>
                <button type="button" onclick="this.closest('.program-section-item').remove()" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 flex items-center gap-1">
                  <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                </button>
              </div>

              <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Tipe Section</label>
                    <select name="sections[{{ $sectionIndex }}][type]" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20">
                      @foreach($sectionTypes as $typeValue => $typeLabel)
                        <option value="{{ $typeValue }}" {{ $sectionType === $typeValue ? 'selected' : '' }}>{{ $typeLabel }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Sort Order</label>
                    <input type="number" name="sections[{{ $sectionIndex }}][sort_order]" value="{{ $section['sort_order'] ?? $sectionIndex }}" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20">
                  </div>
                  <div class="flex items-end">
                    <label class="flex items-center gap-3 w-full p-3 bg-white rounded-lg border border-slate-200 cursor-pointer">
                      <input type="hidden" name="sections[{{ $sectionIndex }}][is_active]" value="0">
                      <input type="checkbox" name="sections[{{ $sectionIndex }}][is_active]" value="1" {{ ($section['is_active'] ?? '1') ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary/20 border-slate-300">
                      <span class="text-sm font-bold text-slate-700">Aktif</span>
                    </label>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Judul Section</label>
                  <input type="text" name="sections[{{ $sectionIndex }}][title]" value="{{ $section['title'] ?? '' }}" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Contoh: 10 Bidang Pekerjaan Tokutei Ginou">
                </div>
                <div>
                  <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Deskripsi Section</label>
                  <textarea name="sections[{{ $sectionIndex }}][description]" rows="2" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Deskripsi singkat section...">{{ $section['description'] ?? '' }}</textarea>
                </div>

                <div>
                  <div class="flex items-center justify-between mb-3">
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500">Item Section</label>
                    <button type="button" onclick="addSectionItem(this)" class="text-xs text-primary font-bold hover:underline flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">add_circle</span> Tambah Item
                    </button>
                  </div>
                  <div class="section-items space-y-3">
                    @foreach($sectionItems as $itemIndex => $item)
                      <div class="section-item grid grid-cols-1 md:grid-cols-[1fr_1fr_120px_auto] gap-3 items-start p-3 bg-white rounded-lg border border-slate-200">
                        <input type="text" name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][title]" value="{{ $item['title'] ?? '' }}" class="rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Judul / Pertanyaan / Step">
                        <input type="text" name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][description]" value="{{ $item['description'] ?? '' }}" class="rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Deskripsi / Jawaban">
                        <input type="text" name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][icon]" value="{{ $item['icon'] ?? '' }}" class="rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Icon">
                        <button type="button" onclick="this.closest('.section-item').remove()" class="h-10 w-10 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100">
                          <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div id="empty-sections-hint" class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
              <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">view_agenda</span>
              <p class="text-sm font-bold text-slate-600">Belum ada section dinamis.</p>
              <p class="text-xs text-slate-400 mt-1">Klik Tambah Section untuk mulai membuat konten halaman program.</p>
            </div>
          @endforelse
        </div>
      </div>

      {{-- Catatan: FAQ per-program sekarang dikelola via Konten Halaman Dinamis (Section type FAQ) di atas. --}}
    </div>

    <div class="space-y-6">
      {{-- Pengaturan --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
        <h4 class="font-bold text-slate-800 mb-6">Pengaturan Publikasi</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
            <select name="status" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
              <option value="aktif" {{ old('status', $program->status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil)</option>
              <option value="nonaktif" {{ old('status', $program->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Urutan Tampil (Sort Order)</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order ?? 0) }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20">
            <p class="text-xs text-slate-400 mt-1">Angka lebih kecil tampil lebih dulu (0, 1, 2...)</p>
          </div>
          <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
            <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $program->is_featured ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary/20 border-slate-300">
            <label for="is_featured" class="text-sm font-bold text-slate-700 cursor-pointer">Jadikan Program Unggulan</label>
          </div>
          <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
            <input type="checkbox" name="has_schema" value="1" id="has_schema" {{ old('has_schema', $program->has_schema ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary/20 border-slate-300">
            <label for="has_schema" class="text-sm font-bold text-slate-700 cursor-pointer">Gunakan Fitur Schema (Beasiswa dll)</label>
          </div>
          <div class="pt-4 flex gap-2">
            <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined">save</span>
              Simpan
            </button>
            <a href="{{ route('admin.programs.index') }}" class="py-3 px-4 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center">
              Batal
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>



  <script>
    let programSectionCount = {{ count($sectionsData) }};
    const sectionTypes = @json($sectionTypes);

    function addProgramSection(type = 'cards') {
      document.getElementById('empty-sections-hint')?.remove();

      const index = programSectionCount++;
      const container = document.getElementById('program-sections-container');
      const typeOptions = Object.entries(sectionTypes)
        .map(([value, label]) => `<option value="${value}" ${value === type ? 'selected' : ''}>${label}</option>`)
        .join('');

      container.insertAdjacentHTML('beforeend', `
        <div class="program-section-item rounded-xl border border-slate-200 bg-slate-50 overflow-hidden" data-section-index="${index}" data-item-count="0">
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 p-4 bg-white border-b border-slate-200">
            <div class="flex items-center gap-3">
              <span class="material-symbols-outlined text-primary">drag_indicator</span>
              <div>
                <p class="font-bold text-sm text-slate-800">Section #${index + 1}</p>
                <p class="text-[11px] text-slate-400">Urutan bisa diatur lewat angka sort order.</p>
              </div>
            </div>
            <button type="button" onclick="this.closest('.program-section-item').remove()" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
            </button>
          </div>

          <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Tipe Section</label>
                <select name="sections[${index}][type]" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20">
                  ${typeOptions}
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Sort Order</label>
                <input type="number" name="sections[${index}][sort_order]" value="${index}" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20">
              </div>
              <div class="flex items-end">
                <label class="flex items-center gap-3 w-full p-3 bg-white rounded-lg border border-slate-200 cursor-pointer">
                  <input type="hidden" name="sections[${index}][is_active]" value="0">
                  <input type="checkbox" name="sections[${index}][is_active]" value="1" checked class="w-5 h-5 rounded text-primary focus:ring-primary/20 border-slate-300">
                  <span class="text-sm font-bold text-slate-700">Aktif</span>
                </label>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Judul Section</label>
              <input type="text" name="sections[${index}][title]" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Contoh: Benefit Program">
            </div>
            <div>
              <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Deskripsi Section</label>
              <textarea name="sections[${index}][description]" rows="2" class="w-full rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Deskripsi singkat section..."></textarea>
            </div>

            <div>
              <div class="flex items-center justify-between mb-3">
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500">Item Section</label>
                <button type="button" onclick="addSectionItem(this)" class="text-xs text-primary font-bold hover:underline flex items-center gap-1">
                  <span class="material-symbols-outlined text-[16px]">add_circle</span> Tambah Item
                </button>
              </div>
              <div class="section-items space-y-3"></div>
            </div>
          </div>
        </div>
      `);
    }

    function addSectionItem(button) {
      const section = button.closest('.program-section-item');
      const sectionIndex = section.dataset.sectionIndex;
      const itemIndex = Number(section.dataset.itemCount || 0);
      section.dataset.itemCount = itemIndex + 1;

      section.querySelector('.section-items').insertAdjacentHTML('beforeend', `
        <div class="section-item grid grid-cols-1 md:grid-cols-[1fr_1fr_120px_auto] gap-3 items-start p-3 bg-white rounded-lg border border-slate-200">
          <input type="text" name="sections[${sectionIndex}][items][${itemIndex}][title]" class="rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Judul / Pertanyaan / Step">
          <input type="text" name="sections[${sectionIndex}][items][${itemIndex}][description]" class="rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Deskripsi / Jawaban">
          <input type="text" name="sections[${sectionIndex}][items][${itemIndex}][icon]" class="rounded-lg border-slate-200 text-sm focus:border-primary focus:ring-primary/20" placeholder="Icon">
          <button type="button" onclick="this.closest('.section-item').remove()" class="h-10 w-10 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100">
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>
      `);
    }


    
    document.querySelectorAll('textarea').forEach(el => {
      el.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
      });
    });
  </script>
@endsection
