<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === "petugas") {
            $transaksis = Transaksi::with(["kendaraan", "areaParkir", "tarif"])
                ->orderByRaw("CASE WHEN status = 'masuk' THEN 0 ELSE 1 END")
                ->latest("created_at")
                ->paginate(8);

            $existingKendaraanPlates = Kendaraan::query()
                ->withCount([
                    "transaksis as parkir_aktif_count" => function ($query) {
                        $query->where("status", "masuk");
                    },
                ])
                ->get(["id_kendaraan", "plat_nomor"])
                ->map(function (Kendaraan $kendaraan) {
                    return [
                        "id_kendaraan" => (int) $kendaraan->id_kendaraan,
                        "plat_nomor" => strtoupper(
                            trim((string) $kendaraan->plat_nomor),
                        ),
                        "sedang_parkir" =>
                            ((int) $kendaraan->parkir_aktif_count) > 0,
                    ];
                })
                ->values();

            $areas = AreaParkir::where("terisi", "<", DB::raw("kapasitas"))
                ->orderBy("nama_area")
                ->get();

            $owners = User::where("role", "owner")
                ->orderBy("nama_lengkap")
                ->get();

            return view(
                "dashboard.petugas",
                compact(
                    "transaksis",
                    "areas",
                    "owners",
                    "existingKendaraanPlates",
                ),
            );
        }

        if ($user->role === "owner") {
            $ownerId = (int) $user->id_user;
            $tanggalMulai = $request->input(
                "tanggal_mulai",
                now()->startOfMonth()->format("Y-m-d"),
            );
            $tanggalAkhir = $request->input(
                "tanggal_akhir",
                now()->format("Y-m-d"),
            );

            $kendaraans = Kendaraan::where("id_user", $ownerId)
                ->latest("created_at")
                ->get();

            $kendaraanAktif = Transaksi::aktif()
                ->whereHas("kendaraan", function ($query) use ($ownerId) {
                    $query->where("id_user", $ownerId);
                })
                ->with(["kendaraan", "tarif"])
                ->get();

            $history = Transaksi::selesai()
                ->with(["kendaraan", "tarif", "areaParkir"])
                ->whereHas("kendaraan", function ($query) use ($ownerId) {
                    $query->where("id_user", $ownerId);
                })
                ->whereDate("waktu_keluar", ">=", $tanggalMulai)
                ->whereDate("waktu_keluar", "<=", $tanggalAkhir)
                ->latest("waktu_keluar")
                ->paginate(8)
                ->withQueryString();

            $areaParkir = AreaParkir::orderBy("nama_area")->get();

            return view(
                "dashboard.owner",
                compact(
                    "kendaraans",
                    "kendaraanAktif",
                    "history",
                    "areaParkir",
                    "tanggalMulai",
                    "tanggalAkhir",
                ),
            );
        }

        $kendaraanAktif = Transaksi::aktif()->count();

        $pendapatanHariIni = Transaksi::selesai()
            ->whereDate("waktu_keluar", today())
            ->sum("biaya_total");

        $totalTransaksiHariIni = Transaksi::whereDate(
            "created_at",
            today(),
        )->count();

        $areaParkir = AreaParkir::all();

        $pendapatanPerHari = Transaksi::selesai()
            ->where("waktu_keluar", ">=", now()->subDays(7))
            ->select(
                DB::raw("DATE(waktu_keluar) as tanggal"),
                DB::raw("SUM(biaya_total) as total"),
            )
            ->groupBy("tanggal")
            ->orderBy("tanggal")
            ->get();

        $transaksiTerbaru = Transaksi::with(["kendaraan", "areaParkir", "user"])
            ->latest("created_at")
            ->take(10)
            ->get();

        return view(
            "dashboard.index",
            compact(
                "kendaraanAktif",
                "pendapatanHariIni",
                "totalTransaksiHariIni",
                "areaParkir",
                "pendapatanPerHari",
                "transaksiTerbaru",
            ),
        );
    }
}
