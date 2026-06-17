@extends('layouts.admin')

@section('admin-title', 'Manajemen Keunggulan')

@section('admin-content')
<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Data Keunggulan</h3>
            <p class="text-sm text-slate-500 mt-1">Kelola poin-poin keunggulan yang tampil di halaman beranda.</p>
        </div>
        <a href="{{ route('admin.keunggulans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition-all shadow-sm shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Keunggulan
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Ikon</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Judul (ID)</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Urutan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($keunggulans as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">{{ $item->icon }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $item->getTranslation('title', 'id') }}</div>
                            <div class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $item->getTranslation('description', 'id') }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                {{ $item->order }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.keunggulans.edit', $item) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form action="{{ route('admin.keunggulans.destroy', $item) }}" method="POST" class="inline-block" data-confirm="Yakin ingin menghapus keunggulan ini?" data-confirm-type="warning" data-confirm-text="Ya, hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-4xl text-slate-200 mb-2">verified_user</span>
                                <p class="text-slate-500 text-sm">Belum ada poin keunggulan.</p>
                                <a href="{{ route('admin.keunggulans.create') }}" class="text-primary text-xs font-semibold mt-2 hover:underline">Tambah Sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
