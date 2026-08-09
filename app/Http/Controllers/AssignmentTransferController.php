<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paket;
use App\Models\LogPaket;
use App\Models\AssignmentTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AssignmentTransferController extends Controller
{
    /**
     * Menampilkan form pengajuan transfer tugas.
     */
    public function create(Paket $paket): View|RedirectResponse
    {
        $user = Auth::user();

        // Validasi kepemilikan paket
        if ($user->jabatan_aktif === 'PPK') {
            if ($paket->ppk_id !== $user->id) {
                abort(403, 'Anda bukan pemilik paket ini.');
            }
        } elseif ($user->jabatan_aktif === 'PP') {
            if ($paket->pp_id !== $user->id) {
                abort(403, 'Anda bukan pengkaji paket ini.');
            }
        } else {
            abort(403, 'Hanya PPK atau PP yang dapat mengajukan transfer.');
        }

        // Proteksi proses tanda tangan (status disetujui / selesai)
        if (in_array($paket->status, ['disetujui', 'selesai'])) {
            return redirect()->route('paket.show', $paket)->with('error', 'Transfer tidak dapat diajukan karena paket sedang dalam proses tanda tangan berjalan atau telah selesai.');
        }

        // Cek jika ada request menunggu yang sedang aktif
        $hasPending = AssignmentTransfer::where('paket_id', $paket->id)
            ->where('status', 'menunggu')
            ->exists();
        if ($hasPending) {
            return redirect()->route('paket.show', $paket)->with('error', 'Ada pengajuan transfer tugas yang masih tertunda untuk paket ini.');
        }

        // Ambil daftar rekan dengan jabatan yang sama
        $users = User::where('jabatan_aktif', $user->jabatan_aktif)
            ->where('status_aktif', 1)
            ->where('id', '!=', $user->id)
            ->get();

        return view('paket.transfer', compact('paket', 'users'));
    }

    /**
     * Menyimpan pengajuan transfer tugas.
     */
    public function store(Request $request, Paket $paket): RedirectResponse
    {
        $user = Auth::user();

        // Validasi kepemilikan paket
        if ($user->jabatan_aktif === 'PPK') {
            if ($paket->ppk_id !== $user->id) {
                abort(403, 'Anda bukan pemilik paket ini.');
            }
        } elseif ($user->jabatan_aktif === 'PP') {
            if ($paket->pp_id !== $user->id) {
                abort(403, 'Anda bukan pengkaji paket ini.');
            }
        } else {
            abort(403, 'Hanya PPK atau PP yang dapat mengajukan transfer.');
        }

        // Proteksi proses tanda tangan (status disetujui / selesai)
        if (in_array($paket->status, ['disetujui', 'selesai'])) {
            return redirect()->route('paket.show', $paket)->with('error', 'Transfer tidak dapat diajukan karena paket sedang dalam proses tanda tangan berjalan atau telah selesai.');
        }

        // Cek jika ada request menunggu yang sedang aktif
        $hasPending = AssignmentTransfer::where('paket_id', $paket->id)
            ->where('status', 'menunggu')
            ->exists();
        if ($hasPending) {
            return redirect()->route('paket.show', $paket)->with('error', 'Ada pengajuan transfer tugas yang masih tertunda untuk paket ini.');
        }

        // Validasi input
        $request->validate([
            'ke_user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user) {
                    $target = User::find($value);
                    if (!$target || $target->jabatan_aktif !== $user->jabatan_aktif || $target->status_aktif !== 1 || $target->id === $user->id) {
                        $fail('Pejabat tujuan tidak valid, harus memiliki jabatan aktif yang sama dan berstatus aktif.');
                    }
                }
            ],
            'alasan' => ['required', 'string', 'min:10'],
        ]);

        // Simpan pengajuan
        AssignmentTransfer::create([
            'paket_id' => $paket->id,
            'dari_user_id' => $user->id,
            'ke_user_id' => $request->ke_user_id,
            'tipe_transfer' => $user->jabatan_aktif,
            'status' => 'menunggu',
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('paket.show', $paket)->with('success', 'Pengajuan transfer tugas berhasil dikirim dan menunggu persetujuan Admin.');
    }

    /**
     * Halaman manajemen transfer bagi Admin.
     */
    public function indexAdmin(): View
    {
        $transfers = AssignmentTransfer::with(['paket', 'dariUser', 'keUser', 'disetujuiOleh'])
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.transfers.index', compact('transfers'));
    }

    /**
     * Menyetujui pengajuan transfer (DB Transaction & Audit Logging).
     */
    public function approveAdmin(AssignmentTransfer $transfer): RedirectResponse
    {
        if ($transfer->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $paket = $transfer->paket;

        // Proteksi tanda tangan
        if (in_array($paket->status, ['disetujui', 'selesai'])) {
            return redirect()->back()->with('error', 'Transfer tidak dapat disetujui karena paket terkait sedang dalam proses tanda tangan atau telah selesai.');
        }

        DB::transaction(function () use ($transfer, $paket) {
            // Update status transfer
            $transfer->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::id(),
            ]);

            // Ubah kepemilikan/pengkaji paket
            if ($transfer->tipe_transfer === 'PPK') {
                $paket->update(['ppk_id' => $transfer->ke_user_id]);
            } elseif ($transfer->tipe_transfer === 'PP') {
                $paket->update(['pp_id' => $transfer->ke_user_id]);
            }

            // Catat ke LogPaket
            LogPaket::create([
                'paket_id' => $paket->id,
                'user_id' => Auth::id(),
                'aksi' => 'MUTASI_TUGAS',
                'keterangan' => "Kepemilikan paket ditransfer dari {$transfer->dariUser->nama} ke {$transfer->keUser->nama} oleh Admin.",
            ]);
        });

        return redirect()->back()->with('success', 'Pengajuan transfer kepemilikan paket berhasil disetujui.');
    }

    /**
     * Menolak pengajuan transfer.
     */
    public function rejectAdmin(Request $request, AssignmentTransfer $transfer): RedirectResponse
    {
        if ($transfer->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_admin' => ['required', 'string', 'min:5'],
        ]);

        $transfer->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
            'disetujui_oleh' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan transfer kepemilikan paket telah ditolak.');
    }
}
