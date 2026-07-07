@extends('layouts.app')

@section('title', ($partnerCampus->getTranslation('name', app()->getLocale()) ?: $partnerCampus->name) . ' - LPK Kizuku')
@section('meta_description', Str::limit(strip_tags($partnerCampus->getTranslation('description', 'id') ?: $partnerCampus->description), 160))
@if($partnerCampus->logo)
@section('og_image', Storage::url($partnerCampus->logo))
@endif

@section('content')
<main class="partner-detail-page" style="padding-top: 68px; min-height: 80vh; background: radial-gradient(circle at 10% 10%, rgba(225, 6, 0, 0.03) 0%, transparent 40%), radial-gradient(circle at 90% 90%, rgba(15, 76, 129, 0.03) 0%, transparent 40%), #fff;">
    
    {{-- Header Page Banner --}}
    <div style="background: linear-gradient(to bottom, rgba(15, 76, 129, 0.12) 0%, rgba(255, 255, 255, 0) 100%); padding: 60px 0 30px; text-align: center; position: relative;">
        <div class="container" style="position: relative; z-index: 1;">
            <h1 style="font-size: clamp(32px, 5vw, 48px); font-weight: 800; margin: 0; letter-spacing: -1.5px; color: var(--blue);">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? '提携機関' : 'Partnership' }}
            </h1>
            <p style="margin-top: 8px; font-size: 15px; font-weight: 500; color: #64748b;">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? 'LPK Kizukuのグローバル提携ネットワーク' : 'Jejaring Kemitraan Internasional LPK Kizuku' }}
            </p>
        </div>
    </div>

    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 20px 20px 60px; position: relative; z-index: 2;">
        {{-- Breadcrumbs --}}
        <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px; font-weight: 500;">
            <a href="{{ url('/') }}" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='inherit'">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? 'ホーム' : 'Home' }}
            </a>
            <span class="material-symbols-outlined" style="font-size: 16px; opacity: 0.5;">chevron_right</span>
            <a href="{{ url('/') }}#kampus-partner" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--red)'" onmouseout="this.style.color='inherit'">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? '提携機関' : 'Partnership' }}
            </a>
            <span class="material-symbols-outlined" style="font-size: 16px; opacity: 0.5;">chevron_right</span>
            <span style="color: var(--blue); font-weight: 700;">
                {{ $partnerCampus->getTranslation('name', app()->getLocale()) ?: $partnerCampus->name }}
            </span>
        </div>

        {{-- Detail Card --}}
        <article class="bg-white rounded-3xl shadow-lg border border-slate-200/80 overflow-hidden" style="position: relative; transition: box-shadow 0.3s;">
            {{-- Banner Image --}}
            <div style="width: 100%; aspect-ratio: 21 / 9; max-height: 400px; position: relative; background: #f8fafc; overflow: hidden;">
                @if($partnerCampus->banner)
                    <img src="{{ Storage::url($partnerCampus->banner) }}" alt="Banner {{ $partnerCampus->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(15,76,129,0.1) 0%, rgba(225,6,0,0.1) 100%); display: flex; align-items: center; justify-content: center; font-weight: 600; color: #94a3b8;">
                        LPK Kizuku Partner Campus
                    </div>
                @endif
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.5) 100%);"></div>
            </div>

            {{-- Overlapping Logo --}}
            <div style="position: relative; margin: -60px auto 0; width: 120px; height: 120px; background: white; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; padding: 18px; border: 4px solid white; z-index: 10;">
                <img src="{{ Storage::url($partnerCampus->logo) }}" alt="{{ $partnerCampus->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>

            {{-- Content Section --}}
            <div style="padding: 40px 32px 50px; text-align: center;">
                <h2 style="font-size: clamp(24px, 4vw, 36px); font-weight: 800; color: #0b1220; letter-spacing: -1px; margin-bottom: 8px;">
                    {{ $partnerCampus->getTranslation('name', app()->getLocale()) ?: $partnerCampus->name }}
                </h2>

                <div style="width: 60px; height: 5px; background: var(--grad-mix); border-radius: 10px; margin: 15px auto 30px;"></div>

                <div class="partner-description" style="color: #4b5563; line-height: 1.8; font-size: 16px; text-align: justify; max-width: 760px; margin: 0 auto; white-space: pre-line;">
                    {!! nl2br(e($partnerCampus->getTranslation('description', app()->getLocale()) ?: ($partnerCampus->getTranslation('description', 'id') ?: $partnerCampus->description))) !!}
                </div>
            </div>
        </article>

        {{-- Other Partners Grid --}}
        @if(isset($recentCampuses) && $recentCampuses->count() > 0)
            <div style="margin-top: 70px;">
                <h3 style="font-size: 22px; font-weight: 800; color: #0b1220; margin-bottom: 28px; letter-spacing: -0.5px;">
                    {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? '他の提携機関' : 'Partnership Lainnya' }}
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
                    @foreach($recentCampuses as $rc)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden" style="display: flex; flex-direction: column; height: 100%; box-shadow: 0 4px 20px rgba(0,0,0,0.03); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-6px)'; this.style.borderColor='rgba(15, 76, 129, 0.2)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='none'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
                            {{-- Mini Banner --}}
                            <div style="width: 100%; aspect-ratio: 16/9; background: #f8fafc; overflow: hidden; position: relative;">
                                @if($rc->banner)
                                    <img src="{{ Storage::url($rc->banner) }}" alt="Banner {{ $rc->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, rgba(15,76,129,0.05) 0%, rgba(225,6,0,0.05) 100%);"></div>
                                @endif
                                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.3) 100%);"></div>
                            </div>
                            
                            {{-- Mini Content --}}
                            <div style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1; text-align: center; align-items: center;">
                                <h4 style="margin: 0 0 10px 0; font-size: 16px; font-weight: 800; color: #0b1220; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;">
                                    {{ $rc->getTranslation('name', app()->getLocale()) ?: $rc->name }}
                                </h4>
                                <div style="width: 30px; height: 3px; background: var(--grad-blue); border-radius: 5px; margin: 4px auto 12px;"></div>
                                
                                <p style="font-size: 13px; color: #6b7280; line-height: 1.5; margin: 0 0 20px 0; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $rc->getShortDescription(app()->getLocale(), 2) }}
                                </p>
                                
                                <a href="{{ route('partner.show', $rc) }}" class="btn btn-outline" style="border-radius: 99px; padding: 6px 20px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; border-color: rgba(15, 76, 129, 0.15); color: var(--blue); font-weight: 600; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--blue)'; this.style.background='rgba(15, 76, 129, 0.05)'" onmouseout="this.style.borderColor='rgba(15, 76, 129, 0.15)'; this.style.background='transparent'">
                                    {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? '詳細を見る' : 'Lihat Selengkapnya' }}
                                    <span class="material-symbols-outlined" style="font-size: 13px;">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Back Button --}}
        <div style="margin-top: 50px; text-align: center;">
            <a href="{{ url('/') }}#kampus-partner" class="btn btn-outline" style="border-radius: 99px; padding: 12px 32px; font-weight: 700;">
                {{ (app()->getLocale() == 'ja' || app()->getLocale() == 'jp') ? 'ホームに戻る' : 'Kembali ke Beranda' }}
            </a>
        </div>
    </div>
</main>
@endsection
