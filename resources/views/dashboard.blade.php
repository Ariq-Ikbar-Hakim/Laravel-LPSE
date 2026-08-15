<x-app-layout>
<style>
    body, * { font-family: 'Plus Jakarta Sans', sans-serif; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="p-6 md:p-8 space-y-6 bg-slate-100 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100 transition-colors duration-300">

    {{-- ============================================================ --}}
    {{-- WELCOME HEADER (shared for all roles)                        --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Halo, {{ Auth::user()->nama }}! 👋</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Masuk sebagai
                <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase">{{ Auth::user()->jabatan_aktif }}</span>
                &bull; {{ Auth::user()->opd ?? 'LPSE Bangkalan' }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-semibold px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-650 dark:text-slate-400 shadow-sm">
                <i class="fa-regular fa-calendar-check mr-1.5 text-indigo-500 dark:text-indigo-400"></i>{{ now()->isoFormat('D MMM YYYY') }}
            </span>
            <button onclick="window.location.reload()" class="w-10 h-10 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm" title="Refresh">
                <i class="fa-solid fa-arrows-rotate text-sm"></i>
            </button>
        </div>
    </div>

    {{-- ================================================================== --}}
    {{-- ADMIN DASHBOARD                                                      --}}
    {{-- ================================================================== --}}
    @if(Auth::user()->jabatan_aktif === 'admin')

        {{-- Metric Cards Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Card 1: Total User Aktif (Highlight) --}}
            <div class="bg-indigo-600 text-white rounded-3xl p-6 shadow-xl shadow-indigo-600/25 flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition duration-500"></div>
                <div class="flex justify-between items-center relative z-10">
                    <span class="text-indigo-100 text-sm font-medium">Pengguna Aktif</span>
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fa-solid fa-users text-xs"></i>
                    </div>
                </div>
                <div class="my-4 relative z-10">
                    <h2 class="text-3xl font-bold">{{ $data['total_users'] ?? 0 }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="bg-white/20 text-white text-xs font-semibold px-2 py-0.5 rounded-full">Terverifikasi</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Pending Registrasi --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">Registrasi Pending</span>
                    <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-950/30 text-amber-500 dark:text-amber-400 flex items-center justify-center">
                        <i class="fa-solid fa-user-clock text-xs"></i>
                    </div>
                </div>
                <div class="my-4">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $data['pending_users'] ?? 0 }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="bg-amber-100 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 text-xs font-semibold px-2 py-0.5 rounded-full">Butuh Persetujuan</span>
                    </div>
                </div>
            </div>

            {{-- Card 3: Total Paket --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-sky-300 dark:hover:border-sky-700 hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Paket</span>
                    <div class="w-8 h-8 rounded-full bg-sky-50 dark:bg-sky-950/30 text-sky-500 dark:text-sky-400 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-xs"></i>
                    </div>
                </div>
                <div class="my-4">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $data['total_paket'] ?? 0 }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs text-slate-400 dark:text-slate-500">Seluruh usulan paket PBJ</span>
                    </div>
                </div>
            </div>

            {{-- Card 4: Total Mutasi --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Mutasi</span>
                    <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 dark:text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-shuffle text-xs"></i>
                    </div>
                </div>
                <div class="my-4">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $data['total_transfers'] ?? 0 }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-xs text-slate-400 dark:text-slate-500">Pengajuan transfer tugas</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Middle Row: Bar Chart + Doughnut Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Bar Chart: Status Paket --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Statistik Status Paket</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Distribusi status seluruh usulan paket pengadaan</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-indigo-100 dark:hover:bg-indigo-950/60 hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="Lihat detail">
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="h-64 relative w-full">
                    <canvas id="adminBarChart"></canvas>
                </div>
            </div>

            {{-- Doughnut Chart: Paket by Status --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Komposisi Paket</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Berdasarkan status saat ini</p>
                    </div>
                    <button class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
                <div class="h-40 relative w-full flex items-center justify-center my-3">
                    <canvas id="adminDonutChart"></canvas>
                </div>
                <div class="space-y-2 mt-3 text-xs">
                    @php
                        $legendItems = [
                            'draft'        => ['label' => 'Draft', 'color' => 'bg-slate-400'],
                            'dikirim'      => ['label' => 'Dikirim', 'color' => 'bg-sky-400'],
                            'kaji_ulang'   => ['label' => 'Kaji Ulang', 'color' => 'bg-amber-400'],
                            'perlu_revisi' => ['label' => 'Perlu Revisi', 'color' => 'bg-rose-450'],
                            'disetujui'    => ['label' => 'Disetujui', 'color' => 'bg-indigo-500'],
                            'selesai'      => ['label' => 'Selesai', 'color' => 'bg-emerald-500'],
                        ];
                        $chartStats = $data['chart_status_stats'] ?? [];
                        $totalPaketChart = array_sum($chartStats) ?: 1;
                    @endphp
                    @foreach($legendItems as $key => $item)
                        @if(($chartStats[$key] ?? 0) > 0)
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full {{ $item['color'] }}"></span>{{ $item['label'] }}</span>
                                <span class="font-semibold text-slate-700 dark:text-slate-350">{{ round(($chartStats[$key] / $totalPaketChart) * 100) }}%</span>
                            </div>
                        @endif
                    @endforeach
                    @if(empty(array_filter($chartStats)))
                        <p class="text-slate-400 dark:text-slate-500 italic text-center py-2">Belum ada data paket.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bottom Action Cards: Pending Users + Transfers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Card: Persetujuan Akun Pending --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-amber-300 dark:hover:border-amber-700 hover:shadow-md transition">
                <div class="space-y-2">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-500 dark:text-amber-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white">
                        {{ $data['pending_users'] ?? 0 }}
                        <span class="text-lg font-medium text-slate-500 dark:text-slate-400">akun</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ ($data['pending_users'] ?? 0) > 0 ? ($data['pending_users']).' akun menunggu verifikasi Admin.' : 'Tidak ada akun yang pending.' }}
                    </p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="w-12 h-12 rounded-full bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center hover:bg-indigo-600 transition shadow-lg shadow-slate-900/10 shrink-0">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card: Mutasi / Transfer Tugas --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition">
                <div class="space-y-2">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-right-left"></i>
                    </div>
                    <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white">
                        {{ $data['total_transfers'] ?? 0 }}
                        <span class="text-lg font-medium text-slate-500 dark:text-slate-400">mutasi</span>
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total riwayat pengajuan transfer tugas paket.</p>
                </div>
                <a href="{{ route('admin.transfers.index') }}" class="w-12 h-12 rounded-full bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center hover:bg-indigo-600 transition shadow-lg shadow-slate-900/10 shrink-0">
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Table: Pending User Registrations --}}
        @if(($data['pending_users'] ?? 0) > 0)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Antrian Persetujuan Akun</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Daftar pendaftaran pengguna yang menunggu verifikasi</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="p-4 pl-6">Nama</th>
                            <th class="p-4">NIP</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">OPD</th>
                            <th class="p-4 pr-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-300 font-medium">
                        @foreach($data['pending_users_list'] as $pUser)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                <td class="p-4 pl-6 font-semibold text-slate-900 dark:text-white">{{ $pUser->nama }}</td>
                                <td class="p-4 font-mono text-slate-500 text-xs">{{ $pUser->nip }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $pUser->jabatan_aktif === 'PPK' ? 'bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400' : 'bg-violet-100 dark:bg-violet-950/40 text-violet-700 dark:text-violet-400' }}">
                                        {{ $pUser->jabatan_aktif }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $pUser->opd ?? '-' }}</td>
                                <td class="p-4 pr-6 text-center">
                                    <form action="{{ route('admin.users.approve', $pUser) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-1.5 rounded-xl text-xs font-semibold bg-slate-900 dark:bg-slate-800 text-white hover:bg-indigo-600 transition">
                                            Setujui
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Table: Recent Transfers --}}
        @if(count($data['recent_transfers_list'] ?? []) > 0)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Riwayat Mutasi Tugas Terkini</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Log transfer kepemilikan paket yang paling baru</p>
                </div>
                <a href="{{ route('admin.transfers.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Kelola Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="p-4 pl-6">Tanggal</th>
                            <th class="p-4">Paket</th>
                            <th class="p-4">Dari</th>
                            <th class="p-4">Ke</th>
                            <th class="p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-300 font-medium">
                        @foreach($data['recent_transfers_list'] as $transfer)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                <td class="p-4 pl-6 text-slate-400 dark:text-slate-500 text-xs whitespace-nowrap">{{ $transfer->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-4 font-semibold text-slate-900 dark:text-white text-xs">
                                    @if($transfer->paket)
                                        {{ $transfer->paket->nama_paket }}
                                    @else
                                        <span class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-wide">SWAP JABATAN & PERAN</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs">{{ $transfer->dariUser->nama ?? '-' }}</td>
                                <td class="p-4 text-xs">{{ $transfer->keUser->nama ?? '-' }}</td>
                                <td class="p-4 text-center">
                                    @php
                                        $cls = match($transfer->status) {
                                            'menunggu'  => 'bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400',
                                            'disetujui' => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400',
                                            'ditolak'   => 'bg-rose-100 dark:bg-rose-950/40 text-rose-705 dark:text-rose-450',
                                            default     => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ strtoupper($transfer->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusStats = JSON.parse('{!! json_encode($data["chart_status_stats"] ?? []) !!}');
            const labels = ['Draft', 'Dikirim', 'Kaji Ulang', 'Perlu Revisi', 'Disetujui', 'Selesai'];
            const keys   = ['draft', 'dikirim', 'kaji_ulang', 'perlu_revisi', 'disetujui', 'selesai'];
            const values = keys.map(k => statusStats[k] || 0);
            const bgColors = ['#94a3b8','#38bdf8','#fbbf24','#f87171','#4f46e5','#10b981'];

            // Bar Chart
            new Chart(document.getElementById('adminBarChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{ data: values,
                        backgroundColor: function(ctx) { return bgColors[ctx.dataIndex]; },
                        borderRadius: 12,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94a3b8' }, border: { dash: [4,4] } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#94a3b8' } }
                    }
                }
            });

            // Donut Chart
            new Chart(document.getElementById('adminDonutChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{ data: values, backgroundColor: bgColors, borderWidth: 0, cutout: '75%' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        });
        </script>


    {{-- ================================================================== --}}
    {{-- PPK DASHBOARD                                                        --}}
    {{-- ================================================================== --}}
    @elseif(Auth::user()->jabatan_aktif === 'PPK')

        {{-- Status Filter Chips (mini-tabs) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
                $ppkStatuses = [
                    ['key'=>'total_paket',   'label'=>'Total Usulan',  'color'=>'bg-indigo-50 dark:bg-indigo-950/30 border-indigo-100 dark:border-indigo-900/40',  'textColor'=>'text-indigo-900 dark:text-indigo-400',  'badge'=>'bg-indigo-600'],
                    ['key'=>'draft_paket',   'label'=>'Draft',          'color'=>'bg-slate-50 dark:bg-slate-800/30 border-slate-200 dark:border-slate-800',   'textColor'=>'text-slate-800 dark:text-slate-400',   'badge'=>'bg-slate-500'],
                    ['key'=>'perlu_revisi',  'label'=>'Perlu Revisi',   'color'=>'bg-rose-50 dark:bg-rose-950/30 border-rose-100 dark:border-rose-900/40',     'textColor'=>'text-rose-900 dark:text-rose-450',    'badge'=>'bg-rose-500'],
                    ['key'=>'disetujui',     'label'=>'Disetujui PP',   'color'=>'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-100 dark:border-emerald-900/40','textColor'=>'text-emerald-900 dark:text-emerald-400', 'badge'=>'bg-emerald-500'],
                    ['key'=>'selesai',       'label'=>'Selesai (BA)',   'color'=>'bg-violet-50 dark:bg-violet-950/30 border-violet-100 dark:border-violet-900/40', 'textColor'=>'text-violet-900 dark:text-violet-400',  'badge'=>'bg-violet-500'],
                ];
            @endphp
            @foreach($ppkStatuses as $s)
                <div class="bg-white dark:bg-slate-900 border {{ $s['color'] }} rounded-3xl p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <span class="{{ $s['textColor'] }} text-sm font-semibold">{{ $s['label'] }}</span>
                    <div class="flex items-baseline justify-between mt-3">
                        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $data[$s['key']] ?? 0 }}</h2>
                        <span class="{{ $s['badge'] }} text-white text-xs font-bold px-2 py-0.5 rounded-full">PBJ</span>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Paket pengadaan</span>
                </div>
            @endforeach
        </div>

        {{-- Chart + Action Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Doughnut: Metode Pengadaan --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Metode Pengadaan</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Paket berdasarkan metode</p>
                    </div>
                </div>
                <div class="h-44 relative w-full flex items-center justify-center my-2">
                    <canvas id="ppkMethodChart"></canvas>
                </div>
                <div id="ppkLegend" class="space-y-1.5 mt-3 text-xs"></div>
            </div>

            {{-- 2x Action Shortcut Cards --}}
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Shortcut: Buat Paket Baru --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-indigo-300 dark:hover:border-indigo-750 hover:shadow-md transition">
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Buat Paket<br><span class="text-lg font-medium text-slate-500 dark:text-slate-400">Baru</span></h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ajukan usulan pengadaan baru kepada Pejabat Pengadaan.</p>
                    </div>
                    <a href="{{ route('paket.create') }}" class="w-12 h-12 rounded-full bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center hover:bg-indigo-600 transition shadow-lg shadow-slate-900/10 shrink-0">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Shortcut: Pantau Status Usulan --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-emerald-300 dark:hover:border-emerald-750 hover:shadow-md transition">
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-binoculars"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Paket<br><span class="text-lg font-medium text-slate-500 dark:text-slate-400">Saya</span></h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Lihat seluruh riwayat dan status paket usulan Anda.</p>
                    </div>
                    <a href="{{ route('paket.index') }}" class="w-12 h-12 rounded-full bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center hover:bg-emerald-600 transition shadow-lg shadow-slate-900/10 shrink-0">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Card: Revisi Menunggu Tindak Lanjut --}}
                @if(($data['perlu_revisi'] ?? 0) > 0)
                <div class="md:col-span-2 bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-3xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 text-rose-500 dark:text-rose-450 flex items-center justify-center shadow-sm shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-rose-900 dark:text-rose-400 text-sm">{{ $data['perlu_revisi'] }} Paket Membutuhkan Revisi</p>
                        <p class="text-xs text-rose-600 dark:text-rose-450 mt-0.5">Pejabat Pengadaan telah memberikan catatan perbaikan. Segera tindak lanjuti.</p>
                    </div>
                    <a href="{{ route('paket.index') }}" class="ml-auto shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-rose-600 text-white hover:bg-rose-700 transition">Lihat →</a>
                </div>
                @endif
            </div>
        </div>

        {{-- Table: Recent Paket --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Usulan Paket Terkini</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">5 paket pengadaan terbaru yang Anda ajukan</p>
                </div>
                <a href="{{ route('paket.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="p-4 pl-6">Kode RUP</th>
                            <th class="p-4">Nama Paket</th>
                            <th class="p-4">Pagu</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-300 font-medium">
                        @forelse($data['recent_paket_list'] ?? [] as $paket)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                <td class="p-4 pl-6 font-mono text-slate-400 dark:text-slate-500 text-xs">{{ $paket->kode_rup }}</td>
                                <td class="p-4 font-semibold text-slate-900 dark:text-white">{{ $paket->nama_paket }}</td>
                                <td class="p-4 text-indigo-600 dark:text-indigo-400 font-semibold text-xs">Rp {{ number_format($paket->pagu, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    @php
                                        $cls = match($paket->status) {
                                            'draft'        => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                            'dikirim'      => 'bg-sky-100 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400',
                                            'kaji_ulang'   => 'bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400',
                                            'perlu_revisi' => 'bg-rose-100 dark:bg-rose-950/40 text-rose-705 dark:text-rose-455',
                                            'disetujui'    => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400',
                                            'selesai'      => 'bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400',
                                            default        => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ strtoupper($paket->status) }}</span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <a href="{{ route('paket.show', $paket) }}" class="text-slate-400 hover:text-indigo-600 p-2 transition">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-500 italic text-sm">Belum ada paket yang Anda ajukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400">
                <span>Menampilkan {{ count($data['recent_paket_list'] ?? []) }} data terbaru</span>
                <a href="{{ route('paket.index') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat semua</a>
            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const metodeStats = JSON.parse('{!! json_encode($data["chart_metode_stats"] ?? []) !!}');
            const metKeys = Object.keys(metodeStats).length > 0 ? Object.keys(metodeStats) : ['Belum ada data'];
            const metVals = Object.values(metodeStats).length > 0 ? Object.values(metodeStats) : [1];
            const colors  = ['#4f46e5','#a78bfa','#34d399','#fbbf24','#f87171','#38bdf8'];

            const ctx = document.getElementById('ppkMethodChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: metKeys,
                    datasets: [{ data: metVals, backgroundColor: colors.slice(0, metKeys.length), borderWidth: 0, cutout: '72%' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // Generate legend
            const legendEl = document.getElementById('ppkLegend');
            const total = metVals.reduce((a,b)=>a+b,0) || 1;
            metKeys.forEach((k, i) => {
                legendEl.innerHTML += `
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full" style="background:${colors[i]}"></span>${k}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-350">${Math.round((metVals[i]/total)*100)}%</span>
                    </div>`;
            });
        });
        </script>


    {{-- ================================================================== --}}
    {{-- PP (PEJABAT PENGADAAN) DASHBOARD                                     --}}
    {{-- ================================================================== --}}
    @elseif(Auth::user()->jabatan_aktif === 'PP')

        {{-- Status Filter Chips --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @php
                $ppStatuses = [
                    ['key'=>'total_paket',     'label'=>'Ditugaskan',    'color'=>'bg-indigo-50 dark:bg-indigo-950/30 border-indigo-100 dark:border-indigo-900/40',  'textColor'=>'text-indigo-900 dark:text-indigo-400',  'badge'=>'bg-indigo-600'],
                    ['key'=>'dikirim_paket',   'label'=>'Perlu Review',  'color'=>'bg-amber-50 dark:bg-amber-950/30 border-amber-100 dark:border-amber-800/40',   'textColor'=>'text-amber-900 dark:text-amber-400',   'badge'=>'bg-amber-500'],
                    ['key'=>'kaji_ulang_paket','label'=>'Kaji Ulang',    'color'=>'bg-sky-50 dark:bg-sky-950/30 border-sky-100 dark:border-sky-900/40',       'textColor'=>'text-sky-900 dark:text-sky-400',     'badge'=>'bg-sky-500'],
                    ['key'=>'disetujui',       'label'=>'Disetujui',     'color'=>'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-100 dark:border-emerald-900/40','textColor'=>'text-emerald-900 dark:text-emerald-400', 'badge'=>'bg-emerald-500'],
                    ['key'=>'selesai',         'label'=>'Selesai',       'color'=>'bg-violet-50 dark:bg-violet-950/30 border-violet-100 dark:border-violet-900/40', 'textColor'=>'text-violet-900 dark:text-violet-400',  'badge'=>'bg-violet-500'],
                ];
            @endphp
            @foreach($ppStatuses as $s)
                <div class="bg-white dark:bg-slate-900 border {{ $s['color'] }} rounded-3xl p-5 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <span class="{{ $s['textColor'] }} text-sm font-semibold">{{ $s['label'] }}</span>
                    <div class="flex items-baseline justify-between mt-3">
                        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $data[$s['key']] ?? 0 }}</h2>
                        <span class="{{ $s['badge'] }} text-white text-xs font-bold px-2 py-0.5 rounded-full">PBJ</span>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Paket pengadaan</span>
                </div>
            @endforeach
        </div>

        {{-- Chart + Action Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Doughnut: Kategori Jenis Barang/Jasa --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Kategori Paket</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Berdasarkan jenis barang/jasa</p>
                    </div>
                </div>
                <div class="h-44 relative w-full flex items-center justify-center my-2">
                    <canvas id="ppJenisChart"></canvas>
                </div>
                <div id="ppLegend" class="space-y-1.5 mt-3 text-xs"></div>
            </div>

            {{-- 2x Action Shortcut Cards --}}
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Shortcut: Tinjau Paket Masuk --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-indigo-300 dark:hover:border-indigo-750 hover:shadow-md transition">
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fa-solid fa-magnifying-glass-chart"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Tinjau<br><span class="text-lg font-medium text-slate-500 dark:text-slate-400">Paket</span></h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Review dokumen usulan paket dari PPK yang masuk ke antrian Anda.</p>
                    </div>
                    <a href="{{ route('paket-review.index') }}" class="w-12 h-12 rounded-full bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center hover:bg-indigo-600 transition shadow-lg shadow-slate-900/10 shrink-0">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Shortcut: Buat Paket Manual (Bypass) --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-amber-300 dark:hover:border-amber-750 hover:shadow-md transition">
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-500 dark:text-amber-400 flex items-center justify-center">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Paket<br><span class="text-lg font-medium text-slate-500 dark:text-slate-400">Manual</span></h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Jalur cepat membuat paket BA secara mandiri tanpa melalui PPK.</p>
                    </div>
                    <a href="{{ route('paket-bypass.create') }}" class="w-12 h-12 rounded-full bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center hover:bg-amber-500 transition shadow-lg shadow-slate-900/10 shrink-0">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Alert: Paket yang perlu diproses segera --}}
                @if(($data['dikirim_paket'] ?? 0) > 0)
                <div class="md:col-span-2 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded-3xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 text-amber-500 dark:text-amber-455 flex items-center justify-center shadow-sm shrink-0">
                        <i class="fa-solid fa-inbox text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-amber-900 dark:text-amber-400 text-sm">{{ $data['dikirim_paket'] }} Paket Masuk Menunggu Review Anda</p>
                        <p class="text-xs text-amber-600 dark:text-amber-450 mt-0.5">PPK telah mengirimkan paket dan menunggu tindak lanjut dari Anda segera.</p>
                    </div>
                    <a href="{{ route('paket-review.index') }}" class="ml-auto shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-amber-500 text-white hover:bg-amber-600 transition">Review →</a>
                </div>
                @endif
            </div>
        </div>

        {{-- Table: Tugas Review Aktif --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-6 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Antrian Paket Masuk</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Usulan paket dari PPK yang perlu Anda review segera</p>
                </div>
                <a href="{{ route('paket-review.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="p-4 pl-6">Kode RUP</th>
                            <th class="p-4">Nama Paket</th>
                            <th class="p-4">PPK Pengirim</th>
                            <th class="p-4">Pagu</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-300 font-medium">
                        @forelse($data['dikirim_paket_list'] ?? [] as $task)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                                <td class="p-4 pl-6 font-mono text-slate-400 dark:text-slate-500 text-xs">{{ $task->kode_rup }}</td>
                                <td class="p-4 font-semibold text-slate-900 dark:text-white">{{ $task->nama_paket }}</td>
                                <td class="p-4 text-slate-500 dark:text-slate-400 text-xs">{{ $task->ppk->nama ?? '-' }}</td>
                                <td class="p-4 text-indigo-600 dark:text-indigo-400 font-semibold text-xs">Rp {{ number_format($task->pagu, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    @php
                                        $cls = match($task->status) {
                                            'dikirim'    => 'bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400',
                                            'kaji_ulang' => 'bg-sky-100 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400',
                                            default      => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ strtoupper($task->status) }}</span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <a href="{{ route('paket.show', $task) }}" class="text-slate-400 hover:text-indigo-600 p-2 transition">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500 italic text-sm">Tidak ada paket yang perlu direview saat ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400">
                <span>Menampilkan {{ count($data['dikirim_paket_list'] ?? []) }} data aktif</span>
                <a href="{{ route('paket-review.index') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat semua</a>
            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const jenisStats = JSON.parse('{!! json_encode($data["chart_jenis_stats"] ?? []) !!}');
            const keys   = Object.keys(jenisStats).length > 0 ? Object.keys(jenisStats) : ['Belum ada data'];
            const values = Object.values(jenisStats).length > 0 ? Object.values(jenisStats) : [1];
            const colors = ['#6366f1','#8b5cf6','#10b981','#f59e0b','#f87171','#38bdf8'];

            new Chart(document.getElementById('ppJenisChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: keys,
                    datasets: [{ data: values, backgroundColor: colors.slice(0, keys.length), borderWidth: 0, cutout: '72%' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            const legendEl = document.getElementById('ppLegend');
            const total = values.reduce((a,b) => a+b, 0) || 1;
            keys.forEach((k, i) => {
                legendEl.innerHTML += `
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full" style="background:${colors[i]}"></span>${k}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-350">${Math.round((values[i]/total)*100)}%</span>
                    </div>`;
            });
        });
        </script>

    @endif

</div>
</x-app-layout>
