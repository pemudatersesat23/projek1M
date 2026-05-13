@extends('layouts.admin')

@php
    $formTitleId = $form->getTranslation('title', 'id', false) ?: '';
    $formTitleJp = $form->getTranslation('title', 'jp', false) ?: '';
    $formDescriptionId = $form->getTranslation('description', 'id', false) ?: '';
    $formDescriptionJp = $form->getTranslation('description', 'jp', false) ?: '';
    $formSuccessId = $form->getTranslation('success_message', 'id', false) ?: '';
    $formSuccessJp = $form->getTranslation('success_message', 'jp', false) ?: '';
@endphp

@section('admin-title', 'Form Builder - ' . ($formTitleId ?: 'Untitled'))

@section('admin-styles')
<style>
    /* Google Forms Builder styles */
    .builder-card { transition: all 0.2s; border-left: 4px solid transparent; }
    .builder-card.active { border-left-color: #0067a3; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); }
    .drag-handle { cursor: grab; }
    .drag-handle:active { cursor: grabbing; }
    .option-row:hover .delete-option-btn { opacity: 1; }
</style>
@endsection

@section('admin-content')
<div class="max-w-4xl mx-auto pb-20" id="form-builder-app" data-form-id="{{ $form->id }}">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200 sticky top-[72px] z-20">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.forms.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-bold text-slate-800 text-lg leading-tight">{{ $formTitleId ?: 'Untitled' }}</h2>
                <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                    <span id="status-badge" class="font-medium {{ $form->status === 'published' ? 'text-emerald-600' : ($form->status === 'draft' ? 'text-amber-600' : 'text-slate-500') }}">
                        {{ ucfirst($form->status) }}
                    </span>
                    <span>&bull;</span>
                    <span>Disimpan otomatis</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Tab navigation: Questions / Responses --}}
            <div class="hidden sm:flex items-center gap-1 bg-slate-100 border border-slate-200 rounded-lg p-1">
                <a href="{{ route('admin.forms.builder', $form->id) }}"
                   class="tab-nav-btn px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-1.5
                          bg-primary text-white shadow-sm">
                    <span class="material-symbols-outlined text-[15px]">edit_note</span> Questions
                </a>
                <a href="{{ route('admin.forms.responses.index', $form->id) }}"
                   class="tab-nav-btn px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-1.5
                          text-slate-500 hover:bg-white hover:text-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-[15px]">inbox</span> Responses
                </a>
            </div>
            <div class="w-px h-6 bg-slate-200 mx-1"></div>
            <a href="{{ route('admin.forms.preview', $form->id) }}" target="_blank" class="p-2 text-slate-500 hover:text-primary hover:bg-primary/10 rounded-full transition-colors tooltip" title="Preview Form">
                <span class="material-symbols-outlined">visibility</span>
            </a>
            <div class="w-px h-6 bg-slate-200 mx-1"></div>
            @if($form->status === 'published')
                <button onclick="setFormStatus('draft')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium text-sm">
                    Jadikan Draft
                </button>
            @else
                <button onclick="publishForm()" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors font-medium text-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">publish</span> Publish
                </button>
            @endif
        </div>
    </div>

    <!-- Form Metadata Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6 border-t-8 border-t-primary builder-card active" id="meta-card" onclick="activateCard('meta-card')">
        <div class="p-6 space-y-4">
            <div>
                <input type="text" id="form-title-id" value="{{ $formTitleId }}" class="w-full text-2xl md:text-3xl font-bold border-0 border-b border-transparent hover:border-slate-300 focus:border-primary focus:ring-0 px-0 py-2 transition-colors bg-transparent placeholder-slate-300" placeholder="Judul Formulir" onchange="saveMetadata()">
            </div>
            <div>
                <textarea id="form-desc-id" rows="2" class="w-full text-sm text-slate-600 border-0 border-b border-transparent hover:border-slate-300 focus:border-primary focus:ring-0 px-0 py-1 transition-colors bg-transparent placeholder-slate-400 resize-none" placeholder="Deskripsi formulir" onchange="saveMetadata()">{{ $formDescriptionId }}</textarea>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-100 hidden" id="meta-advanced">
                <p class="text-xs font-bold text-slate-400 uppercase mb-3">Terjemahan Jepang & Pesan Sukses</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Judul (JP)</label>
                        <input type="text" id="form-title-jp" value="{{ $formTitleJp }}" class="w-full text-sm border-slate-300 rounded focus:border-primary focus:ring-primary" placeholder="Judul (Opsional)" onchange="saveMetadata()">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Deskripsi (JP)</label>
                        <input type="text" id="form-desc-jp" value="{{ $formDescriptionJp }}" class="w-full text-sm border-slate-300 rounded focus:border-primary focus:ring-primary" placeholder="Deskripsi (Opsional)" onchange="saveMetadata()">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Pesan Sukses (ID)</label>
                        <input type="text" id="form-success-id" value="{{ $formSuccessId }}" class="w-full text-sm border-slate-300 rounded focus:border-primary focus:ring-primary" placeholder="Terima kasih, data Anda telah kami terima." onchange="saveMetadata()">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Pesan Sukses (JP)</label>
                        <input type="text" id="form-success-jp" value="{{ $formSuccessJp }}" class="w-full text-sm border-slate-300 rounded focus:border-primary focus:ring-primary" placeholder="Terjemahan Jepang" onchange="saveMetadata()">
                    </div>
                </div>
            </div>
            <button onclick="document.getElementById('meta-advanced').classList.toggle('hidden')" class="text-xs text-primary hover:underline mt-2">Toggle Advanced Settings</button>
        </div>
    </div>

    <!-- Fields Container -->
    <div id="fields-container" class="space-y-4 relative">
        <!-- Cards will be injected here by JS -->
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-8 right-8 z-30">
        <button onclick="addField()" class="w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center hover:bg-primary/90 hover:scale-105 transition-all focus:outline-none focus:ring-4 focus:ring-primary/30">
            <span class="material-symbols-outlined text-[28px]">add</span>
        </button>
    </div>

