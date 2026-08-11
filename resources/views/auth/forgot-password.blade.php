<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="text-sm text-slate-500 mt-1.5 font-medium">Reset kata sandi Anda memerlukan verifikasi Admin.</p>
    </div>

    <div class="space-y-4 text-sm text-slate-650 leading-relaxed font-medium">
        <p>
            Untuk menjaga keamanan data pengadaan, pengajuan reset password wajib melalui verifikasi Admin/UKPBJ secara manual.
        </p>
        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl text-indigo-900 font-semibold text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-info text-base text-indigo-600"></i>
            <span>Silakan hubungi pihak Admin/UKPBJ secara manual untuk mengajukan persetujuan reset kata sandi Anda.</span>
        </div>
        <p class="text-xs text-slate-500 font-normal">
            Setelah Admin menyetujui permintaan Anda di panel dashboard, sebuah link reset password yang aman akan otomatis dikirimkan ke alamat email terdaftar Anda.
        </p>
    </div>

    <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 hover:underline transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Login
        </a>
    </div>
</x-guest-layout>
