{{--
  components/dynamic-form/field.blade.php

  Props:
    $field      — FormField model instance
    $locale     — current app locale (e.g. 'id', 'jp')

  Renders the correct input based on $field->type.
  All escaping uses {{ }} — never {!! !!}.
  No database queries inside this component.
--}}
@php
  $label       = $field->getTranslation('label', $locale) ?: $field->getTranslation('label', 'id');
  $placeholder = $field->getTranslation('placeholder', $locale)
                 ?: $field->getTranslation('placeholder', 'id')
                 ?: '';
  $description = $field->getTranslation('description', $locale)
                 ?: $field->getTranslation('description', 'id')
                 ?: '';
  $fieldKey    = $field->field_name;
  $isRequired  = $field->is_required;
  $type        = $field->type;
  $options     = is_array($field->options) ? $field->options : (json_decode($field->options, true) ?: []);

  // Old value helpers
  $oldAnswerKey = 'dynamic_answers.' . $fieldKey;
  $oldAnswer    = old($oldAnswerKey);
  $oldFile      = old('dynamic_files.' . $fieldKey);
@endphp

<div class="form-group-custom {{ in_array($type, ['textarea']) ? 'form-full' : 'form-half' }} dynamic-field-wrapper">

  {{-- Label (Hidden for section) --}}
  @if($type !== 'section')
    <span class="input-label">
      {{ $label }}
      @if($isRequired)
        <span style="color:#E31E24">*</span>
      @endif
    </span>
  @endif

  {{-- ── TEXT / EMAIL / PHONE / NUMBER / DATE ── --}}
  @if(in_array($type, ['text', 'email', 'phone', 'number', 'date']))
    <input
      type="{{ $type === 'phone' ? 'tel' : $type }}"
      name="dynamic_answers[{{ $fieldKey }}]"
      value="{{ $oldAnswer ?? '' }}"
      class="premium-input"
      placeholder="{{ $placeholder }}"
      @if($isRequired) required @endif
    >
    @error($oldAnswerKey)
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror

  {{-- ── TEXTAREA ── --}}
  @elseif($type === 'textarea')
    <textarea
      name="dynamic_answers[{{ $fieldKey }}]"
      class="premium-input"
      rows="3"
      placeholder="{{ $placeholder }}"
      @if($isRequired) required @endif
    >{{ $oldAnswer ?? '' }}</textarea>
    @error($oldAnswerKey)
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror

  {{-- ── SELECT ── --}}
  @elseif($type === 'select')
    <select
      name="dynamic_answers[{{ $fieldKey }}]"
      class="premium-input premium-select"
      @if($isRequired) required @endif
    >
      <option value="" disabled {{ $oldAnswer === null ? 'selected' : '' }}>
        {{ $placeholder ?: ($locale === 'jp' ? '選択してください' : 'Pilih...') }}
      </option>
      @foreach($options as $opt)
        @php
          $optValue = $opt['value'] ?? '';
          $optLabel = $opt['label'][$locale] ?? $opt['label']['id'] ?? $optValue;
        @endphp
        <option value="{{ $optValue }}" {{ $oldAnswer === $optValue ? 'selected' : '' }}>
          {{ $optLabel }}
        </option>
      @endforeach
    </select>
    @error($oldAnswerKey)
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror

  {{-- ── RADIO ── --}}
  @elseif($type === 'radio')
    <div class="dynamic-radio-group">
      @foreach($options as $opt)
        @php
          $optValue = $opt['value'] ?? '';
          $optLabel = $opt['label'][$locale] ?? $opt['label']['id'] ?? $optValue;
        @endphp
        <label class="dynamic-radio-label">
          <input
            type="radio"
            name="dynamic_answers[{{ $fieldKey }}]"
            value="{{ $optValue }}"
            @if($isRequired) required @endif
            {{ $oldAnswer === $optValue ? 'checked' : '' }}
          >
          <span>{{ $optLabel }}</span>
        </label>
      @endforeach
    </div>
    @error($oldAnswerKey)
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror

  {{-- ── CHECKBOX ── --}}
  @elseif($type === 'checkbox')
    @php $oldChecked = is_array($oldAnswer) ? $oldAnswer : []; @endphp
    <div class="dynamic-checkbox-group">
      @foreach($options as $opt)
        @php
          $optValue = $opt['value'] ?? '';
          $optLabel = $opt['label'][$locale] ?? $opt['label']['id'] ?? $optValue;
        @endphp
        <label class="dynamic-checkbox-label">
          <input
            type="checkbox"
            name="dynamic_answers[{{ $fieldKey }}][]"
            value="{{ $optValue }}"
            {{ in_array($optValue, $oldChecked) ? 'checked' : '' }}
          >
          <span>{{ $optLabel }}</span>
        </label>
      @endforeach
    </div>
    @error($oldAnswerKey)
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror
    @error($oldAnswerKey . '.*')
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror

  {{-- ── FILE ── --}}
  @elseif($type === 'file')
    @php
      $rawTypes      = $field->accepted_file_types;
      $acceptedTypes = is_array($rawTypes) ? $rawTypes : (json_decode($rawTypes, true) ?: config('dynamic_forms.default_allowed_file_extensions'));
      $maxSizeKb     = $field->max_file_size ?? config('dynamic_forms.default_max_file_size');
      $acceptStr     = implode(',', array_map(fn($e) => '.' . $e, $acceptedTypes));
    @endphp
    <div class="upload-zone" id="zone-dyn-{{ $fieldKey }}"
         data-selected-text="{{ $locale === 'jp' ? 'ファイル選択済み' : 'File dipilih' }}"
         data-placeholder-text="{{ $locale === 'jp' ? 'クリックしてアップロード' : 'Klik untuk upload' }}">
      <input
        type="file"
        name="dynamic_files[{{ $fieldKey }}]"
        accept="{{ $acceptStr }}"
        onchange="updateFileName(this, 'zone-dyn-{{ $fieldKey }}')"
        @if($isRequired) required @endif
      >
      <div class="upload-icon">
        <span class="material-symbols-outlined">upload_file</span>
      </div>
      <div class="upload-text text-[11px] font-bold">{{ $label }}</div>
      <div class="file-name-display text-[10px]">
        {{ $locale === 'jp' ? '受け付け形式: ' : 'Format: ' }}{{ implode(', ', $acceptedTypes) }}
        &nbsp;|&nbsp;Maks: {{ round($maxSizeKb / 1024, 1) }} MB
      </div>
    </div>
    @error('dynamic_files.' . $fieldKey)
      <p class="dynamic-field-error">{{ $message }}</p>
    @enderror

  {{-- ── SECTION ── --}}
  @elseif($type === 'section')
    <div class="py-2">
      <h3 class="text-xl font-bold text-purple-800">{{ $label }}</h3>
      @if($description)
        <p class="text-sm text-slate-600 mt-1 whitespace-pre-line">{{ $description }}</p>
      @endif
    </div>
  @endif

  {{-- Description / Hint (Hidden for section as it is rendered above) --}}
  @if($description && $type !== 'section')
    <p class="dynamic-field-hint">{{ $description }}</p>
  @endif

</div>
