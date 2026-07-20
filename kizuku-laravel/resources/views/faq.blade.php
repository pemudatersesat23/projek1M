@extends('layouts.app')

@section('title', app()->getLocale() == 'jp' ? 'FAQ (よくある質問) - Kizuku' : 'FAQ (Pertanyaan Umum) - Kizuku')
@section('meta_description', 'Temukan jawaban untuk pertanyaan umum seputar pendaftaran, program kerja ke Jepang, persyaratan, biaya, dan proses keberangkatan di LPK Kizuku International Academy.')

@push('seo')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @php $allFaqs = collect($faqsGrouped)->flatten(1); @endphp
    @foreach($allFaqs as $i => $faq)
    {
      "@type": "Question",
      "name": "{{ e($faq->getTranslation('question', 'id')) }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ e($faq->getTranslation('answer', 'id')) }}"
      }
    }@if($i < $allFaqs->count() - 1),@endif
    @endforeach
  ]
}
</script>
@endpush

@section('content')
<!-- Hero Section FAQ -->
<section class="faq-hero section-pad" style="padding-top: 140px; padding-bottom: 80px; background: #f8fafc; position: relative; overflow: hidden; min-height: 40vh; display: flex; align-items: center;">
    <div style="position: absolute; top: -10%; left: -5%; width: 450px; height: 450px; background: radial-gradient(circle, rgba(0, 103, 163, 0.08), transparent 70%); filter: blur(50px); pointer-events: none; z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2; text-align: center;">
        <div style="display: inline-block; padding: 6px 16px; border-radius: 99px; background: rgba(0,103,163,0.1); color: var(--primary); font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px;">
            {{ app()->getLocale() == 'jp' ? 'サポート・情報' : 'Pusat Informasi' }}
        </div>
        <h1 style="font-size: 42px; font-weight: 900; color: var(--black); letter-spacing: -1px; margin-bottom: 16px; line-height: 1.2;">
            {{ app()->getLocale() == 'jp' ? 'よくある質問' : 'Pertanyaan Umum' }} (FAQ)
        </h1>
        <p style="font-size: 18px; color: var(--slate-600); max-width: 600px; margin: 0 auto; font-weight: 500; line-height: 1.6;">
            {{ app()->getLocale() == 'jp' ? 'プログラムへの参加や日本での働き方について、よくご相談いただく内容をまとめました。' : 'Temukan jawaban cepat untuk pertanyaan yang paling sering ditanyakan seputar pendaftaran, program, dan keberangkatan.' }}
        </p>
    </div>
</section>

<!-- FAQ List Section -->
<section class="faq-list-section section-pad" style="background: white; padding: 60px 0;">
    <div class="container" style="max-width: 800px;">
        
        @if(empty($faqsGrouped) || count($faqsGrouped) == 0)
            <div style="text-center; padding: 60px 0;">
                <p style="color: var(--slate-500); font-weight: 500; text-align: center;">FAQ belum tersedia saat ini.</p>
            </div>
        @else
            @foreach($faqsGrouped as $kategori => $faqs)
                <div class="faq-category-block" style="margin-bottom: 40px;">
                    <h3 class="faq-category-title" style="font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9;">
                        {{ $kategori }}
                    </h3>
                    
                    <div class="faq-accordion-wrapper">
                        @foreach($faqs as $faq)
                            <div class="faq-item">
                                <button class="faq-btn">
                                    <span class="faq-question">
                                        {{ $faq->getTranslation('question', app()->getLocale(), false) ? $faq->getTranslation('question', app()->getLocale()) : $faq->question }}
                                    </span>
                                    <span class="material-symbols-outlined faq-icon">expand_more</span>
                                </button>
                                <div class="faq-content">
                                    <div class="faq-answer">
                                        {{ $faq->getTranslation('answer', app()->getLocale(), false) ? $faq->getTranslation('answer', app()->getLocale()) : $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Call to action jika FAQ tidak menjawab -->
        <div class="faq-cta-box" style="margin-top: 60px; text-align: center; background: rgba(0,103,163,0.05); border-radius: 24px; padding: 40px; border: 1px solid rgba(0,103,163,0.1);">
            <h3 style="font-size: 24px; font-weight: 800; color: var(--black); margin-bottom: 16px;">
                {{ app()->getLocale() == 'jp' ? 'まだご不明な点はありますか？' : 'Masih punya pertanyaan lain?' }}
            </h3>
            <p style="color: var(--slate-600); font-weight: 500; margin-bottom: 30px;">
                {{ app()->getLocale() == 'jp' ? '私たちのチームがいつでもご相談をお受けします。' : 'Tim kami selalu siap membantu menjawab semua keraguan Anda. Klik tombol di bawah untuk terhubung dengan Admin.' }}
            </p>
            <a href="javascript:void(0)" onclick="openWaModal()" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 32px; font-size: 16px; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,103,163,0.15);">
                <span class="material-symbols-outlined">chat</span>
                {{ app()->getLocale() == 'jp' ? '管理者に問い合わせる' : 'Tanya Admin Sekarang' }}
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
    .faq-accordion-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .faq-item {
        background: #f8fafc;
        border: 2px solid transparent;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .faq-item:hover {
        background: white;
        border-color: rgba(0, 103, 163, 0.1);
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
    }
    
    .faq-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        background: transparent;
        border: none;
        cursor: pointer;
        text-align: left;
        font-family: inherit;
        outline: none;
    }
    
    .faq-question {
        font-size: 17px;
        font-weight: 700;
        color: var(--black);
        padding-right: 20px;
        line-height: 1.4;
    }
    
    .faq-icon {
        color: var(--slate-400);
        transition: transform 0.4s ease, color 0.3s ease;
        flex-shrink: 0;
    }
    
    .faq-item:hover .faq-icon {
        color: var(--primary);
    }
    
    .faq-content {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease;
    }
    
    .faq-answer {
        padding: 0 24px 24px 24px;
        color: var(--slate-600);
        font-size: 15px;
        font-weight: 500;
        line-height: 1.7;
        border-top: 1px solid rgba(0,0,0,0.05);
        margin-top: 5px;
        padding-top: 20px;
    }
    
    /* Active State */
    .faq-item.active {
        background: white;
        border-color: rgba(0, 103, 163, 0.2);
        box-shadow: 0 15px 35px rgba(0, 103, 163, 0.08);
    }
    
    .faq-item.active .faq-icon {
        transform: rotate(180deg);
        color: var(--primary);
    }
    
    .faq-item.active .faq-content {
        max-height: 1000px;
        opacity: 1;
    }
    
    @media (max-width: 768px) {
        .faq-question {
            font-size: 15px;
        }
        .faq-btn {
            padding: 16px 20px;
        }
        .faq-answer {
            padding: 0 20px 20px 20px;
            font-size: 14px;
        }
        .faq-category-title {
            font-size: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const faqBtns = document.querySelectorAll('.faq-btn');
        faqBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const parent = btn.parentElement;
                
                // Tutup FAQ lain yang sedang terbuka (opsional, jika ingin hanya 1 terbuka)
                document.querySelectorAll('.faq-item.active').forEach(item => {
                    if (item !== parent) {
                        item.classList.remove('active');
                    }
                });
                
                // Toggle accordion yang di-klik
                parent.classList.toggle('active');
            });
        });
    });
</script>
@endpush
@endsection
