@extends('layouts.admin')

@section('admin-title', isset($keunggulan) ? 'Edit Keunggulan' : 'Tambah Keunggulan')

@section('admin-content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.keunggulans.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-primary transition-colors mb-4">
            <span class="material-symbols-outlined text-lg mr-1">arrow_back</span>
            Kembali ke Daftar
        </a>
        <h3 class="text-xl font-bold text-slate-800">{{ isset($keunggulan) ? 'Edit Keunggulan' : 'Tambah Keunggulan Baru' }}</h3>
    </div>

    <form action="{{ isset($keunggulan) ? route('admin.keunggulans.update', $keunggulan) : route('admin.keunggulans.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf
        @if(isset($keunggulan))
            @method('PUT')
        @endif

        <div class="p-6 md:p-8 space-y-6">
            {{-- Title ID --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Judul Keunggulan (Bahasa Indonesia)</label>
                <input type="text" name="title_id" value="{{ old('title_id', isset($keunggulan) ? $keunggulan->getTranslation('title', 'id') : '') }}" required
                    placeholder="Contoh: Sending Organization Resmi"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                @error('title_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Description ID --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Deskripsi Singkat (Bahasa Indonesia)</label>
                <textarea name="description_id" rows="3" required
                    placeholder="Jelaskan secara singkat mengenai keunggulan ini..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">{{ old('description_id', isset($keunggulan) ? $keunggulan->getTranslation('description', 'id') : '') }}</textarea>
                @error('description_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Icon --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Ikon (Material Symbol) <span class="text-red-500">*</span></label>
                    <div class="relative mb-2">
                        <select id="iconSelect" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none cursor-pointer" onchange="handleIconChange(this.value)">
                            <option value="verified_user">Verified / Aman</option>
                            <option value="school">Sekolah / Pendidikan</option>
                            <option value="flight_takeoff">Keberangkatan / Pesawat</option>
                            <option value="group">Grup / Komunitas</option>
                            <option value="assignment">Tugas / Ujian</option>
                            <option value="language">Bahasa / Global</option>
                            <option value="business_center">Kerja / Profesional</option>
                            <option value="star">Bintang / Unggulan</option>
                            <option value="emoji_events">Penghargaan / Prestasi</option>
                            <option value="apartment">Gedung / Perusahaan</option>
                            <option value="translate">Terjemahan / Bahasa</option>
                            <option value="work">Pekerjaan</option>
                            <option value="military_tech">Medali / Pencapaian</option>
                            <option value="account_balance">Institusi Resmi</option>
                            <option value="support_agent">Dukungan / Layanan</option>
                            <option value="custom">-- Ketik Nama Ikon Lainnya --</option>
                        </select>
                        <span translate="no" id="iconSelectPreview" class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary pointer-events-none w-6 h-6 overflow-hidden flex items-center justify-center">verified_user</span>
                        <span translate="no" class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm">expand_more</span>
                    </div>

                    <div id="customIconWrapper" class="relative hidden mt-2">
                        <input type="text" name="icon" id="iconInput" value="{{ old('icon', $keunggulan->icon ?? 'verified_user') }}" required
                            oninput="handleIconInput(this)"
                            placeholder="Ketik nama ikon (cth: verified_user)..."
                            class="w-full pl-11 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        <span translate="no" id="iconCustomPreview" class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary pointer-events-none w-6 h-6 overflow-hidden flex items-center justify-center">{{ old('icon', $keunggulan->icon ?? 'verified_user') }}</span>
                    </div>

                    <p class="text-[10px] text-slate-400 mt-2 italic">Untuk menambah ikon lain, pilih "Ketik Nama Ikon Lainnya" lalu cari di <a href="https://fonts.google.com/icons?icon.set=Material+Symbols" target="_blank" class="text-primary hover:underline">Google Fonts</a> dan ketik namanya dengan format huruf kecil dan garis bawah (contoh: <code>favorite</code>).</p>
                    @error('icon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Order --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Urutan Tampil</label>
                    <input type="number" name="order" value="{{ old('order', $keunggulan->order ?? 0) }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    @error('order') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" class="sr-only peer" {{ old('is_active', $keunggulan->is_active ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </div>
                <span class="text-sm font-medium text-slate-700 uppercase tracking-wider">Tampilkan di Beranda</span>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary/90 transition-all shadow-sm shadow-primary/20">
                <span class="material-symbols-outlined text-lg">save</span>
                {{ isset($keunggulan) ? 'Simpan Perubahan' : 'Tambahkan Keunggulan' }}
            </button>
        </div>
    </form>
</div>
@endsection

@section('admin-scripts')
<script>
    function handleIconChange(value) {
        const customWrapper = document.getElementById('customIconWrapper');
        const iconInput = document.getElementById('iconInput');
        const preview = document.getElementById('iconSelectPreview');

        if (value === 'custom') {
            customWrapper.classList.remove('hidden');
            iconInput.focus();
            if (iconInput.value === 'verified_user' || iconInput.value === 'custom') {
                iconInput.value = '';
            }
            preview.innerText = iconInput.value || 'extension';
            document.getElementById('iconCustomPreview').innerText = iconInput.value || 'extension';
        } else {
            customWrapper.classList.add('hidden');
            iconInput.value = value;
            preview.innerText = value;
        }
    }

    function handleIconInput(input) {
        // Sanitize input: lowercase, replace spaces/dashes with underscore, remove non-alphanumeric
        let val = input.value.toLowerCase().replace(/[\s-]/g, '_').replace(/[^a-z0-9_]/g, '');
        input.value = val;
        
        let previewVal = val || 'extension';
        document.getElementById('iconCustomPreview').innerText = previewVal;
        document.getElementById('iconSelectPreview').innerText = previewVal;
    }

    // Initialize state on load
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('iconSelect');
        const input = document.getElementById('iconInput');
        const currentValue = input.value;
        const options = Array.from(select.options).map(opt => opt.value);
        
        if (options.includes(currentValue) && currentValue !== 'custom') {
            select.value = currentValue;
            document.getElementById('iconSelectPreview').innerText = currentValue;
        } else if (currentValue) {
            select.value = 'custom';
            document.getElementById('customIconWrapper').classList.remove('hidden');
            document.getElementById('iconSelectPreview').innerText = currentValue;
            document.getElementById('iconCustomPreview').innerText = currentValue;
        }
    });
</script>
@endsection
