<x-app-layout>
    <style>
        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    <div class="py-8 font-jakarta">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Welcome Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-150 dark:border-gray-700 shadow-sm">
                <div class="space-y-1">
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                        Selamat Datang Kembali, {{ Auth::user()->nama }}! 👋
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Anda masuk sebagai <span class="font-bold text-indigo-600 dark:text-indigo-400 uppercase">{{ Auth::user()->jabatan_aktif }}</span> 
                        pada <span class="font-semibold">{{ Auth::user()->opd ?? 'OPD' }}</span> ({{ Auth::user()->sub_unit_opd ?? '-' }})
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold px-3.5 py-2 bg-slate-100 dark:bg-gray-700 rounded-full text-slate-650 dark:text-gray-300">
                        <i class="fa-regular fa-calendar-check mr-1.5"></i>{{ date('d M Y') }}
                    </span>
                    <button class="w-10 h-10 bg-slate-50 dark:bg-gray-750 border border-gray-200 dark:border-gray-700 rounded-xl flex items-center justify-center text-slate-600 dark:text-gray-300 hover:bg-slate-100 transition">
                        <i class="fa-solid fa-arrow-rotate-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- ================= ADMIN DASHBOARD ================= -->
            @if(Auth::user()->jabatan_aktif === 'admin')
                <!-- Top Grid Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Metric 1: Total Users -->
                    <div class="bg-gradient-to-tr from-indigo-600 to-violet-500 text-white rounded-3xl p-6 shadow-xl shadow-indigo-500/10 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition duration-500"></div>
                        <div class="flex justify-between items-center relative z-10">
                            <span class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Total Pengguna Aktif</span>
                            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                <i class="fa-solid fa-users text-sm"></i>
                            </div>
                        </div>
                        <div class="my-4 relative z-10">
                            <h2 class="text-4xl font-black">{{ $data['total_users'] }}</h2>
                            <div class="flex items-center gap-1.5 mt-2">
                                <span class="text-[10px] text-indigo-200 font-medium">User berstatus aktif di database</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metric 2: Pending Registrations -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-gray-350 dark:hover:border-gray-650 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Registrasi Pending</span>
                            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                <i class="fa-solid fa-user-clock text-sm"></i>
                            </div>
                        </div>
                        <div class="my-4">
                            <h2 class="text-4xl font-black text-gray-900 dark:text-white">{{ $data['pending_users'] }}</h2>
                            <div class="flex items-center gap-1.5 mt-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300">Butuh Review</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metric 3: Total Paket -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-gray-350 dark:hover:border-gray-650 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Paket</span>
                            <div class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                                <i class="fa-solid fa-box text-sm"></i>
                            </div>
                        </div>
                        <div class="my-4">
                            <h2 class="text-4xl font-black text-gray-900 dark:text-white">{{ $data['total_paket'] }}</h2>
                            <div class="flex items-center gap-1.5 mt-2">
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">Seluruh paket yang terdaftar</span>
                            </div>
                        </div>
                    </div>

                    <!-- Metric 4: Total Mutasi/Transfers -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-gray-350 dark:hover:border-gray-650 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Mutasi Tugas</span>
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <i class="fa-solid fa-shuffle text-sm"></i>
                            </div>
                        </div>
                        <div class="my-4">
                            <h2 class="text-4xl font-black text-gray-900 dark:text-white">{{ $data['total_transfers'] }}</h2>
                            <div class="flex items-center gap-1.5 mt-2">
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">Pengalihan tugas PP/PPK</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Section: Chart & Table pending users -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Bar Chart (Statistik Paket) -->
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Statistik Usulan Paket</h3>
                                <p class="text-[10px] text-gray-400">Distribusi paket pengadaan berdasarkan status</p>
                            </div>
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase">Live Data</span>
                        </div>
                        <div class="h-64 relative w-full">
                            <canvas id="adminStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Pending Registrations List -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Persetujuan Akun</h3>
                                <p class="text-[10px] text-gray-400">Registrasi user pending yang butuh verifikasi</p>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-[11px] font-bold text-indigo-650 dark:text-indigo-400 hover:underline">
                                Semua &rarr;
                            </a>
                        </div>
                        
                        <div class="flex-1 divide-y divide-gray-100 dark:divide-gray-750 overflow-y-auto max-h-[220px] pr-1 space-y-3">
                            @forelse($data['pending_users_list'] as $pUser)
                                <div class="flex items-center justify-between pt-2.5">
                                    <div class="space-y-0.5">
                                        <div class="font-semibold text-xs text-gray-900 dark:text-white">{{ $pUser->nama }}</div>
                                        <div class="text-[10px] text-gray-400">NIP: {{ $pUser->nip }} &bull; Role: {{ $pUser->jabatan_aktif }}</div>
                                    </div>
                                    <form action="{{ route('admin.users.approve', $pUser) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded-lg text-[10px] transition">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 italic py-6 text-center">Tidak ada pendaftaran pending.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Recent Transfers -->
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-white text-lg font-jakarta">Pengajuan Mutasi & Transfer Tugas Terkini</h3>
                            <p class="text-[10px] text-gray-400">Log mutasi tugas paket terbaru di sistem</p>
                        </div>
                        <a href="{{ route('admin.transfers.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Kelola Semua Mutasi &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto text-xs">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Paket</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Dari</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Ke</th>
                                    <th class="px-4 py-2.5 text-center font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-750 font-medium">
                                @forelse($data['recent_transfers_list'] as $transfer)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-400">{{ $transfer->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ $transfer->paket->nama_paket }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-650 dark:text-gray-300">{{ $transfer->dariUser->nama }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-650 dark:text-gray-300">{{ $transfer->keUser->nama }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            @php
                                                $statusClasses = [
                                                    'menunggu' => 'bg-amber-100 text-amber-850 dark:bg-amber-900/40 dark:text-amber-300',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-850 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                    'ditolak' => 'bg-rose-100 text-rose-850 dark:bg-rose-900/40 dark:text-rose-300',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusClasses[$transfer->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ strtoupper($transfer->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Belum ada aktivitas mutasi paket.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            <!-- ================= PPK DASHBOARD ================= -->
            @elseif(Auth::user()->jabatan_aktif === 'PPK')
                <!-- Top Grid Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Metric 1: Total Usulan -->
                    <div class="bg-gradient-to-tr from-indigo-600 to-violet-500 text-white rounded-3xl p-6 shadow-xl shadow-indigo-500/10 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition duration-500"></div>
                        <div class="flex justify-between items-center relative z-10">
                            <span class="text-indigo-100 text-[10px] font-semibold uppercase tracking-wider">Total Usulan Paket</span>
                            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fa-solid fa-folder-open text-xs"></i>
                            </div>
                        </div>
                        <div class="my-4 relative z-10">
                            <h2 class="text-3xl font-black">{{ $data['total_paket'] }}</h2>
                            <span class="text-[9px] text-indigo-200 block mt-1">Seluruh paket PPK</span>
                        </div>
                    </div>

                    <!-- Metric 2: Draft -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Draft</span>
                            <div class="w-7 h-7 rounded-lg bg-gray-50 dark:bg-gray-750 text-gray-600 dark:text-gray-300 flex items-center justify-center">
                                <i class="fa-solid fa-file-pen text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-gray-800 dark:text-white">{{ $data['draft_paket'] }}</h2>
                            <span class="text-[9px] text-gray-400 block mt-1">Belum dikirim</span>
                        </div>
                    </div>

                    <!-- Metric 3: Perlu Revisi -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Perlu Revisi</span>
                            <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-955/20 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-rose-650 dark:text-rose-400">{{ $data['perlu_revisi'] }}</h2>
                            <span class="text-[9px] text-rose-400 block mt-1">Butuh perbaikan</span>
                        </div>
                    </div>

                    <!-- Metric 4: Disetujui PP -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Disetujui PP</span>
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-955/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <i class="fa-solid fa-file-shield text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-emerald-650 dark:text-emerald-400">{{ $data['disetujui'] }}</h2>
                            <span class="text-[9px] text-emerald-400 block mt-1">Proses tanda tangan</span>
                        </div>
                    </div>

                    <!-- Metric 5: Selesai -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Selesai (BA)</span>
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-955/20 text-indigo-600 dark:text-indigo-450 flex items-center justify-center">
                                <i class="fa-solid fa-signature text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-indigo-650 dark:text-indigo-400">{{ $data['selesai'] }}</h2>
                            <span class="text-[9px] text-indigo-400 block mt-1">Selesai pengadaan</span>
                        </div>
                    </div>
                </div>

                <!-- Middle Section: Chart & Shortcut Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Doughnut Chart (Metode Pengadaan) -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Metode Pengadaan</h3>
                                <p class="text-[10px] text-gray-400">Penyebaran usulan paket berdasarkan metode</p>
                            </div>
                        </div>
                        <div class="h-48 relative w-full flex items-center justify-center my-2">
                            <canvas id="ppkMethodChart"></canvas>
                        </div>
                    </div>

                    <!-- Action Shortcut Cards -->
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Action 1: Create new paket -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-indigo-400 dark:hover:border-indigo-550 transition duration-150">
                            <div class="space-y-2">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Pengusulan Paket Baru</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-normal">
                                    Daftarkan paket pengadaan baru, isi pagu dana RUP, dan unggah berkas persyaratan.
                                </p>
                            </div>
                            <div class="pt-4 flex justify-between items-center">
                                <a href="{{ route('paket.create') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline uppercase tracking-wider">
                                    Mulai Buat Paket
                                </a>
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-gray-700 flex items-center justify-center text-slate-700 dark:text-gray-300">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Action 2: Monitor packages -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-emerald-400 dark:hover:border-emerald-550 transition duration-150">
                            <div class="space-y-2">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-650 dark:text-emerald-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-binoculars text-sm"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Pantau Status Usulan</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-normal">
                                    Lihat riwayat catatan perbaikan dari Pejabat Pengadaan atau revisi dokumen usulan Anda.
                                </p>
                            </div>
                            <div class="pt-4 flex justify-between items-center">
                                <a href="{{ route('paket.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline uppercase tracking-wider">
                                    Lihat Semua Paket Saya
                                </a>
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-gray-700 flex items-center justify-center text-slate-700 dark:text-gray-300">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Recent Packages -->
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Usulan Paket Terkini</h3>
                            <p class="text-[10px] text-gray-400">Monitoring status usulan paket terbaru Anda</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto text-xs">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Kode RUP</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Nama Paket</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Pagu</th>
                                    <th class="px-4 py-2.5 text-center font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2.5 text-center font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-750 font-medium">
                                @forelse($data['recent_paket_list'] as $paket)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750/30">
                                        <td class="px-4 py-3 whitespace-nowrap font-mono text-gray-500">{{ $paket->kode_rup }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ $paket->nama_paket }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-indigo-600 dark:text-indigo-400 font-bold">Rp {{ number_format($paket->pagu, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            @php
                                                $statusClasses = [
                                                    'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                    'dikirim' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                                    'kaji_ulang' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                                    'perlu_revisi' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                    'selesai' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusClasses[$paket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ strtoupper($paket->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <a href="{{ route('paket.show', $paket) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Belum membuat usulan paket pengadaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            <!-- ================= PEJABAT PENGADAAN (PP) DASHBOARD ================= -->
            @elseif(Auth::user()->jabatan_aktif === 'PP')
                <!-- Top Grid Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <!-- Metric 1: Total Ditugaskan -->
                    <div class="bg-gradient-to-tr from-indigo-600 to-violet-500 text-white rounded-3xl p-6 shadow-xl shadow-indigo-500/10 flex flex-col justify-between relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition duration-500"></div>
                        <div class="flex justify-between items-center relative z-10">
                            <span class="text-indigo-100 text-[10px] font-semibold uppercase tracking-wider">Total Paket Ditugaskan</span>
                            <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </div>
                        </div>
                        <div class="my-4 relative z-10">
                            <h2 class="text-3xl font-black">{{ $data['total_paket'] }}</h2>
                            <span class="text-[9px] text-indigo-200 block mt-1">Paket untuk ditinjau</span>
                        </div>
                    </div>

                    <!-- Metric 2: Dikirim (Perlu Review) -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Perlu Review</span>
                            <div class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-955/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ $data['dikirim_paket'] }}</h2>
                            <span class="text-[9px] text-amber-400 block mt-1">Status dikirim</span>
                        </div>
                    </div>

                    <!-- Metric 3: Kaji Ulang -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Kaji Ulang</span>
                            <div class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-955/20 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                                <i class="fa-solid fa-glasses text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-sky-600 dark:text-sky-400">{{ $data['kaji_ulang_paket'] }}</h2>
                            <span class="text-[9px] text-sky-400 block mt-1">Sedang dikaji</span>
                        </div>
                    </div>

                    <!-- Metric 4: Disetujui PP -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">Menunggu TTD</span>
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-955/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <i class="fa-solid fa-file-signature text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $data['disetujui'] }}</h2>
                            <span class="text-[9px] text-emerald-400 block mt-1">Sudah disetujui</span>
                        </div>
                    </div>

                    <!-- Metric 5: Selesai -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-5 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-[10px] font-semibold uppercase tracking-wider">BA Selesai</span>
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-955/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                            </div>
                        </div>
                        <div class="my-3">
                            <h2 class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ $data['selesai'] }}</h2>
                            <span class="text-[9px] text-indigo-400 block mt-1">Selesai seluruhnya</span>
                        </div>
                    </div>
                </div>

                <!-- Middle Section: Chart & Shortcut Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Doughnut Chart (Kategori Pengadaan) -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-extrabold text-gray-900 dark:text-white text-lg">Kategori Pengadaan</h3>
                                <p class="text-[10px] text-gray-400">Penyebaran usulan paket berdasarkan jenis barang/jasa</p>
                            </div>
                        </div>
                        <div class="h-48 relative w-full flex items-center justify-center my-2">
                            <canvas id="ppJenisChart"></canvas>
                        </div>
                    </div>

                    <!-- Action Shortcut Cards -->
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Action 1: Tinjau Paket -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-indigo-400 dark:hover:border-indigo-550 transition duration-150">
                            <div class="space-y-2">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-microscope text-sm"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Mulai Tinjau Paket</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-normal">
                                    Periksa usulan dokumen PPK, setujui berkas spesifikasi/HPS, atau berikan catatan revisi berkas.
                                </p>
                            </div>
                            <div class="pt-4 flex justify-between items-center">
                                <a href="{{ route('paket-review.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline uppercase tracking-wider">
                                    Tinjau Paket Masuk
                                </a>
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-gray-700 flex items-center justify-center text-slate-700 dark:text-gray-300">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Action 2: Bypass manual -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-amber-400 dark:hover:border-amber-550 transition duration-150">
                            <div class="space-y-2">
                                <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-650 dark:text-amber-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-bolt text-sm"></i>
                                </div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base">Buat Paket Manual (Bypass)</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-normal">
                                    Jalur cepat bagi Pejabat Pengadaan untuk membuat paket secara langsung guna penandatanganan dokumen manual.
                                </p>
                            </div>
                            <div class="pt-4 flex justify-between items-center">
                                <a href="{{ route('paket-bypass.create') }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline uppercase tracking-wider">
                                    Buat Paket Manual
                                </a>
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-gray-700 flex items-center justify-center text-slate-700 dark:text-gray-300">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Assigned Active Tasks -->
                <div class="bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-extrabold text-gray-900 dark:text-white text-lg font-jakarta">Tugas Peninjauan Paket Masuk</h3>
                            <p class="text-[10px] text-gray-400">Usulan paket pengadaan yang butuh peninjauan Anda segera</p>
                        </div>
                        <a href="{{ route('paket-review.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Lihat Semua Tugas &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto text-xs">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-750">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Kode RUP</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Nama Paket</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">PPK Pengirim</th>
                                    <th class="px-4 py-2.5 text-left font-semibold text-gray-500 uppercase tracking-wider">Pagu</th>
                                    <th class="px-4 py-2.5 text-center font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-750 font-medium">
                                @forelse($data['dikirim_paket_list'] as $pTask)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750/30">
                                        <td class="px-4 py-3 whitespace-nowrap font-mono text-gray-500">{{ $pTask->kode_rup }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ $pTask->nama_paket }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-650 dark:text-gray-300">{{ $pTask->ppk->nama ?? '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-indigo-650 dark:text-indigo-400 font-bold">Rp {{ number_format($pTask->pagu, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <a href="{{ route('paket.show', $pTask) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold">
                                                Review &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Tidak ada penugasan paket yang perlu direview.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Charts Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // ================= ADMIN status chart =================
            @if(Auth::user()->jabatan_aktif === 'admin')
                const statusStats = @json($data['chart_status_stats'] ?? []);
                const ctxAdmin = document.getElementById('adminStatusChart').getContext('2d');
                
                const labels = ['Draft', 'Revisi', 'Dikirim', 'Kaji Ulang', 'Disetujui', 'Selesai'];
                const rawValues = [
                    statusStats['draft'] || 0,
                    statusStats['perlu_revisi'] || 0,
                    statusStats['dikirim'] || 0,
                    statusStats['kaji_ulang'] || 0,
                    statusStats['disetujui'] || 0,
                    statusStats['selesai'] || 0
                ];

                new Chart(ctxAdmin, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: rawValues,
                            backgroundColor: function(context) {
                                const index = context.dataIndex;
                                return index === 4 || index === 5 ? '#4f46e5' : '#818cf8';
                            },
                            borderRadius: 10,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#94a3b8' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#94a3b8' }
                            }
                        }
                    }
                });
            @endif

            // ================= PPK method chart =================
            @if(Auth::user()->jabatan_aktif === 'PPK')
                const metodeStats = @json($data['chart_metode_stats'] ?? []);
                const ctxPpk = document.getElementById('ppkMethodChart').getContext('2d');
                
                const metodeLabels = Object.keys(metodeStats).length > 0 ? Object.keys(metodeStats) : ['Tender', 'Pengadaan Langsung', 'E-Purchasing'];
                const metodeValues = Object.keys(metodeStats).length > 0 ? Object.values(metodeStats) : [0, 0, 0];

                new Chart(ctxPpk, {
                    type: 'doughnut',
                    data: {
                        labels: metodeLabels,
                        datasets: [{
                            data: metodeValues,
                            backgroundColor: ['#4f46e5', '#a78bfa', '#34d399', '#fbbf24', '#f87171'],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { family: 'Plus Jakarta Sans', size: 9 } } } }
                    }
                });
            @endif

            // ================= PP jenis chart =================
            @if(Auth::user()->jabatan_aktif === 'PP')
                const jenisStats = @json($data['chart_jenis_stats'] ?? []);
                const ctxPp = document.getElementById('ppJenisChart').getContext('2d');
                
                const jenisLabels = Object.keys(jenisStats).length > 0 ? Object.keys(jenisStats) : ['Barang', 'Konstruksi', 'Jasa Lainnya'];
                const jenisValues = Object.keys(jenisStats).length > 0 ? Object.values(jenisStats) : [0, 0, 0];

                new Chart(ctxPp, {
                    type: 'doughnut',
                    data: {
                        labels: jenisLabels,
                        datasets: [{
                            data: jenisValues,
                            backgroundColor: ['#6366f1', '#8b5cf6', '#10b981', '#f59e0b'],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { family: 'Plus Jakarta Sans', size: 9 } } } }
                    }
                });
            @endif
        });
    </script>
</x-app-layout>
