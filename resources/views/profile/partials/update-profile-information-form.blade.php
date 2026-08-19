<section>
    <header class="border-b border-slate-100 dark:border-slate-800 pb-4 mb-6">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __("Perbarui data pribadi, unit kerja, dan kontak akun Anda.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Picture Section -->
        <div class="flex items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
            <div class="relative shrink-0">
                @php
                    $defaultAvatarUrl = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" fill="none"><circle cx="64" cy="64" r="64" fill="%23e2e8f0"/><path d="M64 68c15.464 0 28-12.536 28-28S79.464 12 64 12s-28 12.536-28 28 12.536 28 28 28zm0 12c-22.091 0-40 17.909-40 40h80c0-22.091-17.909-40-40-40z" fill="%2394a3b8"/></svg>';
                    $avatarUrl = $user->foto_profil ? asset('storage/' . $user->foto_profil) : $defaultAvatarUrl;
                @endphp
                <img id="avatar_preview" src="{{ $avatarUrl }}" alt="Foto Profil" 
                     class="w-24 h-24 rounded-full object-cover border-2 border-slate-200 dark:border-slate-800 shadow-sm">
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-800 dark:text-white">Foto Profil</label>
                <div class="flex items-center gap-3">
                    <!-- Hidden File Input -->
                    <input type="file" id="foto_profil_input" name="foto_profil" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <!-- Hidden Remove Input -->
                    <input type="hidden" id="remove_photo_input" name="remove_photo" value="0">

                    <button type="button" onclick="document.getElementById('foto_profil_input').click()" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition cursor-pointer">
                        Unggah Foto
                    </button>
                    
                    <button type="button" onclick="removeImage()" id="btn_remove_avatar" 
                            class="px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition cursor-pointer {{ !$user->foto_profil ? 'hidden' : '' }}">
                        Hapus
                    </button>
                </div>
                <p class="text-[11px] text-slate-450 dark:text-slate-500 font-medium">Mendukung format PNG, JPEG, WEBP atau GIF. Ukuran maksimum 10MB.</p>
                <x-input-error class="mt-1 text-xs text-rose-500" :messages="$errors->get('foto_profil')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NIP (Read-Only) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">NIP Pengguna (Terkunci)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 dark:text-slate-650">
                        <i class="fa-solid fa-id-card text-xs"></i>
                    </span>
                    <input type="text" value="{{ $user->nip }}" disabled 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 rounded-xl text-sm focus:outline-none cursor-not-allowed">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 dark:text-slate-500" title="NIP tidak dapat diubah">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                    </span>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-user text-xs"></i>
                    </span>
                    <input id="nama" name="nama" type="text" value="{{ old('nama', $user->nama) }}" required autofocus
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('nama')" />
            </div>

            <!-- OPD / Unit Kerja -->
            <div>
                <label for="opd" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">OPD / Unit Kerja</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-building text-xs"></i>
                    </span>
                    <input id="opd" name="opd" type="text" value="{{ old('opd', $user->opd) }}" required
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('opd')" />
            </div>

            <!-- Jabatan (Read-Only) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Jabatan Aktif (Terkunci)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 dark:text-slate-650">
                        <i class="fa-solid fa-briefcase text-xs"></i>
                    </span>
                    <input type="text" value="{{ $user->jabatan_aktif }}" disabled 
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-400 dark:text-slate-500 rounded-xl text-sm focus:outline-none cursor-not-allowed">
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 dark:text-slate-500" title="Jabatan hanya dapat diubah melalui Transfer Jabatan">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                    </span>
                </div>
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="no_telp" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Nomor Telepon</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-phone text-xs"></i>
                    </span>
                    <input id="no_telp" name="no_telp" type="text" value="{{ old('no_telp', $user->no_telp) }}" required
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('no_telp')" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </span>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                           class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-200">
                </div>
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100 dark:border-slate-800">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-md hover:shadow-lg cursor-pointer">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold"
                >
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    document.getElementById('avatar_preview').src = reader.result;
                    document.getElementById('btn_remove_avatar').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
                document.getElementById('remove_photo_input').value = 0;
            }
        }

        function removeImage() {
            document.getElementById('avatar_preview').src = '{{ $defaultAvatarUrl }}';
            document.getElementById('foto_profil_input').value = '';
            document.getElementById('remove_photo_input').value = 1;
            document.getElementById('btn_remove_avatar').classList.add('hidden');
        }
    </script>
</section>
