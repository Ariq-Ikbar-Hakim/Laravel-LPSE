<x-app-layout>
    <div class="py-6 px-4 md:px-8 font-jakarta bg-slate-50 dark:bg-slate-950 min-h-screen" x-data="{ activeTab: 'informasi' }">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">
                    Detail Paket: {{ $paket->nama_paket }}
                </h1>
                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 font-medium">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>{{ date('l, d M Y') }}</span>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400 flex items-start gap-3 shadow-sm" role="alert">
                    <div class="mt-0.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <span class="font-bold block text-emerald-900 dark:text-emerald-300">Berhasil!</span> 
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-900/30 dark:border-rose-800 dark:text-rose-400 flex items-start gap-3 shadow-sm" role="alert">
                    <div class="mt-0.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 dark:text-rose-400"></i>
                    </div>
                    <div>
                        <span class="font-bold block text-rose-900 dark:text-rose-300">Gagal!</span> 
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Main Card Wrapper -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                
                <!-- Package Summary Section -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ $paket->nama_paket }}</h2>
                        <div class="flex items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                            <span class="bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md text-xs font-mono font-medium">RUP: {{ $paket->kode_rup }}</span>
                            <span>&bull;</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">Rp {{ number_format($paket->pagu, 0, ',', '.') }}</span>
                            <span>&bull;</span>
                            <span>{{ $paket->sumber_dana ?? 'APBD' }} {{ $paket->tahun_anggaran ?? '2026' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-3 w-full md:w-auto">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Saat Ini</span>
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-slate-100 text-slate-600 border border-slate-200',
                                    'dikirim' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                    'kaji_ulang' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                    'perlu_revisi' => 'bg-rose-50 text-rose-600 border border-rose-200',
                                    'disetujui' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                    'batal' => 'bg-red-50 text-red-600 border border-red-200',
                                    'selesai' => 'bg-indigo-50 text-indigo-600 border border-indigo-200',
                                ];
                                $class = $statusClasses[$paket->status] ?? 'bg-slate-100 text-slate-600 border border-slate-200';
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $class }} uppercase tracking-wider">
                                {{ str_replace('_', ' ', $paket->status) }}
                            </span>
                        </div>
                        
                        @if(Auth::user()->jabatan_aktif === 'PPK' && in_array($paket->status, ['draft', 'perlu_revisi']))
                            <form action="{{ route('paket.submit', $paket) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full md:w-auto {{ $paket->lampiran->isEmpty() ? 'bg-slate-200 text-slate-500 cursor-not-allowed dark:bg-slate-800 dark:text-slate-500' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20' }} font-bold py-2 px-5 rounded-xl text-sm transition flex items-center gap-2" {{ $paket->lampiran->isEmpty() ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Dokumen ke PP
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex border-b border-slate-100 dark:border-slate-800 px-6 md:px-8 overflow-x-auto hide-scrollbar">
                    <button @click="activeTab = 'informasi'" :class="{ 'border-blue-600 text-blue-600': activeTab === 'informasi', 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300': activeTab !== 'informasi' }" class="whitespace-nowrap py-4 px-2 border-b-2 font-bold text-sm flex items-center gap-2 transition mr-6">
                        <i class="fa-solid fa-circle-info"></i> Informasi Paket
                    </button>
                    <button @click="activeTab = 'lampiran'" :class="{ 'border-blue-600 text-blue-600': activeTab === 'lampiran', 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300': activeTab !== 'lampiran' }" class="whitespace-nowrap py-4 px-2 border-b-2 font-bold text-sm flex items-center gap-2 transition mr-6">
                        <i class="fa-solid fa-folder-open"></i> Dokumen Lampiran
                    </button>
                    <button @click="activeTab = 'diskusi'" :class="{ 'border-blue-600 text-blue-600': activeTab === 'diskusi', 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300': activeTab !== 'diskusi' }" class="whitespace-nowrap py-4 px-2 border-b-2 font-bold text-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-comments"></i> Diskusi & Catatan
                    </button>
                </div>

                <!-- Tabs Content Wrapper -->
                <div class="p-6 md:p-8 bg-slate-50/50 dark:bg-slate-900/50">
                    
                    <!-- TAB 1: Informasi Paket -->
                    <div x-show="activeTab === 'informasi'" style="display: none;" x-transition.opacity>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <!-- Data Terintegrasi SIRUP -->
                            <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                                    <i class="fa-solid fa-database text-blue-600"></i>
                                    <h3 class="font-bold text-slate-800 dark:text-white">Data Terintegrasi SIRUP</h3>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                    <div>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Kode RUP</span>
                                        <span class="inline-block bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg text-sm font-bold text-slate-800 dark:text-slate-200">{{ $paket->kode_rup }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Nama Paket</span>
                                        <span class="block text-sm font-bold text-slate-800 dark:text-slate-200">{{ $paket->nama_paket }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Pagu Anggaran</span>
                                        <span class="block text-sm font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($paket->pagu, 0, ',', '.') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Tahun Anggaran</span>
                                        <span class="block text-sm font-bold text-slate-800 dark:text-slate-200">{{ $paket->tahun_anggaran ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Metode Pengadaan</span>
                                        <span class="block text-sm font-bold text-slate-800 dark:text-slate-200">{{ $paket->metode ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-1">Sumber Dana</span>
                                        <span class="block text-sm font-bold text-slate-800 dark:text-slate-200">{{ $paket->sumber_dana ?? '-' }}</span>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="block text-xs text-slate-500 dark:text-slate-400 mb-2">Keterangan Tambahan</span>
                                        <div class="bg-slate-50 dark:bg-slate-850 p-4 rounded-xl border border-slate-100 dark:border-slate-800 text-sm text-slate-700 dark:text-slate-300">
                                            {{ $paket->keterangan_tambahan ?? 'Tidak ada keterangan tambahan.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tim Pengadaan & Aksi -->
                            <div class="flex flex-col gap-6">
                                <!-- Tim Pengadaan -->
                                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm h-fit">
                                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                                        <i class="fa-solid fa-users text-emerald-600"></i>
                                        <h3 class="font-bold text-slate-800 dark:text-white">Tim Pengadaan</h3>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <!-- PPK -->
                                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850/50">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                PPK
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $paket->ppk->nama ?? '-' }}</h4>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pengusul / Pejabat Pembuat Komitmen</p>
                                                <span class="inline-block mt-2 px-2 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px] text-slate-600 dark:text-slate-300 font-medium">Hukum Kerja</span>
                                            </div>
                                        </div>
                                        
                                        <!-- PP -->
                                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-850/50">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                PP
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $paket->pp->nama ?? '-' }}</h4>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pejabat Pengadaan</p>
                                                <span class="inline-block mt-2 px-2 py-0.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded text-[10px] text-slate-600 dark:text-slate-300 font-medium">Pusat MBG</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Aksi Pejabat Pengadaan -->
                                @if(Auth::user()->jabatan_aktif === 'PP' && $paket->pp_id === Auth::id() && in_array($paket->status, ['dikirim', 'kaji_ulang', 'perlu_revisi']))
                                <div x-data="{ showRevisiOptions: false }" class="bg-slate-50 dark:bg-slate-900 border border-blue-100 dark:border-slate-700 rounded-2xl shadow-sm">
                                    <div class="p-4 bg-blue-50/50 dark:bg-slate-800 border-b border-blue-100 dark:border-slate-700 rounded-t-2xl">
                                        <h3 class="font-bold text-sm text-blue-800 dark:text-blue-400">Aksi Pejabat Pengadaan</h3>
                                    </div>
                                    <div class="p-4">
                                        <form id="pp-action-form" action="{{ route('paket-review.update-status', $paket) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" x-ref="status" value="">
                                            
                                            <div x-show="showRevisiOptions" style="display: none;" class="space-y-4 mb-4">
                                                <textarea name="catatan" rows="3" class="w-full text-sm border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl focus:ring-blue-500 p-3" placeholder="Catatan revisi..."></textarea>
                                                
                                                <div class="p-4 bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-800 rounded-xl">
                                                    <p class="text-[11px] font-bold text-rose-800 dark:text-rose-400 mb-2 uppercase tracking-wide">Pilih lampiran yang direvisi:</p>
                                                    <div class="space-y-2 max-h-32 overflow-y-auto">
                                                        @foreach($paket->lampiran as $lampiran)
                                                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                                                            <input type="checkbox" name="revisi_lampiran[]" value="{{ $lampiran->id }}" class="text-rose-600 focus:ring-rose-500 rounded border-slate-300">
                                                            <span>{{ $lampiran->tipe_dokumen }} <span class="text-xs text-slate-400">({{ $lampiran->nama_file }})</span></span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <button type="button" @click="$refs.status.value='disetujui'; $el.closest('form').submit()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition shadow-sm">
                                                    Setujui Dokumen (Lanjut)
                                                </button>
                                                
                                                <button type="button" x-show="!showRevisiOptions" @click="showRevisiOptions = true" class="w-full bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold py-2.5 px-4 rounded-xl text-sm transition">
                                                    Minta Revisi PPK
                                                </button>
                                                
                                                <button type="button" x-show="showRevisiOptions" style="display: none;" @click="$refs.status.value='perlu_revisi'; $el.closest('form').submit()" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition shadow-sm">
                                                    Kirim Permintaan Revisi
                                                </button>

                                                <button type="button" @click="$refs.status.value='batal'; $el.closest('form').submit()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 font-bold py-2.5 px-4 rounded-xl text-sm transition">
                                                    BATALKAN PAKET
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                        </div>
                    </div>

                    <!-- TAB 2: Dokumen Lampiran -->
                    <div x-show="activeTab === 'lampiran'" style="display: none;" x-transition.opacity>
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
                            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                    <h3 class="font-bold text-slate-800 dark:text-white">Dokumen Lampiran Aktif</h3>
                                </div>
                                @if(Auth::user()->jabatan_aktif === 'PPK' && in_array($paket->status, ['draft', 'perlu_revisi']))
                                    <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
                                        <i class="fa-solid fa-upload"></i> Upload Lampiran
                                    </button>
                                @endif
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                            <th class="p-4 font-bold">Tipe Dokumen</th>
                                            <th class="p-4 font-bold">Nama File & Versi</th>
                                            <th class="p-4 font-bold text-center">Status Validasi</th>
                                            <th class="p-4 font-bold text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                        @forelse($paket->lampiran as $lampiran)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/50 transition">
                                                <td class="p-4 text-sm font-medium text-slate-800 dark:text-slate-200">
                                                    {{ $lampiran->tipe_dokumen }}
                                                </td>
                                                <td class="p-4">
                                                    <div class="flex items-start gap-2">
                                                        <i class="fa-solid fa-file-word text-blue-500 mt-1"></i>
                                                        <div>
                                                            <a href="{{ Storage::url($lampiran->file_path) }}" class="text-blue-600 dark:text-blue-400 font-medium text-sm hover:underline">{{ $lampiran->nama_file }}</a>
                                                            <div class="flex items-center gap-2 mt-1">
                                                                <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px] font-bold">v1</span>
                                                                <span class="text-[11px] text-slate-500">&bull; Oleh: {{ $lampiran->uploader->nama ?? 'Sistem' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-4 text-center">
                                                    @php
                                                        $valClass = [
                                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                            'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                            'revisi' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                        ][$lampiran->status_validasi] ?? 'bg-slate-100 text-slate-700';
                                                        $valText = $lampiran->status_validasi === 'pending' ? 'MENUNGGU' : strtoupper($lampiran->status_validasi);
                                                    @endphp
                                                    <span class="inline-block px-2.5 py-1 rounded text-[10px] font-bold border {{ $valClass }}">
                                                        {{ $valText }}
                                                    </span>
                                                </td>
                                                <td class="p-4 text-center space-y-2">
                                                    <a href="{{ Storage::url($lampiran->file_path) }}" download class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100 transition" title="Download">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-8 text-center text-slate-500">
                                                    <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                                                    <p class="text-sm">Belum ada dokumen lampiran yang diunggah.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Upload Modal (Hidden by default) -->
                        <div id="upload-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
                            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                                <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                                    <h3 class="font-bold">Upload Lampiran Baru</h3>
                                    <button onclick="document.getElementById('upload-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="{{ route('paket.upload-lampiran', $paket) }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Dokumen</label>
                                        <select name="tipe_dokumen" class="w-full text-sm border-slate-300 rounded-xl focus:ring-blue-500" required>
                                            <option value="Kerangka Acuan Kerja (KAK)">Kerangka Acuan Kerja (KAK)</option>
                                            <option value="HPS (Harga Perkiraan Sendiri)">HPS (Harga Perkiraan Sendiri)</option>
                                            <option value="Spesifikasi Teknis">Spesifikasi Teknis</option>
                                            <option value="Rancangan Kontrak">Rancangan Kontrak</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih File</label>
                                        <input type="file" name="file_dokumen" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                    </div>
                                    <div class="pt-2 flex justify-end">
                                        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-xl text-sm hover:bg-blue-700">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Diskusi & Catatan -->
                    <div x-show="activeTab === 'diskusi'" style="display: none;" x-transition.opacity>
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6">
                            <h3 class="font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                                <i class="fa-solid fa-comments text-blue-500"></i> Diskusi & Tanya Jawab
                            </h3>
                            
                            <!-- Comment List -->
                            <div class="space-y-4 mb-6 max-h-[400px] overflow-y-auto pr-2">
                                @forelse($paket->comments as $comment)
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $comment->user->nama }}</span>
                                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-bold">{{ $comment->role_saat_komentar }}</span>
                                            </div>
                                            <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-slate-700 dark:text-slate-300">
                                            @if($comment->lampiran_id)
                                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs font-bold mr-1">[Revisi {{ $comment->lampiran->tipe_dokumen }}]</span>
                                            @endif
                                            {{ $comment->komentar }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-500">
                                        <i class="fa-regular fa-comment-dots text-3xl mb-2 text-slate-300"></i>
                                        @if($paket->status === 'draft')
                                            <p class="text-sm font-medium">Komentar belum terbuka karena dokumen belum terkirim ke PP.</p>
                                        @else
                                            <p class="text-sm">Belum ada diskusi untuk paket ini.</p>
                                        @endif
                                    </div>
                                @endforelse
                            </div>

                            <!-- Add Comment Form -->
                            <form action="{{ route('paket.comment', $paket) }}" method="POST" class="pt-4 border-t border-slate-100 dark:border-slate-800">
                                @csrf
                                <div class="flex flex-col items-end gap-3">
                                    <textarea name="komentar" rows="3" class="w-full text-sm border-slate-300 dark:border-slate-700 dark:bg-slate-800 rounded-xl focus:ring-blue-500 p-3 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed" placeholder="{{ $paket->status === 'draft' ? 'Komentar belum terbuka...' : 'Ketik pesan diskusi...' }}" {{ $paket->status === 'draft' ? 'disabled' : 'required' }}></textarea>
                                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-6 rounded-xl text-sm transition disabled:opacity-50 disabled:cursor-not-allowed" {{ $paket->status === 'draft' ? 'disabled' : '' }}>
                                        Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center text-[10px] text-slate-400 mt-8 pt-4 border-t border-slate-200 dark:border-slate-800">
                <span>&copy; 2026 Biro Pengadaan Barang/Jasa Kab. Bangkalan</span>
                <span>Versi 2.0.0</span>
            </div>

        </div>
    </div>
</x-app-layout>
