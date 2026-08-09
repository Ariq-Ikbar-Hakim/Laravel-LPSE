<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Selamat Datang Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama }}!</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Anda masuk sebagai <span class="font-semibold uppercase text-indigo-600 dark:text-indigo-400">{{ Auth::user()->jabatan_aktif }}</span> 
                        pada {{ Auth::user()->opd }} ({{ Auth::user()->sub_unit_opd }}).
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            @if(Auth::user()->jabatan_aktif === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna Aktif</span>
                        <span class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $data['total_users'] }}</span>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 dark:text-indigo-450 hover:underline mt-4">Kelola Pengguna &rarr;</a>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Permintaan Registrasi (Pending)</span>
                        <span class="text-3xl font-bold text-amber-600 mt-2">{{ $data['pending_users'] }}</span>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 dark:text-indigo-450 hover:underline mt-4">Tinjau Registrasi &rarr;</a>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paket di Sistem</span>
                        <span class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $data['total_paket'] }}</span>
                        <span class="text-xs text-gray-400 mt-4">Monitoring keseluruhan aktif</span>
                    </div>
                </div>
            @elseif(Auth::user()->jabatan_aktif === 'PPK')
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Usulan Paket</span>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $data['total_paket'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Draft (Belum Kirim)</span>
                        <div class="text-2xl font-bold text-gray-500 mt-2">{{ $data['draft_paket'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Perlu Revisi</span>
                        <div class="text-2xl font-bold text-rose-600 mt-2">{{ $data['perlu_revisi'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Disetujui PP</span>
                        <div class="text-2xl font-bold text-emerald-600 mt-2">{{ $data['disetujui'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Selesai (BA TTD)</span>
                        <div class="text-2xl font-bold text-indigo-600 mt-2">{{ $data['selesai'] }}</div>
                    </div>
                </div>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg border-l-4 border-indigo-600">
                        <h4 class="font-bold text-gray-900 dark:text-white">Pengusulan Paket Baru</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Daftarkan paket pengadaan baru, isi pagu dana RUP, dan unggah dokumen persyaratan.</p>
                        <a href="{{ route('paket.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-750 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                            Buat Paket Pengadaan
                        </a>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg border-l-4 border-emerald-600">
                        <h4 class="font-bold text-gray-900 dark:text-white">Daftar Paket Usulan</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Pantau status usulan, lihat catatan perbaikan dokumen dari Pejabat Pengadaan, atau revisi berkas.</p>
                        <a href="{{ route('paket.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-750 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                            Lihat Paket Saya
                        </a>
                    </div>
                </div>
            @elseif(Auth::user()->jabatan_aktif === 'PP')
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Paket Ditugaskan</span>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $data['total_paket'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Dikirim (Perlu Review)</span>
                        <div class="text-2xl font-bold text-amber-600 mt-2">{{ $data['dikirim_paket'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Kaji Ulang</span>
                        <div class="text-2xl font-bold text-blue-600 mt-2">{{ $data['kaji_ulang_paket'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Disetujui (Menunggu TTD)</span>
                        <div class="text-2xl font-bold text-emerald-600 mt-2">{{ $data['disetujui'] }}</div>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Selesai (BA Lengkap)</span>
                        <div class="text-2xl font-bold text-indigo-600 mt-2">{{ $data['selesai'] }}</div>
                    </div>
                </div>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg border-l-4 border-indigo-600">
                        <h4 class="font-bold text-gray-900 dark:text-white">Review Paket Pengadaan</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Periksa dokumen pengajuan PPK, tandai validitas berkas spesifikasi/HPS, dan berikan catatan perbaikan.</p>
                        <a href="{{ route('paket-review.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-750 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                            Mulai Tinjau Paket
                        </a>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg border-l-4 border-amber-600">
                        <h4 class="font-bold text-gray-900 dark:text-white">Bypass Pembuatan Paket Manual</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Jalur pintas PP untuk membuat paket langsung secara mandiri untuk proses penandatanganan BA Manual.</p>
                        <a href="{{ route('paket-bypass.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-750 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                            Buat Paket Manual
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
