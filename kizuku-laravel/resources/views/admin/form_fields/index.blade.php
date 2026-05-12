@extends('layouts.admin')
@section('admin-title', 'Form Builder')

@section('admin-content')
  <div class="flex items-center justify-between mb-8">
    <div>
      <h3 class="text-slate-800 font-bold text-2xl">Form Builder</h3>
      <p class="text-slate-500 text-sm">Kelola field formulir pendaftaran per Program / Skema</p>
    </div>
    <a href="{{ route('admin.form-fields.create') }}" class="px-4 py-2 bg-primary text-white font-bold rounded-lg shadow hover:bg-primary/90 flex items-center gap-2">
      <span class="material-symbols-outlined text-sm">add</span> Tambah Field
    </a>
  </div>

  {{-- Filter Bar --}}
  <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[160px]">
      <label class="block text-xs font-semibold text-slate-500 mb-1">Program</label>
      <select name="program_id" class="w-full rounded-lg border-slate-200 text-sm">
        <option value="">Semua Program</option>
        @foreach($programs as $p)
          <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_program }}</option>
        @endforeach
      </select>
    </div>
    <div class="flex-1 min-w-[140px]">
      <label class="block text-xs font-semibold text-slate-500 mb-1">Lingkup</label>
      <select name="schema_id" class="w-full rounded-lg border-slate-200 text-sm">
        <option value="">Semua Lingkup</option>
        <option value="umum" {{ request('schema_id') === 'umum' ? 'selected' : '' }}>Umum Program</option>
      </select>
    </div>
    <div class="flex-1 min-w-[120px]">
      <label class="block text-xs font-semibold text-slate-500 mb-1">Tipe</label>
      <select name="type" class="w-full rounded-lg border-slate-200 text-sm">
        <option value="">Semua Tipe</option>
        @foreach(config('dynamic_forms.allowed_field_types') as $ft)
          <option value="{{ $ft }}" {{ request('type') == $ft ? 'selected' : '' }}>{{ $ft }}</option>
        @endforeach
      </select>
    </div>
    <div class="flex-1 min-w-[120px]">
      <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
      <select name="status" class="w-full rounded-lg border-slate-200 text-sm">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
      </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-primary text-white font-bold rounded-lg text-sm">Filter</button>
    <a href="{{ route('admin.form-fields.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 font-bold rounded-lg text-sm">Reset</a>
  </form>

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider">
          <tr>
            <th class="px-5 py-4">Label / Field Name</th>
            <th class="px-5 py-4">Program</th>
            <th class="px-5 py-4">Lingkup</th>
            <th class="px-5 py-4">Tipe</th>
            <th class="px-5 py-4 text-center">Wajib</th>
            <th class="px-5 py-4 text-center">Status</th>
            <th class="px-5 py-4 text-center">Urutan</th>
            <th class="px-5 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($formFields as $field)
            <tr class="hover:bg-slate-50/50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex flex-col">
                  <span class="text-sm font-bold text-slate-800">{{ $field->getLabelForLocale() }}</span>
                  <span class="text-xs text-slate-400 font-mono">{{ $field->field_name }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ $field->program?->nama_program }}</td>
              <td class="px-5 py-4">
                @if($field->schema)
                  <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">{{ $field->schema->nama_skema }}</span>
                @else
                  <span class="px-2 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-500">Umum Program</span>
                @endif
              </td>
              <td class="px-5 py-4">
                <span class="px-2 py-1 text-xs font-bold rounded bg-indigo-50 text-indigo-700">{{ $field->type }}</span>
              </td>
              <td class="px-5 py-4 text-center">
                @if($field->is_required)
                  <span class="material-symbols-outlined text-red-500 text-[18px]">check_circle</span>
                @else
                  <span class="material-symbols-outlined text-slate-300 text-[18px]">radio_button_unchecked</span>
                @endif
              </td>
              <td class="px-5 py-4 text-center">
                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $field->status === 'aktif' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500' }}">
                  {{ ucfirst($field->status) }}
                </span>
              </td>
              <td class="px-5 py-4 text-center text-sm font-bold text-slate-600">{{ $field->sort_order }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.form-fields.edit', $field) }}" class="p-2 text-slate-400 hover:text-primary transition-colors hover:bg-primary/5 rounded-lg" title="Edit">
                    <span class="material-symbols-outlined">edit</span>
                  </a>
                  <form action="{{ route('admin.form-fields.destroy', $field) }}" method="POST" onsubmit="return confirm('Nonaktifkan field ini (soft delete)?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-slate-400 hover:text-accent-red transition-colors hover:bg-red-50 rounded-lg" title="Hapus">
                      <span class="material-symbols-outlined">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center gap-2">
                  <span class="material-symbols-outlined text-4xl text-slate-200">dynamic_form</span>
                  <p class="text-slate-400 text-sm">Belum ada field form. <a href="{{ route('admin.form-fields.create') }}" class="text-primary font-bold">Tambah sekarang</a></p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($formFields->hasPages())
      <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
        {{ $formFields->links() }}
      </div>
    @endif
  </div>
@endsection
