{{--
  components/program/detail/status-badge.blade.php
  Props: $status (string — nilai dari batch->status)
         $label   (string — teks yang ditampilkan)
         $extra   (string — style tambahan, opsional)
--}}
@php
  $colors = config('programs.batch_status_colors');
  $color  = $colors[$status] ?? $colors['_default'];
@endphp

<span style="padding:6px 16px; border-radius:99px; background:{{ $color['bg'] }}; color:{{ $color['text'] }}; font-size:10px; font-weight:900; text-transform:uppercase; letter-spacing:1px; {{ $extra ?? '' }}">
  {{ $label }}
</span>
