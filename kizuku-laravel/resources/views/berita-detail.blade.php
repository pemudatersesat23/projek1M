@extends('layouts.app')


@section('title', ($berita->getTranslation('judul', app()->getLocale()) ?: $berita->judul) . ' - LPK Kizuku')

@section('content')
<main class="berita-detail-page" style="padding-top: 68px; min-height: 80vh; background: radial-gradient(circle at 10% 10%, rgba(225, 6, 0, 0.03) 0%, transparent 40%), radial-gradient(circle at 90% 90%, rgba(15, 76, 129, 0.03) 0%, transparent 40%), #fff;">
    
    {{-- Header Page --}}
    <div style="background: linear-gradient(to bottom, rgba(225, 6, 0, 0.12) 0%, rgba(255, 255, 255, 0) 100%); padding: 60px 0 30px; text-align: center; position: relative;">
        <div class="container" style="position: relative; z-index: 1;">
            <h1 style="font-size: clamp(32px, 5vw, 48px); font-weight: 800; margin: 0; letter-spacing: -1.5px; color: var(--red);">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? 'ニュース' : 'Berita' }}
            </h1>
            <p style="margin-top: 8px; font-size: 15px; font-weight: 500; color: #64748b;">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? 'LPK Kizukuからの最新情報' : 'Informasi dan update terbaru dari LPK Kizuku' }}
            </p>
        </div>
    </div>

    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 20px 20px 60px; position: relative; z-index: 2;">
        <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px; font-weight: 500;">
            <a href="{{ url('/') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='inherit'">Home</a>
            <span class="material-symbols-outlined" style="font-size: 16px; opacity: 0.5;">chevron_right</span>
            <span style="color: var(--red); font-weight: 700;">Berita</span>
        </div>

        <article class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @if($berita->image)
                <div class="berita-detail-img" style="width: 100%; max-height: 500px; overflow: hidden;">
                    <img src="{{ asset('storage/' . $berita->image) }}" alt="{{ $berita->getTranslation('judul', app()->getLocale()) ?: $berita->judul }}" style="width: 100%; height: auto; object-fit: cover;">
                </div>
            @else
                <div style="height: 200px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center; font-size: 64px;">
                    {{ $berita->emoji }}
                </div>
            @endif
            <div class="p-8 md:p-12">
                <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin-top: 12px; margin-bottom: 24px; line-height: 1.2;">
                    {{ $berita->getTranslation('judul', app()->getLocale()) ?: $berita->judul }}
                </h1>

                <div class="berita-content" style="color: #475569; line-height: 1.8; font-size: 16px; white-space: pre-line; margin-bottom: 30px;">
                    {!! nl2br(e($berita->getTranslation('isi', app()->getLocale()) ?: $berita->isi)) !!}
                </div>

                <div style="padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                    <span style="font-size: 12px; color: #94a3b8; font-weight: 500;">
                        Dibuat pada {{ $berita->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </article>

        @if(isset($recentNews) && $recentNews->count() > 0)
            <div style="margin-top: 60px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 24px;">{{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? '最新ニュース' : 'Berita Terbaru Lainnya' }}</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
                    @foreach($recentNews as $rn)
                        <a href="{{ route('berita.show', $rn) }}" style="display: flex; gap: 16px; background: white; padding: 16px; rounded-xl; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.1)'" onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                            <div style="width: 80px; height: 80px; border-radius: 10px; background: #f1f5f9; overflow: hidden; flex-shrink: 0;">
                                @if($rn->image)
                                    <img src="{{ asset('storage/' . $rn->image) }}" alt="{{ $rn->getTranslation('judul', app()->getLocale()) ?: $rn->judul }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 24px;">{{ $rn->emoji }}</div>
                                @endif
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #334155; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                    {{ $rn->getTranslation('judul', app()->getLocale()) ?: $rn->judul }}
                                </h4>
                                <span style="font-size: 12px; color: #94a3b8;">{{ $rn->created_at->format('d M Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div style="margin-top: 40px; text-align: center;">
            <a href="{{ url('/') }}#berita" class="btn btn-outline" style="border-radius: 99px; padding: 12px 32px;">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? 'ホームに戻る' : 'Kembali ke Beranda' }}
            </a>
        </div>
    </div>

</main>
@endsection
