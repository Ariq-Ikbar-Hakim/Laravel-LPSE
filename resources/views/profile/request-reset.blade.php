<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengajuan Reset Kata Sandi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-6 text-sm">
                
                <div class="mb-2">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Lupa Kata Sandi Anda?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Reset kata sandi Anda memerlukan verifikasi dan persetujuan dari Administrator LPSE.</p>
                </div>

                @if (session('status') === 'success_request' || $user->reset_requested_at)
                    <!-- State 2: Request Submitted, show WA button -->
                    <div class="space-y-6">
                        <div class="p-5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 rounded-2xl flex flex-col items-center text-center space-y-3">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <i class="fa-solid fa-circle-check text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Pengajuan Berhasil Dicatat</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Data pengajuan Anda terdaftar. Silakan hubungi Admin via WhatsApp untuk segera menyetujui pengajuan.</p>
                            </div>
                        </div>

                        @php
                            $message = "Halo Admin BANGEDI, saya ingin mengajukan permohonan reset password untuk akun saya.\n\nBerikut data akun saya:\n- Nama: " . $user->nama . "\n- NIP: " . $user->nip . "\n- Email: " . $user->email . "\n\nMohon bantuannya untuk menyetujui reset password agar link reset dikirimkan ke email saya. Terima kasih.";
                            $waUrl = "https://wa.me/" . env('WHATSAPP_ADMIN_NUMBER', '6285731080074') . "?text=" . urlencode($message);
                        @endphp

                        <a href="{{ $waUrl }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer font-semibold">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            Hubungi Admin via WhatsApp
                        </a>

                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs text-slate-500 dark:text-slate-400 font-medium">
                            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="fa-solid fa-envelope mr-1.5 text-indigo-500"></i>Langkah Selanjutnya:</p>
                            Setelah Admin menyetujui permintaan Anda di dashboard, link reset password akan dikirim ke email Anda <span class="font-semibold text-indigo-600 dark:text-indigo-400 font-mono">{{ $user->email }}</span>.
                        </div>
                    </div>
                @else
                    <!-- State 1: Form to submit request -->
                    <form method="POST" action="{{ route('profile.store-request-reset') }}" class="space-y-5">
                        @csrf

                        <div class="p-4 bg-indigo-50 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/40 rounded-2xl space-y-2 text-xs">
                            <span class="font-bold text-indigo-900 dark:text-indigo-400 block uppercase tracking-wider text-[10px]">Data Akun Anda</span>
                            <div class="text-slate-600 dark:text-slate-400">Nama Lengkap: <span class="font-semibold text-slate-900 dark:text-white">{{ $user->nama }}</span></div>
                            <div class="text-slate-600 dark:text-slate-400">NIP Pengguna: <span class="font-semibold text-slate-900 dark:text-white font-mono">{{ $user->nip }}</span></div>
                            <div class="text-slate-600 dark:text-slate-400">Alamat Email: <span class="font-semibold text-slate-900 dark:text-white font-mono">{{ $user->email }}</span></div>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer">
                            Kirim Pengajuan Reset Password ke Admin
                        </button>

                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                            <p class="font-semibold text-slate-700 dark:text-slate-300 mb-1"><i class="fa-solid fa-circle-info mr-1.5 text-indigo-500"></i>Catatan Informasi:</p>
                            Tindakan ini akan mendaftarkan pengajuan reset ke dashboard Administrator. Anda kemudian dapat menghubungi Admin untuk mempercepat persetujuan, di mana link pembaruan sandi baru akan dikirimkan otomatis ke inbox email Anda.
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
