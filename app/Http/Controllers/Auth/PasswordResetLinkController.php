<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'identity' => ['required', 'string'],
        ]);

        $user = \App\Models\User::where('status_aktif', 1)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->identity)
                      ->orWhere('nip', $request->identity);
            })->first();

        if (!$user) {
            return back()->withErrors(['identity' => 'Pengguna dengan NIP atau Email tersebut tidak terdaftar atau belum aktif.']);
        }

        $user->update([
            'reset_requested_at' => now(),
        ]);

        return back()->with([
            'status' => 'success_request',
            'requested_user_nip' => $user->nip,
            'requested_user_nama' => $user->nama,
            'requested_user_email' => $user->email,
        ]);
    }
}
