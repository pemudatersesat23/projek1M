<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update informasi profil akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Email verifikasi telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Role Info -->
        <div style="background: rgba(17, 17, 17, .04); padding: 16px; border-radius: 12px; border: 1px solid rgba(17, 17, 17, .08);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 14px; color: #666; margin: 0;">Status Akun</p>
                    <p style="font-size: 16px; font-weight: 600; color: #111; margin: 4px 0 0 0;">
                        @if($user->role === 'admin')
                            <span style="color: var(--red, #e10600);">🔑 Administrator</span>
                        @else
                            <span style="color: #2563eb;">👤 User Biasa</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <button type="submit" style="
                padding: 10px 24px;
                background: linear-gradient(135deg, var(--red, #e10600), rgba(225, 6, 0, .8));
                color: white;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all .3s;
            " onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                💾 Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >✅ Profil berhasil diperbarui!</p>
            @endif
        </div>
    </form>
</section>
