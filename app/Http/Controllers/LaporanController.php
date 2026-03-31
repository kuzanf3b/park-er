<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->input('tanggal_akhir', now()->format('Y-m-d'));
        $user = Auth::user();
        $isOwner = $user->role === 'owner';
        $ownerId = (int) Auth::id();

        $transaksiBase = Transaksi::selesai();
        if ($isOwner) {
            $transaksiBase->whereHas('kendaraan', function ($query) use ($ownerId) {
                $query->where('id_user', $ownerId);
            });
        }

        $transaksis = (clone $transaksiBase)
            ->with(['kendaraan', 'tarif', 'areaParkir', 'user'])
            ->whereDate('waktu_keluar', '>=', $tanggalMulai)
            ->whereDate('waktu_keluar', '<=', $tanggalAkhir)
            ->latest('waktu_keluar')
            ->paginate(20)
            ->withQueryString();

        $totalPendapatan = (clone $transaksiBase)
            ->whereDate('waktu_keluar', '>=', $tanggalMulai)
            ->whereDate('waktu_keluar', '<=', $tanggalAkhir)
            ->sum('biaya_total');

        $totalTransaksi = (clone $transaksiBase)
            ->whereDate('waktu_keluar', '>=', $tanggalMulai)
            ->whereDate('waktu_keluar', '<=', $tanggalAkhir)
            ->count();

        $pendapatanPerJenis = Transaksi::selesai()
            ->whereDate('waktu_keluar', '>=', $tanggalMulai)
            ->whereDate('waktu_keluar', '<=', $tanggalAkhir)
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->when($isOwner, function ($query) use ($ownerId) {
                $query->where('tb_kendaraan.id_user', $ownerId);
            })
            ->select(
                'tb_kendaraan.jenis_kendaraan',
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(biaya_total) as total')
            )
            ->groupBy('tb_kendaraan.jenis_kendaraan')
            ->get();

        return view('laporan.index', compact(
            'transaksis',
            'totalPendapatan',
            'totalTransaksi',
            'pendapatanPerJenis',
            'isOwner',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }
}
