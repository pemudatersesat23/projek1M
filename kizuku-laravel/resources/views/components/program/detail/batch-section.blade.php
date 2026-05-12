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

  function selectSchema(id) {
    selectedSchemaId = id;
    
    // Set value into hidden input in the registration form
    const schemaInput = document.getElementById('selected_schema_id');
    if(schemaInput) {
      schemaInput.value = id;
    }
    
    // Update active state visuals
    document.querySelectorAll('.schema-box').forEach(box => {
      box.style.borderColor = '#e2e8f0';
      box.style.background = 'transparent';
    });
    
    const activeBox = document.getElementById('schema-box-' + id);
    if(activeBox) {
      activeBox.style.borderColor = 'var(--primary)';
      activeBox.style.background = '#f0f9ff';
    }
  }

  // Add click handler to scroll to internal form
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-enroll-internal').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const hasSchema = {{ $program->hasActiveSchemas() ? 'true' : 'false' }};
        if (hasSchema && !selectedSchemaId) {
          e.preventDefault();
          alert('Silakan pilih skema pendaftaran terlebih dahulu.');
          document.querySelector('.schema-selector-card').scrollIntoView({ behavior: 'smooth' });
          return;
        }
        
        const batchId = this.getAttribute('data-batch');
        const batchInput = document.querySelector('input[name="batch_id"]');
        if (batchInput) {
            batchInput.value = batchId;
        }
      });
    });
  });
</script>
