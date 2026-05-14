@php
    $icon = $icon ?? 'info';
    $whitelist = [
        'info', 'person', 'school', 'work', 'language', 'cloud_upload', 'description',
        'checklist', 'calendar', 'phone', 'email', 'location_on', 'payments',
        'verified', 'medical_services', 'trophy', 'engineering', 'flight_takeoff',
        'apartment', 'groups'
    ];

    if (!in_array($icon, $whitelist)) {
        $icon = 'info';
    }

    $color = $color ?? 'currentColor';
    $size = $size ?? '24px';
@endphp

<span class="material-symbols-outlined" style="font-size: {{ $size }}; color: {{ $color }}; vertical-align: middle;">
    {{ $icon }}
</span>
