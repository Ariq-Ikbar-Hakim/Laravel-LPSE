<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Paket Pengadaan: ') }} {{ $paket->nama_paket }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    <span class="font-medium">Berhasil!</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Peringatan!</span> {{ session('error') }}
                </div>
            @endif

            <!-- Info & Actions Card -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Section -->
                <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 shadow rounded-lg space-y-4">
                    <div class="flex justify-between items-start border-b dark:border-gray-700 pb-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $paket->nama_paket }}</h3>
                            <span class="text-xs text-gray-500 font-mono">RUP: {{ $paket->kode_rup }}</span>
                        </div>
                        <div>
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-gray-100 text-gray-800 dark:bg-slate-900 dark:text-gray-300',
                                    'dikirim' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
                                    'kaji_ulang' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'perlu_revisi' => 'bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200',
                                    'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                                    'batal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'selesai' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
                                ];
                                $class = $statusClasses[$paket->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $class }}">
                                {{ str_replace('_', ' ', strtoupper($paket->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-xs text-gray-500 block">Pagu Anggaran:</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">Rp {{ number_format($paket->pagu, 2, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 block">Metode Pembuatan:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $paket->metode ?? 'Sistem RUP (Online)' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 block">Pembuat (PPK):</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $paket->ppk->nama ?? 'Bypass (Tanpa PPK)' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 block">Pejabat Pengadaan (PP):</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $paket->pp->nama ?? 'Belum Ditugaskan' }}</span>
                        </div>
                    </div>

                    <!-- Read Receipt Indicator -->
                    @if($paket->dilihat_admin_at)
                        <div class="p-3 bg-indigo-50 dark:bg-slate-900 text-indigo-850 dark:text-indigo-200 rounded border border-indigo-200 text-xs flex items-center space-x-2">
                            <span class="text-base">🟢</span>
                            <span><strong>Sudah Dilihat Admin:</strong> Paket ini telah dibuka & dipantau oleh Admin LPSE pada {{ $paket->dilihat_admin_at->format('d M Y, H:i') }} WIB.</span>
                        </div>
                    @endif
                </div>

                <!-- PPK / PP Actions Panel -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 dark:text-gray-200 border-b dark:border-gray-700 pb-2 mb-3">Panel Kontrol Akses</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Wewenang pengerjaan status paket berdasarkan wewenang masing-masing role pengguna.
                        </p>
                    </div>

                    <div class="mt-4 space-y-3">
                        <!-- PPK: Submit Paket -->
                        @if(Auth::user()->jabatan_aktif === 'PPK' && in_array($paket->status, ['draft', 'perlu_revisi']))
                            <form action="{{ route('paket.submit', $paket) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-xs transition duration-150 uppercase tracking-widest" {{ $paket->lampiran->isEmpty() ? 'disabled' : '' }}>
                                    Kirim ke PP (Submit)
                                </button>
                                @if($paket->lampiran->isEmpty())
                                    <p class="text-[10px] text-red-500 mt-1">Unggah minimal 1 dokumen persyaratan sebelum mengirim paket.</p>
                                @endif
                            </form>
                        @endif

                        <!-- PP: Update Status Review Paket -->
                        @if(Auth::user()->jabatan_aktif === 'PP' && $paket->pp_id === Auth::id() && in_array($paket->status, ['dikirim', 'kaji_ulang', 'perlu_revisi']))
                            <form action="{{ route('paket-review.update-status', $paket) }}" method="POST" class="space-y-2">
                                @csrf
                                <label class="text-xs text-gray-600 dark:text-gray-400 block font-medium">Ubah Status Review Paket:</label>
                                <select name="status" class="w-full text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-1.5">
                                    <option value="kaji_ulang" {{ $paket->status == 'kaji_ulang' ? 'selected' : '' }}>Dalam Kaji Ulang</option>
                                    <option value="perlu_revisi" {{ $paket->status == 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi (Kembalikan ke PPK)</option>
                                    <option value="disetujui" {{ $paket->status == 'disetujui' ? 'selected' : '' }}>Disetujui (Dokumen Valid)</option>
                                </select>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded text-xs transition duration-150 uppercase tracking-widest">
                                    Kirim Keputusan Review
                                </button>
                            </form>
                        @endif

                        <!-- Swap Jabatan / Mutasi Peran -->
                        @php
                            $isOwner = false;
                            $user = Auth::user();
                            if ($user->jabatan_aktif === 'PPK' && $paket->ppk_id === $user->id) {
                                $isOwner = true;
                            } elseif ($user->jabatan_aktif === 'PP' && $paket->pp_id === $user->id) {
                                $isOwner = true;
                            }
                        @endphp
                        @if($isOwner)
                            @php
                                $pendingSwap = \App\Models\AssignmentTransfer::where('dari_user_id', $user->id)
                                    ->where('status', 'menunggu')
                                    ->first();
                            @endphp
                            @if($pendingSwap)
                                <div class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300 rounded border border-amber-200 text-xs">
                                    <strong>Mutasi Pending:</strong> Pengajuan swap jabatan dengan <span class="font-semibold text-slate-900 dark:text-white">{{ $pendingSwap->keUser->nama }}</span> sedang menunggu persetujuan Admin.
                                </div>
                            @else
                                <a href="{{ route('transfers.create') }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition duration-150 uppercase tracking-widest cursor-pointer shadow-sm">
                                    Ajukan Swap Jabatan
                                </a>
                            @endif
                        @endif

                        <!-- Back Button -->
                        @if(Auth::user()->jabatan_aktif === 'PPK')
                            <a href="{{ route('paket.index') }}" class="block text-center text-xs text-gray-500 hover:underline">Kembali ke Daftar Paket &rarr;</a>
                        @elseif(Auth::user()->jabatan_aktif === 'PP')
                            <a href="{{ route('paket-review.index') }}" class="block text-center text-xs text-gray-500 hover:underline">Kembali ke Daftar Review &rarr;</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="block text-center text-xs text-gray-500 hover:underline">Kembali ke Dashboard &rarr;</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Berita Acara (BA) & Tanda Tangan Digital Section -->
            @php
                $ba = $paket->beritaAcara->first();
            @endphp
            @if($ba)
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg border-t-4 border-indigo-600 space-y-4">
                    <div class="flex justify-between items-center border-b dark:border-gray-700 pb-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Berita Acara & Pengesahan Digital</h3>
                            <span class="text-xs text-gray-500 font-mono">No. BA: {{ $ba->nomor_ba }}</span>
                        </div>
                        <div>
                            @php
                                $baStatusClasses = [
                                    'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    'tanda_tangan_pertama' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'selesai' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                                ];
                                $baClass = $baStatusClasses[$ba->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $baClass }}">
                                BA: {{ strtoupper(str_replace('_', ' ', $ba->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Signatures Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <!-- PP Signature -->
                        <div class="p-4 rounded border dark:border-gray-700 space-y-2 {{ $ba->hasSignatureFrom('PP') ? 'bg-emerald-50/5 dark:bg-emerald-950/5 border-emerald-500/20' : 'bg-gray-50 dark:bg-slate-900' }}">
                            <div class="flex justify-between font-bold text-gray-700 dark:text-gray-300">
                                <span>Pejabat Pengadaan (PP)</span>
                                <span>{{ $ba->hasSignatureFrom('PP') ? '✅ Signed' : '❌ Unsigned' }}</span>
                            </div>
                            @if($ba->hasSignatureFrom('PP'))
                                <div class="text-gray-900 dark:text-gray-200 font-semibold">{{ $ba->ppSignature()->user->nama }}</div>
                                <div class="text-gray-500 dark:text-gray-400">NIP: {{ $ba->ppSignature()->user->nip }}</div>
                                <div class="text-[10px] text-gray-400">IP: {{ $ba->ppSignature()->ip_address }} | {{ $ba->ppSignature()->signed_at->format('d/m/Y H:i') }}</div>
                            @else
                                <div class="text-gray-455 italic">Menunggu tanda tangan Pejabat Pengadaan.</div>
                                <!-- PP Sign Button -->
                                @can('signAsPp', $ba)
                                    <form action="{{ route('berita-acara.sign', $ba) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-750 text-white font-bold py-1.5 px-3 rounded transition duration-150 uppercase tracking-widest text-[10px]">
                                            Tanda Tangani Secara Digital
                                        </button>
                                    </form>
                                @endcan
                            @endif
                        </div>

                        <!-- PPK Signature -->
                        <div class="p-4 rounded border dark:border-gray-700 space-y-2 {{ $ba->hasSignatureFrom('PPK') ? 'bg-emerald-50/5 dark:bg-emerald-950/5 border-emerald-500/20' : 'bg-gray-50 dark:bg-slate-900' }}">
                            <div class="flex justify-between font-bold text-gray-700 dark:text-gray-300">
                                <span>Pejabat Pembuat Komitmen (PPK)</span>
                                <span>{{ $ba->hasSignatureFrom('PPK') ? '✅ Signed' : '❌ Unsigned' }}</span>
                            </div>
                            @if($ba->hasSignatureFrom('PPK'))
                                <div class="text-gray-900 dark:text-gray-200 font-semibold">{{ $ba->ppkSignature()->user->nama }}</div>
                                <div class="text-gray-500 dark:text-gray-400">NIP: {{ $ba->ppkSignature()->user->nip }}</div>
                                <div class="text-[10px] text-gray-400">IP: {{ $ba->ppkSignature()->ip_address }} | {{ $ba->ppkSignature()->signed_at->format('d/m/Y H:i') }}</div>
                            @else
                                <div class="text-gray-450 italic">Menunggu tanda tangan Pejabat Pembuat Komitmen.</div>
                                <!-- PPK Sign Button -->
                                @can('signAsPpk', $ba)
                                    <form action="{{ route('berita-acara.sign', $ba) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-750 text-white font-bold py-1.5 px-3 rounded transition duration-150 uppercase tracking-widest text-[10px]">
                                            Tanda Tangani Secara Digital
                                        </button>
                                    </form>
                                @else
                                    @if(Auth::user()->jabatan_aktif === 'PPK' && $ba->status === 'draft')
                                        <p class="text-[10px] text-rose-500 mt-2 font-medium">Tanda tangan terkunci: Menunggu tanda tangan Pejabat Pengadaan (PP) terlebih dahulu.</p>
                                    @elseif(Auth::user()->jabatan_aktif === 'PPK' && $paket->ppk_id === null && $ba->status === 'tanda_tangan_pertama' && !$paket->lampiran()->where('status_validasi', 'disetujui')->exists())
                                        <p class="text-[10px] text-rose-500 mt-2 font-medium">Tanda tangan terkunci: Belum ada minimal satu berkas lampiran dengan status 'disetujui' untuk paket manual ini.</p>
                                    @endif
                                @endcan
                            @endif
                        </div>
                    </div>

                    <!-- PDF & Verification Links for Completed BA -->
                    @if($ba->status === 'selesai')
                        <div class="flex flex-col sm:flex-row gap-3 pt-2 text-xs">
                            <a href="{{ Storage::url($ba->file_laporan) }}" download class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded transition duration-150 uppercase tracking-widest text-[10px]">
                                📥 Unduh Berita Acara (PDF)
                            </a>
                            <a href="{{ route('verify', $ba->verification_hash) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded hover:bg-gray-250 dark:hover:bg-gray-650 transition duration-150 uppercase tracking-widest text-[10px]">
                                🔍 Halaman Verifikasi Publik
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Documents (Lampiran) Section -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Dokumen Lampiran Pengadaan</h3>

                <!-- Upload Form for PPK -->
                @if(Auth::user()->jabatan_aktif === 'PPK' && $paket->ppk_id === Auth::id() && in_array($paket->status, ['draft', 'perlu_revisi']))
                    <form action="{{ route('paket.upload-lampiran', $paket) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 dark:bg-slate-900 p-4 rounded-lg mb-6 text-xs">
                        @csrf
                        <div>
                            <x-input-label for="tipe_dokumen" :value="__('Jenis Dokumen')" class="text-xs" />
                            <select name="tipe_dokumen" class="mt-1 block w-full text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="KAK (Kerangka Acuan Kerja)">KAK (Kerangka Acuan Kerja)</option>
                                <option value="HPS (Harga Perkiraan Sendiri)">HPS (Harga Perkiraan Sendiri)</option>
                                <option value="Spesifikasi Teknis">Spesifikasi Teknis</option>
                                <option value="Rancangan Kontrak">Rancangan Kontrak</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="file_dokumen" :value="__('Unggah Berkas (Maks 10MB)')" class="text-xs" />
                            <input type="file" name="file_dokumen" class="mt-1.5 block w-full text-xs text-gray-600 dark:text-gray-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-gray-700 dark:file:text-indigo-200" required />
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-xs transition duration-150 uppercase tracking-widest">
                                Upload Lampiran
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Lampiran List -->
                @if($paket->lampiran->isEmpty())
                    <p class="text-xs text-gray-500 dark:text-gray-400 p-4 text-center">Belum ada dokumen lampiran diunggah.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                            <thead class="bg-gray-50 dark:bg-slate-900 text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium">Tipe Dokumen</th>
                                    <th class="px-6 py-3 text-left font-medium">Nama File</th>
                                    <th class="px-6 py-3 text-left font-medium">Diunggah Oleh</th>
                                    <th class="px-6 py-3 text-left font-medium">Tanggal</th>
                                    <th class="px-6 py-3 text-center font-medium">Validasi</th>
                                    <th class="px-6 py-3 text-center font-medium">Review & Unduh</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                                @foreach($paket->lampiran as $lampiran)
                                    <tr class="hover:bg-gray-55 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-semibold whitespace-nowrap">{{ $lampiran->tipe_dokumen }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-[11px]">{{ $lampiran->nama_file }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $lampiran->uploader->nama ?? 'Sistem' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $lampiran->created_at->format('d/m/Y, H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $validationClasses = [
                                                    'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
                                                    'revisi' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300',
                                                ];
                                                $valClass = $validationClasses[$lampiran->status_validasi] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-0.5 inline-flex text-[10px] leading-5 font-semibold rounded-full {{ $valClass }}">
                                                {{ ucfirst($lampiran->status_validasi) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center space-y-2">
                                            <!-- Download Link -->
                                            <a href="{{ Storage::url($lampiran->file_path) }}" download class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-250 py-1 px-2.5 rounded text-[10px] hover:bg-gray-200 inline-block font-medium">Unduh File</a>

                                            <!-- PP Review Interface per Document -->
                                            @if(Auth::user()->jabatan_aktif === 'PP' && $paket->pp_id === Auth::id() && in_array($paket->status, ['dikirim', 'kaji_ulang']))
                                                <form action="{{ route('lampiran.review', $lampiran) }}" method="POST" class="bg-gray-50 dark:bg-slate-900 p-2.5 rounded text-left border dark:border-gray-700 space-y-2 mt-2 max-w-xs">
                                                    @csrf
                                                    <span class="text-[10px] font-bold block text-gray-500">TINJAU DOKUMEN:</span>
                                                    <div class="flex space-x-2">
                                                        <label class="inline-flex items-center text-[10px]">
                                                            <input type="radio" name="status_validasi" value="disetujui" class="text-xs text-indigo-600 focus:ring-indigo-500" {{ $lampiran->status_validasi == 'disetujui' ? 'checked' : '' }}>
                                                            <span class="ml-1 text-emerald-600 font-semibold">Setujui</span>
                                                        </label>
                                                        <label class="inline-flex items-center text-[10px]">
                                                            <input type="radio" name="status_validasi" value="revisi" class="text-xs text-indigo-600 focus:ring-indigo-500" {{ $lampiran->status_validasi == 'revisi' ? 'checked' : '' }}>
                                                            <span class="ml-1 text-rose-600 font-semibold">Revisi</span>
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <textarea name="catatan" placeholder="Catatan perbaikan (wajib jika status Dokumen adalah Revisi)" class="w-full text-[10px] p-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded dark:text-gray-300" rows="2"></textarea>
                                                    </div>
                                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1 px-2 rounded text-[10px] transition duration-150">
                                                        Simpan Review File
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Diskusi & Komentar Section -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg space-y-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b dark:border-gray-700 pb-2">Diskusi & Tanya Jawab</h3>

                <!-- Comment List -->
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @if($paket->comments->isEmpty())
                        <p class="text-xs text-gray-500 dark:text-gray-400 py-4 text-center">Belum ada diskusi atau komentar.</p>
                    @else
                        @foreach($paket->comments as $comment)
                            <div class="p-3 bg-gray-50 dark:bg-slate-900 rounded-lg text-xs space-y-1">
                                <div class="flex justify-between items-center text-gray-500 dark:text-gray-400">
                                    <div>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $comment->user->nama }}</span>
                                        <span class="ml-1 px-1.5 py-0.2 uppercase text-[9px] font-bold rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ $comment->role_saat_komentar }}
                                        </span>
                                    </div>
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-800 dark:text-gray-200 leading-relaxed font-medium">
                                    @if($comment->lampiran_id)
                                        <span class="px-1.5 py-0.2 rounded bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400 font-bold mr-1">
                                            [Revisi {{ $comment->lampiran->tipe_dokumen }}]
                                        </span>
                                    @endif
                                    {{ $comment->komentar }}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Add Comment Form -->
                <form action="{{ route('paket.comment', $paket) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <x-input-label for="komentar" :value="__('Kirim Komentar Umum Baru')" class="text-xs" />
                        <textarea id="komentar" name="komentar" rows="3" class="mt-1 block w-full text-xs border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2" required placeholder="Ketik pesan diskusi umum untuk paket ini..."></textarea>
                        <x-input-error :messages="$errors->get('komentar')" class="mt-2" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-gray-800 dark:bg-gray-200 hover:bg-gray-700 dark:hover:bg-white text-white dark:text-gray-800 font-semibold py-1.5 px-4 rounded text-xs transition duration-150">
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Riwayat Mutasi / Transfer Tugas Paket -->
            @php
                $transfers = \App\Models\AssignmentTransfer::where('paket_id', $paket->id)
                    ->with(['dariUser', 'keUser', 'disetujuiOleh'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp
            @if($transfers->isNotEmpty())
                <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b dark:border-gray-700 pb-2">Riwayat Mutasi & Transfer Tugas</h3>
                    <div class="overflow-x-auto text-xs">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dari</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ke</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alasan Pengaju</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan Admin / Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transfers as $t)
                                    <tr class="hover:bg-gray-55 dark:hover:bg-slate-900">
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ $t->created_at->format('d M Y, H:i') }} WIB
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900 dark:text-gray-200">{{ $t->dariUser->nama }}</div>
                                            <div class="text-[10px] text-gray-400">NIP: {{ $t->dariUser->nip }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-gray-900 dark:text-gray-200">{{ $t->keUser->nama }}</div>
                                            <div class="text-[10px] text-gray-400">NIP: {{ $t->keUser->nip }}</div>
                                        </td>
                                        <td class="px-4 py-3 max-w-[200px] truncate text-gray-700 dark:text-gray-300" title="{{ $t->alasan }}">
                                            {{ $t->alasan }}
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            @php
                                                $sClasses = [
                                                    'menunggu' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                                    'disetujui' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                    'ditolak' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $sClasses[$t->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ strtoupper($t->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                            @if($t->status === 'ditolak')
                                                <span class="text-rose-500 italic">Ditolak: {{ $t->catatan_admin }}</span>
                                            @elseif($t->status === 'disetujui')
                                                <span class="text-emerald-500 font-medium">Disetujui oleh {{ $t->disetujuiOleh->nama ?? 'Admin' }}</span>
                                            @else
                                                <span class="text-amber-500 italic">Menunggu keputusan Admin</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Log Aktivitas (Riwayat Paket) -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b dark:border-gray-700 pb-2">Log Aktivitas & Riwayat Paket</h3>
                <div class="relative pl-6 border-l border-gray-200 dark:border-gray-700 space-y-4 text-xs">
                    @if($paket->logs->isEmpty())
                        <p class="text-xs text-gray-500 dark:text-gray-400 py-2">Belum ada riwayat aktivitas log terekam.</p>
                    @else
                        @foreach($paket->logs as $log)
                            <div class="relative">
                                <!-- Bullet -->
                                <div class="absolute -left-8 top-1.5 bg-indigo-600 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-gray-800"></div>
                                <div class="text-gray-500 dark:text-gray-450">
                                    <span class="font-bold text-gray-800 dark:text-gray-250">{{ $log->user->nama ?? 'Sistem' }}</span> 
                                    melakukan aksi <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $log->aksi }}</span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">{{ $log->created_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 mt-1 font-medium">{{ $log->keterangan }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