</div>

<!-- Template Card -->
<template id="field-card-template">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden builder-card field-card group relative" tabindex="0">
        <!-- Drag handle -->
        <div class="h-6 flex items-center justify-center cursor-grab drag-handle text-slate-300 hover:text-slate-500 bg-slate-50/50" title="Geser untuk mengurutkan">
            <span class="material-symbols-outlined text-[18px]">drag_indicator</span>
        </div>
        
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-4 items-start">
                <!-- Label & Name -->
                <div class="flex-1 space-y-3 w-full">
                    <input type="text" class="field-label-id w-full text-base font-medium border-0 border-b border-transparent hover:border-slate-300 focus:border-primary focus:ring-0 px-0 py-1 transition-colors bg-slate-50/50 focus:bg-transparent" placeholder="Pertanyaan">
                    <input type="text" class="field-name-input w-full text-xs font-mono text-slate-500 border-0 border-b border-transparent hover:border-slate-300 focus:border-primary focus:ring-0 px-0 py-0.5 transition-colors bg-transparent" placeholder="nama_variabel_db (A-Z, 0-9, _)">
                </div>
                
                <!-- Type & Role Selectors -->
                <div class="w-full md:w-64 space-y-3 shrink-0">
                    <select class="field-type-select w-full text-sm border-slate-300 rounded-lg focus:border-primary focus:ring-primary bg-slate-50">
                        <optgroup label="Teks">
                            <option value="text">Jawaban Singkat (Text)</option>
                            <option value="textarea">Paragraf (Textarea)</option>
                            <option value="email">Email</option>
                            <option value="phone">Telepon/WhatsApp</option>
                            <option value="number">Angka (Number)</option>
                        </optgroup>
                        <optgroup label="Pilihan">
                            <option value="select">Dropdown (Select)</option>
                            <option value="radio">Pilihan Ganda (Radio)</option>
                            <option value="checkbox">Kotak Centang (Checkbox)</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="date">Tanggal (Date)</option>
                            <option value="file">Upload File</option>
                        </optgroup>
                    </select>
                    
                    <select class="field-role-select w-full text-xs border-slate-200 rounded text-slate-600 focus:border-primary focus:ring-primary">
                        <option value="none">-- Tanpa Role Spesifik --</option>
                        <option value="applicant_name">Nama Pemohon Utama (Wajib 1)</option>
                        <option value="applicant_email">Email Utama</option>
                        <option value="applicant_phone">WhatsApp Utama</option>
                        <option value="applicant_birth_date">Tanggal Lahir</option>
                        <option value="applicant_address">Alamat</option>
                        <option value="applicant_education">Pendidikan</option>
                    </select>
                </div>
            </div>

            <!-- Options Editor Container (Hidden by default) -->
            <div class="options-container mt-6 hidden">
                <p class="text-xs font-medium text-slate-500 mb-2">Opsi Pilihan:</p>
                <div class="options-list space-y-2">
                    <!-- Options injected here -->
                </div>
                <button type="button" class="add-option-btn mt-2 text-sm text-primary hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">add_circle</span> Tambah Opsi
                </button>
            </div>

            <!-- File Config Container (Hidden by default) -->
            <div class="file-config-container mt-6 hidden bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-700 mb-2">Ekstensi Diizinkan:</p>
                        <div class="flex flex-wrap gap-2 file-ext-checkboxes">
                            <label class="inline-flex items-center text-sm"><input type="checkbox" value="pdf" class="rounded border-slate-300 text-primary focus:ring-primary mr-1"> PDF</label>
                            <label class="inline-flex items-center text-sm"><input type="checkbox" value="jpg" class="rounded border-slate-300 text-primary focus:ring-primary mr-1"> JPG</label>
                            <label class="inline-flex items-center text-sm"><input type="checkbox" value="jpeg" class="rounded border-slate-300 text-primary focus:ring-primary mr-1"> JPEG</label>
                            <label class="inline-flex items-center text-sm"><input type="checkbox" value="png" class="rounded border-slate-300 text-primary focus:ring-primary mr-1"> PNG</label>
                            <label class="inline-flex items-center text-sm"><input type="checkbox" value="doc" class="rounded border-slate-300 text-primary focus:ring-primary mr-1"> DOC</label>
                            <label class="inline-flex items-center text-sm"><input type="checkbox" value="docx" class="rounded border-slate-300 text-primary focus:ring-primary mr-1"> DOCX</label>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-700 mb-2">Maksimal Ukuran (KB):</p>
                        <input type="number" class="file-max-size w-full text-sm border-slate-300 rounded focus:border-primary focus:ring-primary" value="2048">
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-slate-500 mr-2">Wajib diisi</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer field-required-toggle">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                    <button class="save-field-btn p-2 text-emerald-600 hover:bg-emerald-50 rounded-full transition-colors tooltip" title="Simpan Perubahan">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                    </button>
                    <div class="w-px h-4 bg-slate-200 mx-1"></div>
                    <button class="duplicate-field-btn p-2 text-slate-500 hover:text-primary hover:bg-primary/10 rounded-full transition-colors tooltip" title="Duplikasi">
                        <span class="material-symbols-outlined text-[20px]">content_copy</span>
                    </button>
                    <button class="delete-field-btn p-2 text-slate-500 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors tooltip" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@section('admin-scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const formId = {{ $form->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Initial data from server
    let fields = @json($form->fields);
    
    const container = document.getElementById('fields-container');
    const template = document.getElementById('field-card-template');

    // Initialize Sortable
    new Sortable(container, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'opacity-50',
        onEnd: function (evt) {
            reorderFields();
        }
    });

    // Helper: Find field object
    function getFieldObj(id) {
        return fields.find(f => f.id == id);
    }

    // Render all fields
    function renderAll() {
        container.innerHTML = '';
        fields.sort((a,b) => a.sort_order - b.sort_order).forEach(field => {
            renderCard(field);
        });
    }

    // Render single card
    function renderCard(field) {
        const clone = template.content.cloneNode(true);
        const card = clone.querySelector('.field-card');
        card.dataset.id = field.id;
        
        // Locked field visual
        if (field.is_locked) {
            card.classList.add('border-l-amber-400');
            card.querySelector('.field-name-input').readOnly = true;
            card.querySelector('.field-name-input').classList.add('bg-slate-100', 'cursor-not-allowed');
            card.querySelector('.delete-field-btn').remove();
        }

        // Fill data
        card.querySelector('.field-label-id').value = field.label?.id || '';
        card.querySelector('.field-name-input').value = field.field_name || '';
        card.querySelector('.field-type-select').value = field.type || 'text';
        card.querySelector('.field-role-select').value = field.field_role || 'none';
        card.querySelector('.field-required-toggle').checked = field.is_required || false;

        // Type Change Listener
        const typeSelect = card.querySelector('.field-type-select');
        typeSelect.addEventListener('change', (e) => toggleTypeUI(card, e.target.value));
        toggleTypeUI(card, field.type); // Init UI

        // Options specific
        if (['select', 'radio', 'checkbox'].includes(field.type)) {
            renderOptions(card, field.options || []);
        }

        // File specific
        if (field.type === 'file') {
            const exts = field.accepted_file_types || [];
            card.querySelectorAll('.file-ext-checkboxes input').forEach(cb => {
                cb.checked = exts.includes(cb.value);
            });
            card.querySelector('.file-max-size').value = field.max_file_size || 2048;
        }

        // Event Listeners
        card.addEventListener('click', () => activateCard(card));
        
        card.querySelector('.add-option-btn').addEventListener('click', () => {
            addOptionRow(card);
        });

        card.querySelector('.save-field-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            saveField(card);
        });

        card.querySelector('.duplicate-field-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            duplicateField(field.id);
        });

        if (!field.is_locked) {
            card.querySelector('.delete-field-btn')?.addEventListener('click', (e) => {
                e.stopPropagation();
                deleteField(field.id, card);
            });
        }

        container.appendChild(card);
    }

    // Toggle Type UI (show/hide options or file config)
    function toggleTypeUI(card, type) {
        const optsDiv = card.querySelector('.options-container');
        const fileDiv = card.querySelector('.file-config-container');
        
        optsDiv.classList.add('hidden');
        fileDiv.classList.add('hidden');

        if (['select', 'radio', 'checkbox'].includes(type)) {
            optsDiv.classList.remove('hidden');
            if(optsDiv.querySelector('.options-list').children.length === 0){
                addOptionRow(card, {value: 'opsi_1', label: {id: 'Opsi 1'}});
            }
        } else if (type === 'file') {
            fileDiv.classList.remove('hidden');
        }
    }

    // Render Options List
    function renderOptions(card, options) {
        const list = card.querySelector('.options-list');
        list.innerHTML = '';
        if(!options || options.length === 0) {
            addOptionRow(card, {value: 'opsi_1', label: {id: 'Opsi 1'}});
            return;
        }
        options.forEach(opt => addOptionRow(card, opt));
    }

    // Add Option Row
    function addOptionRow(card, optData = null) {
        const list = card.querySelector('.options-list');
        const count = list.children.length + 1;
        const data = optData || {value: 'opsi_'+count, label: {id: 'Opsi '+count}};
        
        const row = document.createElement('div');
        row.className = 'option-row flex items-center gap-2 group';
        row.innerHTML = `
            <span class="material-symbols-outlined text-slate-300 text-[18px]">radio_button_unchecked</span>
            <input type="text" class="opt-label flex-1 text-sm border-0 border-b border-transparent hover:border-slate-300 focus:border-primary focus:ring-0 px-0 py-1 bg-transparent" placeholder="Label Opsi" value="${data.label?.id || ''}">
            <input type="text" class="opt-value w-24 text-xs font-mono text-slate-400 border-0 border-b border-transparent hover:border-slate-300 focus:border-primary focus:ring-0 px-0 py-1 bg-transparent" placeholder="value" value="${data.value || ''}">
            <button type="button" class="delete-option-btn p-1 text-slate-400 hover:text-red-500 opacity-0 transition-opacity"><span class="material-symbols-outlined text-[18px]">close</span></button>
        `;
        
        row.querySelector('.delete-option-btn').addEventListener('click', () => {
            if(list.children.length > 1) row.remove();
        });
        
        list.appendChild(row);
    }

    // Active Card Styling
    function activateCard(activeElem) {
        document.querySelectorAll('.builder-card').forEach(c => c.classList.remove('active'));
        if(typeof activeElem === 'string') {
            document.getElementById(activeElem).classList.add('active');
        } else {
            activeElem.classList.add('active');
        }
    }

    // Generate random string for new field names
    function generateId(length = 6) {
        return Math.random().toString(36).substring(2, 2+length);
    }

    // Add New Field via AJAX
    async function addField() {
        const dummyName = 'q_' + generateId();
        const payload = {
            program_id: {{ $form->program_id }},
            schema_id: {{ $form->schema_id ?? 'null' }},
            label_id: 'Pertanyaan Baru',
            field_name: dummyName,
            type: 'text',
            status: 'aktif'
        };

        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(res.ok) {
                fields.push(data.field);
                renderAll();
                // scroll to bottom
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                showToast('Pertanyaan ditambahkan');
            } else {
                alert(data.message || 'Terjadi kesalahan.');
            }
        } catch(e) { console.error(e); }
    }

    // Save Existing Field via AJAX
    async function saveField(card) {
        const id = card.dataset.id;
        const type = card.querySelector('.field-type-select').value;
        
        const payload = {
            program_id: {{ $form->program_id }},
            schema_id: {{ $form->schema_id ?? 'null' }},
            label_id: card.querySelector('.field-label-id').value || 'Untitled',
            field_name: card.querySelector('.field-name-input').value,
            type: type,
            field_role: card.querySelector('.field-role-select').value,
            is_required: card.querySelector('.field-required-toggle').checked ? 1 : 0,
            status: 'aktif'
        };

        // Options logic
        if (['select', 'radio', 'checkbox'].includes(type)) {
            const opts = [];
            card.querySelectorAll('.option-row').forEach(row => {
                opts.push({
                    value: row.querySelector('.opt-value').value,
                    label: { id: row.querySelector('.opt-label').value }
                });
            });
            payload.options = JSON.stringify(opts);
        }

        // File logic
        if (type === 'file') {
            const exts = [];
            card.querySelectorAll('.file-ext-checkboxes input:checked').forEach(cb => exts.push(cb.value));
            payload.accepted_file_types = JSON.stringify(exts);
            payload.max_file_size = card.querySelector('.file-max-size').value;
        }

        try {
            const saveBtn = card.querySelector('.save-field-btn');
            saveBtn.innerHTML = '<span class="material-symbols-outlined text-[20px] animate-spin">refresh</span>';
            
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields/${id}`, {
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(res.ok) {
                const index = fields.findIndex(f => f.id == id);
                if(index !== -1) fields[index] = data.field;
                showToast('Berhasil disimpan');
            } else {
                alert(data.message || 'Validasi gagal.');
            }
            saveBtn.innerHTML = '<span class="material-symbols-outlined text-[20px]">save</span>';
        } catch(e) { console.error(e); }
    }

    // Duplicate Field
    async function duplicateField(id) {
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields/${id}/duplicate`, {
                method: 'POST',
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken}
            });
            const data = await res.json();
            if(res.ok) {
                const newField = data.field;
                // Increment sort_order for local state consistency
                fields.forEach(f => {
                    if (f.sort_order >= newField.sort_order) {
                        f.sort_order++;
                    }
                });
                fields.push(newField);
                renderAll();
                showToast('Pertanyaan diduplikasi');
            }
        } catch(e) { console.error(e); }
    }

    // Delete Field
    async function deleteField(id, card) {
        if(!confirm('Yakin ingin menghapus pertanyaan ini?')) return;
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/fields/${id}`, {
                method: 'DELETE',
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken}
            });
            if(res.ok) {
                fields = fields.filter(f => f.id != id);
                card.remove();
                showToast('Pertanyaan dihapus');
            } else {
                const data = await res.json();
                alert(data.message);
            }
        } catch(e) { console.error(e); }
    }

    // Reorder Fields
    async function reorderFields() {
        const order = Array.from(container.children).map(card => card.dataset.id);
        // update local state
        order.forEach((id, index) => {
            const f = fields.find(x => x.id == id);
            if(f) f.sort_order = index + 1;
        });

        try {
            await fetch(`/dashboard-admin/forms/${formId}/fields/reorder`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                body: JSON.stringify({order})
            });
            showToast('Urutan disimpan');
        } catch(e) { console.error(e); }
    }

    // Save Metadata
    let metaTimeout;
    function saveMetadata() {
        clearTimeout(metaTimeout);
        metaTimeout = setTimeout(async () => {
            const payload = {
                program_id: {{ $form->program_id }},
                schema_id: {{ $form->schema_id ?? 'null' }},
                title_id: document.getElementById('form-title-id').value || 'Untitled',
                title_jp: document.getElementById('form-title-jp').value,
                description_id: document.getElementById('form-desc-id').value,
                description_jp: document.getElementById('form-desc-jp').value,
                success_message_id: document.getElementById('form-success-id').value,
                success_message_jp: document.getElementById('form-success-jp').value,
            };

            try {
                await fetch(`/dashboard-admin/forms/${formId}`, {
                    method: 'PATCH',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                    body: JSON.stringify(payload)
                });
                showToast('Metadata disimpan');
            } catch(e) { console.error(e); }
        }, 1000); // debounce 1s
    }

    // Publish logic
    async function publishForm() {
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/publish`, {
                method: 'POST',
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken}
            });
            const data = await res.json();
            if(res.ok) {
                alert('Success! Form published.');
                location.reload();
            } else {
                alert(data.message || 'Gagal mempublish form.');
            }
        } catch(e) { console.error(e); }
    }

    async function setFormStatus(status) {
        try {
            const res = await fetch(`/dashboard-admin/forms/${formId}/${status}`, {
                method: 'POST',
                headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken}
            });
            if(res.ok) {
                location.reload();
            }
        } catch(e) { console.error(e); }
    }

    // Simple Toast notification
    function showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 bg-slate-800 text-white px-4 py-2 rounded-full text-sm font-medium shadow-lg z-50 transition-opacity duration-300';
        toast.textContent = msg;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 2000);
    }

    // Initial render
    renderAll();
</script>
@endsection
