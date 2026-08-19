<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\Paket;
use App\Models\Signature;
use App\Models\LogPaket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaAcaraController extends Controller
{
    /**
     * Store a manually created Berita Acara (PP only).
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'paket_id' => ['nullable', 'exists:paket,id'],
            'ppk_id' => ['required', 'exists:users,id'],
            'nama_paket' => ['required', 'string', 'max:255'],
            'kode_rup' => ['required', 'string', 'max:100', 'unique:paket,kode_rup,' . ($request->paket_id ?? 'NULL')],
            'tahun_anggaran' => ['required', 'string', 'max:4'],
            'pagu' => ['required', 'numeric', 'min:0'],
            'hps' => ['required', 'numeric', 'min:0'],
            'nomor_ba' => ['required', 'string', 'max:255', 'unique:berita_acara,nomor_ba'],
            'tanggal_ba' => ['required', 'date'],
            'signature_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        // Pastikan role-nya adalah PP
        if (Auth::user()->jabatan_aktif !== 'PP') {
            abort(403, 'Hanya Pejabat Pengadaan yang dapat membuat Berita Acara.');
        }

        DB::transaction(function () use ($request) {
            if ($request->filled('paket_id')) {
                // 1a. Gunakan Paket Terdaftar
                $paket = Paket::findOrFail($request->paket_id);
                if ($paket->pp_id !== Auth::id()) {
                    abort(403, 'Anda tidak ditugaskan pada paket ini.');
                }
                if ($paket->beritaAcara()->exists()) {
                    abort(400, 'Paket ini sudah memiliki Berita Acara.');
                }

                // Update detail paket agar sinkron dengan form jika diubah, dan status disetujui
                $paket->update([
                    'ppk_id' => $request->ppk_id,
                    'nama_paket' => $request->nama_paket,
                    'pagu' => $request->pagu,
                    'hps' => $request->hps,
                    'tahun_anggaran' => $request->tahun_anggaran,
                    'status' => 'disetujui',
                ]);
            } else {
                // 1b. Buat Paket Baru secara Manual (Offline)
                $paket = Paket::create([
                    'ppk_id' => $request->ppk_id,
                    'pp_id' => Auth::id(),
                    'kode_rup' => $request->kode_rup,
                    'nama_paket' => $request->nama_paket,
                    'pagu' => $request->pagu,
                    'hps' => $request->hps,
                    'status' => 'disetujui',
                    'metode' => 'Manual',
                    'sumber_dana' => 'APBD',
                    'jenis' => 'Barang/Jasa',
                    'tahun_anggaran' => $request->tahun_anggaran,
                    'keterangan_tambahan' => 'Dibuat secara manual oleh Pejabat Pengadaan.',
                ]);

                // Catat Log DRAFT & DIKIRIM hanya jika membuat baru
                LogPaket::create([
                    'paket_id' => $paket->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'DRAFT',
                    'keterangan' => 'Paket dibuat secara manual oleh PP.',
                ]);

                LogPaket::create([
                    'paket_id' => $paket->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'DIKIRIM',
                    'keterangan' => 'Paket otomatis dikirim ke PP.',
                ]);
            }

            // 2. Buat Berita Acara
            $beritaAcara = BeritaAcara::create([
                'paket_id' => $paket->id,
                'nomor_ba' => $request->nomor_ba,
                'tanggal_ba' => $request->tanggal_ba,
                'status' => 'tanda_tangan_pertama',
                'verification_hash' => Str::random(40),
            ]);

            // 3. Simpan Tanda Tangan Gambar PP
            $path = $request->file('signature_image')->store('signatures', 'public');

            Signature::create([
                'berita_acara_id' => $beritaAcara->id,
                'user_id' => Auth::id(),
                'role_saat_ttd' => 'PP',
                'urutan' => 1,
                'signature_image' => $path,
                'ip_address' => $request->ip(),
                'signed_at' => now(),
            ]);

            // 4. Catat Log SETUJU_PP
            LogPaket::create([
                'paket_id' => $paket->id,
                'user_id' => Auth::id(),
                'aksi' => 'SETUJU_PP',
                'keterangan' => 'Paket disetujui dan Berita Acara ditandatangani oleh PP.',
            ]);
        });

        return redirect()->route('berita-acara.index')->with('success', 'Berita Acara berhasil dibuat dan dilanjutkan ke PPK.');
    }

    /**
     * Update a manual Berita Acara (PP only).
     */
    public function updateManual(Request $request, BeritaAcara $beritaAcara)
    {
        // Pastikan role-nya adalah PP dan dia adalah yang ditugaskan ke BA ini
        if (Auth::user()->jabatan_aktif !== 'PP' || $beritaAcara->paket->pp_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit Berita Acara ini.');
        }

        // Hanya bisa diedit jika status masih tanda_tangan_pertama (belum ditandatangani PPK)
        if ($beritaAcara->status !== 'tanda_tangan_pertama') {
            return redirect()->back()->with('error', 'Berita Acara tidak dapat diedit karena sudah ditandatangani oleh PPK.');
        }

        $request->validate([
            'ppk_id' => ['required', 'exists:users,id'],
            'nama_paket' => ['required', 'string', 'max:255'],
            'kode_rup' => ['required', 'string', 'max:100', 'unique:paket,kode_rup,' . $beritaAcara->paket_id],
            'tahun_anggaran' => ['required', 'string', 'max:4'],
            'pagu' => ['required', 'numeric', 'min:0'],
            'hps' => ['required', 'numeric', 'min:0'],
            'nomor_ba' => ['required', 'string', 'max:255', 'unique:berita_acara,nomor_ba,' . $beritaAcara->id],
            'tanggal_ba' => ['required', 'date'],
            'signature_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        DB::transaction(function () use ($request, $beritaAcara) {
            // 1. Update Paket
            $beritaAcara->paket->update([
                'ppk_id' => $request->ppk_id,
                'kode_rup' => $request->kode_rup,
                'nama_paket' => $request->nama_paket,
                'pagu' => $request->pagu,
                'hps' => $request->hps,
                'tahun_anggaran' => $request->tahun_anggaran,
            ]);

            // 2. Update Berita Acara
            $beritaAcara->update([
                'nomor_ba' => $request->nomor_ba,
                'tanggal_ba' => $request->tanggal_ba,
            ]);

            // 3. Jika ada signature_image baru, ganti yang lama
            if ($request->hasFile('signature_image')) {
                $ppSig = $beritaAcara->ppSignature();
                if ($ppSig) {
                    if ($ppSig->signature_image) {
                        Storage::disk('public')->delete($ppSig->signature_image);
                    }
                    $path = $request->file('signature_image')->store('signatures', 'public');
                    $ppSig->update([
                        'signature_image' => $path,
                        'ip_address' => $request->ip(),
                        'signed_at' => now(),
                    ]);
                }
            }

            // 4. Catat Log Aktivitas
            LogPaket::create([
                'paket_id' => $beritaAcara->paket_id,
                'user_id' => Auth::id(),
                'aksi' => 'EDIT',
                'keterangan' => 'Detail Berita Acara diperbarui oleh PP.',
            ]);
        });

        return redirect()->route('berita-acara.index')->with('success', 'Berita Acara berhasil diperbarui.');
    }

    /**
     * Delete a manual Berita Acara (PP only).
     */
    public function destroyManual(BeritaAcara $beritaAcara)
    {
        // Pastikan role-nya adalah PP dan dia adalah yang ditugaskan ke BA ini
        if (Auth::user()->jabatan_aktif !== 'PP' || $beritaAcara->paket->pp_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus Berita Acara ini.');
        }

        // Hanya bisa dihapus jika status masih tanda_tangan_pertama (belum ditandatangani PPK)
        if ($beritaAcara->status !== 'tanda_tangan_pertama') {
            return redirect()->back()->with('error', 'Berita Acara tidak dapat dihapus karena sudah ditandatangani oleh PPK.');
        }

        DB::transaction(function () use ($beritaAcara) {
            // Hapus file signature image jika ada
            foreach ($beritaAcara->signatures as $signature) {
                if ($signature->signature_image) {
                    Storage::disk('public')->delete($signature->signature_image);
                }
            }

            // Hapus QR Code jika ada
            $qrCodePath = "qrcodes/qr_{$beritaAcara->verification_hash}.svg";
            if (Storage::disk('public')->exists($qrCodePath)) {
                Storage::disk('public')->delete($qrCodePath);
            }

            // Hapus berkas PDF laporan jika ada
            if ($beritaAcara->file_laporan && Storage::disk('public')->exists($beritaAcara->file_laporan)) {
                Storage::disk('public')->delete($beritaAcara->file_laporan);
            }

            $paket = $beritaAcara->paket;
            $beritaAcara->delete();

            if ($paket->metode === 'Manual') {
                // Hapus paket manual sepenuhnya beserta lognya
                LogPaket::where('paket_id', $paket->id)->delete();
                $paket->delete();
            } else {
                // Jika paket terhubung dari sistem, kembalikan statusnya ke 'dikirim' (menunggu persetujuan ulang)
                $paket->update([
                    'status' => 'dikirim',
                ]);
                
                LogPaket::create([
                    'paket_id' => $paket->id,
                    'user_id' => Auth::id(),
                    'aksi' => 'REVISI',
                    'keterangan' => 'Berita Acara dibatalkan oleh PP, status paket dikembalikan ke dikirim.',
                ]);
            }
        });

        return redirect()->route('berita-acara.index')->with('success', 'Berita Acara berhasil dihapus.');
    }
}
