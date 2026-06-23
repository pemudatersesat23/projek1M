<!-- ═══ GALLERY ═══ -->
<section id="galeri" class="section-pad" style="background: linear-gradient(180deg, #ffffff 0%, rgba(15, 76, 129, 0.12) 15%, rgba(15, 76, 129, 0.18) 50%, rgba(15, 76, 129, 0.12) 85%, #ffffff 100%), radial-gradient(ellipse at 15% 50%, rgba(15, 76, 129, 0.15) 0%, transparent 55%), radial-gradient(ellipse at 85% 50%, rgba(31, 162, 201, 0.1) 0%, transparent 55%); position: relative; overflow: hidden;">
  <div class="container">
    <div class="sec-head reveal" style="text-align:center;max-width:600px;margin:0 auto 50px;">
      <div class="sec-tag" style="background: rgba(15, 76, 129, 0.08); color: var(--blue); border-color: rgba(15, 76, 129, 0.1);">{{ app()->getLocale() == 'id' ? 'Galeri Kegiatan' : (app()->getLocale() == 'jp' ? '活動ギャラリー' : 'Activity Gallery') }}</div>
      <h2 class="sec-h2" style="background: linear-gradient(90deg, var(--black), var(--blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ app()->getLocale() == 'id' ? 'Momen Berharga Kami' : (app()->getLocale() == 'jp' ? '私たちの貴重な瞬間' : 'Our Precious Moments') }}</h2>
      <p class="sec-p" style="margin:0 auto;">{{ app()->getLocale() == 'id' ? 'Lihat berbagai aktivitas dan keceriaan para siswa selama di LPK Kizuku.' : (app()->getLocale() == 'jp' ? 'LPK Kizukuの学生たちの様々な活動や喜びをご覧ください。' : 'See various activities and the joy of students at LPK Kizuku.') }}</p>
    </div>

    <div class="swiper gallerySwiper reveal">
      <div class="swiper-wrapper">
        @foreach($galleries as $gallery)
          <div class="swiper-slide">
            <div class="gallery-card">
              <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" class="gallery-img">
              @if($gallery->title)
                <div class="gallery-overlay">
                  <span class="gallery-title">{{ $gallery->title }}</span>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination !-bottom-2"></div>
      <!-- Add Navigation -->
      <div class="swiper-button-next !text-primary !-right-2 md:!right-4 after:!text-xl"></div>
      <div class="swiper-button-prev !text-primary !-left-2 md:!left-4 after:!text-xl"></div>
    </div>
  </div>
</section>

<style>
  .gallerySwiper {
    padding: 10px 10px 50px !important;
  }
  .gallery-card {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    background: #eee;
  }
  .gallery-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  .gallery-card:hover .gallery-img {
    transform: scale(1.1);
  }
  .gallery-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 20px;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  .gallery-card:hover .gallery-overlay {
    opacity: 1;
  }
  .gallery-title {
    font-size: 14px;
    font-weight: 600;
  }
</style>
