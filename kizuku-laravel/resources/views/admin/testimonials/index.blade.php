@extends('layouts.admin')
@section('admin-title', 'Manage Testimonials')

@section('admin-content')
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Testimoni Alumni</h3>
      <p class="text-sm text-slate-500 mt-1">Dengar langsung pengalaman mereka yang sukses bersama Kizuku.</p>
    </div>
    <a href="{{ route('admin.testimonials.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">add</span> Tambah Testimoni
    </a>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200">
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Profil</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Konten</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-center">Stars</th>
            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wide text-slate-500 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($testimonials as $testi)
            <tr class="hover:bg-slate-50/50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                    @if($testi->avatar_path)
                      <img src="{{ asset('storage/' . $testi->avatar_path) }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold uppercase">{{ substr($testi->name, 0, 1) }}</div>
                    @endif
                  </div>
                  <div>
                    <div class="font-bold text-slate-800 text-sm leading-tight">{{ $testi->name }}</div>
                    <div class="text-slate-500 text-[11px] mt-0.5">{{ $testi->role }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <p class="text-slate-600 text-xs italic line-clamp-2 italic">"{{ $testi->content }}"</p>
              </td>
              <td class="px-6 py-4">
                <div class="flex justify-center text-amber-400">
                  @for($i=1; $i<=$testi->stars; $i++)
                    <span class="material-symbols-outlined text-base">star</span>
                  @endfor
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.testimonials.edit', $testi) }}" class="p-2 text-slate-400 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-lg">edit</span>
                  </a>
                  <form action="{{ route('admin.testimonials.destroy', $testi) }}" method="POST" onsubmit="return confirm('Hapus testimoni ini?')">
                    @csrf @method('DELETE')
                    <button class="p-2 text-slate-400 hover:text-accent-red transition-colors">
                      <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-12 text-center">
                <div class="text-slate-300 mb-2"><span class="material-symbols-outlined text-4xl">reviews</span></div>
                <p class="text-slate-500 text-sm">Belum ada data testimoni.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
