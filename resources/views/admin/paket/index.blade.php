<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen text-slate-850 dark:text-slate-100 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Semua Paket Pengadaan</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pantau status, lampiran dokumen, dan catatan pengadaan seluruh OPD di sistem.</p>
                </div>
            </div>

            <!-- Search Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
                <form action="{{ route('admin.paket.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket atau kode RUP..." 
                               class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.paket.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition text-center">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">
                    {{ __('Daftar Paket Masuk') }}
                </h3>

                @if($paket->isEmpty())
                    <p class="text-sm text-slate-400 dark:text-slate-500 py-4 italic text-center">Belum ada data paket pengadaan.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                                    <th class="p-4 pl-6">Kode RUP</th>
                                    <th class="p-4">Nama Paket</th>
                                    <th class="p-4">Pagu Anggaran</th>
                                    <th class="p-4">Pengaju (PPK)</th>
                                    <th class="p-4">Pemeriksa (PP)</th>
                                    <th class="p-4 text-center">Status</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-300 font-medium">
                                @foreach($paket as $p)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4 pl-6 font-mono text-xs text-slate-400 dark:text-slate-500">{{ $p->kode_rup }}</td>
                                        <td class="p-4">
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $p->nama_paket }}</div>
                                            @if($p->dilihat_admin_at)
                                                <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">
                                                    <i class="fa-solid fa-circle-check mr-1"></i>Sudah Dilihat Admin
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-4 font-semibold text-indigo-600 dark:text-indigo-400 text-xs">
                                            Rp {{ number_format($p->pagu, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 text-xs">
                                            <div class="text-slate-800 dark:text-slate-200">{{ $p->ppk->nama ?? '-' }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $p->ppk->opd ?? '-' }}</div>
                                        </td>
                                        <td class="p-4 text-xs text-slate-800 dark:text-slate-200">
                                            {{ $p->pp->nama ?? '-' }}
                                        </td>
                                        <td class="p-4 text-center">
                                            @php
                                                $cls = match($p->status) {
                                                    'draft'        => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                                    'dikirim'      => 'bg-sky-100 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400',
                                                    'kaji_ulang'   => 'bg-amber-100 dark:bg-amber-950/40 text-amber-705 dark:text-amber-400',
                                                    'perlu_revisi' => 'bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400',
                                                    'disetujui'    => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400',
                                                    'selesai'      => 'bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400',
                                                    default        => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ strtoupper($p->status) }}</span>
                                        </td>
                                        <td class="p-4 pr-6 text-center">
                                            <a href="{{ route('paket.show', $p) }}" class="px-3.5 py-1.5 bg-slate-900 dark:bg-slate-850 hover:bg-indigo-600 dark:hover:bg-indigo-650 text-white rounded-xl text-xs font-semibold transition">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $paket->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
