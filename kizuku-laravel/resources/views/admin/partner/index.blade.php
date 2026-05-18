@extends('layouts.admin')
@section('admin-title', 'Kelola Partnership')

@section('admin-content')
  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
      <h3 class="text-lg font-bold text-slate-800">Kelola Partnership</h3>
      <p class="text-sm text-slate-500 mt-1">Tambah atau kelola daftar partnership yang tampil di halaman publik.</p>
    </div>
    <a href="{{ route('admin.partner-campus.create') }}" class="px-5 py-2.5 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
      <span class="material-symbols-outlined text-lg">add_circle</span> Tambah Kampus
    </a>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center gap-3">
      <span class="material-symbols-outlined text-emerald-600">check_circle</span>
      <span class="text-sm font-medium text-emerald-700">{{ session('success') }}</span>
    </div>
  @endif

  {{-- List Kampus --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($campuses as $campus)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition-shadow">
      <div class="flex flex-col items-center gap-4 text-center">
        <div class="w-32 h-32 flex items-center justify-center p-2 rounded-lg bg-slate-50 border border-slate-100">
          <img src="{{ Storage::url($campus->logo) }}" alt="{{ $campus->getTranslation('name', 'id', false) ?: $campus->name }}" class="max-w-full max-h-full object-contain">
        </div>
        <div class="flex-1 min-w-0 w-full">
          <h5 class="font-bold text-base text-slate-800 mb-4 truncate">{{ $campus->getTranslation('name', 'id', false) ?: $campus->name }}</h5>

          <div class="flex items-center justify-center gap-2 mt-auto">
            <a href="{{ route('admin.partner-campus.edit', $campus) }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg text-xs font-medium hover:bg-slate-50 transition-colors flex items-center gap-1">
              <span class="material-symbols-outlined text-sm">edit</span> Edit
            </a>
            <form method="POST" action="{{ route('admin.partner-campus.destroy', $campus) }}" class="inline" onsubmit="return confirm('Hapus kampus ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="px-3 py-1.5 border border-red-200 text-accent-red rounded-lg text-xs font-medium hover:bg-red-50 transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">delete</span> Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center text-slate-400">
      <span class="material-symbols-outlined text-4xl mb-2 block">school</span>
      Belum ada partnership.
    </div>
    @endforelse
  </div>
@endsection
