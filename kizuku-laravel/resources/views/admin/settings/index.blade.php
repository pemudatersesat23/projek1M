@extends('layouts.admin')

@section('admin-title', 'Pengaturan Situs')

@section('admin-content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-800">Pengaturan Situs</h3>
        <p class="text-sm text-slate-500 mt-1">Kelola konfigurasi umum website.</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">settings</span>
                Konfigurasi Umum
            </h4>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                @foreach($settings as $setting)
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">
                        {{ $setting->label }}
                    </label>
                    
                    @if($setting->type === 'textarea')
                        <textarea name="{{ $setting->key }}" rows="3" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none resize-y">{{ $setting->value }}</textarea>
                    @else
                        <input type="{{ $setting->type }}" name="{{ $setting->key }}" value="{{ $setting->value }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary outline-none">
                    @endif
                    
                    <p class="text-[11px] text-slate-400 mt-1">Key: <code>{{ $setting->key }}</code></p>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
