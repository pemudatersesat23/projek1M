<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — LPK Kizuku International Academy</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo tab broswer.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --red: #E10600;
            --blue: #0F4C81;
            --cyan: #1FA2C9;
            --black: #0B1220;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Sora', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--black);
            overflow: hidden;
        }

        /* ── LEFT PANEL (decorative) ── */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--blue), #0a3560, var(--black));
            position: relative;
            display: none;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .login-left { display: flex; }
        }

        .login-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .left-blob-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(225, 6, 0, 0.12), transparent 70%);
            top: -100px;
            left: -100px;
            filter: blur(60px);
            animation: float 12s infinite ease-in-out;
        }

        .left-blob-2 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(31, 162, 201, 0.1), transparent 70%);
            bottom: -80px;
            right: -80px;
            filter: blur(50px);
            animation: float 16s infinite ease-in-out reverse;
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 40px;
        }

        .left-content img {
            width: 180px;
            margin-bottom: 40px;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));
        }

        .left-content h2 {
            color: #fff;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .left-content h2 span {
            background: linear-gradient(90deg, var(--cyan), #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .left-content p {
            color: rgba(255,255,255,0.5);
            font-size: 14px;
            line-height: 1.6;
            max-width: 340px;
            margin: 0 auto;
        }

        .left-feature {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 50px;
        }

        .left-feature-item {
            text-align: center;
        }

        .left-feature-item .feat-num {
            display: block;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
        }

        .left-feature-item .feat-num span {
            color: var(--cyan);
        }

        .left-feature-item .feat-label {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* ── RIGHT PANEL (form) ── */
        .login-right {
            width: 100%;
            max-width: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            background: #fff;
            position: relative;
        }

        @media (min-width: 768px) {
            .login-right {
                border-radius: 40px 0 0 40px;
            }
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .login-logo-mobile {
            display: block;
            text-align: center;
            margin-bottom: 30px;
        }

        @media (min-width: 768px) {
            .login-logo-mobile { display: none; }
        }

        .login-logo-mobile img {
            width: 120px;
        }

        .login-header h1 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--black);
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 15px;
            font-family: 'Sora', sans-serif;
            background: #f9fafb;
            transition: all 0.3s ease;
            outline: none;
            color: var(--black);
        }

        .form-group input:focus {
            border-color: var(--blue);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15, 76, 129, 0.08);
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--blue);
            border-radius: 4px;
            cursor: pointer;
        }

        .remember-check span {
            font-size: 13px;
            color: #6B7280;
            font-weight: 500;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #b80500;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--blue), #0a3560);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(15, 76, 129, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
        }

        .login-divider::before,
        .login-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .login-divider span {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .back-home-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            color: #6B7280;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-home-link:hover {
            border-color: var(--red);
            color: var(--red);
            background: rgba(225, 6, 0, 0.03);
        }

        .login-error {
            padding: 12px 16px;
            background: rgba(225, 6, 0, 0.06);
            border: 1px solid rgba(225, 6, 0, 0.15);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .login-error p {
            color: var(--red);
            font-size: 13px;
            font-weight: 500;
            margin: 0;
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #9ca3af;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</head>
<body>

    <!-- ── LEFT: Decorative Panel ── -->
    <div class="login-left">
        <div class="left-blob-1"></div>
        <div class="left-blob-2"></div>
        <div class="left-content">
            <img src="{{ asset('image/logo kiuzuku utama.png') }}" alt="LPK Kizuku">
            <h2>Selamat Datang di<br><span>Admin Panel</span></h2>
            <p>Kelola program pelatihan, berita, dan data peserta dari satu tempat yang mudah dan terpusat.</p>
            <div class="left-feature">
                <div class="left-feature-item">
                    <span class="feat-num">1000<span>+</span></span>
                    <span class="feat-label">Alumni</span>
                </div>
                <div class="left-feature-item">
                    <span class="feat-num">10<span>+</span></span>
                    <span class="feat-label">Tahun</span>
                </div>
                <div class="left-feature-item">
                    <span class="feat-num">4<span>+</span></span>
                    <span class="feat-label">Program</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ── RIGHT: Login Form ── -->
    <div class="login-right">
        <div class="login-form-wrapper">
            <!-- Mobile Logo -->
            <div class="login-logo-mobile">
                <img src="{{ asset('image/logo kiuzuku utama.png') }}" alt="LPK Kizuku">
            </div>

            <div class="login-header">
                <h1>Login Admin 🔐</h1>
                <p>Masukkan akun admin Anda untuk melanjutkan</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="login-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Session Status -->
            @if (session('status'))
                <div style="padding:12px 16px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);border-radius:12px;margin-bottom:20px;">
                    <p style="color:#059669;font-size:13px;font-weight:500;margin:0;">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@kizuku.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>

                <div class="form-row">
                    <label class="remember-check">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Ingat Saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="login-btn">Masuk ke Dashboard</button>
            </form>

            <div class="login-divider">
                <span>atau</span>
            </div>

            <a href="{{ url('/') }}" class="back-home-link">
                ← Kembali ke Website
            </a>

            <div class="login-footer">
                &copy; {{ date('Y') }} LPK Kizuku International Academy
            </div>
        </div>
    </div>

</body>
</html>
