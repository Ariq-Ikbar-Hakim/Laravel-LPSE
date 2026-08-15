<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Paket;
use App\Models\BeritaAcara;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar user pending dan aktif.
     */
    public function index(): View
    {
        $pendingUsers = User::where('status_aktif', 0)->get();
        $activeUsers = User::where('status_aktif', 1)
            ->where('id', '!=', auth()->id())
            ->get();

        return view('admin.users.index', compact('pendingUsers', 'activeUsers'));
    }

    /**
     * Setujui pendaftaran user baru.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['status_aktif' => 1]);

        try {
            Mail::to($user->email)->send(new \App\Mail\AccountApprovedNotification($user->nama));
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Akun ' . $user->nama . ' berhasil disetujui, namun gagal mengirim email notifikasi: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Akun ' . $user->nama . ' berhasil disetujui dan email notifikasi telah dikirim.');
    }

    /**
     * Tolak pendaftaran user baru (Hard Delete sesuai PRD).
     */
    public function reject(User $user): RedirectResponse
    {
        $nama = $user->nama;
        $email = $user->email;

        try {
            Mail::to($email)->send(new \App\Mail\AccountRejectedNotification($nama));
        } catch (\Exception $e) {
            // Tetap jalankan penghapusan jika email gagal terkirim
        }

        $user->delete(); // Hard delete dari database

        return redirect()->back()->with('success', 'Pendaftaran akun ' . $nama . ' telah ditolak dan email notifikasi telah dikirim.');
    }

    /**
     * Ubah jabatan/role pengguna dan catat ke riwayat.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'jabatan_aktif' => ['required', 'string', 'in:admin,PPK,PP'],
        ]);

        DB::transaction(function () use ($request, $user) {
            $oldRole = $user->jabatan_aktif;
            $newRole = $request->jabatan_aktif;

            if ($oldRole !== $newRole) {
                $user->update(['jabatan_aktif' => $newRole]);
            }
        });

        return redirect()->back()->with('success', 'Jabatan user ' . $user->nama . ' berhasil diperbarui.');
    }

    /**
     * Generate token reset password (32-byte, expired 24 jam) dan kirim email.
     */
    public function generateResetToken(User $user): RedirectResponse
    {
        // Token acak 32-byte (64 karakter heksadesimal)
        $token = bin2hex(random_bytes(32));

        // Simpan token di tabel password_reset_tokens (re-use tabel bawaan laravel)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Alamat link reset password
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        // Kirim email (menggunakan log driver di local .env)
        try {
            Mail::to($user->email)->send(new \App\Mail\ResetPasswordNotification($user->nama, $resetUrl));

            // Hapus status permintaan reset
            $user->update([
                'reset_requested_at' => null,
            ]);

            return redirect()->back()->with('success', 'Token reset password berhasil dibuat dan dikirim ke email ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Token berhasil dibuat, namun gagal mengirim email: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan daftar paket untuk Admin.
     */
    public function paketIndex(Request $request): View
    {
        $query = Paket::with(['ppk', 'pp']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_paket', 'like', '%' . $search . '%')
                  ->orWhere('kode_rup', 'like', '%' . $search . '%');
            });
        }
        $paket = $query->latest()->paginate(15);
        return view('admin.paket.index', compact('paket'));
    }

    /**
     * Tampilkan daftar berita acara untuk Admin.
     */
    public function beritaAcaraIndex(Request $request): View
    {
        $query = BeritaAcara::with(['paket.ppk', 'paket.pp']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_ba', 'like', '%' . $search . '%')
                  ->orWhereHas('paket', function($pq) use ($search) {
                      $pq->where('nama_paket', 'like', '%' . $search . '%');
                  });
            });
        }
        $beritaAcara = $query->latest()->paginate(15);
        return view('berita-acara.index', compact('beritaAcara'));
    }

    /**
     * Tampilkan halaman verifikasi akun baru (pending).
     */
    public function verificationIndex(): View
    {
        $pendingUsers = User::where('status_aktif', 0)->latest()->get();
        return view('admin.users.verification', compact('pendingUsers'));
    }

    /**
     * Tampilkan halaman reset password pengguna.
     */
    public function resetPasswordIndex(Request $request): View
    {
        $pendingResets = User::where('status_aktif', 1)
            ->whereNotNull('reset_requested_at')
            ->orderBy('reset_requested_at', 'asc')
            ->get();

        $query = User::where('status_aktif', 1)->where('id', '!=', auth()->id());
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users.reset-password', compact('users', 'pendingResets'));
    }
}
