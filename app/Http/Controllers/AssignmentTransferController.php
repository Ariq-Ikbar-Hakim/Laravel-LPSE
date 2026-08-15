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
     * Menampilkan form pengajuan swap jabatan & wewenang.
     */
    public function create(): View
    {
        $user = Auth::user();

        if (!in_array($user->jabatan_aktif, ['PPK', 'PP'])) {
            abort(403, 'Hanya pejabat PPK atau PP yang dapat mengajukan swap jabatan.');
        }

        // Cek jika ada pengajuan swap yang statusnya masih menunggu
        $pendingSwap = AssignmentTransfer::with('keUser')
            ->where('dari_user_id', $user->id)
            ->where('status', 'menunggu')
            ->first();

        // Ambil daftar rekan aktif dengan peran PPK atau PP (kecuali diri sendiri)
        $users = User::where('status_aktif', 1)
            ->where('id', '!=', $user->id)
            ->whereIn('jabatan_aktif', ['PPK', 'PP'])
            ->get();

        // Hitung total paket aktif saat ini milik pengaju
        $packageCount = ($user->jabatan_aktif === 'PPK')
            ? Paket::where('ppk_id', $user->id)->count()
            : Paket::where('pp_id', $user->id)->count();
        return view('transfers.create', compact('users', 'packageCount', 'pendingSwap'));
    }

    /**
     * Menyimpan pengajuan swap jabatan & wewenang.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!in_array($user->jabatan_aktif, ['PPK', 'PP'])) {
            abort(403, 'Hanya pejabat PPK atau PP yang dapat mengajukan swap jabatan.');
        }

        // Cek jika ada pengajuan swap yang statusnya masih menunggu
        $hasPending = AssignmentTransfer::where('dari_user_id', $user->id)
            ->where('status', 'menunggu')
            ->exists();

        if ($hasPending) {
            return redirect()->route('dashboard')->with('error', 'Anda memiliki pengajuan swap jabatan yang masih tertunda persetujuannya.');
        }

        // Validasi input
        $request->validate([
            'ke_user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user) {
                    $target = User::find($value);
                    if (!$target || $target->status_aktif !== 1 || $target->id === $user->id || !in_array($target->jabatan_aktif, ['PPK', 'PP'])) {
                        $fail('Pejabat tujuan tidak valid.');
                    }
                }
            ],
            'alasan' => ['required', 'string', 'min:10'],
        ]);

        // Simpan pengajuan swap jabatan
        AssignmentTransfer::create([
            'paket_id' => null, // NULL untuk swap jabatan & seluruh paket
            'dari_user_id' => $user->id,
            'ke_user_id' => $request->ke_user_id,
            'tipe_transfer' => $user->jabatan_aktif, // Simpan role awal pengaju
            'status' => 'menunggu',
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan swap jabatan berhasil dikirim. Menunggu persetujuan Admin.');
    }

    /**
     * Halaman manajemen transfer / swap bagi Admin.
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
     * Menyetujui pengajuan swap jabatan (DB Transaction & Assignment Swapping).
     */
    public function approveAdmin(AssignmentTransfer $transfer): RedirectResponse
    {
        if ($transfer->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($transfer) {
            // Update status pengajuan
            $transfer->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::id(),
            ]);

            $userA = User::find($transfer->dari_user_id);
            $userB = User::find($transfer->ke_user_id);

            $roleA = $userA->jabatan_aktif;
            $roleB = $userB->jabatan_aktif;

            if ($roleA !== $roleB) {
                // Case 1: Swap Lintas Peran (PPK <-> PP)
                // Swap user roles
                $userA->update(['jabatan_aktif' => $roleB]);
                $userB->update(['jabatan_aktif' => $roleA]);

                // Swap packages
                $ppkUser = ($roleA === 'PPK') ? $userA : $userB;
                $ppUser = ($roleA === 'PP') ? $userA : $userB;

                $newPpkUser = ($roleA === 'PPK') ? $userB : $userA;
                $newPpUser = ($roleA === 'PP') ? $userB : $userA;

                // Move PPK packages from old PPK to new PPK
                $ppkPackages = Paket::where('ppk_id', $ppkUser->id)->get();
                foreach ($ppkPackages as $paket) {
                    $paket->update(['ppk_id' => $newPpkUser->id]);
                    LogPaket::create([
                        'paket_id' => $paket->id,
                        'user_id' => Auth::id(),
                        'aksi' => 'SWAP_JABATAN',
                        'keterangan' => "Kepemilikan paket dialihkan dari {$ppkUser->nama} ke {$newPpkUser->nama} karena Swap Jabatan lintas peran.",
                    ]);
                }

                // Move PP packages from old PP to new PP
                $ppPackages = Paket::where('pp_id', $ppUser->id)->get();
                foreach ($ppPackages as $paket) {
                    $paket->update(['pp_id' => $newPpUser->id]);
                    LogPaket::create([
                        'paket_id' => $paket->id,
                        'user_id' => Auth::id(),
                        'aksi' => 'SWAP_JABATAN',
                        'keterangan' => "Pemeriksa paket dialihkan dari {$ppUser->nama} ke {$newPpUser->nama} karena Swap Jabatan lintas peran.",
                    ]);
                }
            } else {
                // Case 2: Swap Sesama Peran (PPK <-> PPK atau PP <-> PP)
                if ($roleA === 'PPK') {
                    // Ambil ID paket milik masing-masing user sebelum swap
                    $paketA_Ids = Paket::where('ppk_id', $userA->id)->pluck('id');
                    $paketB_Ids = Paket::where('ppk_id', $userB->id)->pluck('id');

                    // Lakukan pertukaran kepemilikan
                    Paket::whereIn('id', $paketB_Ids)->update(['ppk_id' => $userA->id]);
                    Paket::whereIn('id', $paketA_Ids)->update(['ppk_id' => $userB->id]);

                    // Catat log audit paket milik User A yang dipindah ke User B
                    foreach (Paket::whereIn('id', $paketA_Ids)->get() as $p) {
                        LogPaket::create([
                            'paket_id' => $p->id,
                            'user_id' => Auth::id(),
                            'aksi' => 'SWAP_JABATAN',
                            'keterangan' => "Kepemilikan paket dialihkan dari {$userA->nama} ke {$userB->nama} karena Swap Jabatan sesama peran.",
                        ]);
                    }

                    // Catat log audit paket milik User B yang dipindah ke User A
                    foreach (Paket::whereIn('id', $paketB_Ids)->get() as $p) {
                        LogPaket::create([
                            'paket_id' => $p->id,
                            'user_id' => Auth::id(),
                            'aksi' => 'SWAP_JABATAN',
                            'keterangan' => "Kepemilikan paket dialihkan dari {$userB->nama} ke {$userA->nama} karena Swap Jabatan sesama peran.",
                        ]);
                    }
                } elseif ($roleA === 'PP') {
                    // Ambil ID paket milik masing-masing user sebelum swap
                    $paketA_Ids = Paket::where('pp_id', $userA->id)->pluck('id');
                    $paketB_Ids = Paket::where('pp_id', $userB->id)->pluck('id');

                    // Lakukan pertukaran kepemilikan
                    Paket::whereIn('id', $paketB_Ids)->update(['pp_id' => $userA->id]);
                    Paket::whereIn('id', $paketA_Ids)->update(['pp_id' => $userB->id]);

                    // Catat log audit paket milik User A yang dipindah ke User B
                    foreach (Paket::whereIn('id', $paketA_Ids)->get() as $p) {
                        LogPaket::create([
                            'paket_id' => $p->id,
                            'user_id' => Auth::id(),
                            'aksi' => 'SWAP_JABATAN',
                            'keterangan' => "Pemeriksa paket dialihkan dari {$userA->nama} ke {$userB->nama} karena Swap Jabatan sesama peran.",
                        ]);
                    }

                    // Catat log audit paket milik User B yang dipindah ke User A
                    foreach (Paket::whereIn('id', $paketB_Ids)->get() as $p) {
                        LogPaket::create([
                            'paket_id' => $p->id,
                            'user_id' => Auth::id(),
                            'aksi' => 'SWAP_JABATAN',
                            'keterangan' => "Pemeriksa paket dialihkan dari {$userB->nama} ke {$userA->nama} karena Swap Jabatan sesama peran.",
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Pengajuan swap jabatan berhasil disetujui. Peran dan seluruh paket tugas telah ditukar.');
    }

    /**
     * Menolak pengajuan swap jabatan.
     */
    public function rejectAdmin(Request $request, AssignmentTransfer $transfer): RedirectResponse
    {
        if ($transfer->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_admin' => ['nullable', 'string'],
        ]);

        $transfer->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
            'disetujui_oleh' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan swap jabatan telah ditolak.');
    }
}
