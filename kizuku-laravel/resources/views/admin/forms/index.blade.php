@extends('layouts.admin')

@section('admin-title', 'Form Builder Baru')

@section('admin-content')

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Manajemen Formulir Pendaftaran</h3>
            <p class="text-sm text-slate-500 mt-1">Buat dan kelola pertanyaan formulir secara dinamis untuk setiap program.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.forms.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium text-sm">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Buat Form Baru
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="p-4 border-b border-slate-200 bg-slate-50">
        <form method="GET" action="{{ route('admin.forms.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Program</label>
                <select name="program_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    <option value="">Semua Program</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>
                            {{ $program->nama_program }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium text-sm">Filter</button>
                <a href="{{ route('admin.forms.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700 text-sm ml-2">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Title / Program</th>
                    <th class="py-3 px-4">Konteks</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-center">Pertanyaan</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($forms as $form)
                @php
                    $formTitle = $form->getTranslation('title', 'id', false) ?: 'Untitled';
                    $isPublished = $form->status === 'published';
                @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3 px-4">
                        <div class="font-medium text-slate-900">{{ $formTitle }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $form->program?->nama_program ?? '-' }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="text-slate-600">
                            @if($form->schema)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    Skema: {{ $form->schema->nama_skema }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    Program Umum
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        @if($form->status === 'published')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Published
                            </span>
                        @elseif($form->status === 'draft')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                Archived
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-medium text-xs">
                            {{ $form->fields_count }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.forms.preview', $form->id) }}" target="_blank" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded transition-colors" title="Preview">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </a>
                            <a href="{{ route('admin.forms.builder', $form->id) }}" class="p-1.5 text-primary hover:bg-primary/10 rounded transition-colors" title="Buka Builder">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <a href="{{ route('admin.forms.responses.index', $form->id) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors" title="Responses">
                                <span class="material-symbols-outlined text-[20px]">inbox</span>
                            </a>
                            {{-- Tombol Hapus --}}
                            <button
                                type="button"
                                onclick="confirmDelete({{ $form->id }}, '{{ addslashes($formTitle) }}', {{ $isPublished ? 'true' : 'false' }})"
                                class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                title="Hapus Form"
                            >
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">description</span>
                        <p>Belum ada form yang dibuat.</p>
                        <a href="{{ route('admin.forms.create') }}" class="text-primary hover:underline mt-2 inline-block">Buat Form Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($forms->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $forms->links() }}
    </div>
    @endif
</div>

{{-- Hidden DELETE form --}}
<form id="delete-form" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

{{-- Modal Konfirmasi Hapus --}}
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="cancelDelete()"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 animate-fadeIn">
        {{-- Ikon peringatan --}}
        <div class="flex items-center justify-center w-14 h-14 bg-red-100 rounded-full mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[32px]">delete_forever</span>
        </div>

        <h3 class="text-lg font-bold text-slate-800 text-center mb-1">Hapus Form?</h3>
        <p class="text-sm text-slate-500 text-center mb-1">Anda akan menghapus form:</p>
        <p id="modal-form-title" class="text-sm font-semibold text-slate-800 text-center mb-4 px-4 py-2 bg-slate-50 rounded-lg border border-slate-200"></p>
        <p id="modal-warning-normal" class="text-xs text-red-600 text-center mb-6 bg-red-50 border border-red-200 rounded-lg px-3 py-2 hidden">
            ⚠️ Tindakan ini akan menghapus form beserta semua pertanyaannya secara permanen dan <strong>tidak dapat dibatalkan</strong>.
        </p>
        <p id="modal-warning-published" class="text-xs text-red-700 text-center mb-6 bg-red-50 border border-red-300 rounded-lg px-3 py-2 hidden">
            🚨 Form ini sedang <strong>Published</strong>. Menghapusnya akan menghentikan penerimaan pendaftaran dan menghapus semua pertanyaan secara <strong>permanen</strong>.
        </p>

        <div class="flex gap-3">
            <button
                type="button"
                onclick="cancelDelete()"
                class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors text-sm"
            >
                Batal
            </button>
            <button
                type="button"
                onclick="executeDelete()"
                class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors text-sm flex items-center justify-center gap-2"
            >
                <span class="material-symbols-outlined text-[18px]">delete</span>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(-8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.18s ease-out forwards; }
</style>

<script>
    let deleteTargetId = null;

    function confirmDelete(formId, formTitle, isPublished) {
        deleteTargetId = formId;
        document.getElementById('modal-form-title').textContent = formTitle;

        // Tampilkan peringatan sesuai status
        const warnNormal    = document.getElementById('modal-warning-normal');
        const warnPublished = document.getElementById('modal-warning-published');
        if (isPublished) {
            warnNormal.classList.add('hidden');
            warnPublished.classList.remove('hidden');
        } else {
            warnPublished.classList.add('hidden');
            warnNormal.classList.remove('hidden');
        }

        document.getElementById('delete-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cancelDelete() {
        deleteTargetId = null;
        document.getElementById('delete-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function executeDelete() {
        if (!deleteTargetId) return;
        const form = document.getElementById('delete-form');
        form.action = `/dashboard-admin/forms/${deleteTargetId}`;
        form.submit();
    }

    // Tutup modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') cancelDelete();
    });

    // Auto-dismiss flash messages setelah 5 detik
    setTimeout(() => {
        ['flash-success', 'flash-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.remove();
        });
    }, 5000);
</script>

@endsection
