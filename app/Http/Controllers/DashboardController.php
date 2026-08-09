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
            $data['total_transfers'] = \App\Models\AssignmentTransfer::count();
            
            // List untuk tabel dashboard
            $data['pending_users_list'] = User::where('status_aktif', 0)->latest()->limit(5)->get();
            $data['recent_transfers_list'] = \App\Models\AssignmentTransfer::with(['dariUser', 'keUser', 'paket'])->latest()->limit(5)->get();
            
            // Statistik Status Paket untuk Chart
            $data['chart_status_stats'] = Paket::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        } elseif ($user->jabatan_aktif === 'PPK') {
            $data['total_paket'] = Paket::where('ppk_id', $user->id)->count();
            $data['draft_paket'] = Paket::where('ppk_id', $user->id)->where('status', 'draft')->count();
            $data['perlu_revisi'] = Paket::where('ppk_id', $user->id)->where('status', 'perlu_revisi')->count();
            $data['disetujui'] = Paket::where('ppk_id', $user->id)->where('status', 'disetujui')->count();
            $data['selesai'] = Paket::where('ppk_id', $user->id)->where('status', 'selesai')->count();
            
            // List 5 paket terbaru
            $data['recent_paket_list'] = Paket::where('ppk_id', $user->id)->latest()->limit(5)->get();
            
            // Statistik Metode untuk Chart
            $data['chart_metode_stats'] = Paket::where('ppk_id', $user->id)
                ->selectRaw('metode, count(*) as count')
                ->groupBy('metode')
                ->pluck('count', 'metode')
                ->toArray();
        } elseif ($user->jabatan_aktif === 'PP') {
            $data['total_paket'] = Paket::where('pp_id', $user->id)->where('status', '!=', 'draft')->count();
            $data['dikirim_paket'] = Paket::where('pp_id', $user->id)->where('status', 'dikirim')->count();
            $data['kaji_ulang_paket'] = Paket::where('pp_id', $user->id)->where('status', 'kaji_ulang')->count();
            $data['disetujui'] = Paket::where('pp_id', $user->id)->where('status', 'disetujui')->count();
            $data['selesai'] = Paket::where('pp_id', $user->id)->where('status', 'selesai')->count();
            
            // List tugas aktif (dikirim atau kaji_ulang)
            $data['dikirim_paket_list'] = Paket::where('pp_id', $user->id)
                ->whereIn('status', ['dikirim', 'kaji_ulang'])
                ->latest()
                ->limit(5)
                ->get();
                
            // Statistik Jenis untuk Chart
            $data['chart_jenis_stats'] = Paket::where('pp_id', $user->id)
                ->selectRaw('jenis, count(*) as count')
                ->groupBy('jenis')
                ->pluck('count', 'jenis')
                ->toArray();
        }

        return view('dashboard', compact('data'));
    }
}
