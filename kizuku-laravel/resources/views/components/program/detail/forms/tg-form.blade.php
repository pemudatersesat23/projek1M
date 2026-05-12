{{--
  Form: Tokutei Ginou (TG)
  Di-include dari registration-form.blade.php
  Vars: $program
--}}
@php $tgFields = config('programs.tg_fields'); @endphp

<div class="form-section-label">
  <span class="material-symbols-outlined">school</span>
  <span class="section-text">{{ __('messages.form.sections.pendidikan') }}</span>
</div>

<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.education') }} *</span>
  <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" class="premium-input" placeholder="SMA/D3/S1" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.major_study') }} *</span>
  <input type="text" name="additional_data[jurusan]" class="premium-input" placeholder="{{ __('messages.form.placeholders.major') }}" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.school_univ') }} *</span>
  <input type="text" name="additional_data[universitas]" class="premium-input" placeholder="{{ __('messages.form.placeholders.univ') }}" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.grad_year') }} *</span>
  <input type="number" name="additional_data[tahun_lulus]" class="premium-input" placeholder="{{ __('messages.form.placeholders.grad_year') }}" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.jp_skill') }} *</span>
  <input type="text" name="additional_data[level_bahasa]" class="premium-input" placeholder="{{ __('messages.form.placeholders.jp_level') }}" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.experience_field') }} *</span>
  <input type="text" name="pengalaman_kerja" class="premium-input" placeholder="{{ __('messages.form.placeholders.experience') }}" required>
</div>

{{-- 10 Bidang SSW Checkbox --}}
<div class="form-full">
  <span class="input-label" style="display:block; margin-bottom:16px;">{{ __('messages.form.placeholders.ssw_field') }} *</span>
  <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
    @foreach($tgFields as $key => $field)
      <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: #f8fafc; border-radius: 16px; border: 1px solid #f1f5f9; cursor: pointer;">
        <input type="checkbox" name="additional_data[bidang_tg][]" value="{{ $key }}" style="width: 20px; height: 20px; accent-color: var(--primary);">
        <span style="font-size: 13px; font-weight: 700; color: #334155;">{{ $loop->iteration }}. {{ __($field['msg']) }}</span>
      </label>
    @endforeach
  </div>
</div>

{{-- Tambahan TG --}}
<div class="form-section-label">
  <span class="material-symbols-outlined">info</span>
  <span class="section-text">{{ __('messages.form.sections.tambahan') }}</span>
</div>
<div class="form-group-custom form-full">
  <span class="input-label">{{ __('messages.form.motiv_japan') }} *</span>
  <textarea name="additional_data[motivasi]" rows="2" class="premium-input" placeholder="{{ __('messages.form.placeholders.motivation') }}" required></textarea>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.contract_3y') }} *</span>
  <select name="additional_data[kontrak_3_tahun]" class="premium-input premium-select" required>
    <option value="ya">{{ app()->getLocale() === 'jp' ? 'はい、可能です' : 'Ya, Bersedia' }}</option>
    <option value="tidak">{{ app()->getLocale() === 'jp' ? 'いいえ' : 'Tidak' }}</option>
  </select>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.prev_intern') }} *</span>
  <select name="additional_data[pernah_magang]" class="premium-input premium-select" required>
    <option value="tidak">{{ app()->getLocale() === 'jp' ? 'いいえ' : 'Belum Pernah' }}</option>
    <option value="ya">{{ app()->getLocale() === 'jp' ? 'はい、あります' : 'Pernah' }}</option>
  </select>
</div>

{{-- Pernyataan --}}
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
    <input type="checkbox" name="additional_data[agreement_selection]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
    <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">{{ __('messages.form.agreement_selection') }} *</span>
  </label>
  <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
    <input type="checkbox" name="additional_data[agreement_kendari]" value="1" style="margin-top: 4px; width: 24px; height: 24px; accent-color: var(--primary);" required>
    <span style="font-size: 14px; font-weight: 700; color: #334155; line-height: 1.6;">{{ __('messages.form.agreement_kendari') }} *</span>
  </label>
</div>
