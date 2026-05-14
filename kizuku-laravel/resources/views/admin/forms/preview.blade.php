@extends('layouts.admin')

@php
    $formTitleId = $form->getTranslation('title', 'id', false) ?: 'Untitled Form';
    $formDescriptionId = $form->getTranslation('description', 'id', false) ?: '';
@endphp

@section('admin-title', 'Preview: ' . $formTitleId)

@section('admin-styles')
<style>
    /* Google Forms-like Aesthetics for Preview */
    :root {
        --google-purple: #673ab7;
        --google-bg: #f0ebf8;
    }

    body {
        background-color: var(--google-bg) !important;
    }

    main {
        background-color: var(--google-bg) !important;
    }

    .preview-container {
        max-width: 770px;
        margin: 0 auto;
    }

    .google-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #dadce0;
        margin-bottom: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .header-card {
        border-top: 10px solid var(--google-purple);
    }

    .sticky-action-bar {
        background: white;
        border-bottom: 1px solid #dadce0;
        position: sticky;
        top: 0;
        z-index: 50;
        margin-left: -2rem;
        margin-right: -2rem;
        margin-top: -2rem;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .required-note {
        color: #d93025;
        font-size: 14px;
        margin-top: 12px;
    }

    /* Customizing component styles for preview */
    .dynamic-field-wrapper {
        width: 100% !important;
    }
    
    .input-label {
        font-size: 16px;
        font-weight: 500;
        color: #202124;
        margin-bottom: 8px;
        display: block;
    }

    .premium-input {
        border: none !important;
        border-bottom: 1px solid #dadce0 !important;
        border-radius: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        background: transparent !important;
        width: 100%;
        max-width: 300px; /* Short answer style */
    }

    .premium-input:focus {
        border-bottom: 2px solid var(--google-purple) !important;
        outline: none !important;
        box-shadow: none !important;
    }

    textarea.premium-input {
        max-width: 100%; /* Paragraph style */
    }

    .dynamic-radio-label, .dynamic-checkbox-label {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        cursor: pointer;
        font-size: 14px;
    }

    .dynamic-radio-label input, .dynamic-checkbox-label input {
        margin-right: 12px;
        width: 18px;
        height: 18px;
        accent-color: var(--google-purple);
    }

    .dynamic-field-hint {
        font-size: 12px;
        color: #70757a;
        margin-top: 4px;
    }

    .upload-zone {
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 16px;
        background: #f8f9fa;
        text-align: left;
        pointer-events: none; /* No real upload in preview */
    }

    .upload-icon {
        color: #5f6368;
    }

    .section-card {
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        margin-top: 24px;
    }
</style>
@endsection

@section('admin-content')
<div class="relative min-h-screen">

    <!-- Action Bar Sticky -->
    <div class="sticky-action-bar shadow-sm">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-amber-500">visibility</span>
            <div>
                <p class="text-sm font-bold text-slate-800">Mode Pratinjau</p>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Data tidak akan disimpan</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.forms.responses.index', $form->id) }}" class="px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                Lihat Jawaban
            </a>
            <a href="{{ route('admin.forms.builder', $form->id) }}" class="px-6 py-2 bg-purple-600 text-white font-bold text-sm rounded-lg hover:bg-purple-700 transition-colors shadow-md">
                Kembali ke Builder
            </a>
        </div>
    </div>

    <!-- Main Preview Canvas -->
    <div class="preview-container mt-8 pb-32 px-4 sm:px-0">
        
        <!-- Header Card -->
        <div class="google-card header-card">
            <h1 class="text-3xl font-medium text-[#202124] mb-2">{{ $formTitleId }}</h1>
            @if($formDescriptionId !== '')
                <p class="text-sm text-[#202124] whitespace-pre-line">{{ $formDescriptionId }}</p>
            @endif
            
            <div class="border-t border-slate-200 mt-6 pt-2">
                <p class="required-note">* Menunjukkan pertanyaan yang wajib diisi</p>
            </div>
        </div>

        <!-- Field Cards -->
        <form onsubmit="event.preventDefault(); alert('Ini adalah mode pratinjau. Form tidak dapat dikirim.');">
            @forelse($form->fields()->ordered()->get() as $field)
                @if($field->status === 'aktif')
                    @php
                        $settings = is_array($field->settings) ? $field->settings : (json_decode($field->settings, true) ?: []);
                        $accentColor = $settings['section_color'] ?? '#673ab7';
                    @endphp
                    <div class="google-card {{ $field->type === 'section' ? 'section-card' : '' }}" 
                         style="{{ $field->type === 'section' ? '' : 'border-left: 0;' }}">
                        @include('components.dynamic-form.field', ['field' => $field, 'locale' => 'id'])
                    </div>
                @endif
            @empty
                <div class="google-card text-center py-12">
                    <span class="material-symbols-outlined text-5xl text-slate-200 mb-2">empty_dashboard</span>
                    <p class="text-slate-500">Formulir ini belum memiliki pertanyaan aktif.</p>
                </div>
            @endforelse

            <!-- Submit Button Placeholder -->
            @if($form->fields()->where('status', 'aktif')->exists())
                <div class="flex justify-between items-center py-4">
                    <button type="submit" class="px-6 py-2 bg-purple-700 text-white font-medium rounded hover:bg-purple-800 transition-colors shadow-sm">
                        Kirim
                    </button>
                    <div class="flex gap-4">
                        <div class="h-2 w-24 bg-purple-100 rounded-full overflow-hidden">
                            <div class="h-full w-1/3 bg-purple-600"></div>
                        </div>
                        <span class="text-xs text-slate-500">Halaman 1 dari 1</span>
                    </div>
                </div>
                <div class="mt-8 text-center">
                    <p class="text-[10px] text-slate-400">Formulir ini dibuat di dalam LPK Kizuku Form Builder.</p>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
