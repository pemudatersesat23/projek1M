@extends('layouts.admin')

@section('admin-title', 'Responses - ' . ($form->title['id'] ?? 'Untitled'))

@section('admin-styles')
<style>
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
        padding: 2px 10px;
        border-radius: 999px;
        font-size: .7rem;
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
<div class="space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-400">
        <a href="{{ route('admin.forms.index') }}" class="hover:text-primary transition-colors">Form Builder</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-slate-600 font-medium truncate max-w-[240px]">{{ $form->title['id'] ?? 'Untitled' }}</span>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-slate-800 font-semibold">Responses</span>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-4 border-b border-slate-200 bg-slate-50">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('admin.forms.index') }}"
                   class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-full transition-colors flex-shrink-0">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                </a>
                <div class="min-w-0">
                    <h2 class="font-bold text-slate-800 text-base leading-tight truncate">
                        {{ $form->title['id'] ?? 'Untitled' }}
                    </h2>
                    <div class="flex flex-wrap items-center gap-2 mt-0.5 text-xs text-slate-500">
                        <span>Program: {{ $form->program?->nama_program ?? '-' }}</span>
                        <span class="text-slate-300">|</span>
                        <span>Schema: {{ $form->schema?->nama_skema ?? 'Umum' }}</span>
                        <span class="text-slate-300">|</span>
                        <span>Batch: {{ $form->batch?->nama_batch ?? '-' }}</span>
                        <span class="text-slate-300">|</span>
                        <span class="{{ $form->status === 'published' ? 'text-emerald-600' : ($form->status === 'draft' ? 'text-amber-600' : 'text-slate-500') }} font-medium">
                            {{ ucfirst($form->status) }}
                        </span>
                        <span class="text-slate-300">|</span>
                        <span>{{ $totalResponses }} responses</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('admin.forms.builder', $form->id) }}"
                   class="px-3 py-2 bg-white border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors font-medium text-sm">
                    Kembali ke Builder
                </a>
                <a href="{{ route('admin.forms.responses.export.csv', $form->id) }}"
                   class="px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium text-sm inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    Export CSV
                </a>
                <div class="flex items-center gap-1 bg-white border border-slate-200 rounded-lg p-1">
                    <a href="{{ route('admin.forms.builder', $form->id) }}"
                       class="tab-btn px-4 py-1.5 rounded-md text-sm font-semibold flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">edit_note</span> Questions
                    </a>
                    <a href="{{ route('admin.forms.responses.index', $form->id) }}"
                       class="tab-btn active px-4 py-1.5 rounded-md text-sm font-semibold flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">inbox</span>
                        Responses
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold bg-white/30 text-white">
                            {{ $totalResponses }}
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 border-b border-slate-100 bg-slate-50">
            <form method="GET" action="{{ route('admin.forms.responses.index', $form->id) }}"
                  class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Nama / Email / HP</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari..."
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Seleksi</label>
                    <select name="status"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                        <option value="">Semua Status</option>
                        @foreach(['baru' => 'Baru', 'review' => 'Review', 'interview' => 'Interview', 'lolos' => 'Lolos', 'tidak_lolos' => 'Tidak Lolos'] as $val => $label)
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium text-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.forms.responses.index', $form->id) }}"
                       class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors font-medium text-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                    <tr>
                        <th class="py-3 px-4">Submitted At</th>
                        <th class="py-3 px-4">Applicant Name</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Phone</th>
                        <th class="py-3 px-4">Program</th>
                        <th class="py-3 px-4">Batch</th>
                        <th class="py-3 px-4">Schema</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($responses as $applicant)
                    <tr class="hover:bg-primary/5 transition-colors">
                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap text-xs">
                            {{ $applicant->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800">
                            {{ $applicant->nama ?: '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">
                            {{ $applicant->email ?: '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">
                            {{ $applicant->phone ?: '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">
                            {{ $applicant->program?->nama_program ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">
                            {{ $applicant->batch?->nama_batch ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-xs">
                            @if($applicant->programSchema)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $applicant->programSchema->nama_skema }}
                                </span>
                            @else
                                <span class="text-slate-400">Umum</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="status-badge badge-{{ $applicant->status_seleksi }}">
                                {{ ucfirst(str_replace('_', ' ', $applicant->status_seleksi)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.forms.responses.show', [$form->id, $applicant->id]) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                <span class="material-symbols-outlined text-sm">open_in_new</span> View Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center border-2 border-dashed border-slate-200">
                                    <span class="material-symbols-outlined text-3xl text-slate-300">inbox</span>
                                </div>
                                <p class="font-semibold text-slate-500">Belum ada response untuk form ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($responses->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $responses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
