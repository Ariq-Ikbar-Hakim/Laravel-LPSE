<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Paket Ditugaskan</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Peninjauan dan pemeriksaan usulan paket pengadaan barang/jasa dari PPK.</p>
                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 flex items-center gap-3 text-emerald-800 dark:text-emerald-350 text-sm" role="alert">
                    <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                    <span><strong class="font-bold">Berhasil!</strong> {{ session('success') }}</span>
                </div>
            @endif

            <!-- Status Tabs Filter Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-4 shadow-sm overflow-x-auto whitespace-nowrap scrollbar-none">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 mr-2 uppercase tracking-wider"><i class="fa-solid fa-filter mr-1"></i>Status:</span>
                    @foreach(['all' => 'Semua Ditugaskan', 'dikirim' => 'Dikirim (Baru)', 'kaji_ulang' => 'Kaji Ulang', 'perlu_revisi' => 'Revisi', 'disetujui' => 'Disetujui', 'selesai' => 'Selesai'] as $key => $label)
                        @php
                            $active = $status === $key;
                        @endphp
                        <a href="{{ route('paket-review.index', ['status' => $key]) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-650 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-850 hover:text-slate-700 dark:hover:text-slate-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Review Table Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
                @if($paket->isEmpty())
                    <div class="p-12 text-center text-slate-400 dark:text-slate-500 italic text-sm space-y-2">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-850 rounded-full flex items-center justify-center mx-auto text-slate-350 dark:text-slate-650">
                            <i class="fa-regular fa-folder-open text-xl"></i>
                        </div>
                        <p>Tidak ada paket pengadaan ditugaskan dengan status ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-850/50">
                                    <th class="p-4 pl-6">Kode RUP</th>
                                    <th class="p-4">Nama Paket</th>
                                    <th class="p-4">Pembuat (PPK)</th>
                                    <th class="p-4">Pagu Anggaran</th>
                                    <th class="p-4 text-center">Status</th>
                                    <th class="p-4 pr-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-650 dark:text-slate-350 font-medium">
                                @foreach($paket as $item)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-850/40 transition">
                                        <td class="p-4 pl-6 font-mono text-slate-400 dark:text-slate-500 text-xs">{{ $item->kode_rup }}</td>
                                        <td class="p-4 font-semibold text-slate-900 dark:text-white">{{ $item->nama_paket }}</td>
                                        <td class="p-4 text-slate-500 text-xs">{{ $item->ppk->nama ?? 'Bypass (Tanpa PPK)' }}</td>
                                        <td class="p-4 text-slate-650 dark:text-slate-300 font-semibold text-xs">Rp {{ number_format($item->pagu, 0, ',', '.') }}</td>
                                        <td class="p-4 text-center">
                                            @php
                                                $statusClasses = [
                                                    'dikirim' => 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',
                                                    'kaji_ulang' => 'bg-sky-100 text-sky-700 dark:bg-sky-950/40 dark:text-sky-400',
                                                    'perlu_revisi' => 'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-450',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',
                                                    'selesai' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400',
                                                ];
                                                $class = $statusClasses[$item->status] ?? 'bg-slate-100 text-slate-600';
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $class }}">
                                                {{ strtoupper($item->status) }}
                                            </span>
                                        </td>
                                        <td class="p-4 pr-6 text-center">
                                            <a href="{{ route('paket.show', $item) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-900 dark:bg-slate-800 text-white hover:bg-indigo-650 transition">
                                                Tinjau &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/20">
                        {{ $paket->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
