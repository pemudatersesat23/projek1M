@extends('layouts.admin')
@section('admin-title', 'Export Data')

@section('admin-content')
  <div class="mb-6">
    <h3 class="text-lg font-bold text-slate-800">Export Data Pendaftar</h3>
    <p class="text-sm text-slate-500 mt-1">Download data pendaftar untuk arsip kantor atau laporan.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">download</span> Pilih Format Export
        </h4>
      </div>
      <form method="GET" action="{{ route('admin.export') }}" class="p-6 space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Filter Program</label>
          <select id="exportProgram" name="program_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="">Semua Program</option>
            @foreach($programs as $program)
              <option value="{{ $program->id }}" {{ (string) ($filters['program_id'] ?? '') === (string) $program->id ? 'selected' : '' }}>
                {{ $program->getTranslation('nama_program', app()->getLocale(), false) ?: $program->getTranslation('nama_program', 'id', false) ?: $program->nama_program }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Filter Batch</label>
          <select id="exportBatch" name="batch_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="">Semua Batch</option>
            @foreach($batches as $batch)
              <option value="{{ $batch->id }}" data-program-id="{{ $batch->program_id }}" {{ (string) ($filters['batch_id'] ?? '') === (string) $batch->id ? 'selected' : '' }}>
                {{ $batch->getTranslation('nama_batch', app()->getLocale(), false) ?: $batch->getTranslation('nama_batch', 'id', false) ?: $batch->nama_batch }}
                @if($batch->program)
                  - {{ $batch->program->getTranslation('nama_program', app()->getLocale(), false) ?: $batch->program->getTranslation('nama_program', 'id', false) ?: $batch->program->nama_program }}
                @endif
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Filter Status</label>
          <select id="exportStatus" name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="">Semua Status</option>
            <option value="baru" {{ ($filters['status'] ?? '') === 'baru' ? 'selected' : '' }}>Baru</option>
            <option value="review" {{ ($filters['status'] ?? '') === 'review' ? 'selected' : '' }}>Review</option>
            <option value="interview" {{ ($filters['status'] ?? '') === 'interview' ? 'selected' : '' }}>Interview</option>
            <option value="lolos" {{ ($filters['status'] ?? '') === 'lolos' ? 'selected' : '' }}>Lolos</option>
            <option value="tidak_lolos" {{ ($filters['status'] ?? '') === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
          </select>
        </div>

        <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <button type="submit"
             class="w-full px-5 py-3 bg-slate-100 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-lg">filter_alt</span> Terapkan Filter
          </button>
          <a href="{{ route('admin.export.download', $filters) }}" id="exportExcelLink"
             class="w-full px-5 py-3 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-lg">grid_on</span> Download Excel
          </a>
        </div>
      </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">preview</span> Preview Data
        </h4>
        <p class="text-xs text-slate-400 mt-1">{{ $totalApplicants }} data pendaftar akan di-export</p>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
            <tr>
              <th class="px-4 py-3">Nama</th>
              <th class="px-4 py-3">Program</th>
              <th class="px-4 py-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($previewApplicants as $applicant)
            <tr class="hover:bg-slate-50">
              <td class="px-4 py-3 text-sm font-medium">{{ $applicant->nama ?: '-' }}</td>
              <td class="px-4 py-3 text-xs text-slate-500">{{ $applicant->program?->nama_program ?? '-' }}</td>
              <td class="px-4 py-3 text-xs text-slate-500">{{ $applicant->status_seleksi ?: '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="px-4 py-10 text-center text-sm text-slate-400">Belum ada data pendaftar.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($totalApplicants > 5)
      <div class="px-6 py-3 border-t border-slate-100 text-center">
        <span class="text-xs text-slate-400">Menampilkan 5 dari {{ $totalApplicants }} data</span>
      </div>
      @endif
    </div>
  </div>
@endsection

@section('admin-scripts')
<script>
  function updateExportLinks() {
    const program = document.getElementById('exportProgram').value;
    const batch = document.getElementById('exportBatch').value;
    const status = document.getElementById('exportStatus').value;
    const base = "{{ route('admin.export.download') }}";
    const params = new URLSearchParams();
    if (program) params.append('program_id', program);
    if (batch) params.append('batch_id', batch);
    if (status) params.append('status', status);
    const queryStr = params.toString() ? '?' + params.toString() : '';
    document.getElementById('exportExcelLink').href = base + queryStr;
  }
  function syncBatchOptions() {
    const program = document.getElementById('exportProgram').value;
    const batchSelect = document.getElementById('exportBatch');

    batchSelect.querySelectorAll('option[data-program-id]').forEach(option => {
      const matchesProgram = !program || option.dataset.programId === program;
      option.hidden = !matchesProgram;
      option.disabled = !matchesProgram;
    });

    if (batchSelect.selectedOptions[0]?.disabled) {
      batchSelect.value = '';
    }

    updateExportLinks();
  }

  document.getElementById('exportProgram').addEventListener('change', syncBatchOptions);
  document.getElementById('exportBatch').addEventListener('change', updateExportLinks);
  document.getElementById('exportStatus').addEventListener('change', updateExportLinks);
  syncBatchOptions();
</script>
@endsection
