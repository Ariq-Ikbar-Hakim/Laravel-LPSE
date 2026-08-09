<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Publik Berita Acara LPSE</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col justify-between">

    <div class="py-10 px-4 max-w-4xl mx-auto w-full space-y-6">

        <!-- Top Header -->
        <div class="text-center space-y-2">
            <h1 class="text-2xl md:text-3xl font-extrabold text-indigo-900 tracking-tight">Sistem Verifikasi Publik LPSE</h1>
            <p class="text-sm text-gray-500">Biro Pengadaan Barang dan Jasa Pemerintah &bull; Verifikasi Tanda Tangan Digital Berita Acara</p>
            <div class="h-1 w-20 bg-indigo-600 mx-auto rounded"></div>
        </div>

        <!-- Main Status Banner -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 md:p-8 space-y-6">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 border-gray-100 gap-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1">Berita Acara</span>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800 font-mono">{{ $beritaAcara->nomor_ba }}</h2>
                    </div>
                    <div>
                        @php
                            $statusClasses = [
                                'draft' => 'bg-gray-100 text-gray-800 border-gray-200',
                                'tanda_tangan_pertama' => 'bg-blue-50 text-blue-800 border-blue-200',
                                'selesai' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                            ];
                            $labels = [
                                'draft' => 'Draft (Belum TTD)',
                                'tanda_tangan_pertama' => 'Tanda Tangan Pertama (PP)',
                                'selesai' => 'Selesai Tanda Tangan (Sah)',
                            ];
                            $class = $statusClasses[$beritaAcara->status] ?? 'bg-gray-100 text-gray-800';
                            $label = $labels[$beritaAcara->status] ?? ucfirst($beritaAcara->status);
                        @endphp
                        <span class="px-3 py-1.5 text-xs font-bold rounded-full border {{ $class }}">
                            {{ strtoupper($label) }}
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="space-y-4">
                        <h3 class="font-bold text-indigo-900 text-xs uppercase tracking-widest border-b pb-1">Detail Paket Pengadaan</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-450 block text-[11px] font-bold uppercase">Nama Paket:</span>
                                <span class="font-semibold text-gray-800">{{ $beritaAcara->paket->nama_paket }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block text-[11px] font-bold uppercase">Kode RUP:</span>
                                <span class="font-mono text-gray-700">{{ $beritaAcara->paket->kode_rup }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block text-[11px] font-bold uppercase">Pagu Anggaran:</span>
                                <span class="font-bold text-indigo-700">Rp {{ number_format($beritaAcara->paket->pagu, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h3 class="font-bold text-indigo-900 text-xs uppercase tracking-widest border-b pb-1">Metode & Sumber Dana</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-gray-450 block text-[11px] font-bold uppercase">Metode Pembuatan:</span>
                                <span class="font-semibold text-gray-800">{{ $beritaAcara->paket->metode ?? 'Sistem RUP (Online)' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block text-[11px] font-bold uppercase">Sumber Dana:</span>
                                <span class="font-semibold text-gray-800">{{ $beritaAcara->paket->sumber_dana ?? 'APBD' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-450 block text-[11px] font-bold uppercase">Jenis Pengadaan:</span>
                                <span class="font-semibold text-gray-800">{{ $beritaAcara->paket->jenis ?? 'Barang/Jasa' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signatures Status -->
                <div class="space-y-4 pt-4">
                    <h3 class="font-bold text-indigo-900 text-xs uppercase tracking-widest border-b pb-1">Pihak Penandatangan Digital</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- PP -->
                        <div class="p-4 rounded-xl border flex items-start space-x-3 {{ $beritaAcara->hasSignatureFrom('PP') ? 'bg-emerald-50/30 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="text-lg">
                                {{ $beritaAcara->hasSignatureFrom('PP') ? '✅' : '❌' }}
                            </div>
                            <div class="text-xs space-y-1">
                                <span class="font-bold text-gray-500 uppercase block text-[10px]">Pejabat Pengadaan (PP)</span>
                                @if($beritaAcara->hasSignatureFrom('PP'))
                                    <div class="font-bold text-gray-800 text-sm">{{ $beritaAcara->ppSignature()->user->nama }}</div>
                                    <div>NIP: {{ $beritaAcara->ppSignature()->user->nip }}</div>
                                    <div class="text-[10px] text-gray-400 italic">Signed at: {{ $beritaAcara->ppSignature()->signed_at->format('d M Y, H:i') }} WIB</div>
                                @else
                                    <div class="text-gray-400 italic">Belum menandatangani Berita Acara.</div>
                                @endif
                            </div>
                        </div>

                        <!-- PPK -->
                        <div class="p-4 rounded-xl border flex items-start space-x-3 {{ $beritaAcara->hasSignatureFrom('PPK') ? 'bg-emerald-50/30 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="text-lg">
                                {{ $beritaAcara->hasSignatureFrom('PPK') ? '✅' : '❌' }}
                            </div>
                            <div class="text-xs space-y-1">
                                <span class="font-bold text-gray-500 uppercase block text-[10px]">Pejabat Pembuat Komitmen (PPK)</span>
                                @if($beritaAcara->hasSignatureFrom('PPK'))
                                    <div class="font-bold text-gray-800 text-sm">{{ $beritaAcara->ppkSignature()->user->nama }}</div>
                                    <div>NIP: {{ $beritaAcara->ppkSignature()->user->nip }}</div>
                                    <div class="text-[10px] text-gray-400 italic">Signed at: {{ $beritaAcara->ppkSignature()->signed_at->format('d M Y, H:i') }} WIB</div>
                                @else
                                    <div class="text-gray-400 italic">Belum menandatangani Berita Acara.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Database File Hash Info -->
                @if($beritaAcara->status === 'selesai')
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-2">
                        <span class="text-xs font-bold text-indigo-950 uppercase tracking-widest block">SHA-256 Hash Resmi (Terdaftar di Database):</span>
                        <div class="font-mono text-xs text-indigo-650 bg-indigo-50/40 p-2 rounded border break-all select-all font-semibold">
                            {{ $beritaAcara->signatures()->value('hash_dokumen') }}
                        </div>
                        <p class="text-[10.5px] text-gray-400 leading-normal">
                            Setiap berkas PDF Berita Acara LPSE final yang sah wajib memiliki checksum SHA-256 yang persis sama dengan hash terdaftar di atas. Modifikasi teks atau isi dokumen sekecil apapun akan merubah struktur hash secara total.
                        </p>
                    </div>
                @endif

            </div>
        </div>

        <!-- Wow Factor: File Integrity Matcher (Verify Uploaded PDF) -->
        @if($beritaAcara->status === 'selesai')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 md:p-8 space-y-6">
                <h3 class="font-bold text-indigo-900 text-sm uppercase tracking-widest border-b pb-2">Uji Keabsahan Dokumen Berita Acara (Unggah PDF)</h3>
                
                <!-- Match Results Alert -->
                @if(session('file_verified'))
                    @if(session('is_valid'))
                        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm space-y-1">
                            <div class="font-bold flex items-center">
                                <span class="text-lg mr-2">🟢</span> INTEGRITAS VALID & COCOK!
                            </div>
                            <p class="text-xs">Berkas PDF yang Anda unggah 100% asli. Checksum SHA-256 cocok persis dengan data tanda tangan digital terdaftar.</p>
                        </div>
                    @else
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-sm space-y-1">
                            <div class="font-bold flex items-center">
                                <span class="text-lg mr-2">🔴</span> WARNING: INTEGRITAS TIDAK VALID / MODIFIKASI TERDETEKSI!
                            </div>
                            <p class="text-xs">
                                Berkas PDF yang Anda unggah tidak cocok dengan hash terdaftar di sistem. Hal ini menunjukkan dokumen tersebut bukan versi asli, telah dirubah isinya, atau merupakan dokumen yang berbeda.
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-mono p-3 bg-gray-55 rounded border border-gray-200">
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold font-sans">Hash File yang Diunggah:</span>
                            <span class="break-all font-semibold">{{ session('uploaded_hash') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] font-bold font-sans">Hash Resmi Sistem:</span>
                            <span class="break-all font-semibold">{{ session('registered_hash') }}</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('verify.file', $beritaAcara->verification_hash) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="space-y-2 text-xs">
                        <label class="font-semibold text-gray-700 block">Pilih Berkas PDF Berita Acara Anda:</label>
                        <input type="file" name="uploaded_pdf" accept="application/pdf" required class="block w-full text-xs text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border file:border-indigo-200 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-750 hover:file:bg-indigo-100" />
                    </div>
                    <button type="submit" class="w-full md:w-auto px-5 py-2 bg-indigo-600 hover:bg-indigo-750 text-white text-xs uppercase font-bold tracking-widest rounded shadow transition duration-150">
                        Cocokkan Integritas Berkas
                    </button>
                </form>
            </div>
        @endif

    </div>

    <!-- Footer -->
    <div class="py-6 border-t bg-white text-center text-xs text-gray-400">
        &copy; 2026 Layanan Pengadaan Secara Elektronik (LPSE). All rights reserved.
    </div>

</body>
</html>
