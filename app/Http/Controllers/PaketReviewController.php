<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Lampiran;
use App\Models\DocumentComment;
use App\Http\Requests\ReviewLampiranRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PaketReviewController extends Controller
{
    /**
     * Display listing of assigned packages for review (PP).
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Paket::where('pp_id', Auth::id())->where('status', '!=', 'draft');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $paket = $query->latest()->paginate(10);

        return view('paket.review-index', compact('paket', 'status'));
    }

    /**
     * Review a specific document lampiran.
     */
    public function reviewLampiran(ReviewLampiranRequest $request, Lampiran $lampiran)
    {
        $paket = $lampiran->paket;
        Gate::authorize('view', $paket);

        // Update status validasi lampiran
        $lampiran->update([
            'status_validasi' => $request->status_validasi,
        ]);

        // Jika ada catatan/komentar, simpan ke document_comments
        if ($request->filled('catatan')) {
            DocumentComment::create([
                'paket_id' => $paket->id,
                'lampiran_id' => $lampiran->id,
                'user_id' => Auth::id(),
                'role_saat_komentar' => Auth::user()->jabatan_aktif,
                'komentar' => $request->catatan,
            ]);
        }

        return redirect()->back()->with('success', "Status dokumen {$lampiran->tipe_dokumen} diperbarui menjadi: " . ucfirst($request->status_validasi));
    }

    /**
     * Update overall package status by PP.
     */
    public function updateStatus(Request $request, Paket $paket)
    {
        Gate::authorize('view', $paket);

        $request->validate([
            'status' => ['required', 'string', 'in:perlu_revisi,disetujui,kaji_ulang,batal'],
            'catatan' => ['nullable', 'string'],
            'revisi_lampiran' => ['nullable', 'array'],
            'revisi_lampiran.*' => ['exists:lampiran,id'],
        ]);

        $paket->update([
            'status' => $request->status,
        ]);

        // Jika disetujui, kita setujui semua lampiran yang masih pending
        if ($request->status === 'disetujui') {
            $paket->lampiran()->where('status_validasi', 'pending')->update(['status_validasi' => 'disetujui']);
        }

        // Jika perlu revisi, update status lampiran yang dipilih dan buat komentar
        if ($request->status === 'perlu_revisi' && $request->filled('revisi_lampiran')) {
            foreach ($request->revisi_lampiran as $lampiranId) {
                $lampiran = Lampiran::find($lampiranId);
                if ($lampiran && $lampiran->paket_id === $paket->id) {
                    $lampiran->update(['status_validasi' => 'revisi']);
                    
                    if ($request->filled('catatan')) {
                        DocumentComment::create([
                            'paket_id' => $paket->id,
                            'lampiran_id' => $lampiran->id,
                            'user_id' => Auth::id(),
                            'role_saat_komentar' => Auth::user()->jabatan_aktif,
                            'komentar' => $request->catatan,
                        ]);
                    }
                }
            }
        } elseif ($request->filled('catatan')) {
            // Komentar umum (tanpa lampiran spesifik)
            DocumentComment::create([
                'paket_id' => $paket->id,
                'lampiran_id' => null,
                'user_id' => Auth::id(),
                'role_saat_komentar' => Auth::user()->jabatan_aktif,
                'komentar' => $request->catatan,
            ]);
        }

        return redirect()->back()->with('success', "Status review paket berhasil diubah menjadi: " . str_replace('_', ' ', ucfirst($request->status)));
    }

    /**
     * Show form for bypass package creation (PP).
     */
    public function bypassCreate()
    {
        return view('paket.bypass');
    }

    /**
     * Store bypass package in database (PP).
     */
    public function bypassStore(Request $request)
    {
        $request->validate([
            'kode_rup' => ['required', 'string', 'max:50'],
            'nama_paket' => ['required', 'string', 'max:255'],
            'pagu' => ['required', 'numeric', 'min:0'],
        ]);

        // Buat paket bypass sesuai PRD
        $paket = Paket::create([
            'ppk_id' => null, // Jalur bypass PP, tanpa PPK
            'pp_id' => Auth::id(),
            'kode_rup' => $request->kode_rup,
            'nama_paket' => $request->nama_paket,
            'pagu' => $request->pagu,
            'status' => 'disetujui', // Otomatis disetujui
            'metode' => 'Manual (Dibuat PP)',
            'sumber_dana' => 'APBD',
            'jenis' => 'Barang/Jasa',
        ]);

        return redirect()->route('paket.show', $paket)->with('success', 'Paket Manual (Bypass PP) berhasil dibuat dengan status Disetujui.');
    }

    /**
     * Sign the Berita Acara (PP or PPK).
     */
    public function signBa(Request $request, \App\Models\BeritaAcara $beritaAcara)
    {
        $user = Auth::user();

        $request->validate([
            'signature_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        if ($user->jabatan_aktif === 'PP') {
            Gate::authorize('signAsPp', $beritaAcara);

            $path = $request->file('signature_image')->store('signatures', 'public');

            \App\Models\Signature::create([
                'berita_acara_id' => $beritaAcara->id,
                'user_id' => $user->id,
                'role_saat_ttd' => 'PP',
                'urutan' => 1,
                'signature_image' => $path,
                'ip_address' => $request->ip(),
                'signed_at' => now(),
            ]);

            $beritaAcara->update([
                'status' => 'tanda_tangan_pertama',
            ]);

            return redirect()->back()->with('success', 'Berita Acara berhasil ditandatangani oleh Pejabat Pengadaan.');
        }

        if ($user->jabatan_aktif === 'PPK') {
            Gate::authorize('signAsPpk', $beritaAcara);

            $path = $request->file('signature_image')->store('signatures', 'public');

            // Simpan tanda tangan PPK
            $signaturePpk = \App\Models\Signature::create([
                'berita_acara_id' => $beritaAcara->id,
                'user_id' => $user->id,
                'role_saat_ttd' => 'PPK',
                'urutan' => 2,
                'signature_image' => $path,
                'ip_address' => $request->ip(),
                'signed_at' => now(),
            ]);

            // Update status BA dan status paket
            $beritaAcara->update([
                'status' => 'selesai',
            ]);

            $beritaAcara->paket->update([
                'status' => 'selesai',
            ]);

            // 1. Generate QR Code
            $qrCodePath = "qrcodes/qr_{$beritaAcara->verification_hash}.svg";
            $verificationUrl = route('verify', $beritaAcara->verification_hash);
            
            // Buat folder qrcodes jika belum ada
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('qrcodes')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('qrcodes');
            }
            
            $qrCodeContent = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($verificationUrl);
            \Illuminate\Support\Facades\Storage::disk('public')->put($qrCodePath, $qrCodeContent);

            // Update path QR Code pada signature
            $signaturePpk->update(['qr_code_path' => $qrCodePath]);
            $beritaAcara->ppSignature()->update(['qr_code_path' => $qrCodePath]);

            // 2. Generate PDF Final
            $pdfPath = "berita-acara/ba_{$beritaAcara->id}.pdf";
            
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('berita-acara')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('berita-acara');
            }

            // Memuat file PDF dengan layout
            $paket = $beritaAcara->paket;
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.berita_acara', [
                'beritaAcara' => $beritaAcara,
                'paket' => $paket,
                'qrCodePath' => storage_path("app/public/{$qrCodePath}"),
            ]);

            \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, $pdf->output());

            // 3. Hitung SHA-256 dan simpan di signatures
            $fileContent = \Illuminate\Support\Facades\Storage::disk('public')->get($pdfPath);
            $fileHash = hash('sha256', $fileContent);

            // Simpan hash ke database
            $beritaAcara->update(['file_laporan' => $pdfPath]);
            $beritaAcara->signatures()->update(['hash_dokumen' => $fileHash]);

            return redirect()->back()->with('success', 'Berita Acara berhasil disahkan (selesai ditandatangani kedua belah pihak) dan PDF final siap diunduh.');
        }

        abort(403, 'Peran jabatan Anda tidak valid untuk menandatangani dokumen ini.');
    }
}
