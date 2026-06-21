@extends('layouts.admin')
@section('admin-title', 'Kelola Alur Pendaftaran')

@section('admin-content')
  <div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-slate-800 font-bold text-2xl">Manajemen Alur Pendaftaran</h3>
        <p class="text-sm text-slate-500 mt-1">Atur langkah-langkah pendaftaran. Drag and drop untuk mengurutkan.</p>
    </div>
    <a href="{{ route('admin.alur.create') }}" class="btn btn-primary flex items-center gap-2">
      <span class="material-symbols-outlined">add</span> Tambah Langkah
    </a>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse" id="alur-table">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-100">
            <th class="p-4 w-12"></th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Ikon</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Judul & Deskripsi</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100" id="sortable-alur">
          @forelse($alurs as $alur)
            <tr class="hover:bg-slate-50 transition-colors" data-id="{{ $alur->id }}">
              <td class="p-4 cursor-move text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">drag_indicator</span>
              </td>
              <td class="p-4">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">{{ $alur->icon }}</span>
                </div>
              </td>
              <td class="p-4">
                <p class="font-bold text-slate-800 text-sm mb-1">{{ $alur->getTranslation('title', 'id') }}</p>
                <p class="text-xs text-slate-500 line-clamp-1">{{ $alur->getTranslation('description', 'id') }}</p>
              </td>
              <td class="p-4">
                @if($alur->is_active)
                  <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Aktif</span>
                @else
                  <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Nonaktif</span>
                @endif
              </td>
              <td class="p-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.alur.edit', $alur) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                  </a>
                  <form action="{{ route('admin.alur.destroy', $alur) }}" method="POST" class="inline-block" data-confirm="Yakin ingin menghapus langkah ini?" data-confirm-type="warning" data-confirm-text="Ya, hapus">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                      <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="p-8 text-center text-slate-500">
                <p>Belum ada data alur pendaftaran.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('admin-scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Drag and Drop Sortable
    if(document.getElementById('sortable-alur')) {
        new Sortable(document.getElementById('sortable-alur'), {
            handle: '.cursor-move',
            animation: 150,
            ghostClass: 'bg-slate-50',
            onEnd: function () {
                let orders = [];
                document.querySelectorAll('#sortable-alur tr').forEach((tr, index) => {
                    orders.push({ id: tr.dataset.id, order: index + 1 });
                });

                fetch('{{ route('admin.alur.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: orders })
                });
            }
        });
    }
</script>
@endsection
