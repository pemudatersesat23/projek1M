@extends('layouts.admin')

@section('admin-title', 'Buat Form Baru')

@section('admin-content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Buat Formulir Baru</h3>
        <p class="text-sm text-slate-500 mt-1">Isi informasi dasar formulir pendaftaran.</p>
    </div>

    <form action="{{ route('admin.forms.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Formulir (ID) <span class="text-red-500">*</span></label>
            <input type="text" name="title_id" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary" placeholder="Contoh: Form Pendaftaran Beasiswa 2026">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Formulir (JP)</label>
            <input type="text" name="title_jp" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary" placeholder="Contoh: 2026年奨学金申込書">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Program <span class="text-red-500">*</span></label>
            <select name="program_id" id="program_id" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Pilih Program</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama_program }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Skema Program (Opsional)</label>
            <select name="schema_id" id="schema_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Berlaku untuk semua skema / Program Umum</option>
            </select>
            <p class="text-xs text-slate-500 mt-1">Pilih jika form ini hanya berlaku untuk skema spesifik.</p>
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('admin.forms.index') }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium text-sm transition-colors">Batal</a>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 font-medium text-sm transition-colors">Buat & Lanjut ke Builder</button>
        </div>
    </form>
</div>

@endsection

@section('admin-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('program_id');
    const schemaSelect = document.getElementById('schema_id');

    programSelect.addEventListener('change', function() {
        const programId = this.value;
        schemaSelect.innerHTML = '<option value="">Berlaku untuk semua skema / Program Umum</option>';
        
        if (programId) {
            fetch(`/dashboard-admin/form-fields-schemas?program_id=${programId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(schema => {
                        const option = document.createElement('option');
                        option.value = schema.id;
                        option.textContent = schema.nama_skema;
                        schemaSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Error fetching schemas:', err));
        }
    });
});
</script>
@endsection
