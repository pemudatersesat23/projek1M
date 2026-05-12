{{--
  components/program/detail/batch-section.blade.php
  Props: $program
--}}
<div class="batch-section-wrapper" style="display: flex; flex-direction: column; gap: 24px;">

  {{-- ── SCHEMA SELECTOR ── --}}
  @if($program->hasActiveSchemas())
    <div class="schema-selector-card reveal reveal-d2" style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 15px 40px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
      <h3 style="font-size: 20px; font-weight: 900; color: #0f1c23; margin-bottom: 20px;">Pilih Skema Pendaftaran</h3>
      <p style="color: #64748b; font-size: 14px; margin-bottom: 24px; line-height: 1.6;">
        Program ini memiliki beberapa skema pendaftaran. Silakan pilih skema yang sesuai dengan kualifikasi Anda.
      </p>

      <div class="schema-list" style="display: flex; flex-direction: column; gap: 16px;">
        @foreach($program->activeSchemas as $schema)
          <label class="schema-option" style="display: block; cursor: pointer;">
            <input type="radio" name="schema_selection" value="{{ $schema->id }}" style="display: none;" onchange="selectSchema({{ $schema->id }})">
            <div class="schema-box" id="schema-box-{{ $schema->id }}" style="padding: 20px; border: 2px solid #e2e8f0; border-radius: 16px; transition: all 0.3s;">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <div>
                  <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; background: #f1f5f9; color: #475569; font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 8px;">
                    {{ str_replace('_', ' ', $schema->tipe) }}
                  </span>
                  <h4 style="font-size: 16px; font-weight: 800; color: #0f1c23;">{{ $schema->getTranslation('nama_skema', app()->getLocale()) ?: $schema->nama_skema }}</h4>
                </div>
                <div class="schema-radio-circle" style="width: 20px; height: 20px; border-radius: 50%; border: 2px solid #cbd5e1; position: relative;">
                  <div class="schema-radio-dot" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0); width: 10px; height: 10px; border-radius: 50%; background: var(--primary); transition: all 0.2s;"></div>
                </div>
              </div>
              
              <p style="font-size: 13px; color: #64748b; margin-bottom: 12px; line-height: 1.5;">
                {{ $schema->getTranslation('deskripsi', app()->getLocale()) ?: $schema->deskripsi }}
              </p>
              
              <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 12px;">
                <span style="font-size: 14px; font-weight: 900; color: var(--primary);">{{ $schema->formattedPrice() }}</span>
                @if($schema->batch)
                  <span style="font-size: 12px; font-weight: 700; color: #f59e0b;">🔥 Khusus: {{ $schema->batch->nama_batch }}</span>
                @endif
              </div>
            </div>
          </label>
        @endforeach
      </div>
    </div>
  @endif

  {{-- ── BATCH LIST ── --}}
  <div class="batch-list-wrapper reveal reveal-d3">
    <h3 style="font-size: 20px; font-weight: 900; color: #0f1c23; margin-bottom: 20px;">Jadwal & Gelombang</h3>
    
    @forelse($program->activeBatches as $batch)
      <div class="batch-card" style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 15px 40px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; margin-bottom: 16px;">
        <div class="batch-status-badge {{ $batch->frontendStatusClass() }}">{{ $batch->frontendStatusLabel() }}</div>
        <span class="batch-name" style="display: block; font-size: 22px; font-weight: 900; color: #0f1c23; margin-top: 16px;">{{ $batch->nama_batch }}</span>

        <div class="batch-dates" style="margin: 24px 0; display: flex; flex-direction: column; gap: 12px;">
          <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:12px 16px; border-radius:12px;">
            <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Pendaftaran</span>
            <span style="font-weight:800; color:#0f1c23; font-size: 14px;">{{ $batch->tanggal_buka?->format('d M') }} – {{ $batch->tanggal_tutup?->format('d M Y') }}</span>
          </div>
          @if($batch->tanggal_mulai)
          <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:12px 16px; border-radius:12px;">
            <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Mulai Kelas</span>
            <span style="font-weight:800; color:#0f1c23; font-size: 14px;">{{ $batch->tanggal_mulai->format('d M Y') }}</span>
          </div>
          @endif
          @if($batch->kuota)
            <div style="display:flex; justify-content:space-between; align-items:center; background:#f0fdf4; padding:12px 16px; border-radius:12px; border:1px solid #dcfce7;">
              <span style="font-size:12px; font-weight:800; color:#059669; text-transform:uppercase;">Kuota</span>
              <span style="font-weight:900; color:#059669; font-size: 14px;">{{ $batch->kuota }} Peserta</span>
            </div>
          @endif
        </div>

        <div class="action-buttons">
          @if(!$batch->isRegistrationEnabled())
            <button disabled class="btn" style="width:100%; padding:16px; border-radius:12px; justify-content:center; display:flex; font-weight:800; background:#e2e8f0; color:#94a3b8; cursor:not-allowed;">
              Pendaftaran {{ $batch->frontendStatusLabel() }}
            </button>
          @elseif($batch->cta_type === 'disabled')
            <button disabled class="btn" style="width:100%; padding:16px; border-radius:12px; justify-content:center; display:flex; font-weight:800; background:#e2e8f0; color:#94a3b8; cursor:not-allowed;">
              Pendaftaran Tidak Tersedia
            </button>
          @elseif($batch->cta_type === 'whatsapp')
            <a href="{{ $batch->whatsapp_link ?? 'https://wa.me/6281212345678' }}"
               target="_blank"
               class="btn btn-primary"
               style="width:100%; padding:16px; border-radius:12px; justify-content:center; display:flex; font-weight:900; background:#25d366; border-color:#25d366; box-shadow: 0 10px 25px rgba(37,211,102,0.2);">
              <span class="material-symbols-outlined" style="margin-right: 8px;">chat</span>
              Daftar via WhatsApp
            </a>
          @else
            <a href="#registration-section"
               class="btn btn-primary btn-enroll-internal"
               data-batch="{{ $batch->id }}"
               style="width:100%; padding:16px; border-radius:12px; justify-content:center; display:flex; font-weight:900; box-shadow: 0 10px 25px rgba(0,103,163,0.2);">
              Daftar Sekarang
            </a>
          @endif
        </div>
      </div>
    @empty
      <div class="batch-card" style="background: white; border-radius: 24px; padding: 32px; box-shadow: 0 15px 40px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; text-align: center;">
        <div class="batch-status-badge" style="background:#f1f5f9; color:#64748b; margin: 0 auto 16px auto;">Pendaftaran Ditutup</div>
        <span class="batch-name" style="display: block; font-size: 18px; font-weight: 900; color: #0f1c23;">Belum Ada Jadwal</span>
        <p class="text-slate-500 text-sm mb-6 mt-4 font-semibold leading-relaxed">Saat ini belum ada gelombang pendaftaran yang aktif.</p>
        <a href="#kontak" class="btn btn-primary" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:12px;">
          Tanya Admin
        </a>
      </div>
    @endforelse
  </div>

