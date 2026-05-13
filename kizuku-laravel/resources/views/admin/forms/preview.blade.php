@extends('layouts.admin')

@section('admin-title', 'Preview: ' . ($form->title['id'] ?? 'Untitled'))

@section('admin-content')
<div class="max-w-3xl mx-auto pb-20">
    
    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-6 bg-amber-50 border border-amber-200 p-4 rounded-xl shadow-sm">
        <div class="flex items-center gap-3 text-amber-800">
            <span class="material-symbols-outlined">visibility</span>
            <div>
                <p class="font-bold text-sm">Mode Preview</p>
                <p class="text-xs">Ini adalah pratinjau form. Data yang dikirim di sini tidak akan disimpan.</p>
            </div>
        </div>
        <a href="{{ route('admin.forms.builder', $form->id) }}" class="px-4 py-2 bg-white border border-amber-300 text-amber-700 rounded-lg hover:bg-amber-100 transition-colors font-medium text-sm">
            Tutup Preview
        </a>
    </div>

    <!-- The Form Simulation -->
    <div class="bg-white rounded-xl shadow-md border-t-8 border-t-primary overflow-hidden">
        
        <!-- Header -->
        <div class="p-8 border-b border-slate-200">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $form->title['id'] ?? 'Untitled Form' }}</h1>
            @if(!empty($form->description['id']))
                <p class="text-slate-600 whitespace-pre-line">{{ $form->description['id'] }}</p>
            @endif
            <div class="mt-4 text-xs font-medium text-red-500">* Menunjukkan pertanyaan yang wajib diisi</div>
        </div>

        <!-- Form Fields -->
        <form class="p-8 space-y-8" onsubmit="event.preventDefault(); alert('Ini hanya preview, data tidak disimpan.');">
            @forelse($form->fields as $field)
                @if($field->status === 'aktif')
                    <div class="p-6 border border-slate-200 rounded-xl bg-slate-50/30">
                        @include('components.dynamic-form.field', ['field' => $field, 'locale' => 'id'])
                    </div>
                @endif
            @empty
                <div class="text-center py-8 text-slate-500">
                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">hourglass_empty</span>
                    <p>Form ini belum memiliki field aktif.</p>
                </div>
            @endforelse

            <!-- Submit Button (Disabled) -->
            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-md">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
