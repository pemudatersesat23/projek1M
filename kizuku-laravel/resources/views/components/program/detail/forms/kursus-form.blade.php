{{--
  Form: Kursus Bahasa Jepang (Offline)
  Di-include dari registration-form.blade.php
--}}
@php
  $courseChoices = [
    'n5'   => __('messages.form.course_levels.n5'),
    'n4'   => __('messages.form.course_levels.n4'),
    'n3'   => __('messages.form.course_levels.n3'),
    'kaiwa'=> __('messages.form.course_levels.kaiwa'),
    'jlpt' => __('messages.form.course_levels.jlpt'),
    'tg'   => __('messages.form.course_levels.tg'),
    'eng'  => __('messages.form.course_levels.eng'),
  ];
@endphp

<div class="form-section-label">
  <span class="material-symbols-outlined">menu_book</span>
  <span class="section-text">{{ __('messages.form.sections.pilihan') }}</span>
</div>

<div class="form-full">
  <span class="input-label mb-4 block">{{ __('messages.form.shift') }} *</span>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    @foreach($courseChoices as $key => $lbl)
      <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer hover:bg-white transition-all">
        <input type="radio" name="additional_data[pilihan_kelas]" value="{{ $lbl }}" class="w-5 h-5 text-primary">
        <span class="text-xs font-bold">{{ $lbl }}</span>
      </label>
    @endforeach
  </div>
</div>

<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.system') }} *</span>
  <select name="additional_data[sistem_belajar]" class="premium-input premium-select" required>
    <option value="online">{{ __('messages.form.online') }}</option>
    <option value="offline">{{ __('messages.form.offline') }}</option>
  </select>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.jp_skill') }} *</span>
  <input type="text" name="additional_data[level_sekarang]" class="premium-input" placeholder="{{ __('messages.form.placeholders.jp_level') }}" required>
</div>

<div class="form-section-label">
  <span class="material-symbols-outlined">target</span>
  <span class="section-text">{{ __('messages.form.sections.tujuan') }}</span>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.program.focus') }} *</span>
  <input type="text" name="additional_data[tujuan_kursus]" class="premium-input" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.placeholders.grad_year') }} / {{ __('messages.form.placeholders.motiv') }}</span>
  <input type="text" name="additional_data[target_jlpt]" class="premium-input" placeholder="JLPT / {{ app()->getLocale() === 'jp' ? '日本への出発' : 'Keberangkatan' }}">
</div>

<div class="form-section-label">
  <span class="material-symbols-outlined">verified_user</span>
  <span class="section-text">{{ __('messages.form.sections.pernyataan') }}</span>
</div>
<div class="form-full" style="display: flex; flex-direction: column; gap: 12px; padding: 20px; background: #f8fafc; border-radius: 24px; border: 1px solid #f1f5f9;">
  <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
    <input type="checkbox" name="additional_data[agreement_truth]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
    <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">{{ __('messages.form.agreement_truth') }} *</span>
  </label>
  <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
    <input type="checkbox" name="additional_data[agreement_rules]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
    <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">{{ __('messages.form.agreement_rules') }} *</span>
  </label>
</div>
