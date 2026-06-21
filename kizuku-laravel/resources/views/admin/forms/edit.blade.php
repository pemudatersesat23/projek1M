@extends('layouts.admin')

@section('admin-title', 'Edit Pengaturan Form')

@section('admin-content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Edit Pengaturan Formulir</h3>
        <p class="text-sm text-slate-500 mt-1">Ubah judul, program, skema, atau batch target formulir ini.</p>
    </div>

    <form action="{{ route('admin.forms.update', $form->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PATCH')
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Formulir (ID) <span class="text-red-500">*</span></label>
            <input type="text" name="title_id" value="{{ old('title_id', $form->getTranslation('title', 'id', false)) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary" placeholder="Contoh: Form Pendaftaran Beasiswa 2026">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Program <span class="text-red-500">*</span></label>
            <select name="program_id" id="program_id" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Pilih Program</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ (old('program_id', $form->program_id) == $program->id) ? 'selected' : '' }}>
                        {{ $program->nama_program }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Skema Program (Opsional)</label>
            <select name="schema_id" id="schema_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary" data-selected="{{ old('schema_id', $form->schema_id) }}">
                <option value="">Berlaku untuk semua skema / Program Umum</option>
            </select>
            <p class="text-xs text-slate-500 mt-1">Pilih jika form ini hanya berlaku untuk skema spesifik.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Batch / Gelombang (Opsional)</label>
            <select name="batch_id" id="batch_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary" data-selected="{{ old('batch_id', $form->batch_id) }}">
                <option value="">Berlaku untuk semua batch di program ini</option>
            </select>
            <p class="text-xs text-slate-500 mt-1">Pilih jika form ini khusus dibuat hanya untuk Batch tertentu.</p>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('admin.forms.index') }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium text-sm transition-colors">Batal</a>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 font-medium text-sm transition-colors">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@section('admin-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('program_id');
    const schemaSelect = document.getElementById('schema_id');
    const batchSelect = document.getElementById('batch_id');

    function loadDependencies(programId) {
        if (!programId) return;
        
        const selectedSchema = schemaSelect.dataset.selected;
        const selectedBatch = batchSelect.dataset.selected;

        schemaSelect.innerHTML = '<option value="">Berlaku untuk semua skema / Program Umum</option>';
        batchSelect.innerHTML = '<option value="">Berlaku untuk semua batch di program ini</option>';
        
        // Fetch schemas
        fetch(`/dashboard-admin/form-dependencies/schemas?program_id=${programId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(schema => {
                    const option = document.createElement('option');
                    option.value = schema.id;
                    option.textContent = schema.nama_skema;
                    if (schema.id == selectedSchema) option.selected = true;
                    schemaSelect.appendChild(option);
                });
            })
            .catch(err => console.error('Error fetching schemas:', err));
            
        // Fetch batches
        fetch(`/dashboard-admin/form-dependencies/batches?program_id=${programId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(batch => {
                    const option = document.createElement('option');
                    option.value = batch.id;
                    option.textContent = batch.nama_batch;
                    if (batch.id == selectedBatch) option.selected = true;
                    batchSelect.appendChild(option);
                });
            })
            .catch(err => console.error('Error fetching batches:', err));
    }

    programSelect.addEventListener('change', function() {
        schemaSelect.dataset.selected = '';
        batchSelect.dataset.selected = '';
        loadDependencies(this.value);
    });

    // Load on initialization if program is selected
    if (programSelect.value) {
        loadDependencies(programSelect.value);
    }
});
</script>
@endsection
