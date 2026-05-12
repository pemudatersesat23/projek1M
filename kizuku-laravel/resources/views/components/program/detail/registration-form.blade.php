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

      @if(session('success'))
        <div style="padding:24px; border-radius:32px; background:#ecfdf5; color:#059669; font-weight:800; font-size:15px; margin-bottom:40px; border:1px solid #dcfce7; text-align:center;">
          ✅ {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('pendaftaran.store') }}" method="POST"
            enctype="multipart/form-data"
            class="form-grid">
        @csrf
        <input type="hidden" name="program_id" value="{{ $program->id }}">
        <input type="hidden" name="batch_id"   value="{{ $activeBatch->id }}">
        <input type="hidden" name="schema_id"  id="selected_schema_id" value="">

        {{-- ── DATA PRIBADI (common) ── --}}
        <div class="form-section-label">
          <span class="material-symbols-outlined">person</span>
          <span class="section-text">{{ __('messages.form.sections.pribadi') }}</span>
        </div>

        <div class="form-group-custom form-half">
          <span class="input-label">{{ __('messages.form.name') }} *</span>
          <input type="text" name="nama" value="{{ old('nama') }}" class="premium-input" placeholder="{{ __('messages.form.placeholders.ktp') }}" required>
        </div>
        <div class="form-group-custom form-half">
          <span class="input-label">{{ __('messages.form.gender') }} *</span>
          <select name="jenis_kelamin" class="premium-input premium-select" required>
            <option value="" disabled selected>{{ $isJp ? '性別を選択' : 'Pilih Jenis Kelamin' }}</option>
            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>{{ $isJp ? '男性' : 'Laki-laki' }}</option>
            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>{{ $isJp ? '女性' : 'Perempuan' }}</option>
          </select>
        </div>
        <div class="form-group-custom form-half">
          <span class="input-label">{{ $isJp ? '出生地' : 'Tempat Lahir' }} *</span>
          <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="premium-input" placeholder="{{ __('messages.form.placeholders.pob') }}" required>
        </div>
        <div class="form-group-custom form-half">
          <span class="input-label">{{ $isJp ? '生年月日' : 'Tanggal Lahir' }} *</span>
          <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="premium-input" required>
        </div>
        <div class="form-group-custom form-full">
          <span class="input-label">{{ __('messages.form.domicile') }} *</span>
          <textarea name="alamat" rows="2" class="premium-input" placeholder="{{ __('messages.form.placeholders.address') }}" required>{{ old('alamat') }}</textarea>
        </div>
        <div class="form-group-custom form-half">
          <span class="input-label">{{ __('messages.form.phone') }} *</span>
          <input type="text" name="phone" value="{{ old('phone') }}" class="premium-input" placeholder="08XXXXXXXXXX" required>
        </div>
        <div class="form-group-custom form-half">
          <span class="input-label">{{ __('messages.form.email') }} *</span>
          <input type="email" name="email" value="{{ old('email') }}" class="premium-input" placeholder="nama@email.com" required>
        </div>

        {{-- ── FORM SPESIFIK PER PROGRAM (legacy hardcoded) ── --}}
        @if($program->slug === 'tokutei-ginou-tg')
          @include('components.program.detail.forms.tg-form')
        @elseif($program->slug === 'engineer-jepang-gijinkoku')
          @include('components.program.detail.forms.engineering-form')
        @elseif($program->slug === 'kenshusei-jishussei-magang-jepang')
          @include('components.program.detail.forms.magang-form')
        @elseif($program->slug === 'kursus-bahasa-jepang-offline')
          @include('components.program.detail.forms.kursus-form')
        @elseif($program->slug === 'engineer-jepang-ex-internship')
          @include('components.program.detail.forms.ex-internship-form')
        @endif

        {{-- ── DYNAMIC FIELDS (from Form Builder) ── --}}
        @if($dynFields->isNotEmpty())
          {{-- Non-file dynamic fields --}}
          @php $nonFileFields = $dynFields->filter(fn($f) => !$f->isFile()); @endphp
          @if($nonFileFields->isNotEmpty())
            <div class="form-section-label form-full">
              <span class="material-symbols-outlined">dynamic_form</span>
              <span class="section-text">
                {{ $dynLocale === 'jp' ? '追加情報' : 'Informasi Tambahan' }}
              </span>
            </div>
            @foreach($nonFileFields as $dynField)
              @include('components.dynamic-form.field', ['field' => $dynField, 'locale' => $dynLocale])
            @endforeach
          @endif

          {{-- File dynamic fields --}}
          @php $fileFields = $dynFields->filter(fn($f) => $f->isFile()); @endphp
          @if($fileFields->isNotEmpty())
            <div class="form-section-label form-full">
              <span class="material-symbols-outlined">cloud_upload</span>
              <span class="section-text">
                {{ $dynLocale === 'jp' ? '追加書類' : 'Dokumen Tambahan' }}
              </span>
            </div>
            <div class="docs-grid form-full">
              @foreach($fileFields as $dynField)
                @include('components.dynamic-form.field', ['field' => $dynField, 'locale' => $dynLocale])
              @endforeach
            </div>
          @endif
        @endif

        {{-- ── SCHEMA-SPECIFIC DYNAMIC FIELDS (loaded via AJAX on schema selection) ── --}}
        {{-- This div gets populated by fetchDynamicFields() in batch-section.blade.php --}}
        <div id="dynamic-fields-container" class="contents"></div>

        {{-- ── DOKUMEN (berdasarkan slug via config) ── --}}

        <div class="form-section-label">
          <span class="material-symbols-outlined">cloud_upload</span>
          <span class="section-text">{{ __('messages.form.sections.dokumen') }}</span>
        </div>

        <div class="docs-grid form-full">
          @foreach($docs as $name => $msgKey)
            @php $label = __($msgKey); @endphp
            <div class="form-group-custom">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">
                {{ $label }}
                @if(!Str::contains(strtolower($label), ['jika ada', 'optional', '任意']) && $name !== 'sertifikat')
                  <span class="text-red-500">*</span>
                @endif
              </label>
              <div class="upload-zone"
                   id="zone-{{ $name }}"
                   data-selected-text="{{ __('messages.form.file_selected') }}"
                   data-placeholder-text="{{ __('messages.form.upload_placeholder') }}">
                <input type="file"
                       name="{{ $name }}"
                       onchange="updateFileName(this, 'zone-{{ $name }}')"
                       @if(!Str::contains(strtolower($label), ['jika ada', 'optional', '任意']) && $name !== 'sertifikat') required @endif>
                <div class="upload-icon">
                  <span class="material-symbols-outlined">
                    @if($name == 'foto') image
                    @elseif(in_array($name, ['cv','transkrip','ijazah'])) description
                    @else cloud_upload
                    @endif
                  </span>
                </div>
                <div class="upload-text text-[11px] font-bold">{{ $label }}</div>
                <div class="file-name-display text-[10px]"></div>
              </div>
            </div>
          @endforeach
        </div>

        {{-- ── SUBMIT ── --}}
        <div class="form-full pt-6">
          <button type="submit" class="btn btn-primary w-full py-4 rounded-xl text-lg font-black shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
            {{ __('messages.form.submit') }}
          </button>
        </div>

      </form>
    </div>
  </div>
</section>
