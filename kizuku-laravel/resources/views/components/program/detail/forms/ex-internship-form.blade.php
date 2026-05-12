{{--
  Form: Engineer Ex-Internship
  Di-include dari registration-form.blade.php
--}}

<div class="form-section-label">
  <span class="material-symbols-outlined">history_edu</span>
  <span class="section-text">{{ __('messages.form.sections.pendidikan') }}</span>
</div>

<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.major_study') }} *</span>
  <select name="additional_data[jurusan]" class="premium-input premium-select" required>
    @foreach(['mesin', 'mesin_elektro', 'sipil', 'lainnya'] as $mj)
      <option value="{{ __('messages.form.majors.' . $mj) }}">{{ __('messages.form.majors.' . $mj) }}</option>
    @endforeach
  </select>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.school_univ') }} *</span>
  <input type="text" name="additional_data[universitas]" class="premium-input" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.grad_year') }} *</span>
  <input type="number" name="additional_data[tahun_lulus]" class="premium-input" required>
</div>
<div class="form-group-custom form-half">
  <span class="input-label">{{ __('messages.form.jp_skill') }} *</span>
  <select name="additional_data[level_bahasa]" class="premium-input premium-select" required>
    <option value="Belum belajar">{{ app()->getLocale() === 'jp' ? '未学習' : 'Belum belajar' }}</option>
    <option value="Sedang belajar">{{ app()->getLocale() === 'jp' ? '学習中' : 'Sedang belajar' }}</option>
    <option value="JLPT N5">JLPT N5</option>
    <option value="JLPT N4">JLPT N4</option>
    <option value="JLPT N3 atau lebih">JLPT N3+</option>
  </select>
</div>
<div class="form-group-custom form-full">
  <span class="input-label">{{ __('messages.form.experience_field') }}</span>
  <textarea name="pengalaman_kerja" rows="2" class="premium-input" placeholder="{{ __('messages.form.placeholders.experience') }}"></textarea>
</div>

<div class="form-section-label">
  <span class="material-symbols-outlined">info</span>
  <span class="section-text">{{ __('messages.form.sections.tambahan') }}</span>
</div>
<div class="form-group-custom form-full">
  <span class="input-label">{{ __('messages.form.motiv_japan') }} *</span>
  <textarea name="additional_data[motivasi]" rows="2" class="premium-input" placeholder="{{ __('messages.form.placeholders.motivation') }}" required></textarea>
</div>

<div class="form-section-label">
  <span class="material-symbols-outlined">verified_user</span>
  <span class="section-text">{{ __('messages.form.sections.pernyataan') }}</span>
</div>
<div class="form-full" style="display:flex; flex-direction:column; gap:12px; padding:20px; background:#f8fafc; border-radius:24px; border:1px solid #f1f5f9;">
  <label style="display:flex; align-items:flex-start; gap:12px; cursor:pointer;">
    <input type="checkbox" name="additional_data[agreement_truth]" value="1" style="margin-top:4px; width:24px; height:24px; accent-color:var(--primary);" required>
    <span style="font-size:14px; font-weight:700; color:#334155; line-height:1.6;">{{ __('messages.form.agreement_truth') }} *</span>
  </label>
</div>
