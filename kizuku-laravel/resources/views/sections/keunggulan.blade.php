@php
    $keunggulans = \App\Models\Keunggulan::where('is_active', true)->orderBy('order', 'asc')->get();
@endphp

@if($keunggulans->count() > 0)
<!-- ═══ KEUNGGULAN KIZUKU ═══ -->
<section id="keunggulan" class="section-pad" style="background: white; position: relative; overflow: hidden;">
    <!-- Decorative element -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.4; pointer-events: none; background-image: radial-gradient(#0067a3 0.5px, transparent 0.5px); background-size: 24px 24px; z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div class="sec-head reveal" style="text-align:center; max-width:700px; margin:0 auto 60px;">
            <div class="sec-tag" style="background: rgba(0, 103, 163, 0.08); color: var(--primary); border-color: rgba(0, 103, 163, 0.1);">
                {{ app()->getLocale() == 'jp' ? '✦ なぜ私たちを選ぶのか' : '✦ KENAPA MEMILIH KAMI?' }}
            </div>
            <h2 class="sec-h2" style="background: linear-gradient(90deg, #0f1c23, var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                {{ app()->getLocale() == 'jp' ? 'キズク インターナショナル アカデミーの強み' : 'Keunggulan Kizuku International Academy' }}
            </h2>
            <p class="sec-p" style="margin:0 auto; color: #64748b;">
                {{ app()->getLocale() == 'jp' ? '私たちは、日本でのキャリアの夢を叶えるための最高のパートナーです。' : 'Kami memberikan standar pelatihan dan pelayanan terbaik untuk memastikan setiap langkah Anda menuju Jepang berjalan lancar dan terjamin.' }}
            </p>
        </div>

        <div class="keunggulan-grid">
            @foreach($keunggulans as $item)
            <div class="keunggulan-card reveal" style="--d: {{ $loop->index * 100 }}ms">
                <div class="k-icon-box">
                    <span class="material-symbols-outlined">{{ $item->icon }}</span>
                </div>
                <h4 class="k-title">{{ $item->getTranslation('title', app()->getLocale()) }}</h4>
                <p class="k-desc">{{ $item->getTranslation('description', app()->getLocale()) }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <style>
        .keunggulan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .keunggulan-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .keunggulan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,103,163,0.1);
            border-color: rgba(0,103,163,0.2);
        }

        .k-icon-box {
            width: 70px;
            height: 70px;
            background: #f8fafc;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .k-icon-box span {
            font-size: 36px;
        }

        .keunggulan-card:hover .k-icon-box {
            background: var(--primary);
            color: #fff;
            transform: rotate(5deg) scale(1.1);
        }

        .k-title {
            font-size: 18px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .k-desc {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 640px) {
            .keunggulan-card {
                padding: 30px 20px;
            }
        }
    </style>
</section>
@endif
