<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Lampiran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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
            'kode_rup' => ['required', 'string', 'max:50'],
            'nama_paket' => ['required', 'string', 'max:255'],
            'pagu' => ['required', 'numeric', 'min:0'],
            'tahun_anggaran' => ['required', 'string', 'max:4'],
            'metode_pengadaan' => ['nullable', 'string', 'max:255'],
            'sumber_dana' => ['nullable', 'string', 'max:255'],
            'jenis_pengadaan' => ['nullable', 'string', 'max:255'],
            'pp_id' => ['required', 'exists:users,id'],
            'keterangan_tambahan' => ['nullable', 'string'],
        ]);

        $paket = Paket::create([
            'ppk_id' => Auth::id(),
            'kode_rup' => $request->kode_rup,
            'nama_paket' => $request->nama_paket,
            'pagu' => $request->pagu,
            'status' => 'draft',
            'metode' => $request->metode_pengadaan,
            'sumber_dana' => $request->sumber_dana,
            'jenis' => $request->jenis_pengadaan,
            'pp_id' => $request->pp_id,
            'tahun_anggaran' => $request->tahun_anggaran,
            'keterangan_tambahan' => $request->keterangan_tambahan,
        ]);

        return redirect()->route('paket.show', $paket)->with('success', 'Draft paket berhasil dibuat. Silakan unggah dokumen persyaratan.');
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

        $paket->load(['ppk', 'pp', 'lampiran.uploader', 'comments.user', 'comments.lampiran', 'logs.user']);

        return view('paket.show', compact('paket'));
    }

    /**
     * Upload lampiran dokumen.
     */
    public function uploadLampiran(Request $request, Paket $paket)
    {
        Gate::authorize('update', $paket);

        $request->validate([
            'file_dokumen' => ['required', 'file', 'max:10240'], // maks 10MB
            'tipe_dokumen' => ['required', 'string', 'max:100'],
        ]);

        $file = $request->file('file_dokumen');
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();

        // Hitung versi revisi
        $version = Lampiran::where('paket_id', $paket->id)
            ->where('tipe_dokumen', $request->tipe_dokumen)
            ->count() + 1;

        $fileName = "paket_{$paket->id}_{$timestamp}_rev{$version}.{$extension}";
        $filePath = $file->storeAs("lampiran/{$paket->id}", $fileName, 'public');

        Lampiran::create([
            'paket_id' => $paket->id,
            'file_path' => $filePath,
            'nama_file' => $file->getClientOriginalName(),
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
}