</div>

<style>
  .batch-status-badge {
    display: inline-flex;
    padding: 6px 14px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .schema-option input:checked + .schema-box {
    border-color: var(--primary);
    background: #f0f9ff;
  }
  .schema-option input:checked + .schema-box .schema-radio-circle {
    border-color: var(--primary);
  }
  .schema-option input:checked + .schema-box .schema-radio-dot {
    transform: translate(-50%, -50%) scale(1);
  }
</style>

<script>
  let selectedSchemaId = null;
  const dynamicFieldsUrl = '{{ route("api.dynamic-fields") }}';
  const programId        = {{ $program->id }};
  const currentLocale    = '{{ app()->getLocale() }}';

  function selectSchema(id) {
    selectedSchemaId = id;

    // Set value into hidden input in the registration form
    const schemaInput = document.getElementById('selected_schema_id');
    if (schemaInput) schemaInput.value = id;

    // Update active state visuals
    document.querySelectorAll('.schema-box').forEach(box => {
      box.style.borderColor = '#e2e8f0';
      box.style.background  = 'transparent';
    });
    const activeBox = document.getElementById('schema-box-' + id);
    if (activeBox) {
      activeBox.style.borderColor = 'var(--primary)';
      activeBox.style.background  = '#f0f9ff';
    }

    // Fetch dynamic fields for this schema and inject them
    fetchDynamicFields(id);
  }

  function fetchDynamicFields(schemaId) {
    const url = dynamicFieldsUrl + '?program_id=' + programId + (schemaId ? '&schema_id=' + schemaId : '');
    fetch(url)
      .then(r => r.json())
      .then(fields => renderDynamicFields(fields))
      .catch(() => {});
  }

  function renderDynamicFields(fields) {
    const container = document.getElementById('dynamic-fields-container');
    if (!container) return;

    container.innerHTML = '';

    if (!fields || !fields.length) return;

    const nonFile = fields.filter(f => f.type !== 'file');
    const fileF   = fields.filter(f => f.type === 'file');

    if (nonFile.length) {
      container.insertAdjacentHTML('beforeend', buildSectionLabel('dynamic_form', currentLocale === 'jp' ? '追加情報' : 'Informasi Tambahan (Skema)'));
      nonFile.forEach(f => container.insertAdjacentHTML('beforeend', buildFieldHtml(f)));
    }

    if (fileF.length) {
      container.insertAdjacentHTML('beforeend', buildSectionLabel('cloud_upload', currentLocale === 'jp' ? '追加書類 (スキーマ)' : 'Dokumen Tambahan (Skema)'));
      const grid = document.createElement('div');
      grid.className = 'docs-grid form-full';
      fileF.forEach(f => grid.insertAdjacentHTML('beforeend', buildFieldHtml(f)));
      container.appendChild(grid);
    }
  }

  function buildSectionLabel(icon, text) {
    return `<div class="form-section-label form-full"><span class="material-symbols-outlined">${icon}</span><span class="section-text">${escHtml(text)}</span></div>`;
  }

  function buildFieldHtml(f) {
    const reqAttr = f.is_required ? 'required' : '';
    const req     = f.is_required ? '<span style="color:#E31E24">*</span>' : '';
    const isHalf  = ['text','email','phone','number','date','select','radio'].includes(f.type);
    const wrapper = `form-group-custom ${isHalf ? 'form-half' : 'form-full'} dynamic-field-wrapper`;

    let inputHtml = '';

    if (['text','email','number','date'].includes(f.type)) {
      inputHtml = `<input type="${f.type}" name="dynamic_answers[${escHtml(f.field_name)}]" class="premium-input" placeholder="${escHtml(f.placeholder)}" ${reqAttr}>`;
    } else if (f.type === 'phone') {
      inputHtml = `<input type="tel" name="dynamic_answers[${escHtml(f.field_name)}]" class="premium-input" placeholder="${escHtml(f.placeholder)}" ${reqAttr}>`;
    } else if (f.type === 'textarea') {
      inputHtml = `<textarea name="dynamic_answers[${escHtml(f.field_name)}]" class="premium-input" rows="3" placeholder="${escHtml(f.placeholder)}" ${reqAttr}></textarea>`;
    } else if (f.type === 'select') {
      const opts = (f.options || []).map(o => {
        const lbl = (o.label && (o.label[currentLocale] || o.label['id'])) || o.value;
        return `<option value="${escHtml(o.value)}">${escHtml(lbl)}</option>`;
      }).join('');
      inputHtml = `<select name="dynamic_answers[${escHtml(f.field_name)}]" class="premium-input premium-select" ${reqAttr}><option value="" disabled selected>${f.placeholder || 'Pilih...'}</option>${opts}</select>`;
    } else if (f.type === 'radio') {
      const opts = (f.options || []).map(o => {
        const lbl = (o.label && (o.label[currentLocale] || o.label['id'])) || o.value;
        return `<label class="dynamic-radio-label"><input type="radio" name="dynamic_answers[${escHtml(f.field_name)}]" value="${escHtml(o.value)}" ${reqAttr}><span>${escHtml(lbl)}</span></label>`;
      }).join('');
      inputHtml = `<div class="dynamic-radio-group">${opts}</div>`;
    } else if (f.type === 'checkbox') {
      const opts = (f.options || []).map(o => {
        const lbl = (o.label && (o.label[currentLocale] || o.label['id'])) || o.value;
        return `<label class="dynamic-checkbox-label"><input type="checkbox" name="dynamic_answers[${escHtml(f.field_name)}][]" value="${escHtml(o.value)}"><span>${escHtml(lbl)}</span></label>`;
      }).join('');
      inputHtml = `<div class="dynamic-checkbox-group">${opts}</div>`;
    } else if (f.type === 'file') {
      const exts    = f.accepted_file_types || ['pdf','jpg','jpeg','png'];
      const maxMb   = ((f.max_file_size || 2048) / 1024).toFixed(1);
      const accept  = exts.map(e => '.' + e).join(',');
      const zoneId  = 'zone-dyn-ajax-' + f.field_name;
      inputHtml = `<div class="upload-zone" id="${zoneId}">
        <input type="file" name="dynamic_files[${escHtml(f.field_name)}]" accept="${accept}" onchange="updateFileName(this,'${zoneId}')" ${reqAttr}>
        <div class="upload-icon"><span class="material-symbols-outlined">upload_file</span></div>
        <div class="upload-text text-[11px] font-bold">${escHtml(f.label)}</div>
        <div class="file-name-display text-[10px]">Format: ${exts.join(', ')} | Maks: ${maxMb} MB</div>
      </div>`;
    }

    const hint = f.description ? `<p class="dynamic-field-hint">${escHtml(f.description)}</p>` : '';
    return `<div class="${wrapper}"><span class="input-label">${escHtml(f.label)} ${req}</span>${inputHtml}${hint}</div>`;
  }

  function escHtml(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
  }

  // Add click handler to scroll to internal form
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-enroll-internal').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const hasSchema = {{ $program->hasActiveSchemas() ? 'true' : 'false' }};
        if (hasSchema && !selectedSchemaId) {
          e.preventDefault();
          alert('Silakan pilih skema pendaftaran terlebih dahulu.');
          document.querySelector('.schema-selector-card')?.scrollIntoView({ behavior: 'smooth' });
          return;
        }

        const batchId   = this.getAttribute('data-batch');
        const batchInput = document.querySelector('input[name="batch_id"]');
        if (batchInput) batchInput.value = batchId;
      });
    });
  });
</script>

