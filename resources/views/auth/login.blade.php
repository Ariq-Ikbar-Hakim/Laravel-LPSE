<x-guest-layout>
    <!-- Header Form -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Masuk ke Akun Anda</h2>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Silakan masukkan detail akun Anda untuk mengakses dashboard.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- NIP -->
        <div>
            <label for="nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIP (Nomor Induk Pegawai)</label>
            <div class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i class="fa-solid fa-id-card text-sm"></i>
                </span>
                <input id="nip" type="text" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP Anda" required autofocus autocomplete="username"
                    class="w-full pl-11 pr-4 py-3.5 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
            </div>
            <x-input-error :messages="$errors->get('nip')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
            <div class="relative group" x-data="{ show: false }">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input id="password" :type="show ? 'text' : 'password'" name="password" placeholder="••••••••••••" required autocomplete="current-password"
                    class="w-full pl-11 pr-10 py-3.5 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:border-indigo-600 focus:bg-white rounded-2xl text-sm text-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-50 transition-all duration-200">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors text-sm">
                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- reCAPTCHA bypassed -->

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between text-xs font-semibold">
            <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer text-slate-650">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                <span>Ingat Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-700 hover:underline">Lupa password?</a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" 
            class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-600/15 hover:shadow-indigo-600/35 transition-all duration-300 text-sm flex items-center justify-center gap-2 transform active:scale-[0.99]">
            <span>Masuk Sekarang</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>

    <!-- Register Footer -->
    <p class="text-center text-xs text-slate-500 mt-8 font-medium">
        Belum memiliki akun? <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors">Daftar Sekarang</a>
    </p>
</x-guest-layout>
