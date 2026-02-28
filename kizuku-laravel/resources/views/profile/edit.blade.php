@extends('layouts.authenticated')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 100px 5vw 40px 5vw;">
    <div style="max-width: 600px; margin: 0 auto;">
        <!-- Page Header -->
        <div style="margin-bottom: 40px;">
            <h1 style="font-size: 32px; font-weight: 700; color: #111; margin: 0 0 8px 0;">⚙️ Pengaturan Profil</h1>
            <p style="font-size: 16px; color: #666; margin: 0;">Kelola informasi akun Anda</p>
        </div>

        <!-- Profile Form Card -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Password Form Card -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 24px;">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>

<style>
    x-input-label, 
    x-text-input,
    x-input-error,
    x-primary-button {
        all: initial;
        display: revert;
    }

    section {
        margin: 0;
    }

    section header {
        margin-bottom: 24px;
    }

    section header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #111;
        margin: 0 0 8px 0;
    }

    section header p {
        font-size: 14px;
        color: #666;
        margin: 0;
    }

    section form {
        margin-top: 24px;
    }

    section div {
        margin-bottom: 20px;
    }

    section input,
    section textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid rgba(17, 17, 17, 0.15);
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }

    section input:focus,
    section textarea:focus {
        outline: none;
        border-color: var(--red, #e10600);
        box-shadow: 0 0 0 3px rgba(225, 6, 0, 0.1);
    }

    section label {
        display: block;
        font-weight: 500;
        color: #111;
        margin-bottom: 8px;
        font-size: 14px;
    }

    section button[type="submit"] {
        padding: 10px 24px;
        background: linear-gradient(135deg, var(--red, #e10600), rgba(225, 6, 0, 0.8));
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    section button[type="submit"]:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    section button[type="submit"].delete {
        background: linear-gradient(135deg, #dc2626, #991b1b);
    }

    .text-green-600 {
        color: #16a34a;
        font-weight: 500;
    }

    .text-red-600 {
        color: #dc2626;
        font-weight: 500;
    }

    .mt-2 {
        margin-top: 8px;
    }

    .text-sm {
        font-size: 13px;
    }
</style>
@endsection

