@extends('layouts.admin')
@section('admin-title', $formField->exists ? 'Edit Field' : 'Tambah Field Baru')

@section('admin-content')
  <div class="mb-8">
    <a href="{{ route('admin.form-fields.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
    </a>
    <h3 class="text-slate-800 font-bold text-2xl">
      {{ $formField->exists ? 'Edit Field: ' . $formField->getLabelForLocale() : 'Buat Field Baru' }}
    </h3>
  </div>

  {{-- Validation Errors --}}
  @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
      <div class="flex">
        <span class="material-symbols-outlined text-red-500 mr-3">error</span>
        <div>
          <h3 class="text-sm font-bold text-red-800 mb-1">Terdapat kesalahan:</h3>
          <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <form action="{{ $formField->exists ? route('admin.form-fields.update', $formField) : route('admin.form-fields.store') }}"
        method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    @if($formField->exists) @method('PUT') @endif

    {{-- Left: Main Config --}}
    <div class="lg:col-span-2 space-y-6">

      {{-- Program & Schema --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">link</span> Konteks Form
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Program <span class="text-red-500">*</span></label>
            <select name="program_id" id="ff_program_id" required
                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 text-sm"
                    data-schemas-url="{{ route('admin.form-fields.schemas') }}">
              <option value="">-- Pilih Program --</option>
              @foreach($programs as $p)
                <option value="{{ $p->id }}" {{ old('program_id', $formField->program_id ?? '') == $p->id ? 'selected' : '' }}>
                  {{ $p->nama_program }}
                </option>
              @endforeach
            </select>
            <p class="text-xs text-slate-400 mt-1">Field akan terikat ke program ini.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Skema (Opsional)</label>
            <select name="schema_id" id="ff_schema_id"
                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 text-sm">
              <option value="">-- Umum Program (tanpa skema) --</option>
              @foreach($schemas as $s)
                <option value="{{ $s->id }}" {{ old('schema_id', $formField->schema_id ?? '') == $s->id ? 'selected' : '' }}>
                  {{ $s->nama_skema }}
                </option>
              @endforeach
            </select>
            <p class="text-xs text-slate-400 mt-1">Kosongkan = field berlaku untuk semua pendaftar program ini.</p>
          </div>
        </div>
      </div>

      {{-- Label & Identitas --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">label</span> Label & Identitas
        </h4>
        <div class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Label (Indonesia) <span class="text-red-500">*</span></label>
              <input type="text" name="label_id"
                     value="{{ old('label_id', $formField->exists ? $formField->getTranslation('label','id') : '') }}"
                     required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 text-sm"
                     placeholder="Contoh: Level Bahasa Jepang">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Label (日本語)</label>
              <input type="text" name="label_jp"
                     value="{{ old('label_jp', $formField->exists ? $formField->getTranslation('label','jp') : '') }}"
                     class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 text-sm"
                     placeholder="Contoh: 日本語レベル">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Field Name <span class="text-red-500">*</span></label>
            <input type="text" name="field_name"
                   value="{{ old('field_name', $formField->field_name ?? '') }}"
                   required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 text-sm font-mono"
                   placeholder="level_bahasa_jepang"
                   {{ $formField->exists ? 'readonly' : '' }}>
            <p class="text-xs text-slate-400 mt-1">
              Huruf kecil, angka, underscore saja. Contoh: <code>upload_cv</code>.
              @if($formField->exists) <span class="text-amber-600 font-semibold">Tidak bisa diubah setelah dibuat.</span> @endif
            </p>
            @error('field_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Field <span class="text-red-500">*</span></label>
            <select name="type" id="ff_type" required
                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary/20 text-sm">
              @foreach(config('dynamic_forms.allowed_field_types') as $ft)
                <option value="{{ $ft }}" {{ old('type', $formField->type ?? '') == $ft ? 'selected' : '' }}>{{ $ft }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- Placeholder & Description --}}
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">info</span> Placeholder & Petunjuk
        </h4>
        <div class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Placeholder (ID)</label>
              <input type="text" name="placeholder_id"
                     value="{{ old('placeholder_id', $formField->exists ? $formField->getTranslation('placeholder','id') : '') }}"
                     class="w-full rounded-lg border-slate-200 text-sm" placeholder="Contoh: Pilih level bahasa...">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Placeholder (JP)</label>
              <input type="text" name="placeholder_jp"
                     value="{{ old('placeholder_jp', $formField->exists ? $formField->getTranslation('placeholder','jp') : '') }}"
                     class="w-full rounded-lg border-slate-200 text-sm">
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi/Petunjuk (ID)</label>
              <textarea name="description_id" rows="2"
                        class="w-full rounded-lg border-slate-200 text-sm"
                        placeholder="Petunjuk pengisian untuk pengguna">{{ old('description_id', $formField->exists ? $formField->getTranslation('description','id') : '') }}</textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi/Petunjuk (JP)</label>
              <textarea name="description_jp" rows="2"
                        class="w-full rounded-lg border-slate-200 text-sm">{{ old('description_jp', $formField->exists ? $formField->getTranslation('description','jp') : '') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- Options Editor (choice fields) --}}
      <div id="section-options" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm" style="display:none">
        <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">list</span> Pilihan (Options)
          <span class="text-xs text-red-500 font-bold">* Wajib untuk select/radio/checkbox</span>
        </h4>
        <p class="text-xs text-slate-400 mb-4">Format JSON. Setiap opsi harus memiliki <code>value</code> unik dan <code>label</code> (id & jp).</p>
        <div id="options-rows" class="space-y-2 mb-4"></div>
        <button type="button" onclick="addOptionRow()"
                class="px-3 py-2 border border-dashed border-primary text-primary text-sm font-bold rounded-lg hover:bg-primary/5">
          + Tambah Opsi
        </button>
        {{-- Hidden textarea that gets populated before submit --}}
        <textarea name="options" id="ff_options_json" class="hidden">{{ old('options', $formField->options ? json_encode($formField->options) : '') }}</textarea>
        @error('options') <p class="text-xs text-red-600 mt-2">{{ $message }}</p> @enderror
      </div>

      {{-- File Config (file type) --}}
      <div id="section-file" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm" style="display:none">
        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">upload_file</span> Konfigurasi Upload File
        </h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Ekstensi yang Diizinkan</label>
            <div class="flex flex-wrap gap-3">
              @foreach(config('dynamic_forms.default_allowed_file_extensions') as $ext)
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" name="file_extensions[]" value="{{ $ext }}"
                         class="rounded border-slate-300 text-primary focus:ring-primary/20"
                         {{ in_array($ext, old('file_extensions', $formField->accepted_file_types ?? config('dynamic_forms.default_allowed_file_extensions'))) ? 'checked' : '' }}>
                  <span class="font-mono text-slate-700">.{{ $ext }}</span>
                </label>
              @endforeach
            </div>
            <input type="hidden" name="accepted_file_types" id="ff_accepted_file_types"
                   value="{{ old('accepted_file_types', $formField->accepted_file_types ? json_encode($formField->accepted_file_types) : '') }}">
            @error('accepted_file_types') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ukuran Maks (KB)</label>
            <input type="number" name="max_file_size"
                   value="{{ old('max_file_size', $formField->max_file_size ?? config('dynamic_forms.default_max_file_size')) }}"
                   min="1" class="w-48 rounded-lg border-slate-200 text-sm">
            <p class="text-xs text-slate-400 mt-1">Default: {{ config('dynamic_forms.default_max_file_size') }} KB ({{ config('dynamic_forms.default_max_file_size')/1024 }} MB)</p>
          </div>
        </div>
      </div>

    </div>

    {{-- Right: Settings --}}
    <div class="space-y-6">
      <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-24">
        <h4 class="font-bold text-slate-800 mb-4">Pengaturan Field</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
              <option value="aktif"    {{ old('status', $formField->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil ke User)</option>
              <option value="nonaktif" {{ old('status', $formField->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Urutan Tampil</label>
            <input type="number" name="sort_order"
                   value="{{ old('sort_order', $formField->sort_order ?? 0) }}"
                   min="0" class="w-full rounded-lg border-slate-200 text-sm">
            <p class="text-xs text-slate-400 mt-1">Angka kecil = tampil lebih dulu.</p>
          </div>
          <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
            <input type="hidden" name="is_required" value="0">
            <input type="checkbox" name="is_required" id="ff_is_required" value="1"
                   class="rounded border-slate-300 text-primary focus:ring-primary/20"
                   {{ old('is_required', $formField->is_required ?? false) ? 'checked' : '' }}>
            <label for="ff_is_required" class="text-sm font-medium text-slate-700 cursor-pointer">
              Field Wajib Diisi <span class="text-xs text-slate-400">(is_required)</span>
            </label>
          </div>
          <div class="pt-4 flex flex-col gap-2">
            <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined">save</span> Simpan
            </button>
            <a href="{{ route('admin.form-fields.index') }}" class="py-3 px-4 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all text-center text-sm">
              Batal
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('admin-scripts')
<script>
// ── Type switching ──────────────────────────────────────────────────────────
const choiceTypes = @json(config('dynamic_forms.choice_field_types'));
const ffType      = document.getElementById('ff_type');
const secOptions  = document.getElementById('section-options');
const secFile     = document.getElementById('section-file');

function switchType(type) {
  secOptions.style.display = choiceTypes.includes(type) ? '' : 'none';
  secFile.style.display    = type === 'file'            ? '' : 'none';
  if (type === 'file') rebuildFileTypes();
}
ffType.addEventListener('change', () => switchType(ffType.value));
switchType(ffType.value); // init on load

// ── Program → Schema AJAX ───────────────────────────────────────────────────
const ffProgram   = document.getElementById('ff_program_id');
const ffSchema    = document.getElementById('ff_schema_id');
const schemasUrl  = ffProgram.dataset.schemasUrl;
const currentSchema = '{{ old("schema_id", $formField->schema_id ?? "") }}';

function loadSchemas(programId, selectedId = null) {
  ffSchema.innerHTML = '<option value="">-- Umum Program (tanpa skema) --</option>';
  if (!programId) return;
  fetch(schemasUrl + '?program_id=' + programId)
    .then(r => r.json())
    .then(schemas => {
      schemas.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.nama_skema;
        if (String(s.id) === String(selectedId)) opt.selected = true;
        ffSchema.appendChild(opt);
      });
    });
}

ffProgram.addEventListener('change', () => loadSchemas(ffProgram.value));
// On edit page, reload schemas for current program
if (ffProgram.value) loadSchemas(ffProgram.value, currentSchema);

// ── Options Editor ──────────────────────────────────────────────────────────
let optionRows = [];

function loadExistingOptions() {
  const raw = document.getElementById('ff_options_json').value;
  try {
    const parsed = JSON.parse(raw);
    if (Array.isArray(parsed)) {
      optionRows = parsed;
      renderOptionRows();
    }
  } catch(e) {}
}

function renderOptionRows() {
  const container = document.getElementById('options-rows');
  container.innerHTML = '';
  optionRows.forEach((opt, i) => {
    const row = document.createElement('div');
    row.className = 'grid grid-cols-[1fr_1fr_1fr_auto] gap-2 items-center bg-slate-50 p-3 rounded-lg';
    row.innerHTML = `
      <input type="text" placeholder="value (slug)" value="${opt.value || ''}"
             class="rounded border-slate-200 text-sm font-mono" oninput="updateOption(${i},'value',this.value)">
      <input type="text" placeholder="Label ID" value="${opt.label?.id || ''}"
             class="rounded border-slate-200 text-sm" oninput="updateOption(${i},'label_id',this.value)">
      <input type="text" placeholder="Label JP" value="${opt.label?.jp || ''}"
             class="rounded border-slate-200 text-sm" oninput="updateOption(${i},'label_jp',this.value)">
      <button type="button" onclick="removeOption(${i})" class="text-red-400 hover:text-red-600">
        <span class="material-symbols-outlined text-[18px]">delete</span>
      </button>`;
    container.appendChild(row);
  });
  syncOptionsJson();
}

function updateOption(i, field, val) {
  if (field === 'value') optionRows[i].value = val;
  else if (field === 'label_id') { if(!optionRows[i].label) optionRows[i].label = {}; optionRows[i].label.id = val; }
  else if (field === 'label_jp') { if(!optionRows[i].label) optionRows[i].label = {}; optionRows[i].label.jp = val; }
  syncOptionsJson();
}

function removeOption(i) { optionRows.splice(i, 1); renderOptionRows(); }

function addOptionRow() {
  optionRows.push({ value: '', label: { id: '', jp: '' } });
  renderOptionRows();
}

function syncOptionsJson() {
  document.getElementById('ff_options_json').value = JSON.stringify(optionRows);
}

loadExistingOptions();

// ── File Types: checkboxes → hidden JSON ───────────────────────────────────
function rebuildFileTypes() {
  const checked = [...document.querySelectorAll('input[name="file_extensions[]"]:checked')]
    .map(cb => cb.value);
  document.getElementById('ff_accepted_file_types').value = JSON.stringify(checked);
}
document.querySelectorAll('input[name="file_extensions[]"]').forEach(cb => {
  cb.addEventListener('change', rebuildFileTypes);
});
rebuildFileTypes(); // init

// Before form submit, sync hidden JSON fields
document.querySelector('form').addEventListener('submit', function() {
  syncOptionsJson();
  rebuildFileTypes();
});
</script>
@endsection
