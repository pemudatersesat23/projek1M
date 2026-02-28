<section>
    <header>
        <h2 style="font-size: 18px; font-weight: 600; color: #dc2626; margin: 0 0 8px 0;">⚠️ Hapus Akun</h2>
        <p style="font-size: 14px; color: #666; margin: 0;">Setelah akun dihapus, semua data akan hilang permanen. Harap download data Anda sebelum menghapus akun.</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" style="margin-top: 24px;">
        @csrf
        @method('delete')

        <div style="margin-bottom: 20px;">
            <label for="delete_password" style="display: block; font-weight: 500; color: #111; margin-bottom: 8px; font-size: 14px;">
                Masukkan Password untuk Konfirmasi
            </label>
            <input id="delete_password" name="password" type="password" style="width: 100%; padding: 10px 12px; border: 1px solid rgba(17, 17, 17, 0.15); border-radius: 8px; font-size: 14px; box-sizing: border-box;" placeholder="Password Anda" />
            @error('password', 'userDeletion')
                <p style="color: #dc2626; font-size: 13px; margin-top: 6px; margin-bottom: 0;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="submit" style="padding: 10px 24px; background: linear-gradient(135deg, #dc2626, rgba(220, 38, 38, 0.8)); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all .3s; font-size: 14px;">
                🗑️ Permanently Hapus Akun
            </button>
            <p style="font-size: 12px; color: #dc2626; margin: 0;">⚠️ Aksi ini tidak dapat dibatalkan!</p>
        </div>
    </form>
</section>
