<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceLogoutOnChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $sessionJabatan = session('user_jabatan');
            $currentJabatan = $request->user()->jabatan_aktif;

            if ($sessionJabatan && $sessionJabatan !== $currentJabatan) {
                \Illuminate\Support\Facades\Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'nip' => 'Jabatan Anda telah diubah oleh Admin. Silakan login kembali.',
                ]);
            }

            if (!$sessionJabatan) {
                session(['user_jabatan' => $currentJabatan]);
            }
        }

        return $next($request);
    }
}
