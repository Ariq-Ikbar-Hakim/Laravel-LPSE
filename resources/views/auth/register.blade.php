<x-guest-layout>
    <!-- Header Form -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftarkan Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Lengkapi informasi berikut untuk mengajukan akun pengadaan baru.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- NIP -->
            <div>
                <label for="nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIP</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-id-card text-sm"></i>
                    </span>
                    <input id="nip" type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 1992..." required autofocus autocomplete="username"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                </div>
                <x-input-error :messages="$errors->get('nip')" class="mt-1" />
            </div>

            <!-- Nama -->
            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap & Gelar" required autocomplete="name"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                </div>
                <x-input-error :messages="$errors->get('nama')" class="mt-1" />
            </div>

            <!-- OPD -->
            <div>
                <label for="opd" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">OPD / Instansi</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-building text-sm"></i>
                    </span>
                    <input id="opd" type="text" name="opd" value="{{ old('opd') }}" placeholder="Dinas / Badan / Kantor" required
                        class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                </div>
                <x-input-error :messages="$errors->get('opd')" class="mt-1" />
            </div>

            <!-- Sub Unit OPD -->
            <div>
                <label for="sub_unit_opd" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sub Unit OPD</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-network-wired text-sm"></i>
                    </span>
                    <input id="sub_unit_opd" type="text" name="sub_unit_opd" value="{{ old('sub_unit_opd') }}" placeholder="Bidang / Seksi / Bagian" required
                        class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                </div>
                <x-input-error :messages="$errors->get('sub_unit_opd')" class="mt-1" />
            </div>

            <!-- Jabatan Diajukan -->
            <div>
                <label for="jabatan_aktif" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jabatan Diajukan</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-user-tie text-sm"></i>
                    </span>
                    <select id="jabatan_aktif" name="jabatan_aktif" required
                        class="w-full pl-11 pr-10 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200 appearance-none">
                        <option value="" disabled selected>Pilih Jabatan</option>
                        <option value="PPK" {{ old('jabatan_aktif') == 'PPK' ? 'selected' : '' }}>PPK (Pejabat Pembuat Komitmen)</option>
                        <option value="PP" {{ old('jabatan_aktif') == 'PP' ? 'selected' : '' }}>PP (Pejabat Pengadaan)</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('jabatan_aktif')" class="mt-1" />
            </div>

            <!-- Nomor SK -->
            <div>
                <label for="sk_nomor" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor SK Jabatan</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-file-contract text-sm"></i>
                    </span>
                    <input id="sk_nomor" type="text" name="sk_nomor" value="{{ old('sk_nomor') }}" placeholder="Nomor SK Pengangkatan" required
                        class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                </div>
                <x-input-error :messages="$errors->get('sk_nomor')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div class="sm:col-span-2">
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="alamat@instansi.go.id" required autocomplete="username"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative group" x-data="{ show: false }">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input id="password" :type="show ? 'text' : 'password'" name="password" placeholder="••••••••••••" required autocomplete="new-password"
                        class="w-full pl-11 pr-10 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-650 transition-colors text-sm">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konfirmasi Sandi</label>
                <div class="relative group" x-data="{ show: false }">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </span>
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••••••" required autocomplete="new-password"
                        class="w-full pl-11 pr-10 py-3 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-650 transition-colors text-sm">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-600/15 hover:shadow-indigo-600/35 transition-all duration-300 text-sm flex items-center justify-center gap-2 mt-6 transform active:scale-[0.99]">
            <span>Daftar Akun Baru</span>
            <i class="fa-solid fa-user-plus text-xs"></i>
        </button>
    </form>

    <!-- Login Footer -->
    <p class="text-center text-xs text-slate-500 mt-8 font-medium">
        Sudah terdaftar? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">Masuk Di Sini</a>
    </p>
</x-guest-layout>
