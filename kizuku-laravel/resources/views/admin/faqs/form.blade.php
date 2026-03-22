@extends('layouts.admin')
@section('admin-title', isset($faq) ? 'Edit FAQ' : 'Tambah FAQ')

@section('admin-content')
  <div class="mb-8">
    <a href="{{ route('admin.faqs.index') }}" class="text-sm text-slate-500 hover:text-primary flex items-center gap-1 mb-2">
      <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali
    </a>
    <h3 class="text-slate-800 font-bold text-2xl">{{ isset($faq) ? 'Edit FAQ' : 'Tambah FAQ Baru' }}</h3>
  </div>

  <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm max-w-3xl">
    <form action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST">
      @csrf
      @if(isset($faq)) @method('PUT') @endif

      <div class="space-y-6">
        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Urutan Tampil (Order) *</label>
          <input type="number" name="order" value="{{ old('order', $faq->order ?? 0) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary" required>
          <p class="text-xs text-slate-500 mt-1">Gunakan angka (misal: 1, 2, 3) untuk mengatur urutan munculnya FAQ di halaman depan.</p>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Kategori (ID) *</label>
          <input type="text" name="kategori_id" value="{{ old('kategori_id', isset($faq) ? $faq->getTranslation('kategori', 'id', false) : 'Umum') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary" required>
          <p class="text-xs text-slate-500 mt-1">Misal: Program, Biaya, Persyaratan, Umum</p>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Pertanyaan (ID) *</label>
          <input type="text" name="question_id" value="{{ old('question_id', isset($faq) ? $faq->getTranslation('question', 'id') : '') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary" required>
        </div>

        <div>
          <label class="block text-sm font-bold text-slate-700 mb-2">Jawaban (ID) *</label>
          <textarea name="answer_id" rows="4" class="w-full rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary" required>{{ old('answer_id', isset($faq) ? $faq->getTranslation('answer', 'id') : '') }}</textarea>
        </div>

        <div class="p-4 bg-primary/5 rounded-xl border border-primary/10">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
            <div>
              <span class="block text-sm font-bold text-slate-800">Tampilkan FAQ ini</span>
              <span class="block text-xs text-slate-500">Jika tidak dicentang, FAQ ini akan disembunyikan dari halaman publik.</span>
            </div>
          </label>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
          <button type="submit" class="btn btn-primary px-8">
            <span class="material-symbols-outlined text-[20px] mr-2">save</span> Simpan FAQ
          </button>
        </div>
      </div>
    </form>
  </div>
@endsection
