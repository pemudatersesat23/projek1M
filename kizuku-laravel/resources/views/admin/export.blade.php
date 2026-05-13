@extends('layouts.admin')
@section('admin-title', 'Export Data')

@section('admin-content')
  <div class="mb-6">
    <h3 class="text-lg font-bold text-slate-800">Export Data Pendaftar</h3>
    <p class="text-sm text-slate-500 mt-1">Download data pendaftar legacy untuk arsip kantor atau laporan.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
      <div class="px-6 py-4 border-b border-slate-200">
        <h4 class="font-bold text-slate-800 flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">download</span> Pilih Format Export
        </h4>
      </div>
      <div class="p-6 space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Filter Program ID</label>
          <input id="exportProgram" type="number" min="1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="Kosongkan untuk semua program">
        </div>
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Filter Status</label>
          <select id="exportStatus" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary cursor-pointer outline-none">
            <option value="">Semua Status</option>
            <option value="baru">Baru</option>
            <option value="review">Review</option>
            <option value="interview">Interview</option>
            <option value="lolos">Lolos</option>
            <option value="tidak_lolos">Tidak Lolos</option>
          </select>
        </div>

        <div class="pt-4 space-y-3">
          <a href="{{ route('admin.export.download', ['format' => 'csv']) }}" id="exportCsvLink"
             class="w-full px-5 py-3 bg-emerald-600 text-white rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-lg">table_view</span> Download CSV
          </a>
          <a href="{{ route('admin.export.download', ['format' => 'csv']) }}" id="exportExcelLink"
             class="w-full px-5 py-3 bg-primary text-white rounded-xl text-sm font-medium hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-lg">grid_on</span> Download Excel (CSV)
          </a>
        </div>
      </div>
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
    const status = document.getElementById('exportStatus').value;
    const base = "{{ route('admin.export.download', ['format' => 'csv']) }}";
    const params = new URLSearchParams();
    if (program) params.append('program_id', program);
    if (status) params.append('status', status);
    const queryStr = params.toString() ? '&' + params.toString() : '';
    document.getElementById('exportCsvLink').href = base + queryStr;
    document.getElementById('exportExcelLink').href = base + queryStr;
  }
  document.getElementById('exportProgram').addEventListener('input', updateExportLinks);
  document.getElementById('exportStatus').addEventListener('change', updateExportLinks);
</script>
@endsection
