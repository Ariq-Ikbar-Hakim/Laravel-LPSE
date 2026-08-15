<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nip' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'opd' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
            'jabatan_aktif' => ['required', 'string', 'in:PPK,PP'],
        ]);

        $user = User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'opd' => $request->opd,
            'no_telp' => $request->no_telp,
            'jabatan_aktif' => $request->jabatan_aktif,
            'status_aktif' => 0, // Pending verifikasi admin
        ]);

        event(new Registered($user));

        // Tidak otomatis login karena status_aktif = 0
        return redirect()->route('register.pending');
    }
}
