<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Reset kata sandi Anda memerlukan verifikasi dari Admin.</p>
    </div>

    @if (session('status') === 'success_request')
        <!-- State 2: Request Submitted successfully, show WA button -->
        <div class="space-y-6">
            <div class="p-5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 rounded-2xl flex flex-col items-center text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-circle-check text-2xl animate-bounce"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm">Pengajuan Berhasil Dicatat</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Data Anda terdaftar. Silakan hubungi Admin via WhatsApp untuk segera menyetujui pengajuan.</p>
                </div>
            </div>

            @php
                $message = "Halo Admin BANGEDI, saya ingin mengajukan permohonan reset password untuk akun saya.\n\nBerikut data akun saya:\n- Nama: " . session('requested_user_nama') . "\n- NIP: " . session('requested_user_nip') . "\n- Email: " . session('requested_user_email') . "\n\nMohon bantuannya untuk menyetujui reset password agar link reset dikirimkan ke email saya. Terima kasih.";
                $waUrl = "https://wa.me/" . env('WHATSAPP_ADMIN_NUMBER', '6285731080074') . "?text=" . urlencode($message);
            @endphp

            <a href="{{ $waUrl }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Hubungi Admin via WhatsApp
            </a>

            <div class="p-4 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs text-slate-500 dark:text-slate-400 font-medium">
                <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="fa-solid fa-envelope mr-1.5 text-indigo-500"></i>Langkah Selanjutnya:</p>
                Setelah Admin menyetujui permintaan Anda di dashboard, link reset password akan dikirim ke email <span class="font-semibold text-indigo-600 dark:text-indigo-400 font-mono">{{ session('requested_user_email') }}</span>.
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 transition-colors">
                    <i class="fa-solid fa-redo-alt"></i>
                    Ajukan Kembali
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 transition-colors">
                    Kembali ke Login
                </a>
            </div>
        </div>
    @else
        <!-- State 1: Form to input NIP or Email -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Identity Input (NIP or Email) -->
            <div>
                <label for="identity" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">NIP atau Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-user-shield text-sm"></i>
                    </span>
                    <input id="identity" type="text" name="identity" value="{{ old('identity') }}" required autofocus
                           placeholder="Masukkan NIP atau Email terdaftar..."
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition duration-200" />
                </div>
                <x-input-error :messages="$errors->get('identity')" class="mt-2 text-xs text-rose-500" />
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-slate-900 dark:bg-slate-800 hover:bg-indigo-600 dark:hover:bg-indigo-650 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer">
                Ajukan Reset Password
            </button>

            <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <p>
                    <i class="fa-solid fa-shield-halved mr-1.5 text-indigo-500"></i>Untuk menjaga keamanan data pengadaan, pengajuan wajib melalui otorisasi Admin/UKPBJ.
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Login
                </a>
            </div>
        </form>
    @endif
</x-guest-layout>
