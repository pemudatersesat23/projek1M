<section>
    <header>
        <h2 style="font-size: 18px; font-weight: 600; color: #111; margin: 0 0 8px 0;">🔐 {{ __('messages.nav.home') === 'Beranda' ? 'Ubah Password' : 'パスワード変更' }}</h2>
        <p style="font-size: 14px; color: #666; margin: 0;">{{ __('messages.nav.home') === 'Beranda' ? 'Pastikan akun Anda menggunakan password yang kuat dan aman.' : 'アカウントが強力で安全なパスワードを使用していることを確認してください。' }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="margin-top: 24px;">
        @csrf
        @method('put')

        <div style="margin-bottom: 20px;">
            <label for="update_password_current_password" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                {{ __('messages.nav.home') === 'Beranda' ? 'Password Sekarang' : '現在のパスワード' }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" autocomplete="current-password" placeholder="{{ __('messages.nav.home') === 'Beranda' ? 'Masukkan password saat ini' : '現在のパスワードを入力してください' }}" />
            @error('current_password', 'updatePassword')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="update_password_password" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                {{ __('messages.nav.home') === 'Beranda' ? 'Password Baru' : '新しいパスワード' }}
            </label>
            <input id="update_password_password" name="password" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" autocomplete="new-password" placeholder="{{ __('messages.nav.home') === 'Beranda' ? 'Masukkan password baru' : '新しいパスワードを入力してください' }}" />
            @error('password', 'updatePassword')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="update_password_password_confirmation" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                {{ __('messages.nav.home') === 'Beranda' ? 'Konfirmasi Password' : 'パスワードの確認' }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" autocomplete="new-password" placeholder="{{ __('messages.nav.home') === 'Beranda' ? 'Ulangi password baru' : '新しいパスワードを繰り返します' }}" />
            @error('password_confirmation', 'updatePassword')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
            <button type="submit" style="padding: 10px 24px; background: linear-gradient(135deg, var(--red, #e10600), rgba(225, 6, 0, 0.8)); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all .3s; font-size: 14px;">
                💾 {{ __('messages.nav.home') === 'Beranda' ? 'Simpan' : '保存' }}
            </button>

            @if (session('status') === 'password-updated')
                <p style="color: #16a34a; font-weight: 500; font-size: 13px; margin: 0;">✅ {{ __('messages.nav.home') === 'Beranda' ? 'Password berhasil diperbarui!' : 'パスワードが正常に更新されました！' }}</p>
            @endif
        </div>
    </form>
</section>
