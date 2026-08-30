<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Lampiran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource for PPK.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Paket::where('ppk_id', Auth::id());

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $paket = $query->latest()->paginate(10);

        return view('paket.index', compact('paket', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ppUsers = User::where('jabatan_aktif', 'PP')->where('status_aktif', 1)->get();
        return view('paket.create', compact('ppUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pdf_sirup' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'pp_id' => ['required', 'exists:users,id'],
        ]);

        try {
            $pdfPath = $request->file('pdf_sirup')->path();
            $baseImagePath = storage_path('app/temp_sirup_' . time());
            
            // 1. Ekstrak PDF ke gambar menggunakan Ghostscript
            $gsProcess = new Process(['gs', '-dSAFER', '-dBATCH', '-dNOPAUSE', '-r300', '-sDEVICE=png16m', '-sOutputFile=' . $baseImagePath . '_%d.png', $pdfPath]);
            $gsProcess->run();

            if (!$gsProcess->isSuccessful()) {
                throw new \Exception('Gagal mengekstrak gambar dari PDF menggunakan Ghostscript.');
            }

            // 2. Lakukan OCR pada setiap halaman menggunakan Tesseract
            $text = '';
            $images = glob($baseImagePath . '_*.png');
            
            if (empty($images)) {
                throw new \Exception('Tidak ada halaman yang dapat dibaca dari PDF.');
            }

            foreach ($images as $img) {
                $tesseract = new Process(['tesseract', $img, 'stdout', '-l', 'ind+eng']);
                $tesseract->run();
                $text .= $tesseract->getOutput() . "\n";
                @unlink($img); // Bersihkan file temp
            }

            // Hapus spasi berlebih tapi pertahankan newline/tab
            $text = preg_replace('/[ ]{2,}/', ' ', $text);
            
            // Ekstrak Data
            $kode_rup = null;
            if (preg_match('/Kode RUP[\s\t]+([0-9]+)/i', $text, $matches)) {
                $kode_rup = trim($matches[1]);
            }

            $nama_paket = null;
            if (preg_match('/Nama Paket[\s\t]+([^\n]+)/i', $text, $matches)) {
                $nama_paket = trim($matches[1]);
            }

            $pagu = 0;
            if (preg_match('/Total Pagu[\s\t]+Rp\.?[\s\t]*([\d\.]+)/i', $text, $matches)) {
                $paguStr = str_replace('.', '', $matches[1]);
                $pagu = (float) $paguStr;
            }

            $tahun_anggaran = null;
            if (preg_match('/Tahun Anggaran[\s\t]+(\d{4})/i', $text, $matches)) {
                $tahun_anggaran = trim($matches[1]);
            }

            $metode_pengadaan = null;
            if (preg_match('/Metode Pemilihan[\s\t]+([^\n]+)/i', $text, $matches)) {
                $metode_pengadaan = trim($matches[1]);
            }

            $sumber_dana = null;
            if (preg_match('/(APBD|APBN|BLUD)[\s\t]+\d{4}/i', $text, $matches)) {
                $sumber_dana = strtoupper($matches[1]);
            } else if (preg_match('/Sumber Dana[\s\S]{0,100}?(APBD|APBN|BLUD)/i', $text, $matches)) {
                $sumber_dana = strtoupper($matches[1]);
            }

            $jenis_pengadaan = null;
            if (preg_match('/Jenis Pengadaan[\s\t]+([^\n\,]+)/i', $text, $matches)) {
                $jenis_pengadaan = trim($matches[1]);
            }

            // Ekstrak 7 Spesifikasi Berita Acara
            $uraian_pekerjaan = null;
            if (preg_match('/Uraian Pekerjaan[\s\t]+([^\n]+)/i', $text, $matches)) {
                $uraian_pekerjaan = trim($matches[1]);
            }
            
            $spesifikasi_pekerjaan = null;
            if (preg_match('/Spesifikasi Pekerjaan[\s\t]+([^\n]+)/i', $text, $matches)) {
                $spesifikasi_pekerjaan = trim($matches[1]);
            }
            
            $jadwal_pelaksanaan = null;
            if (preg_match('/Jadwal Pelaksanaan Kontrak[\s\t]+Mulai[\s\t]+Akhir[\s\t\n]+([a-z]+[\s\t]+\d{4})[\s\t]+([a-z]+[\s\t]+\d{4})/i', $text, $matches)) {
                $jadwal_pelaksanaan = "Mulai " . trim($matches[1]) . " - Akhir " . trim($matches[2]);
            }
            
            $pemanfaatan = null;
            if (preg_match('/Pemanfaatan Barang\/Jasa[\s\t]+Mulai[\s\t]+Akhir[\s\t\n]+([a-z]+[\s\t]+\d{4})[\s\t]+([a-z]+[\s\t]+\d{4})/i', $text, $matches)) {
                $pemanfaatan = "Mulai " . trim($matches[1]) . " - Akhir " . trim($matches[2]);
            }

            $keterangan_tambahan = json_encode([
                'spesifikasi_teknis' => $spesifikasi_pekerjaan,
                'uraian_pekerjaan' => $uraian_pekerjaan,
                'jadwal_pelaksanaan' => $jadwal_pelaksanaan,
                'waktu_penggunaan' => $pemanfaatan,
                'sumber_data' => 'Otomatis via OCR PDF SIRUP'
            ]);

            $paket = Paket::create([
                'ppk_id' => Auth::id(),
                'kode_rup' => $kode_rup ?? '0000000',
                'nama_paket' => $nama_paket ?? 'Nama Paket Tidak Terbaca',
                'pagu' => $pagu,
                'status' => 'draft',
                'metode' => $metode_pengadaan,
                'sumber_dana' => $sumber_dana,
                'jenis' => $jenis_pengadaan,
                'pp_id' => $request->pp_id,
                'tahun_anggaran' => $tahun_anggaran ?? date('Y'),
                'keterangan_tambahan' => $keterangan_tambahan,
            ]);

            $file = $request->file('pdf_sirup');
            $extension = $file->getClientOriginalExtension();
            $timestamp = time();
            $fileName = "paket_{$paket->id}_{$timestamp}_sirup.{$extension}";
            $filePath = $file->storeAs("lampiran/{$paket->id}", $fileName, 'public');

            Lampiran::create([
                'paket_id' => $paket->id,
                'file_path' => $filePath,
                'nama_file' => 'Dokumen_SIRUP_Upload.pdf',
                'tipe_dokumen' => 'Dokumen Anggaran',
                'uploaded_by' => Auth::id(),
                'status_validasi' => 'pending',
            ]);

            return redirect()->route('paket.show', $paket)->with('success', 'Draft paket berhasil dibuat secara otomatis dari dokumen SIRUP. Silakan unggah dokumen persyaratan lainnya.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses dokumen SIRUP: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Paket $paket)
    {
        Gate::authorize('view', $paket);

        // Jika Admin yang membuka, catat read receipt
        if (Auth::user()->jabatan_aktif === 'admin') {
            $paket->update([
                'dilihat_admin_at' => now(),
            ]);
        }

        $paket->load(['ppk', 'pp', 'lampiran.uploader', 'comments.user', 'comments.lampiran', 'logs.user', 'beritaAcara.signatures.user']);

        return view('paket.show', compact('paket'));
    }

    /**
     * Upload lampiran dokumen.
     */
    public function uploadLampiran(Request $request, Paket $paket)
    {
        Gate::authorize('update', $paket);

        $request->validate([
            'file_dokumen' => [
                'required',
                'file',
                'max:3072', // maks 3MB
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,txt,csv,rtf',
            ],
            'tipe_dokumen' => ['required', 'string', 'max:100'],
        ], [
            'file_dokumen.mimes' => 'Format file tidak diizinkan. Hanya dokumen yang diperbolehkan (PDF, Word, Excel, PowerPoint, dll).',
            'file_dokumen.max'   => 'Ukuran file melebihi batas maksimal 3 MB.',
        ]);

        $file = $request->file('file_dokumen');
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();

        // Cek apakah ada revisi untuk tipe dokumen ini
        $adaRevisi = Lampiran::where('paket_id', $paket->id)
            ->where('tipe_dokumen', $request->tipe_dokumen)
            ->where('status_validasi', 'revisi')
            ->exists();

        if ($adaRevisi) {
            $lampiranRevisiTerakhir = Lampiran::where('paket_id', $paket->id)
                ->where('tipe_dokumen', $request->tipe_dokumen)
                ->where('status_validasi', 'revisi')
                ->latest('id')
                ->first();

            $revisiCount = Lampiran::where('paket_id', $paket->id)
                ->where('tipe_dokumen', $request->tipe_dokumen)
                ->where('status_validasi', 'revisi')
                ->count();

            $versionLabel = 'r' . $revisiCount;
        } else {
            $versionCount = Lampiran::where('paket_id', $paket->id)
                ->where('tipe_dokumen', $request->tipe_dokumen)
                ->count() + 1;
            $versionLabel = 'v' . $versionCount;
        }

        $originalNameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newOriginalName = "{$originalNameWithoutExt}{$versionLabel}.{$extension}";

        $version = Lampiran::where('paket_id', $paket->id)
            ->where('tipe_dokumen', $request->tipe_dokumen)
            ->count() + 1;

        $fileName = "paket_{$paket->id}_{$timestamp}_rev{$version}.{$extension}";
        $filePath = $file->storeAs("lampiran/{$paket->id}", $fileName, 'public');

        Lampiran::create([
            'paket_id' => $paket->id,
            'file_path' => $filePath,
            'nama_file' => $newOriginalName,
            'tipe_dokumen' => $request->tipe_dokumen,
            'uploaded_by' => Auth::id(),
            'status_validasi' => 'pending',
        ]);

        return redirect()->back()->with('success', "Dokumen {$request->tipe_dokumen} berhasil diunggah.");
    }

    /**
     * Submit paket ke PP.
     */
    public function submitPaket(Paket $paket)
    {
        Gate::authorize('update', $paket);

        // Cek minimal 1 lampiran
        if ($paket->lampiran()->count() === 0) {
            return redirect()->back()->with('error', 'Paket kosong tidak dapat dikirim. Silakan unggah minimal satu dokumen persyaratan.');
        }

        // Cek apakah ada dokumen yang direvisi yang belum diunggah ulang
        $dokumenRevisi = $paket->lampiran()->where('status_validasi', 'revisi')->pluck('tipe_dokumen')->unique();
        foreach ($dokumenRevisi as $tipe) {
            $sudahDiunggah = $paket->lampiran()
                ->where('tipe_dokumen', $tipe)
                ->where('status_validasi', 'pending')
                ->exists();
            if (!$sudahDiunggah) {
                return redirect()->back()->with('error', "Anda wajib mengunggah dokumen revisi untuk {$tipe} sebelum mengirim paket ini.");
            }
        }

        // Cari PP secara acak untuk ditugaskan jika belum ada
        if (!$paket->pp_id) {
            $pp = User::where('jabatan_aktif', 'PP')->where('status_aktif', 1)->inRandomOrder()->first();
            if (!$pp) {
                return redirect()->back()->with('error', 'Tidak ada Pejabat Pengadaan (PP) aktif dalam sistem. Silakan hubungi Admin.');
            }
            $paket->pp_id = $pp->id;
        }

        $paket->update([
            'status' => 'dikirim',
        ]);

        return redirect()->route('paket.show', $paket)->with('success', 'Paket berhasil dikirim ke Pejabat Pengadaan untuk direview.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paket $paket)
    {
        Gate::authorize('delete', $paket);

        // Hapus file-file di storage
        foreach ($paket->lampiran as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
        }

        $paket->delete();

        return redirect()->route('paket.index')->with('success', 'Paket draft berhasil dihapus.');
    }

    /**
     * Tampilkan daftar Berita Acara yang terikat dengan PP, PPK, atau Admin.
     */
    public function beritaAcaraIndex(Request $request)
    {
        $user = Auth::user();
        $query = \App\Models\BeritaAcara::with('paket.ppk');

        if ($user->jabatan_aktif === 'PPK') {
            $query->whereHas('paket', function ($q) use ($user) {
                $q->where('ppk_id', $user->id);
            });
        } elseif ($user->jabatan_aktif === 'PP') {
            $query->whereHas('paket', function ($q) use ($user) {
                $q->where('pp_id', $user->id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_ba', 'like', '%' . $search . '%')
                  ->orWhereHas('paket', function ($pq) use ($search) {
                      $pq->where('nama_paket', 'like', '%' . $search . '%');
                  });
            });
        }

        $beritaAcara = $query->latest()->paginate(15);
        $ppkUsers = \App\Models\User::where('jabatan_aktif', 'PPK')->where('status_aktif', 1)->orderBy('nama')->get();
        
        $availablePaket = collect();
        if ($user->jabatan_aktif === 'PP') {
            $availablePaket = \App\Models\Paket::where('pp_id', $user->id)
                ->whereDoesntHave('beritaAcara')
                ->whereIn('status', ['dikirim', 'disetujui', 'proses_ba', 'perlu_revisi'])
                ->orderBy('nama_paket')
                ->get();
        }

        return view('berita-acara.index', compact('beritaAcara', 'ppkUsers', 'availablePaket'));
    }
}
