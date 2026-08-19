<x-app-layout>
    <div class="py-8 px-4 md:px-8 font-jakarta bg-slate-100 dark:bg-slate-955 min-h-screen text-slate-850 dark:text-slate-100 transition-colors duration-300">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Berita Acara</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Log dokumen hasil kaji ulang Berita Acara (BA) digital beserta tanda tangan elektronik.</p>
                </div>
                @if(Auth::user()->jabatan_aktif === 'PP')
                    <button onclick="openCreateModal()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-sm cursor-pointer inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> Tambah Berita Acara
                    </button>
                @endif
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-100 dark:bg-emerald-950/40 border border-emerald-250 dark:border-emerald-900 text-emerald-800 dark:text-emerald-400 rounded-2xl text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span><strong>Berhasil!</strong> {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-100 dark:bg-rose-950/40 border border-rose-250 dark:border-rose-900 text-rose-800 dark:text-rose-400 rounded-2xl text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span><strong>Gagal!</strong> {{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-100 dark:bg-rose-950/40 border border-rose-250 dark:border-rose-900 text-rose-800 dark:text-rose-400 rounded-2xl text-sm space-y-1">
                    <div class="flex items-center gap-3 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                        <span>Terdapat kesalahan input:</span>
                    </div>
                    <ul class="list-disc pl-8 text-xs space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Search Card -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm">
                <form action="{{ route('berita-acara.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor berita acara..." 
                               class="w-full pl-9 pr-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition cursor-pointer">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('berita-acara.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition text-center">
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
                    <p class="text-sm text-slate-400 dark:text-slate-500 py-4 italic text-center">Belum ada data berita acara.</p>
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
                                    <th class="p-4 pr-6 text-center">Aksi / Dokumen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-600 dark:text-slate-300 font-medium">
                                @foreach($beritaAcara as $ba)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition">
                                        <td class="p-4 pl-6 font-mono text-xs font-semibold text-slate-900 dark:text-white">{{ $ba->nomor_ba }}</td>
                                        <td class="p-4">
                                            <a href="{{ route('paket.show', $ba->paket_id) }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                                {{ $ba->paket->nama_paket ?? '-' }}
                                            </a>
                                            <div class="text-[10px] text-slate-400 mt-0.5">RUP: {{ $ba->paket->kode_rup ?? '-' }} @if($ba->paket->metode === 'Manual') <span class="px-1.5 py-0.5 bg-slate-150 dark:bg-slate-800 text-slate-500 rounded text-[8px] font-bold uppercase ml-1">Manual</span> @else <span class="px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 rounded text-[8px] font-bold uppercase ml-1">Semi-Otomatis</span> @endif</div>
                                        </td>
                                        <td class="p-4 text-xs text-slate-500 dark:text-slate-400">
                                            {{ $ba->tanggal_ba ? $ba->tanggal_ba->format('d M Y') : ($ba->created_at ? $ba->created_at->format('d M Y') : '-') }}
                                        </td>
                                        <td class="p-4 text-xs">
                                            {{ $ba->paket->ppk->opd ?? '-' }}
                                        </td>
                                        <td class="p-4 text-center">
                                            @php
                                                $cls = match($ba->status) {
                                                    'draft'                 => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400',
                                                    'tanda_tangan_pertama'  => 'bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400',
                                                    'tanda_tangan_kedua',
                                                    'selesai'               => 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400',
                                                    default                 => 'bg-slate-100 dark:bg-slate-850 text-slate-600 dark:text-slate-400',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ strtoupper($ba->status) }}</span>
                                        </td>
                                        <td class="p-4 pr-6 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($ba->file_laporan)
                                                    <a href="{{ asset('storage/' . $ba->file_laporan) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-900 dark:bg-slate-850 text-white rounded-xl text-xs font-semibold hover:bg-indigo-600 transition">
                                                        <i class="fa-solid fa-file-pdf"></i> PDF
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Belum digenerate</span>
                                                @endif

                                                @if(Auth::user()->jabatan_aktif === 'PP')
                                                    @if($ba->status === 'draft' && $ba->paket->pp_id === Auth::id())
                                                        <button onclick="openSignModal('{{ route('berita-acara.sign', $ba) }}', 'Pejabat Pengadaan (PP)')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1">
                                                            <i class="fa-solid fa-file-signature text-[10px]"></i> TTD PP
                                                        </button>
                                                    @endif

                                                    @if($ba->status === 'tanda_tangan_pertama' && $ba->paket->pp_id === Auth::id())
                                                        <!-- Edit BA -->
                                                        <button onclick="openEditModal({{ json_encode($ba) }}, {{ json_encode($ba->paket) }})" class="p-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition inline-flex items-center justify-center" title="Edit Berita Acara">
                                                            <i class="fa-solid fa-pencil text-xs"></i>
                                                        </button>
                                                        <!-- Hapus BA -->
                                                        <form action="{{ route('berita-acara.destroy-manual', $ba) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Berita Acara ini?')" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition inline-flex items-center justify-center" title="Hapus Berita Acara">
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                @if(Auth::user()->jabatan_aktif === 'PPK')
                                                    @if($ba->status === 'tanda_tangan_pertama' && ($ba->paket->ppk_id === null || $ba->paket->ppk_id === Auth::id()))
                                                        @php
                                                            $canSign = true;
                                                            if ($ba->paket->ppk_id === null) {
                                                                $canSign = $ba->paket->lampiran()->where('status_validasi', 'disetujui')->exists();
                                                            }
                                                        @endphp
                                                        @if($canSign)
                                                            <button onclick="openSignModal('{{ route('berita-acara.sign', $ba) }}', 'Pejabat Pembuat Komitmen (PPK)')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-750 text-white rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1">
                                                                <i class="fa-solid fa-file-signature text-[10px]"></i> TTD PPK
                                                            </button>
                                                        @else
                                                            <span class="text-[10px] text-rose-500 italic block">Butuh 1 lampiran disetujui</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
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

    <!-- Modal Create (Unggah Berita Acara - Hybrid) -->
    <div id="create-modal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-3xl shadow-xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Tambah Berita Acara</h3>
                <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <!-- Form Modal -->
            <form action="{{ route('berita-acara.store-manual') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-4">
                @csrf
                <input type="hidden" name="paket_id" id="create_paket_id_val" value="">

                <!-- Tipe Metode BA Selector -->
                <div class="p-1 bg-slate-100 dark:bg-slate-950 rounded-2xl">
                    <div class="grid grid-cols-2 gap-1">
                        <label class="flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-slate-900 text-slate-800 dark:text-white rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 cursor-pointer text-xs font-bold text-center transition" id="label_method_semi">
                            <input type="radio" name="method_type" value="semi" checked onchange="toggleMethodType('semi')" class="hidden">
                            <i class="fa-solid fa-circle-check text-indigo-600"></i> Semi-Otomatis
                        </label>
                        <label class="flex items-center justify-center gap-2 py-2 px-3 text-slate-500 dark:text-slate-400 rounded-xl cursor-pointer text-xs font-bold text-center transition" id="label_method_manual">
                            <input type="radio" name="method_type" value="manual" onchange="toggleMethodType('manual')" class="hidden">
                            <i class="fa-solid fa-circle-dot text-slate-400"></i> Benar-benar Manual
                        </label>
                    </div>
                </div>

                <!-- Hubungkan dengan Paket (Hanya Semi-Otomatis) -->
                <div id="paket_select_container">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Pilih Paket Terdaftar</label>
                    <select id="create_paket_select" onchange="handlePaketSelection(this, 'create')" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Paket Pengadaan --</option>
                        @foreach($availablePaket as $p)
                            <option value="{{ $p->id }}" data-paket="{{ json_encode($p) }}">{{ $p->nama_paket }} (RUP: {{ $p->kode_rup }})</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Pilih PPK -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Pilih PPK (Pengusul Paket)</label>
                    <select id="create_ppk_id" name="ppk_id" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih PPK --</option>
                        @foreach($ppkUsers as $ppk)
                            <option value="{{ $ppk->id }}">{{ $ppk->nama }} (NIP: {{ $ppk->nip }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Paket -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Paket Pekerjaan</label>
                    <input type="text" id="create_nama_paket" name="nama_paket" required placeholder="Contoh: Pengadaan Laptop Dinas Tahun 2026" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Kode RUP & Tahun Anggaran -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Kode RUP</label>
                        <input type="text" id="create_kode_rup" name="kode_rup" required placeholder="Bebas" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tahun Anggaran</label>
                        <input type="text" id="create_tahun_anggaran" name="tahun_anggaran" required value="2026" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Pagu & HPS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Pagu (Rp)</label>
                        <input type="number" id="create_pagu" name="pagu" required placeholder="150.000.000" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">HPS (Rp)</label>
                        <input type="number" id="create_hps" name="hps" required placeholder="0" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Nomor BA -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Berita Acara</label>
                    <input type="text" id="create_nomor_ba" name="nomor_ba" required placeholder="Contoh: BA/12/LPSE/2026" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Tanggal BA -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Berita Acara</label>
                    <input type="date" id="create_tanggal_ba" name="tanggal_ba" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Unggah TTD PP -->
                <div class="p-4 bg-slate-55 dark:bg-slate-800/40 border border-indigo-100 dark:border-slate-800 rounded-2xl space-y-2">
                    <label class="block text-xs font-bold text-indigo-900 dark:text-indigo-400 uppercase">Unggah Tanda Tangan Anda (PP)</label>
                    <input type="file" name="signature_image" required accept="image/png, image/jpeg, image/jpg" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-800 dark:file:text-white" />
                    <p class="text-[10px] text-slate-400">Format PNG/JPG.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-655 dark:text-slate-300 rounded-xl text-sm font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                        Simpan & Lanjutkan ke PPK
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit (Edit Berita Acara) -->
    <div id="edit-modal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-3xl shadow-xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Ubah Berita Acara</h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <!-- Form Modal -->
            <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <!-- Pilih PPK -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Pilih PPK (Pengusul Paket)</label>
                    <select id="edit_ppk_id" name="ppk_id" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih PPK --</option>
                        @foreach($ppkUsers as $ppk)
                            <option value="{{ $ppk->id }}">{{ $ppk->nama }} (NIP: {{ $ppk->nip }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Paket -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Paket Pekerjaan</label>
                    <input type="text" id="edit_nama_paket" name="nama_paket" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Kode RUP & Tahun Anggaran -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Kode RUP</label>
                        <input type="text" id="edit_kode_rup" name="edit_kode_rup" readonly class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500 cursor-not-allowed">
                        <input type="hidden" id="edit_kode_rup_hidden" name="kode_rup">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tahun Anggaran</label>
                        <input type="text" id="edit_tahun_anggaran" name="tahun_anggaran" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Pagu & HPS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Pagu (Rp)</label>
                        <input type="number" id="edit_pagu" name="pagu" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">HPS (Rp)</label>
                        <input type="number" id="edit_hps" name="hps" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Nomor BA -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Berita Acara</label>
                    <input type="text" id="edit_nomor_ba" name="nomor_ba" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Tanggal BA -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Berita Acara</label>
                    <input type="date" id="edit_tanggal_ba" name="tanggal_ba" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-800 dark:bg-slate-955 dark:text-white rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Unggah TTD PP -->
                <div class="p-4 bg-slate-55 dark:bg-slate-800/40 border border-indigo-100 dark:border-slate-800 rounded-2xl space-y-2">
                    <label class="block text-xs font-bold text-indigo-900 dark:text-indigo-400 uppercase">Unggah Tanda Tangan Anda (PP) (Opsional)</label>
                    <input type="file" name="signature_image" accept="image/png, image/jpeg, image/jpg" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-800 dark:file:text-white" />
                    <p class="text-[10px] text-slate-400">Kosongkan jika tidak ingin merubah tanda tangan basah lama.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-655 dark:text-slate-300 rounded-xl text-sm font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tanda Tangan Berita Acara -->
    <div id="sign-modal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-md rounded-3xl shadow-xl overflow-hidden transform transition-all flex flex-col">
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-indigo-600"></i>
                    <span>Tanda Tangan Digital Berita Acara</span>
                </h3>
                <button onclick="closeSignModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <!-- Form Modal -->
            <form id="sign-form" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                
                <div class="text-slate-500 dark:text-slate-400 text-sm leading-normal">
                    Anda akan menandatangani dokumen Berita Acara ini secara digital sebagai <strong id="sign-role-title" class="text-indigo-650 dark:text-indigo-400"></strong>.
                </div>

                <!-- Unggah TTD -->
                <div class="p-4 bg-slate-55 dark:bg-slate-800/40 border border-indigo-100 dark:border-slate-800 rounded-2xl space-y-2">
                    <label class="block text-xs font-bold text-indigo-900 dark:text-indigo-400 uppercase">Unggah Gambar Tanda Tangan Anda (PNG/JPG)</label>
                    <input type="file" name="signature_image" required accept="image/png, image/jpeg, image/jpg" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-800 dark:file:text-white" />
                    <p class="text-[10px] text-slate-400">Silakan unggah pindaian tanda tangan basah Anda.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeSignModal()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-655 dark:text-slate-300 rounded-xl text-sm font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-nib"></i> Tandatangani
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScripts -->
    <script>
        function openCreateModal() {
            document.getElementById('create-modal').classList.remove('hidden');
            document.getElementById('create-modal').classList.add('flex');
            
            // Reset to Semi-Otomatis
            document.querySelectorAll('input[name="method_type"]').forEach(radio => {
                if (radio.value === 'semi') {
                    radio.checked = true;
                }
            });
            toggleMethodType('semi');
            document.getElementById('create_nomor_ba').value = '';
            document.getElementById('create_tanggal_ba').value = '';
        }
        function closeCreateModal() {
            document.getElementById('create-modal').classList.add('hidden');
            document.getElementById('create-modal').classList.remove('flex');
        }

        function toggleMethodType(type) {
            const selectContainer = document.getElementById('paket_select_container');
            const selectElement = document.getElementById('create_paket_select');
            
            const btnSemi = document.getElementById('label_method_semi');
            const btnManual = document.getElementById('label_method_manual');

            if (type === 'semi') {
                selectContainer.classList.remove('hidden');
                selectElement.required = true;
                
                // Styling labels
                btnSemi.className = "flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-slate-900 text-slate-800 dark:text-white rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 cursor-pointer text-xs font-bold text-center transition";
                btnSemi.querySelector('i').className = "fa-solid fa-circle-check text-indigo-600";
                
                btnManual.className = "flex items-center justify-center gap-2 py-2 px-3 text-slate-500 dark:text-slate-400 rounded-xl cursor-pointer text-xs font-bold text-center transition";
                btnManual.querySelector('i').className = "fa-solid fa-circle-dot text-slate-400";
                
                handlePaketSelection(selectElement, 'create');
            } else {
                selectContainer.classList.add('hidden');
                selectElement.required = false;
                selectElement.value = '';
                
                // Styling labels
                btnManual.className = "flex items-center justify-center gap-2 py-2 px-3 bg-white dark:bg-slate-900 text-slate-800 dark:text-white rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 cursor-pointer text-xs font-bold text-center transition";
                btnManual.querySelector('i').className = "fa-solid fa-circle-check text-indigo-600";
                
                btnSemi.className = "flex items-center justify-center gap-2 py-2 px-3 text-slate-500 dark:text-slate-400 rounded-xl cursor-pointer text-xs font-bold text-center transition";
                btnSemi.querySelector('i').className = "fa-solid fa-circle-dot text-slate-400";

                handlePaketSelection(selectElement, 'create');
            }
        }

        function handlePaketSelection(select, type) {
            const val = select.value;
            const ppkField = document.getElementById(type + '_ppk_id');
            const namaField = document.getElementById(type + '_nama_paket');
            const rupField = document.getElementById(type + '_kode_rup');
            const tahunField = document.getElementById(type + '_tahun_anggaran');
            const paguField = document.getElementById(type + '_pagu');
            const hpsField = document.getElementById(type + '_hps');
            const hiddenPaketVal = document.getElementById(type + '_paket_id_val');

            if (val) {
                // Linked flow
                const option = select.options[select.selectedIndex];
                const paket = JSON.parse(option.getAttribute('data-paket'));

                if (hiddenPaketVal) hiddenPaketVal.value = paket.id;

                ppkField.value = paket.ppk_id;
                ppkField.style.pointerEvents = 'none';
                ppkField.style.backgroundColor = '#f3f4f6';

                namaField.value = paket.nama_paket;
                namaField.readOnly = true;
                namaField.style.backgroundColor = '#f3f4f6';

                rupField.value = paket.kode_rup;
                rupField.readOnly = true;
                rupField.style.backgroundColor = '#f3f4f6';

                tahunField.value = paket.tahun_anggaran || '2026';
                tahunField.readOnly = true;
                tahunField.style.backgroundColor = '#f3f4f6';

                paguField.value = parseInt(paket.pagu);
                paguField.readOnly = true;
                paguField.style.backgroundColor = '#f3f4f6';

                hpsField.value = parseInt(paket.hps) || 0;
                hpsField.readOnly = true;
                hpsField.style.backgroundColor = '#f3f4f6';
            } else {
                // Manual Offline flow
                if (hiddenPaketVal) hiddenPaketVal.value = '';

                ppkField.value = '';
                ppkField.style.pointerEvents = 'auto';
                ppkField.style.backgroundColor = '';

                namaField.value = '';
                namaField.readOnly = false;
                namaField.style.backgroundColor = '';

                rupField.value = '';
                rupField.readOnly = false;
                rupField.style.backgroundColor = '';

                tahunField.value = '2026';
                tahunField.readOnly = false;
                tahunField.style.backgroundColor = '';

                paguField.value = '';
                paguField.readOnly = false;
                paguField.style.backgroundColor = '';

                hpsField.value = '0';
                hpsField.readOnly = false;
                hpsField.style.backgroundColor = '';
            }
        }

        function openEditModal(ba, paket) {
            const form = document.getElementById('edit-form');
            form.action = `/berita-acara/${ba.id}/manual`;
            
            document.getElementById('edit_ppk_id').value = paket.ppk_id;
            document.getElementById('edit_nama_paket').value = paket.nama_paket;
            document.getElementById('edit_kode_rup').value = paket.kode_rup;
            document.getElementById('edit_kode_rup_hidden').value = paket.kode_rup;
            document.getElementById('edit_tahun_anggaran').value = paket.tahun_anggaran || '2026';
            document.getElementById('edit_pagu').value = parseInt(paket.pagu);
            document.getElementById('edit_hps').value = parseInt(paket.hps) || 0;
            document.getElementById('edit_nomor_ba').value = ba.nomor_ba;
            document.getElementById('edit_tanggal_ba').value = ba.tanggal_ba ? ba.tanggal_ba.substring(0, 10) : '';

            // Jika BA ini terhubung ke paket sistem (bukan manual offline), kunci formnya
            const isManualPaket = (paket.metode === 'Manual');
            const ppkField = document.getElementById('edit_ppk_id');
            const namaField = document.getElementById('edit_nama_paket');
            const tahunField = document.getElementById('edit_tahun_anggaran');
            const paguField = document.getElementById('edit_pagu');
            const hpsField = document.getElementById('edit_hps');

            if (!isManualPaket) {
                ppkField.style.pointerEvents = 'none';
                ppkField.style.backgroundColor = '#f3f4f6';
                namaField.readOnly = true;
                namaField.style.backgroundColor = '#f3f4f6';
                tahunField.readOnly = true;
                tahunField.style.backgroundColor = '#f3f4f6';
                paguField.readOnly = true;
                paguField.style.backgroundColor = '#f3f4f6';
                hpsField.readOnly = true;
                hpsField.style.backgroundColor = '#f3f4f6';
            } else {
                ppkField.style.pointerEvents = 'auto';
                ppkField.style.backgroundColor = '';
                namaField.readOnly = false;
                namaField.style.backgroundColor = '';
                tahunField.readOnly = false;
                tahunField.style.backgroundColor = '';
                paguField.readOnly = false;
                paguField.style.backgroundColor = '';
                hpsField.readOnly = false;
                hpsField.style.backgroundColor = '';
            }

            document.getElementById('edit-modal').classList.remove('hidden');
            document.getElementById('edit-modal').classList.add('flex');
        }
        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
            document.getElementById('edit-modal').classList.remove('flex');
        }

        function openSignModal(actionUrl, roleTitle) {
            const form = document.getElementById('sign-form');
            form.action = actionUrl;
            document.getElementById('sign-role-title').innerText = roleTitle;
            document.getElementById('sign-modal').classList.remove('hidden');
            document.getElementById('sign-modal').classList.add('flex');
        }
        function closeSignModal() {
            document.getElementById('sign-modal').classList.add('hidden');
            document.getElementById('sign-modal').classList.remove('flex');
        }
    </script>
</x-app-layout>
