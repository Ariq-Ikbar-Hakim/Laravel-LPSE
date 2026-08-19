<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara - {{ $beritaAcara->nomor_ba }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.5;
            margin: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11px;
            margin: 3px 0 0 0;
            font-weight: normal;
        }
        .nomor-ba {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin-top: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 3px;
            text-transform: uppercase;
            color: #1e3a8a;
        }
        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.info-table td {
            padding: 4px 2px;
            vertical-align: top;
        }
        table.info-table td.label {
            width: 30%;
            color: #555555;
        }
        table.info-table td.value {
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cccccc;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        table.data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
        .pembuka {
            text-align: justify;
            margin-bottom: 12px;
        }
        .signatures-container {
            width: 100%;
            margin-top: 25px;
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
            margin-bottom: 5px;
            height: 25px;
        }
        .sig-image-container {
            height: 70px;
            display: block;
            margin: 5px auto;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .sig-nip {
            color: #555555;
            font-size: 9px;
            margin-top: 1px;
        }
        .sig-date {
            font-size: 8px;
            color: #777777;
            margin-top: 2px;
            font-style: italic;
        }
        .clear {
            clear: both;
        }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #dddddd;
            padding-top: 10px;
            font-size: 9px;
            color: #666666;
        }
        .qr-code-box {
            float: left;
            width: 80px;
            margin-right: 15px;
        }
        .verify-text {
            margin-top: 5px;
            line-height: 1.4;
        }
        .verify-hash {
            font-family: monospace;
            font-size: 8px;
            background-color: #f9fafb;
            padding: 2px 4px;
            border: 1px solid #e5e7eb;
            display: inline-block;
            margin-top: 3px;
            color: #374151;
        }
        .status-badge {
            font-size: 8px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
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
        Pada hari ini, tanggal <strong>{{ $beritaAcara->tanggal_ba ? $beritaAcara->tanggal_ba->format('d F Y') : ($beritaAcara->created_at ? $beritaAcara->created_at->format('d F Y') : now()->format('d F Y')) }}</strong>, bertempat di Biro Pengadaan Barang dan Jasa, telah disahkan Dokumen Pengadaan secara elektronik berdasarkan hasil kaji ulang bersama pihak Pejabat Pengadaan (PP) dan Pejabat Pembuat Komitmen (PPK). Pengesahan ini dilakukan secara digital menggunakan mekanisme hash verifikasi internal sistem LPSE dengan data paket sebagai berikut:
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
            <td class="label">Tahun Anggaran</td>
            <td>:</td>
            <td class="value">{{ $paket->tahun_anggaran ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pagu Dana Anggaran</td>
            <td>:</td>
            <td class="value">Rp {{ number_format($paket->pagu, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Nilai HPS Pekerjaan</td>
            <td>:</td>
            <td class="value">Rp {{ number_format($paket->hps ?? 0, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Metode & Jenis Pengadaan</td>
            <td>:</td>
            <td class="value">
                {{ $paket->metode ?? 'Sistem RUP (Online)' }} / {{ $paket->jenis ?? 'Barang dan Jasa' }}
            </td>
        </tr>
        <tr>
            <td class="label">Sumber Dana</td>
            <td>:</td>
            <td class="value">{{ $paket->sumber_dana ?? 'APBD' }}</td>
        </tr>
    </table>

    <div class="section-title font-bold">Daftar Lampiran Dokumen</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 45%;">Nama Dokumen</th>
                <th style="width: 25%;">Tipe Dokumen</th>
                <th style="width: 25%; text-align: center;">Status Validasi</th>
            </tr>
        </thead>
        <tbody>
            @if($paket->lampiran->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center; color: #888888; font-style: italic;">Tidak ada lampiran dokumen terlampir pada paket ini.</td>
                </tr>
            @else
                @foreach($paket->lampiran as $index => $lampiran)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $lampiran->nama_file }}</td>
                        <td>{{ $lampiran->tipe_dokumen }}</td>
                        <td style="text-align: center;">
                            @php
                                $badgeClass = match($lampiran->status_validasi) {
                                    'disetujui' => 'status-approved',
                                    'perlu_revisi' => 'status-rejected',
                                    default => 'status-pending',
                                };
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">{{ $lampiran->status_validasi }}</span>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="section-title">Riwayat Log Aktivitas Paket</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Waktu Aktivitas</th>
                <th style="width: 25%;">Oleh Pengguna</th>
                <th style="width: 15%;">Aksi</th>
                <th style="width: 40%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if($paket->logs->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center; color: #888888; font-style: italic;">Tidak ada riwayat aktivitas yang tercatat.</td>
                </tr>
            @else
                @foreach($paket->logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }} WIB</td>
                        <td>{{ $log->user->nama ?? '-' }} ({{ strtoupper($log->user->jabatan_aktif ?? '-') }})</td>
                        <td><span style="font-weight: bold; color: #1e3a8a;">{{ $log->aksi }}</span></td>
                        <td>{{ $log->keterangan }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="signatures-container">
        <!-- PP Signature -->
        <div class="sig-box">
            <div class="sig-title">Pejabat Pengadaan (PP)</div>
            @if($beritaAcara->hasSignatureFrom('PP'))
                <div class="sig-image-container">
                    @if(file_exists(storage_path("app/public/{$qrCodePath}")))
                        <img src="{{ storage_path("app/public/{$qrCodePath}") }}" style="width: 70px; height: 70px; display: block; margin: 0 auto;" />
                    @elseif(file_exists($qrCodePath))
                        <img src="{{ $qrCodePath }}" style="width: 70px; height: 70px; display: block; margin: 0 auto;" />
                    @else
                        <div style="height: 70px; line-height: 70px; color: #999999; font-style: italic; border: 1px dashed #cccccc; border-radius: 8px;">(Tanda Tangan Digital)</div>
                    @endif
                </div>
                <div class="sig-name">{{ $beritaAcara->ppSignature()->user->nama }}</div>
                <div class="sig-nip">NIP: {{ $beritaAcara->ppSignature()->user->nip }}</div>
                <div class="sig-date">Ditandatangani secara digital:<br>{{ $beritaAcara->ppSignature()->signed_at->format('d/m/Y H:i:s') }} WIB</div>
            @else
                <div class="sig-image-container" style="line-height: 70px; color: #999999; font-style: italic;">(Belum Ditandatangani)</div>
                <div class="sig-name" style="color: #999999;">-</div>
                <div class="sig-nip" style="color: #999999;">NIP: -</div>
            @endif
        </div>

        <!-- PPK Signature -->
        <div class="sig-box right">
            <div class="sig-title">Pejabat Pembuat Komitmen (PPK)</div>
            @if($beritaAcara->hasSignatureFrom('PPK'))
                <div class="sig-image-container">
                    @if(file_exists(storage_path("app/public/{$qrCodePath}")))
                        <img src="{{ storage_path("app/public/{$qrCodePath}") }}" style="width: 70px; height: 70px; display: block; margin: 0 auto;" />
                    @elseif(file_exists($qrCodePath))
                        <img src="{{ $qrCodePath }}" style="width: 70px; height: 70px; display: block; margin: 0 auto;" />
                    @else
                        <div style="height: 70px; line-height: 70px; color: #999999; font-style: italic; border: 1px dashed #cccccc; border-radius: 8px;">(Tanda Tangan Digital)</div>
                    @endif
                </div>
                <div class="sig-name">{{ $beritaAcara->ppkSignature()->user->nama }}</div>
                <div class="sig-nip">NIP: {{ $beritaAcara->ppkSignature()->user->nip }}</div>
                <div class="sig-date">Ditandatangani secara digital:<br>{{ $beritaAcara->ppkSignature()->signed_at->format('d/m/Y H:i:s') }} WIB</div>
            @else
                <div class="sig-image-container" style="line-height: 70px; color: #999999; font-style: italic;">(Belum Ditandatangani)</div>
                <div class="sig-name" style="color: #999999;">-</div>
                <div class="sig-nip" style="color: #999999;">NIP: -</div>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        <div class="qr-code-box">
            @if(file_exists(storage_path("app/public/{$qrCodePath}")))
                <img src="{{ storage_path("app/public/{$qrCodePath}") }}" style="width: 70px; height: 70px; display: block;" />
            @elseif(file_exists($qrCodePath))
                <img src="{{ $qrCodePath }}" style="width: 70px; height: 70px; display: block;" />
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
