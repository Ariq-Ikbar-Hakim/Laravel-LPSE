<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-950 min-h-screen text-slate-850 dark:text-slate-100 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Berita Acara</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Log dokumen hasil kaji ulang Berita Acara (BA) digital beserta tanda tangan elektronik.</p>
                </div>
            </div>

            <!-- Search Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
                <form action="{{ route('admin.berita-acara.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor berita acara..." 
                               class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.berita-acara.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition text-center">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-800 pb-3">
                    {{ __('Semua Berita Acara (BA)') }}
                </h3>

                @if($beritaAcara->isEmpty())
                    <p class="text-sm text-slate-400 dark:text-slate-500 py-4 italic text-center">Belum ada data berita acara dibuat.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-xs uppercase bg-slate-50/50 dark:bg-slate-800/50">
                                    <th class="p-4 pl-6">Nomor BA</th>
                                    <th class="p-4">Paket Pengadaan</th>
                                    <th class="p-4">Tanggal Rilis</th>
                                    <th class="p-4">OPD Pemilik</th>
                                    <th class="p-4 text-center">Status Tanda Tangan</th>
                                    <th class="p-4 pr-6 text-center">Unduh Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-655 dark:text-slate-300 font-medium">
                                @foreach($beritaAcara as $ba)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4 pl-6 font-mono text-xs font-semibold text-slate-900 dark:text-white">{{ $ba->nomor_ba }}</td>
                                        <td class="p-4">
                                            <a href="{{ route('paket.show', $ba->paket_id) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                                {{ $ba->paket->nama_paket ?? '-' }}
                                            </a>
                                            <div class="text-[10px] text-slate-400 mt-0.5">RUP: {{ $ba->paket->kode_rup ?? '-' }}</div>
                                        </td>
                                        <td class="p-4 text-xs text-slate-550 dark:text-slate-400">
                                            {{ $ba->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="p-4 text-xs">
                                            {{ $ba->paket->ppk->opd ?? '-' }}
                                        </td>
                                        <td class="p-4 text-center">
                                            @php
                                                $cls = match($ba->status) {
                                                    'draft'                 => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                                    'tanda_tangan_pertama'  => 'bg-amber-100 dark:bg-amber-950/40 text-amber-705 dark:text-amber-400',
                                                    'tanda_tangan_kedua',
                                                    'selesai'               => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400',
                                                    default                 => 'bg-slate-100 dark:bg-slate-850 text-slate-600 dark:text-slate-400',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ strtoupper($ba->status) }}</span>
                                        </td>
                                        <td class="p-4 pr-6 text-center">
                                            @if($ba->file_laporan)
                                                <a href="{{ asset('storage/' . $ba->file_laporan) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-900 dark:bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-indigo-600 transition">
                                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Belum digenerate</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $beritaAcara->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
