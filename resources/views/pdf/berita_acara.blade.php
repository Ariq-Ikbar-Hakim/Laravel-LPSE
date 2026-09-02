<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara Persetujuan Paket</title>
    <style>
        /* Margins are handled by Browsershot */
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 11pt; 
            line-height: 1.5; 
            color: #000;
        }
        .text-blue { color: #1b365d; }
        
        /* Header */
        .header-table { width: 100%; margin-bottom: 5px; }
        .header-table td { vertical-align: middle; border: none; padding: 0; }
        .header-logo { width: 80px; text-align: center; }
        .header-logo img { max-width: 75px; max-height: 75px; }
        .header-text { padding-left: 15px; }
        .header-title-1 { font-size: 13pt; font-weight: bold; margin: 0; }
        .header-title-2 { font-size: 12pt; font-weight: bold; margin: 0; }
        .header-address { font-size: 8pt; font-style: italic; margin-top: 2px; }
        
        .header-line {
            border-bottom: 3px solid #1b365d;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        /* Document Title */
        .doc-title { text-align: center; margin-bottom: 20px; }
        .doc-title h1 { font-size: 14pt; font-weight: bold; margin: 0 0 5px 0; }
        .doc-title p { margin: 0; font-size: 11pt; }

        /* Content */
        .section-title { font-size: 11pt; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        p { text-align: justify; margin-top: 0; margin-bottom: 10px; }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px 10px; vertical-align: top; }
        .data-table th, .data-table .col-label { width: 35%; font-weight: bold; }
        /* Mencegah row tabel terpotong di tengah yang menyebabkan teks naik ke margin atas di halaman berikutnya */
        .data-table tr { page-break-inside: avoid; }

        .signatures-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .signatures-table td {
            width: 50%;
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .sig-title { font-weight: bold; margin-bottom: 15px; }
        .qr-code { width: 100px; margin: 0 auto 10px auto; text-align: center; }
        .qr-code img,
        .qr-code .qr-placeholder { width: 100px; height: 100px; }
        .qr-placeholder {
            border: 1px dashed #ccc;
            line-height: 100px;
            margin: 0 auto;
            font-size: 8pt;
            color: #999;
        }
        .sig-name { font-weight: bold; text-decoration: underline; margin-top: 5px; }
        .sig-nip { margin-top: 2px; }

        /* Verification Box */
        .verification-box { 
            border: 1px solid #ccc; 
            background-color: #f9f9f9; 
            padding: 15px; 
            text-align: center; 
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .verification-title { font-weight: bold; color: #1b365d; margin-bottom: 15px; }
        .verification-qr { margin: 0 auto 10px auto; }
        .verification-qr img { width: 80px; height: 80px; }
        .verification-desc { font-size: 9pt; font-style: italic; color: #555; }
        
        .footer-note {
            font-size: 9pt;
            font-style: italic;
            color: #777;
            text-align: center;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        
        /* No footer css, browsershot handles it */
    </style>
</head>
<body>
    <main>
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <img src="{{ public_path('assets/logo-dpmd-bangkalan.png') }}" alt="Logo">
                </td>
                <td class="header-text text-blue">
                    <div class="header-title-1">PEMERINTAH KABUPATEN BANGKALAN</div>
                    <div class="header-title-2">DINAS PEMBERDAYAAN MASYARAKAT DAN DESA</div>
                    <div class="header-address">Jl. Halim Perdana Kusuma No. 2, Bangkalan, Kode Pos 69116 | Telp: (031) 3095018 | Email: dpmd@bangkalankab.go.id</div>
                </td>
            </tr>
        </table>
        <div class="header-line"></div>

        <!-- Document Title -->
        <div class="doc-title text-blue">
            <h1>BERITA ACARA PERSETUJUAN PAKET</h1>
            <p style="color: #000;">Nomor: {{ $beritaAcara->nomor_ba ?? 'BA/' . date('Y/m/d') . '/' . ($beritaAcara->id ?? '') }}</p>
        </div>

        <!-- Intro -->
        <p>Berdasarkan Peraturan Presiden Nomor 16 Tahun 2018 tentang Pengadaan Barang/Jasa Pemerintah beserta perubahan dan aturan turunannya, serta memperhatikan hasil kajian dan persiapan pengadaan yang telah dilaksanakan, dengan ini disampaikan hal-hal sebagai berikut:</p>

        <!-- Section I -->
        <div class="section-title text-blue">I. DASAR</div>
        <p>Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::parse($beritaAcara->tanggal_ba ?? now())->translatedFormat('d F Y') }}</strong>, Pejabat Pengadaan (PP) dan Pejabat Pembuat Komitmen (PPK) telah melaksanakan reviu dan menyetujui dokumen persiapan pengadaan paket sebagaimana rincian pada bagian II di bawah ini, sebagai kelanjutan dari proses persiapan pengadaan yang tercantum dalam Rencana Umum Pengadaan (RUP).</p>

        @php
            $ketTambahan = json_decode($paket->keterangan_tambahan, true) ?? [];
            $spesifikasi = $ketTambahan['spesifikasi_teknis'] ?? '-';
            $uraian = $ketTambahan['uraian_pekerjaan'] ?? '-';
            $waktuPenggunaan = $ketTambahan['waktu_penggunaan'] ?? '-';
            $jadwal = $ketTambahan['jadwal_pelaksanaan'] ?? '-';
        @endphp

        <!-- Section II -->
        <div class="section-title text-blue">II. URAIAN PAKET PENGADAAN</div>
        <table class="data-table">
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">Nama Paket</td>
                <td>{{ $paket->nama_paket }}</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">Kode RUP</td>
                <td>{{ $paket->kode_rup }}</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">Tahun Anggaran</td>
                <td>{{ $paket->tahun_anggaran ?? date('Y') }}</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">Pagu</td>
                <td>Rp {{ number_format($paket->pagu, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Section III -->
        <div class="section-title text-blue">III. HASIL REVIU DOKUMEN PERSIAPAN PENGADAAN</div>
        <table class="data-table">
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">1. Spesifikasi Teknis/KAK</td>
                <td>Sesuai. Uraian: {{ $uraian }}, Spesifikasi: {{ $spesifikasi }}</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">2. Review HPS</td>
                <td>Dikecualikan / Tidak berlaku untuk pengadaan ini (HPS tidak tercantum di SIRUP).</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">3. Review Rancangan Kontrak</td>
                <td>Menyesuaikan format standar Pengadaan Langsung.</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">4. Review Dokumen Anggaran</td>
                <td>Sesuai. Pagu tersedia sebesar Rp {{ number_format($paket->pagu, 0, ',', '.') }} bersumber dari {{ $paket->sumber_dana ?? 'APBD' }}.</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">5. Review RUP</td>
                <td>Sesuai. ID Paket RUP: {{ $paket->kode_rup }}, Metode Pemilihan: {{ $paket->metode ?? 'Pengadaan Langsung' }}.</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">6. Review Waktu Penggunaan Barang/Jasa</td>
                <td>Sesuai. Pemanfaatan: {{ $waktuPenggunaan }}, Jadwal Pelaksanaan: {{ $jadwal }}.</td>
            </tr>
            <tr>
                <td class="col-label text-blue" style="background-color: #f0f4f8;">7. Review Analisis Pasar</td>
                <td>Ketersediaan barang/jasa dan pelaku usaha di pasar dinilai memadai.</td>
            </tr>
        </table>

        <!-- Section IV -->
        <div class="section-title text-blue">IV. KESIMPULAN</div>
        <p>Berdasarkan hasil reviu ke-7 (tujuh) aspek di atas, dokumen persiapan pengadaan paket <strong>{{ $paket->nama_paket }}</strong> dinyatakan LENGKAP dan telah memenuhi ketentuan untuk dilanjutkan ke tahap proses pengadaan berikutnya sesuai dengan peraturan perundang-undangan yang berlaku.</p>

        <!-- Section V -->
        <div class="section-title text-blue">V. PENUTUP</div>
        <p>Demikian Berita Acara Persetujuan Paket ini dibuat dengan sebenar-benarnya dalam rangkap secukupnya, untuk dapat dipergunakan sebagaimana mestinya dan ditandatangani secara elektronik menggunakan QR Code oleh para pihak yang berwenang.</p>

        {{-- SIGNATURES --}}
        <table class="signatures-table" style="page-break-inside: avoid;">
            <tr>
                {{-- Pejabat Pengadaan (PP) --}}
                <td>
                    <div class="sig-title">Pejabat Pengadaan (PP)</div>
                    <div class="qr-code">
                        @if(isset($beritaAcara) && $beritaAcara->hasSignatureFrom('PP'))
                            @php
                                $ppUrl = asset('storage/' . $beritaAcara->ppSignature()->signature_image);
                                $qrImagePP = base64_encode(
                                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                                        ->merge(public_path('assets/logo-dpmd-bangkalan.png'), 0.3, true)
                                        ->size(100)
                                        ->errorCorrection('H')
                                        ->generate($ppUrl)
                                );
                            @endphp
                            <img src="data:image/png;base64,{!! $qrImagePP !!}" alt="QR PP">
                        @else
                            <div class="qr-placeholder">[ QR CODE VERIFIKASI ]</div>
                        @endif
                    </div>
                    @if(isset($beritaAcara) && $beritaAcara->hasSignatureFrom('PP'))
                        <div class="sig-name">{{ $beritaAcara->ppSignature()->user->nama }}</div>
                        <div class="sig-nip">NIP. {{ $beritaAcara->ppSignature()->user->nip }}</div>
                    @else
                        <div class="sig-name" style="color: #999;">{{ $paket->pejabatPengadaan->nama ?? '.........................................' }}</div>
                        <div class="sig-nip" style="color: #999;">NIP. {{ $paket->pejabatPengadaan->nip ?? '.........................' }}</div>
                    @endif
                </td>

                {{-- Pejabat Pembuat Komitmen (PPK) --}}
                <td>
                    <div class="sig-title">Pejabat Pembuat Komitmen (PPK)</div>
                    <div class="qr-code">
                        @if(isset($beritaAcara) && $beritaAcara->hasSignatureFrom('PPK'))
                            @php
                                $ppkUrl = asset('storage/' . $beritaAcara->ppkSignature()->signature_image);
                                $qrImagePPK = base64_encode(
                                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                                        ->merge(public_path('assets/logo-dpmd-bangkalan.png'), 0.3, true)
                                        ->size(100)
                                        ->errorCorrection('H')
                                        ->generate($ppkUrl)
                                );
                            @endphp
                            <img src="data:image/png;base64,{!! $qrImagePPK !!}" alt="QR PPK">
                        @else
                            <div class="qr-placeholder">[ QR CODE VERIFIKASI ]</div>
                        @endif
                    </div>
                    @if(isset($beritaAcara) && $beritaAcara->hasSignatureFrom('PPK'))
                        <div class="sig-name">{{ $beritaAcara->ppkSignature()->user->nama }}</div>
                        <div class="sig-nip">NIP. {{ $beritaAcara->ppkSignature()->user->nip }}</div>
                    @else
                        <div class="sig-name" style="color: #999;">{{ $paket->pejabatPembuatKomitmen->nama ?? '.........................................' }}</div>
                        <div class="sig-nip" style="color: #999;">NIP. {{ $paket->pejabatPembuatKomitmen->nip ?? '.........................' }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Verification Box -->
        <div class="verification-box">
            <div class="verification-title">Verifikasi Dokumen</div>
            <div class="verification-qr">
                @php
                    $veriUrl = isset($beritaAcara) ? route('verify', $beritaAcara->verification_hash) : '#';
                    $qrImageVeri = base64_encode(
                        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                            ->merge(public_path('assets/logo-dpmd-bangkalan.png'), 0.3, true)
                            ->size(80)
                            ->errorCorrection('H')
                            ->generate($veriUrl)
                    );
                @endphp
                <img src="data:image/png;base64,{!! $qrImageVeri !!}" alt="QR Verifikasi">
            </div>
            <div class="verification-desc">
                Pindai kode QR ini untuk memverifikasi keaslian dokumen pada sistem: {{ $veriUrl }}
            </div>
        </div>

        <div class="footer-note">
            Dokumen ini sah dan dapat diverifikasi melalui QR Code / tautan verifikasi pada sistem.<br>
            Segala perubahan yang dilakukan tanpa sepengetahuan pihak penerbit dokumen dinyatakan tidak sah dan tidak berlaku.
        </div>
    </main>
</body>
</html>
