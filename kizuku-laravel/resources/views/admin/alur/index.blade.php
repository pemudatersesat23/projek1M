@extends('layouts.admin')
@section('admin-title', 'Kelola Alur Pendaftaran')

@section('admin-content')
  <div class="flex justify-between items-center mb-8">
    <div>
        <h3 class="text-slate-800 font-bold text-2xl">Manajemen Alur Pendaftaran</h3>
        <p class="text-sm text-slate-500 mt-1">Atur langkah-langkah pendaftaran. Drag and drop untuk mengurutkan.</p>
    </div>
    <button type="button" onclick="openCreateModal()" class="btn btn-primary flex items-center gap-2">
      <span class="material-symbols-outlined">add</span> Tambah Langkah
    </button>
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
                @if($alur->getTranslation('title', 'jp', false))
                  <p class="text-[10px] text-primary/80 font-bold mt-1">🇯🇵 {{ $alur->getTranslation('title', 'jp') }}</p>
                @endif
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
                  <button type="button" onclick="openEditModal({{ $alur->toJson() }})" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                  </button>
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

  {{-- Modal Form --}}
  <div id="alurModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="alurModalContent">
      <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
        <h3 class="font-bold text-slate-800 text-lg" id="modalTitle">Tambah Langkah</h3>
        <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      
      <form id="alurForm" method="POST" action="{{ route('admin.alur.store') }}" class="p-6">
        @csrf
        <input type="hidden" name="_method" id="formMethod" value="POST">
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ikon (Google Material Symbol)</label>
                <input type="text" name="icon" id="icon" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary" placeholder="e.g. app_registration" required>
                <p class="text-xs text-slate-500 mt-1">Cari ikon di <a href="https://fonts.google.com/icons" target="_blank" class="text-primary hover:underline">Google Fonts</a>.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul (ID) <span class="text-red-500">*</span></label>
                    <input type="text" name="title_id" id="title_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul (JP)</label>
                    <input type="text" name="title_jp" id="title_jp" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi (ID) <span class="text-red-500">*</span></label>
                    <textarea name="description_id" id="description_id" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi (JP)</label>
                    <textarea name="description_jp" id="description_jp" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary"></textarea>
                </div>
            </div>

            <label class="flex items-center gap-2 cursor-pointer mt-2">
                <input type="checkbox" name="is_active" id="is_active" class="rounded border-slate-300 text-primary focus:ring-primary" value="1" checked>
                <span class="text-sm font-semibold text-slate-700">Aktifkan Langkah Ini</span>
            </label>
        </div>

        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
            <button type="button" onclick="closeModal()" class="px-5 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Batal</button>
            <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors shadow-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    const modal = document.getElementById('alurModal');
    const modalContent = document.getElementById('alurModalContent');
    const form = document.getElementById('alurForm');

    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Langkah';
        form.action = '{{ route('admin.alur.store') }}';
        document.getElementById('formMethod').value = 'POST';
        form.reset();
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function openEditModal(alur) {
        document.getElementById('modalTitle').textContent = 'Edit Langkah';
        form.action = `/admin/alur-pendaftaran/${alur.id}`;
        document.getElementById('formMethod').value = 'PUT';
        
        document.getElementById('icon').value = alur.icon || '';
        document.getElementById('title_id').value = alur.title?.id || '';
        document.getElementById('title_jp').value = alur.title?.jp || '';
        document.getElementById('description_id').value = alur.description?.id || '';
        document.getElementById('description_jp').value = alur.description?.jp || '';
        document.getElementById('is_active').checked = !!alur.is_active;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

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
@endpush
