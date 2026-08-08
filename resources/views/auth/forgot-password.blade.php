<x-guest-layout>
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Lupa Password Akun?</h2>
        <p class="mb-4">
            Untuk menjaga keamanan data pengadaan, pengajuan reset password wajib melalui verifikasi Admin.
        </p>
        <p class="mb-4 font-semibold text-indigo-650 dark:text-indigo-400">
            Silakan hubungi pihak Admin/UKPBJ secara manual melalui kontak WhatsApp resmi untuk meminta persetujuan reset password.
        </p>
        <p>
            Setelah Admin menyetujui permintaan Anda di panel dashboard, sebuah link reset password (berlaku 24 jam) akan otomatis dikirimkan ke alamat email Anda yang terdaftar di sistem.
        </p>
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('login') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            Kembali ke Halaman Login
        </a>
    </div>
</x-guest-layout>
