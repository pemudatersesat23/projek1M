@extends('layouts.authenticated')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 100px 5vw 40px 5vw;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Welcome Section -->
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 36px; font-weight: 700; color: #111; margin: 0 0 8px 0;">👋 {{ __('messages.dashboard.welcome') }}, {{ auth()->user()->name }}!</h1>
            <p style="font-size: 16px; color: #666; margin: 0;">{{ __('messages.dashboard.login_as') }} <strong>{{ auth()->user()->role === 'admin' ? __('messages.dashboard.admin_label') : __('messages.dashboard.user_label') }}</strong></p>
        </div>

        <!-- Dashboard Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
            
            <!-- Profile Card -->
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #0067a3;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="font-size: 32px;">👤</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111;">{{ __('messages.dashboard.profile') }}</h3>
                </div>
                <p style="font-size: 14px; color: #666; margin: 0 0 16px 0;">{{ __('messages.dashboard.profile_desc') }}</p>
                <a href="{{ route('profile.edit') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #0067a3, rgba(0, 103, 163, 0.8)); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">{{ __('messages.dashboard.edit_profile') }} →</a>
            </div>

            <!-- Information Card -->
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #e10600;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="font-size: 32px;">ℹ️</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111;">{{ __('messages.dashboard.info') }}</h3>
                </div>
                <p style="font-size: 14px; color: #666; margin: 0 0 16px 0;">
                    <strong>Email:</strong> {{ auth()->user()->email }}<br><br>
                    <strong>Status:</strong> {{ auth()->user()->role === 'admin' ? __('messages.dashboard.admin_label') : __('messages.dashboard.user_label') }}
                </p>
            </div>

            <!-- Home Card -->
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #16a34a;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="font-size: 32px;">🏠</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111;">{{ __('messages.dashboard.back_home') }}</h3>
                </div>
                <p style="font-size: 14px; color: #666; margin: 0 0 16px 0;">{{ __('messages.dashboard.back_home_desc') }}</p>
                <a href="{{ route('home') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #16a34a, rgba(22, 163, 74, 0.8)); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">{{ __('messages.dashboard.view_home') }} →</a>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h2 style="font-size: 20px; font-weight: 600; color: #111; margin: 0 0 20px 0;">📋 {{ __('messages.dashboard.quick_links') }}</h2>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('profile.edit') }}" style="padding: 10px 16px; background: #f0f0f0; color: #111; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: background .3s;">{{ __('messages.dashboard.settings') }}</a>
                <a href="{{ route('home') }}" style="padding: 10px 16px; background: #f0f0f0; color: #111; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: background .3s;">{{ __('messages.dashboard.view_home') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
