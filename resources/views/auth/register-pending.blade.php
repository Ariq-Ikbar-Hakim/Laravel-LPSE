<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
            Pendaftaran Berhasil!
        </h2>
        <p class="mb-4">
            Akun Anda telah berhasil didaftarkan ke sistem BANGEDI. Saat ini, akun Anda berstatus <strong>Pending (Menunggu Verifikasi)</strong>.
        </p>
        <p class="mb-4">
            Pihak Admin LPSE akan memverifikasi dokumen SK Jabatan Anda sebelum mengaktifkan akun Anda. Mohon tunggu proses verifikasi selesai.
        </p>
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('login') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            Kembali ke Halaman Login
        </a>
    </div>
</x-guest-layout>
