<section>
    <header>
        <h2 style="font-size: 18px; font-weight: 600; color: #111; margin: 0 0 8px 0;">🔐 Ubah Password</h2>
        <p style="font-size: 14px; color: #666; margin: 0;">Pastikan akun Anda menggunakan password yang kuat dan aman.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="margin-top: 24px;">
        @csrf
        @method('put')

        <div style="margin-bottom: 20px;">
            <label for="update_password_current_password" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                Password Sekarang
            </label>
            <input id="update_password_current_password" name="current_password" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" autocomplete="current-password" placeholder="Masukkan password saat ini" />
            @error('current_password', 'updatePassword')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="update_password_password" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                Password Baru
            </label>
            <input id="update_password_password" name="password" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" autocomplete="new-password" placeholder="Masukkan password baru" />
            @error('password', 'updatePassword')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="update_password_password_confirmation" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                Konfirmasi Password
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" autocomplete="new-password" placeholder="Ulangi password baru" />
            @error('password_confirmation', 'updatePassword')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
            <button type="submit" style="padding: 10px 24px; background: linear-gradient(135deg, var(--red, #e10600), rgba(225, 6, 0, 0.8)); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all .3s; font-size: 14px;">
                💾 Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p style="color: #16a34a; font-weight: 500; font-size: 13px; margin: 0;">✅ Password berhasil diperbarui!</p>
            @endif
        </div>
    </form>
</section>
