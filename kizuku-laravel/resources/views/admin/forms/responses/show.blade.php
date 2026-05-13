@extends('layouts.admin')

@section('admin-title', 'Detail Response - ' . ($applicant->nama ?: 'Pendaftar'))

@section('admin-styles')
<style>
    .detail-label {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        margin-bottom: 2px;
    }
    .detail-value { font-size: .9rem; font-weight: 600; color: #1e293b; }
    .detail-value.empty { color: #cbd5e1; font-weight: 400; }
    .tab-btn { transition: all 0.15s; }
    .tab-btn.active {
        background: #0067a3;
        color: #fff;
        box-shadow: 0 1px 3px rgba(0, 103, 163, .25);
    }
    .tab-btn:not(.active) { color: #64748b; }
    .tab-btn:not(.active):hover { background: #f1f5f9; color: #334155; }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 12px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
    }
    .badge-baru { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
    .badge-review { background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; }
    .badge-interview { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
    .badge-lolos { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-tidak_lolos { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
</style>
@endsection

@section('admin-content')
@php
    $labelText = function ($snapshot, $fallback = '-') {
        if (is_array($snapshot)) {
            return $snapshot['id'] ?? (array_values($snapshot)[0] ?? $fallback);
        }

        return filled($snapshot) ? (string) $snapshot : $fallback;
    };

    $formTitleSnapshot = $labelText($applicant->form_title_snapshot, '-');
@endphp

<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400 flex-wrap">
        <a href="{{ route('admin.forms.index') }}" class="hover:text-primary transition-colors">Form Builder</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('admin.forms.responses.index', $form->id) }}"
           class="hover:text-primary transition-colors truncate max-w-[180px]">
            {{ $form->title['id'] ?? 'Untitled' }}
        </a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-slate-800 font-semibold">{{ $applicant->nama ?: 'Detail Response' }}</span>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.forms.responses.index', $form->id) }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors flex-shrink-0">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div class="min-w-0">
                <h2 class="font-bold text-slate-800 text-base leading-tight">
                    Response: {{ $applicant->nama ?: '#' . $applicant->id }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Dikirim {{ $applicant->created_at->format('d M Y, H:i') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-1 flex-shrink-0 bg-slate-50 border border-slate-200 rounded-lg p-1">
            <a href="{{ route('admin.forms.builder', $form->id) }}"
               class="tab-btn px-4 py-1.5 rounded-md text-sm font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">edit_note</span> Questions
            </a>
            <a href="{{ route('admin.forms.responses.index', $form->id) }}"
               class="tab-btn active px-4 py-1.5 rounded-md text-sm font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">inbox</span> Responses
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">dynamic_form</span>
                    <h3 class="font-bold text-slate-800">Dynamic Answers</h3>
                    <span class="ml-auto text-xs text-slate-400 font-medium">
                        {{ $applicant->dynamicAnswers->count() }} jawaban
                    </span>
                </div>

                @if($applicant->dynamicAnswers->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                <tr>
                                    <th class="py-3 px-4">Label</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4">Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($applicant->dynamicAnswers as $answer)
                                    @php
                                        $value = $answer->value;
                                    @endphp
                                    <tr>
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            {{ $labelText($answer->field_label_snapshot) }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-600">
                                            {{ $answer->field_type_snapshot ?: '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-700">
                                            @if(is_array($value))
                                                @if(count($value) > 0)
                                                    <ul class="list-disc list-inside space-y-0.5">
                                                        @foreach($value as $item)
                                                            <li>{{ $item }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-slate-400">-</span>
                                                @endif
                                            @else
                                                {{ filled($value) ? $value : '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <span class="material-symbols-outlined text-3xl text-slate-200">inbox</span>
                        <p class="text-slate-400 text-sm mt-2">Tidak ada jawaban teks.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">upload_file</span>
                    <h3 class="font-bold text-slate-800">Dynamic Files</h3>
                    <span class="ml-auto text-xs text-slate-400 font-medium">
                        {{ $applicant->dynamicFiles->count() }} file
                    </span>
                </div>

                @if($applicant->dynamicFiles->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                                <tr>
                                    <th class="py-3 px-4">Label</th>
                                    <th class="py-3 px-4">Original Name</th>
                                    <th class="py-3 px-4">Size</th>
                                    <th class="py-3 px-4">Mime Type</th>
                                    <th class="py-3 px-4 text-right">Download</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($applicant->dynamicFiles as $dynFile)
                                    <tr>
                                        <td class="py-3 px-4 font-medium text-slate-800">
                                            {{ $labelText($dynFile->field_label_snapshot) }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-700">
                                            {{ $dynFile->original_name }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                            {{ $dynFile->readableSize() }}
                                        </td>
                                        <td class="py-3 px-4 text-slate-600">
                                            {{ $dynFile->mime_type ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <a href="{{ route('admin.applicants.dynamic-files.download', [$applicant, $dynFile]) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                                <span class="material-symbols-outlined text-sm">download</span> Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <span class="material-symbols-outlined text-3xl text-slate-200">cloud_off</span>
                        <p class="text-slate-400 text-sm mt-2">Tidak ada file yang diunggah.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                <h4 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">info</span>
                    Metadata Response
                </h4>

                <div>
                    <p class="detail-label">Submitted At</p>
                    <p class="detail-value">{{ $applicant->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div>
                    <p class="detail-label">Form Title Snapshot</p>
                    <p class="detail-value {{ $formTitleSnapshot === '-' ? 'empty' : '' }}">{{ $formTitleSnapshot }}</p>
                </div>

                <div>
                    <p class="detail-label">Form Version Snapshot</p>
                    <p class="detail-value">{{ $applicant->form_version_snapshot ? 'v' . $applicant->form_version_snapshot : '-' }}</p>
                </div>

                <div>
                    <p class="detail-label">Status Applicant</p>
                    <span class="status-badge badge-{{ $applicant->status_seleksi }}">
                        {{ ucfirst(str_replace('_', ' ', $applicant->status_seleksi)) }}
                    </span>
                </div>

                <div>
                    <p class="detail-label">Nama</p>
                    <p class="detail-value {{ $applicant->nama ? '' : 'empty' }}">{{ $applicant->nama ?: '-' }}</p>
                </div>

                <div>
                    <p class="detail-label">Email</p>
                    <p class="detail-value {{ $applicant->email ? '' : 'empty' }}">{{ $applicant->email ?: '-' }}</p>
                </div>

                <div>
                    <p class="detail-label">Phone</p>
                    <p class="detail-value {{ $applicant->phone ? '' : 'empty' }}">{{ $applicant->phone ?: '-' }}</p>
                </div>
            </div>

            <div class="bg-primary/5 rounded-xl border border-primary/10 p-5 space-y-4">
                <h4 class="font-bold text-primary text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">school</span>
                    Program / Batch / Schema
                </h4>

                <div>
                    <p class="detail-label text-primary/60">Program</p>
                    <p class="detail-value">{{ $applicant->program?->nama_program ?? '-' }}</p>
                </div>

                <div>
                    <p class="detail-label text-primary/60">Batch</p>
                    <p class="detail-value">{{ $applicant->batch?->nama_batch ?? '-' }}</p>
                </div>

                <div>
                    <p class="detail-label text-primary/60">Schema</p>
                    <p class="detail-value">{{ $applicant->programSchema?->nama_skema ?? 'Umum' }}</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 space-y-4">
                <h4 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-400 text-[18px]">bookmark</span>
                    Form Link
                </h4>

                <div>
                    <p class="detail-label">Form ID</p>
                    <p class="detail-value font-mono text-xs text-slate-500">#{{ $applicant->form_id }}</p>
                </div>

                <div class="pt-2 border-t border-slate-200">
                    <a href="{{ route('admin.forms.builder', $form->id) }}"
                       class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">open_in_new</span>
                        Buka Form Builder
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.applicants.show', $applicant->id) }}"
               class="flex items-center gap-2 text-xs text-slate-400 hover:text-primary transition-colors font-medium">
                <span class="material-symbols-outlined text-sm">person</span>
                Lihat di Halaman Pendaftar Legacy
            </a>
        </div>
    </div>
</div>
@endsection
