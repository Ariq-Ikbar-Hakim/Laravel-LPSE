<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return redirect()->back()->with('success', 'Akun ' . $user->nama . ' berhasil disetujui.');
    }

    /**
     * Tolak pendaftaran user baru (Hard Delete sesuai PRD).
     */
    public function reject(User $user): RedirectResponse
    {
        $nama = $user->nama;
        $user->delete(); // Hard delete dari database

        return redirect()->back()->with('success', 'Pendaftaran akun ' . $nama . ' telah ditolak dan data dihapus secara permanen.');
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

            return redirect()->back()->with('success', 'Token reset password berhasil dibuat dan dikirim ke email ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Token berhasil dibuat, namun gagal mengirim email: ' . $e->getMessage());
        }
    }
}
