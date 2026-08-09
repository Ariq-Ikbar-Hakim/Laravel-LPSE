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
            'status' => ['required', 'string', 'in:perlu_revisi,disetujui,kaji_ulang'],
        ]);

        $paket->update([
            'status' => $request->status,
        ]);

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
}
