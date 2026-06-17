@extends('layouts.admin')
@section('admin-title', 'Manajemen FAQ')

@section('admin-content')
  <div class="flex justify-between items-center mb-8">
    <h3 class="text-slate-800 font-bold text-2xl">Manajemen FAQ Global</h3>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary flex items-center gap-2">
      <span class="material-symbols-outlined">add</span> Tambah FAQ
    </a>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-100">
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider"># Order</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Kategori</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Pertanyaan</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($faqs as $faq)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="p-4">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold text-sm">
                  {{ $faq->order }}
                </span>
              </td>
              <td class="p-4">
                <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs font-bold">{{ $faq->getTranslation('kategori', 'id', false) ?: 'Umum' }}</span>
              </td>
              <td class="p-4">
                <p class="font-bold text-slate-800 text-sm mb-1 line-clamp-1">{{ $faq->getTranslation('question', 'id') }}</p>
                <p class="text-xs text-slate-500 line-clamp-1">{{ $faq->getTranslation('answer', 'id') }}</p>
                @if($faq->getTranslation('question', 'jp', false))
                  <p class="text-[10px] text-primary/80 font-bold mt-1">🇯🇵 JP Translated</p>
                @endif
              </td>
              <td class="p-4">
                @if($faq->is_active)
                  <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Aktif</span>
                @else
                  <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Nonaktif</span>
                @endif
              </td>
              <td class="p-4">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.faqs.edit', $faq) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                  </a>
                  <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline-block" data-confirm="Yakin ingin menghapus FAQ ini?" data-confirm-type="warning" data-confirm-text="Ya, hapus">
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
              <td colspan="4" class="p-8 text-center text-slate-500">
                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">chat_bubble_outline</span>
                <p>Belum ada daftar FAQ.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
