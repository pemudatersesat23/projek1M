{{--
  registration-form.blade.php
  Props: $program, $activeBatch
  Wrapper untuk form pendaftaran lengkap:
  - Data pribadi (common untuk semua)
  - Form spesifik program (berdasarkan slug)
  - Upload dokumen (berdasarkan slug via config)
  - Tombol submit
--}}
@php
  $locale   = app()->getLocale();
  $isJp     = $locale === 'jp';
  $slugDocs = config('programs.docs_per_slug');
  $docs     = $slugDocs[$program->slug] ?? $slugDocs['_default'];

  // Nama program untuk header form
  $slugJpNames = config('programs.slug_jp_names');
  $programName = $isJp
    ? ($slugJpNames[$program->slug] ?? $program->nama_program)
    : $program->nama_program;

  // Dynamic form
  $dynFields    = $dynamicFields ?? collect();
  $dynHasFiles  = $hasDynamicFiles ?? false;
  $dynLocale    = $currentLocale  ?? $locale;
  $shouldRenderDynamicForm = ($form && $dynFields->isNotEmpty()) || $program->hasActiveSchemas();
@endphp

<section id="registration-section" class="registration-section reveal">
  <div class="container">
    <div class="form-card">

      {{-- Header --}}
      <div class="form-header">
        <h2 class="form-title">{{ __('messages.home.kontak_form_title') }}</h2>
        <p class="form-subtitle">
          {{ $isJp ? 'プログラム登録' : 'Pendaftaran Program' }}
          {{ $programName }} — {{ $activeBatch->nama_batch }}
        </p>
      </div>

      <form action="{{ route('pendaftaran.store') }}" method="POST"
            enctype="multipart/form-data"
            class="form-grid">
        @csrf
        <input type="hidden" name="program_id" value="{{ $program->id }}">
        <input type="hidden" name="batch_id"   value="{{ $activeBatch->id }}">
        <input type="hidden" name="schema_id"  id="selected_schema_id" value="">
        <input type="hidden" name="form_id"    id="selected_form_id"   value="{{ $form->id ?? '' }}">

        @if($shouldRenderDynamicForm)
            {{-- Render all fields from the resolved form --}}

        <div id="ssr-fields-container" class="form-grid contents">
            @if($form && $dynFields->isNotEmpty())
                @foreach($dynFields as $dynField)
                  @include('components.dynamic-form.field', ['field' => $dynField, 'locale' => $dynLocale])
                @endforeach
            @endif
        </div>

            {{-- ── SCHEMA-SPECIFIC DYNAMIC FIELDS (loaded via AJAX on schema selection) ── --}}
            {{-- This div gets populated by fetchDynamicFields() in batch-section.blade.php --}}
            <div id="dynamic-fields-container" class="contents"></div>

            {{-- ── SUBMIT ── --}}
            <div class="form-full pt-6">
              <button type="submit" class="btn btn-primary w-full py-4 rounded-xl text-lg font-black shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                {{ __('messages.form.submit') }}
              </button>
            </div>
        @else
            <div class="form-full py-12 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <span class="material-symbols-outlined text-5xl text-slate-300 mb-4">edit_off</span>
                <p class="text-slate-500 font-medium">
                    {{ $isJp ? 'このプログラムの登録フォームはまだ利用できません。' : 'Formulir pendaftaran untuk program ini belum tersedia.' }}
                </p>
                <p class="text-slate-400 text-sm mt-1">
                    {{ $isJp ? '後でもう一度お試しください。' : 'Silakan hubungi admin atau coba lagi nanti.' }}
                </p>
            </div>
        @endif

      </form>
    </div>
  </div>
</section>
