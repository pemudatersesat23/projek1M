{{--
  components/program/detail/batch-section.blade.php
  Props: $activeBatch, $nextBatch
--}}

<div class="batch-card reveal reveal-d2">

  @if($activeBatch)

    <div class="batch-status-badge status-active-badge">{{ __('messages.program.enroll_open') }}</div>
    <span class="batch-name">{{ $activeBatch->nama_batch }}</span>

    <div class="batch-dates" style="margin: 32px 0; display: flex; flex-direction: column; gap: 16px;">
      <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
        <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ __('messages.program.batch.pendaftaran') }}</span>
        <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_buka?->format('d M') }} – {{ $activeBatch->tanggal_tutup?->format('d M Y') }}</span>
      </div>
      <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
        <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ __('messages.program.batch.mulai') }}</span>
        <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_mulai?->format('d M Y') }}</span>
      </div>
      @if($activeBatch->kuota)
        <div style="display:flex; justify-content:space-between; align-items:center; background:#f0fdf4; padding:16px 20px; border-radius:16px; border:1px solid #dcfce7;">
          <span style="font-size:12px; font-weight:800; color:#059669; text-transform:uppercase;">{{ __('messages.program.batch.kuota') }}</span>
          <span style="font-weight:900; color:#059669;">{{ $activeBatch->kuota ?? 'Fleksibel' }} {{ __('messages.program.batch.peserta') }}</span>
        </div>
        @if($activeBatch->tanggal_estimasi_selesai)
          <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:16px 20px; border-radius:16px;">
            <span style="font-size:12px; font-weight:800; color:#94a3b8; text-transform:uppercase;">{{ __('messages.program.batch.estimasi') }}</span>
            <span style="font-weight:800; color:#0f1c23;">{{ $activeBatch->tanggal_estimasi_selesai->format('d M Y') }}</span>
          </div>
        @endif
      @endif
    </div>

    <div class="action-buttons">
      @if($activeBatch->cta_type === 'whatsapp')
        <a href="{{ $activeBatch->whatsapp_link ?? 'https://wa.me/6281212345678' }}"
           target="_blank"
           class="btn btn-primary"
           style="width:100%; padding:18px; border-radius:16px; justify-content:center; display:flex; font-weight:900; font-size:16px; background:#25d366; border-color:#25d366; box-shadow: 0 10px 25px rgba(37,211,102,0.2);">
          <svg style="width:20px; height:20px; margin-right:10px;" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
          </svg>
          {{ __('messages.program.batch.wa_btn') }}
        </a>
      @else
        <a href="#registration-section"
           class="btn btn-primary"
           style="width:100%; padding:18px; border-radius:16px; justify-content:center; display:flex; font-weight:900; font-size:16px; box-shadow: 0 10px 25px rgba(0,103,163,0.2);">
          {{ __('messages.program.batch.enroll_btn') }}
        </a>
      @endif
    </div>

  @elseif($nextBatch)

    <div class="batch-status-badge" style="background:rgba(217, 119, 6, 0.1); color:#d97706;">{{ __('messages.program.enroll_soon') }}</div>
    <span class="batch-name">{{ $nextBatch->nama_batch }}</span>
    <p class="text-slate-500 text-sm mb-8 mt-4 font-semibold leading-relaxed">
      {{ app()->getLocale() === 'jp'
        ? 'このバッチの登録は ' . $nextBatch->tanggal_buka?->format('Y年m月d日') . ' に開始されます。'
        : 'Pendaftaran untuk batch ini akan dibuka pada ' . $nextBatch->tanggal_buka?->format('d F Y') . '.' }}
    </p>
    <a href="#kontak" class="btn btn-outline" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">
      {{ __('messages.nav.kontak') }}
    </a>

  @else

    <div class="batch-status-badge" style="background:#f1f5f9; color:#64748b;">{{ __('messages.program.enroll_closed') }}</div>
    <span class="batch-name">{{ __('messages.program.no_schedule') }}</span>
    <p class="text-slate-500 text-sm mb-8 mt-4 font-semibold leading-relaxed">{{ __('messages.program.no_schedule_p') }}</p>
    <a href="#kontak" class="btn btn-primary" style="width:100%; justify-content:center; display:flex; padding:16px; border-radius:16px;">
      {{ __('messages.program.ask_admin') }}
    </a>

  @endif

</div>
