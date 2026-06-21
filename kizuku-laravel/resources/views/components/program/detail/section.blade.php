@php
  $title = $section->localizedTitle();
  $description = $section->localizedDescription();
  $items = $section->localizedItems();
@endphp

@if($section->type === 'text')
  <div class="pd-dynamic-section reveal">
    @if($title)
      <h3 class="pd-section-h3">{{ $title }}</h3>
    @endif
    @if($description)
      <div class="pd-rich-text">{!! nl2br(e($description)) !!}</div>
    @endif
  </div>
@elseif($section->type === 'info_grid')
  <div class="focus-grid reveal">
    @foreach($items as $item)
      <div class="focus-box">
        <span class="focus-label">{{ $item['title'] ?? '' }}</span>
        <p class="focus-text">{{ $item['description'] ?? '' }}</p>
      </div>
    @endforeach
  </div>
@elseif($section->type === 'cards')
  <div class="pd-dynamic-section reveal">
    @if($title)
      <h3 class="pd-section-h3" style="margin-top: 40px;">{{ $title }}</h3>
    @endif
    @if($description)
      <p class="pd-section-desc">{{ $description }}</p>
    @endif
    <div class="pd-card-grid">
      @foreach($items as $item)
        <div class="tg-field-card pd-content-card">
          <div class="pd-number-badge">
            <span>{{ $loop->iteration }}</span>
          </div>
          <div>
            <span class="pd-card-title">{{ $item['title'] ?? '' }}</span>
            @if(!empty($item['description']))
              <span class="pd-card-desc">{{ $item['description'] }}</span>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
@elseif($section->type === 'checklist')
  <div class="pd-dynamic-section reveal">
    @if($title)
      <h3 class="pd-section-h3">{{ $title }}</h3>
    @endif
    @if($description)
      <p class="pd-section-desc">{{ $description }}</p>
    @endif
    <div class="pd-benefit-list">
      @foreach($items as $item)
        <div class="pd-benefit-item">
          <div class="pd-check">
            <span class="material-symbols-outlined" style="font-size: 16px; font-weight: 800;">check</span>
          </div>
          <span>{{ $item['title'] ?? '' }}</span>
        </div>
      @endforeach
    </div>
  </div>
@elseif($section->type === 'timeline')
  <div class="pd-dynamic-section reveal">
    @if($title)
      <h3 class="pd-section-h3">{{ $title }}</h3>
    @endif
    @if($description)
      <p class="pd-section-desc">{{ $description }}</p>
    @endif
    <div class="selection-timeline">
      @foreach($items as $item)
        <div class="timeline-item">
          <div class="timeline-num">{{ $loop->iteration }}</div>
          <div class="timeline-content">
            <div class="timeline-title">{{ $item['title'] ?? '' }}</div>
            @if(!empty($item['description']))
              <p class="pd-timeline-desc">{{ $item['description'] }}</p>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
@elseif($section->type === 'faq')
  <div class="pd-dynamic-section reveal">
    @if($title)
      <h3 class="pd-section-h3">{{ $title }}</h3>
    @endif
    @if($description)
      <p class="pd-section-desc">{{ $description }}</p>
    @endif
    @foreach($items as $item)
      <div class="pd-faq-item">
        <h5 class="pd-faq-q">{{ $item['title'] ?? '' }}</h5>
        <p class="pd-faq-a">{{ $item['description'] ?? '' }}</p>
      </div>
    @endforeach
  </div>
@endif
