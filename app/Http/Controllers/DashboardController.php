<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard stats.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $data = [];

        if ($user->jabatan_aktif === 'admin') {
            $data['total_users'] = User::where('status_aktif', 1)->count();
            $data['pending_users'] = User::where('status_aktif', 0)->count();
            $data['total_paket'] = Paket::count();
        } elseif ($user->jabatan_aktif === 'PPK') {
            $data['total_paket'] = Paket::where('ppk_id', $user->id)->count();
            $data['draft_paket'] = Paket::where('ppk_id', $user->id)->where('status', 'draft')->count();
            $data['perlu_revisi'] = Paket::where('ppk_id', $user->id)->where('status', 'perlu_revisi')->count();
            $data['disetujui'] = Paket::where('ppk_id', $user->id)->where('status', 'disetujui')->count();
            $data['selesai'] = Paket::where('ppk_id', $user->id)->where('status', 'selesai')->count();
        } elseif ($user->jabatan_aktif === 'PP') {
            $data['total_paket'] = Paket::where('pp_id', $user->id)->where('status', '!=', 'draft')->count();
            $data['dikirim_paket'] = Paket::where('pp_id', $user->id)->where('status', 'dikirim')->count();
            $data['kaji_ulang_paket'] = Paket::where('pp_id', $user->id)->where('status', 'kaji_ulang')->count();
            $data['disetujui'] = Paket::where('pp_id', $user->id)->where('status', 'disetujui')->count();
            $data['selesai'] = Paket::where('pp_id', $user->id)->where('status', 'selesai')->count();
        }

        return view('dashboard', compact('data'));
    }
}
