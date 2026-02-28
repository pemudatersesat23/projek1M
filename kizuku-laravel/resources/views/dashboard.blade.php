@extends('layouts.authenticated')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 100px 5vw 40px 5vw;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Welcome Section -->
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 36px; font-weight: 700; color: #111; margin: 0 0 8px 0;">👋 Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p style="font-size: 16px; color: #666; margin: 0;">Anda login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>
        </div>

        <!-- Dashboard Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
            
            <!-- Profile Card -->
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #0067a3;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="font-size: 32px;">👤</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111;">Profil Saya</h3>
                </div>
                <p style="font-size: 14px; color: #666; margin: 0 0 16px 0;">Kelola informasi pribadi, email, dan password Anda</p>
                <a href="{{ route('profile.edit') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #0067a3, rgba(0, 103, 163, 0.8)); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">Edit Profil →</a>
            </div>

            <!-- Information Card -->
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #e10600;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="font-size: 32px;">ℹ️</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111;">Informasi Akun</h3>
                </div>
                <p style="font-size: 14px; color: #666; margin: 0 0 16px 0;">
                    <strong>Email:</strong> {{ auth()->user()->email }}<br><br>
                    <strong>Status:</strong> {{ auth()->user()->role === 'admin' ? '🔐 Admin' : '👤 Peserta Kizuku' }}
                </p>
            </div>

            <!-- Home Card -->
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid #16a34a;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="font-size: 32px;">🏠</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #111;">Kembali ke Beranda</h3>
                </div>
                <p style="font-size: 14px; color: #666; margin: 0 0 16px 0;">Lihat informasi lengkap tentang LPK Kizuku dan program pelatihan kami</p>
                <a href="{{ route('home') }}" style="display: inline-block; padding: 10px 20px; background: linear-gradient(135deg, #16a34a, rgba(22, 163, 74, 0.8)); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px;">Lihat Beranda →</a>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h2 style="font-size: 20px; font-weight: 600; color: #111; margin: 0 0 20px 0;">📋 Tautan Cepat</h2>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('profile.edit') }}" style="padding: 10px 16px; background: #f0f0f0; color: #111; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: background .3s;">Pengaturan Profil</a>
                <a href="{{ route('home') }}" style="padding: 10px 16px; background: #f0f0f0; color: #111; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: background .3s;">Halaman Utama</a>
            </div>
        </div>
    </div>
</div>
@endsection
