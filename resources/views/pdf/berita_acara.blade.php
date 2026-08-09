<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara - {{ $beritaAcara->nomor_ba }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333333;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        .nomor-ba {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 25px;
            text-decoration: underline;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 3px;
            text-transform: uppercase;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }
        table.info-table td.label {
            width: 30%;
            color: #666666;
        }
        table.info-table td.value {
            font-weight: bold;
        }
        .pembuka {
            text-align: justify;
            margin-bottom: 20px;
        }
        .signatures-container {
            width: 100%;
            margin-top: 40px;
        }
        .sig-box {
            width: 48%;
            float: left;
            text-align: center;
        }
        .sig-box.right {
            float: right;
        }
        .sig-title {
            font-weight: bold;
            margin-bottom: 60px;
            height: 35px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .sig-nip {
            color: #666666;
            font-size: 11px;
            margin-top: 2px;
        }
        .sig-date {
            font-size: 10px;
            color: #999999;
            margin-top: 4px;
            font-style: italic;
        }
        .clear {
            clear: both;
        }
        .footer {
            margin-top: 60px;
            border-top: 1px solid #dddddd;
            padding-top: 15px;
            font-size: 10px;
            color: #888888;
        }
        .qr-code-box {
            float: left;
            width: 120px;
            margin-right: 15px;
        }
        .verify-text {
            margin-top: 10px;
            line-height: 1.5;
        }
        .verify-hash {
            font-family: monospace;
            font-size: 9px;
            background-color: #f5f5f5;
            padding: 3px;
            border: 1px solid #e0e0e0;
            display: inline-block;
            margin-top: 5px;
            color: #444444;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Layanan Pengadaan Secara Elektronik (LPSE)</h1>
        <h2>Pemerintah Provinsi / Kabupaten / Kota &bull; Biro Pengadaan Barang dan Jasa</h2>
    </div>

    <div class="nomor-ba">
        BERITA ACARA PERSETUJUAN DOKUMEN PENGADAAN<br>
        Nomor: {{ $beritaAcara->nomor_ba }}
    </div>

    <div class="pembuka">
        Pada hari ini, tanggal <strong>{{ now()->format('d F Y') }}</strong>, bertempat di Biro Pengadaan Barang dan Jasa, telah disahkan Dokumen Pengadaan secara elektronik berdasarkan hasil kaji ulang bersama pihak Pejabat Pengadaan (PP) dan Pejabat Pembuat Komitmen (PPK). Pengesahan ini dilakukan secara digital menggunakan mekanisme hash verifikasi internal sistem LPSE dengan data paket sebagai berikut:
    </div>

    <div class="section-title">Informasi Paket Pengadaan</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Paket Pengadaan</td>
            <td>:</td>
            <td class="value">{{ $paket->nama_paket }}</td>
        </tr>
        <tr>
            <td class="label">Kode RUP (Rencana Umum)</td>
            <td>:</td>
            <td class="value font-mono">{{ $paket->kode_rup }}</td>
        </tr>
        <tr>
            <td class="label">Pagu Dana Anggaran</td>
            <td>:</td>
            <td class="value">Rp {{ number_format($paket->pagu, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Metode Pembuatan</td>
            <td>:</td>
            <td class="value">{{ $paket->metode ?? 'Sistem RUP (Online)' }}</td>
        </tr>
        <tr>
            <td class="label">Sumber Dana / Jenis</td>
            <td>:</td>
            <td class="value">
                @if($paket->sumber_dana)
                    {{ $paket->sumber_dana }} / {{ $paket->jenis }}
                @else
                    APBD / Barang dan Jasa
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">Pernyataan Persetujuan</div>
    <div class="pembuka">
        Dengan ditandatanganinya Berita Acara ini secara digital oleh kedua belah pihak di bawah ini, maka Dokumen Pengadaan yang dilampirkan dinyatakan **Sah, Lengkap, dan Valid** untuk dilanjutkan ke proses pengadaan tahap berikutnya.
    </div>

    <div class="signatures-container">
        <!-- PP Signature -->
        <div class="sig-box">
            <div class="sig-title">Pejabat Pengadaan (PP)</div>
            @if($beritaAcara->hasSignatureFrom('PP'))
                <div class="sig-name">{{ $beritaAcara->ppSignature()->user->nama }}</div>
                <div class="sig-nip">NIP: {{ $beritaAcara->ppSignature()->user->nip }}</div>
                <div class="sig-date">Ditandatangani secara digital:<br>{{ $beritaAcara->ppSignature()->signed_at->format('d/m/Y H:i:s') }} WIB</div>
            @else
                <div style="margin-top: 15px; color: #999999; font-style: italic;">(Belum Ditandatangani)</div>
            @endif
        </div>

        <!-- PPK Signature -->
        <div class="sig-box right">
            <div class="sig-title">Pejabat Pembuat Komitmen (PPK)</div>
            @if($beritaAcara->hasSignatureFrom('PPK'))
                <div class="sig-name">{{ $beritaAcara->ppkSignature()->user->nama }}</div>
                <div class="sig-nip">NIP: {{ $beritaAcara->ppkSignature()->user->nip }}</div>
                <div class="sig-date">Ditandatangani secara digital:<br>{{ $beritaAcara->ppkSignature()->signed_at->format('d/m/Y H:i:s') }} WIB</div>
            @else
                <div style="margin-top: 15px; color: #999999; font-style: italic;">(Belum Ditandatangani)</div>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        <div class="qr-code-box">
            @if(file_exists($qrCodePath))
                <img src="{{ $qrCodePath }}" style="width: 100px; height: 100px; display: block;" />
            @endif
        </div>
        <div class="verify-text">
            <strong>Verifikasi Keaslian Dokumen:</strong><br>
            Dokumen ini ditandatangani secara digital dan memiliki QR Code verifikasi. Pindai QR Code di samping untuk memeriksa keabsahan pihak penandatangan dan integritas dokumen secara publik.<br>
            SHA-256 Hash Dokumen:<br>
            <span class="verify-hash">{{ $beritaAcara->signatures()->value('hash_dokumen') ?? 'Belum Terhitung' }}</span>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
