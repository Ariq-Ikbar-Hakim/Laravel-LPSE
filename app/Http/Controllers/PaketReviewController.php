<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewLampiranRequest;
use App\Models\BeritaAcara;
use App\Models\DocumentComment;
use App\Models\Lampiran;
use App\Models\Paket;
use App\Models\Signature;
use App\Services\PdfService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

        return redirect()->back()->with('success', "Status dokumen {$lampiran->tipe_dokumen} diperbarui menjadi: ".ucfirst($request->status_validasi));
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

            // Otomatis buat Berita Acara jika belum ada agar PP bisa langsung tanda tangan
            $beritaAcara = BeritaAcara::where('paket_id', $paket->id)->first();
            if (! $beritaAcara) {
                BeritaAcara::create([
                    'paket_id' => $paket->id,
                    'nomor_ba' => 'BA/'.date('Y/m/d').'/'.$paket->id,
                    'tanggal_ba' => now(),
                    'status' => 'draft',
                    'verification_hash' => Str::random(40),
                ]);
            }
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

        return redirect()->back()->with('success', 'Status review paket berhasil diubah menjadi: '.str_replace('_', ' ', ucfirst($request->status)));
    }

    /**
     * Show form for bypass package creation (PP).
     */
    public function bypassCreate()
    {
        $ppkUsers = \App\Models\User::where('jabatan_aktif', 'PPK')->where('status_aktif', 1)->orderBy('nama')->get();
        return view('paket.bypass', compact('ppkUsers'));
    }

    /**
     * Store bypass package in database (PP).
     */
    public function bypassStore(Request $request)
    {
        $request->validate([
            'ppk_id' => ['required', \Illuminate\Validation\Rule::exists('users', 'id')->where('jabatan_aktif', 'PPK')->where('status_aktif', 1)],
            'kode_rup' => ['required', 'string', 'max:50'],
            'nama_paket' => ['required', 'string', 'max:255'],
            'pagu' => ['required', 'numeric', 'min:0'],
        ]);

        // Buat paket bypass sesuai PRD
        $paket = DB::transaction(function () use ($request) {
            $paket = Paket::create([
                'ppk_id' => $request->ppk_id,
                'pp_id' => Auth::id(),
                'kode_rup' => $request->kode_rup,
                'nama_paket' => $request->nama_paket,
                'pagu' => $request->pagu,
                'status' => 'disetujui', // Otomatis disetujui
                'metode' => 'Manual (Dibuat PP)',
                'sumber_dana' => 'APBD',
                'jenis' => 'Barang/Jasa',
            ]);

            BeritaAcara::create([
                'paket_id' => $paket->id,
                'nomor_ba' => 'BA/'.date('Y/m/d').'/'.$paket->id,
                'tanggal_ba' => now(),
                'status' => 'draft',
                'verification_hash' => Str::random(40),
            ]);

            return $paket;
        });

        return redirect()->route('paket.show', $paket)->with('success', 'Paket Manual (Bypass PP) berhasil dibuat dengan status Disetujui.');
    }

    /**
     * Sign the Berita Acara (PP or PPK).
     */
    public function signBa(Request $request, BeritaAcara $beritaAcara)
    {
        $user = Auth::user();

        $request->validate([
            'signature_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $path = null;
        $pdfPath = null;
        try {
            return DB::transaction(function () use ($request, $user, $beritaAcara, &$path, &$pdfPath) {
                $beritaAcara = BeritaAcara::whereKey($beritaAcara->id)->lockForUpdate()->firstOrFail();

                if ($user->jabatan_aktif === 'PP') {
                    Gate::authorize('signAsPp', $beritaAcara);

                    $path = $request->file('signature_image')->store('signatures', 'public');

                    Signature::create([
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
                    $signaturePpk = Signature::create([
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

                    // 2. Generate PDF Final
                    $timestamp = time();
                    $pdfFileName = "BA_Paket_{$beritaAcara->paket_id}_{$timestamp}.pdf";
                    $pdfPath = "berita-acara/{$pdfFileName}";
                    if (! Storage::disk('public')->exists('berita-acara')) {
                        Storage::disk('public')->makeDirectory('berita-acara');
                    }

                    $paket = $beritaAcara->paket;
                    $signatures = $beritaAcara->signatures;

                    $options = [
                        'margins' => ['top' => 25, 'right' => 20, 'bottom' => 30, 'left' => 20],
                        'footerHtml' => '<div style="font-size: 10px; color: #555; width: 100%; text-align: center; font-family: \'Times New Roman\', Times, serif; padding-left: 20px; padding-right: 20px;"><div style="float: left;">Dokumen ini dihasilkan otomatis oleh Sistem Pengadaan Barang/Jasa</div><div style="float: right;">Halaman <span class="pageNumber"></span></div></div>',
                    ];

                    // Generate PDF using PdfService abstraction (engine configurable)
                    $pdfContent = app(PdfService::class)->generate('pdf.berita_acara', [
                        'beritaAcara' => $beritaAcara,
                        'paket' => $paket,
                        'signatures' => $signatures,
                    ], 'chromium', $options);
                    // Store the generated PDF
                    if (! Storage::disk('public')->put($pdfPath, $pdfContent)) {
                        throw new \RuntimeException('Gagal menyimpan PDF final.');
                    }

                    // 3. Hitung SHA-256 dan simpan di signatures
                    $fileContent = Storage::disk('public')->get($pdfPath);
                    $fileHash = hash('sha256', $fileContent);

                    // Simpan hash ke database
                    $beritaAcara->update(['file_laporan' => $pdfPath]);
                    $beritaAcara->signatures()->update(['hash_dokumen' => $fileHash]);

                    return redirect()->back()->with('success', 'Berita Acara berhasil disahkan (selesai ditandatangani kedua belah pihak) dan PDF final siap diunduh.');
                }

                abort(403, 'Peran jabatan Anda tidak valid untuk menandatangani dokumen ini.');
            });
        } catch (\Throwable $exception) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            if ($pdfPath) {
                Storage::disk('public')->delete($pdfPath);
            }
            if ($exception instanceof AuthorizationException || $exception instanceof HttpExceptionInterface) {
                throw $exception;
            }
            report($exception);

            return redirect()->back()->with('error', 'Tanda tangan belum disimpan karena pembuatan dokumen gagal. Silakan coba kembali atau hubungi admin.');
        }
    }
}
