<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Services\ParkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    public function index(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'tarif', 'areaParkir', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('transaksi.index', compact('transaksis'));
    }

    public function createMasuk()
    {
        $areas = AreaParkir::where('terisi', '<', DB::raw('kapasitas'))->get();
        return view('transaksi.masuk', compact('areas'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'id_area' => 'required|exists:tb_area_parkir,id_area',
            'warna' => 'nullable|string|max:50',
            'pemilik' => 'nullable|string|max:100',
        ]);

        try {
            $this->parkingService->vehicleEntry($request->all(), (int) Auth::id());
            return redirect()->route('transaksi.index')
                ->with('success', 'Kendaraan ' . strtoupper($request->plat_nomor) . ' berhasil masuk.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function showKeluar(int $id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'areaParkir'])->findOrFail($id);

        if ($transaksi->status !== 'masuk') {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi ini sudah selesai.');
        }

        $preview = $this->parkingService->previewBiaya($transaksi);

        return view('transaksi.keluar', compact('transaksi', 'preview'));
    }

    public function processKeluar(int $id)
    {
        try {
            $result = $this->parkingService->vehicleExit($id, (int) Auth::id());
            return redirect()->route('transaksi.receipt', $id)
                ->with('success', 'Kendaraan berhasil keluar.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function receipt(int $id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'areaParkir', 'user'])->findOrFail($id);

        if ($transaksi->status !== 'keluar') {
            return redirect()->route('transaksi.index');
        }

        $tarifPerJam = (int) $transaksi->tarif->tarif_per_jam;
        $biayaDetail = $this->parkingService->hitungBiaya($transaksi->durasi, $tarifPerJam);

        return view('transaksi.receipt', compact('transaksi', 'biayaDetail'));
    }

    public function aktifJson()
    {
        $aktif = Transaksi::aktif()
            ->with(['kendaraan', 'areaParkir', 'tarif'])
            ->latest('waktu_masuk')
            ->get()
            ->map(function (Transaksi $transaksi) {
                $durasiMenit = $transaksi->waktu_masuk->diffInMinutes(now());
                $durasiJam = max(1, (int) ceil($durasiMenit / 60));
                $estimasi = $this->parkingService->hitungBiaya($durasiJam, (int) $transaksi->tarif->tarif_per_jam);

                return [
                    'id_parkir' => $transaksi->id_parkir,
                    'plat_nomor' => $transaksi->kendaraan->plat_nomor,
                    'jenis_kendaraan' => $transaksi->kendaraan->jenis_kendaraan,
                    'area' => $transaksi->areaParkir?->nama_area,
                    'waktu_masuk' => $transaksi->waktu_masuk->format('d/m/Y H:i'),
                    'durasi_jam' => $durasiJam,
                    'estimasi_biaya' => (int) $estimasi['total'],
                ];
            });

        $areas = AreaParkir::where('terisi', '<', DB::raw('kapasitas'))
            ->orderBy('nama_area')
            ->get()
            ->map(fn(AreaParkir $area) => [
                'id_area' => $area->id_area,
                'nama_area' => $area->nama_area,
                'jenis_kendaraan' => $area->jenis_kendaraan,
                'sisa_kapasitas' => $area->sisaKapasitas(),
            ]);

        return response()->json([
            'aktif' => $aktif,
            'areas' => $areas,
        ]);
    }

    public function storeMasukJson(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'id_area' => 'required|exists:tb_area_parkir,id_area',
            'warna' => 'nullable|string|max:50',
            'pemilik' => 'nullable|string|max:100',
        ]);

        try {
            $transaksi = $this->parkingService->vehicleEntry($request->all(), (int) Auth::id());

            return response()->json([
                'message' => 'Kendaraan berhasil masuk.',
                'id_parkir' => $transaksi->id_parkir,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function previewKeluarJson(int $id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'areaParkir'])->findOrFail($id);

        if ($transaksi->status !== 'masuk') {
            return response()->json(['message' => 'Transaksi ini sudah selesai.'], 422);
        }

        $preview = $this->parkingService->previewBiaya($transaksi);

        return response()->json([
            'id_parkir' => $transaksi->id_parkir,
            'plat_nomor' => $transaksi->kendaraan->plat_nomor,
            'durasi' => (int) $preview['durasi'],
            'biaya_normal' => (int) $preview['biaya_normal'],
            'biaya_denda' => (int) $preview['biaya_denda'],
            'total' => (int) $preview['total'],
        ]);
    }

    public function processKeluarJson(int $id)
    {
        try {
            $result = $this->parkingService->vehicleExit($id, (int) Auth::id());

            return response()->json([
                'message' => 'Kendaraan berhasil keluar.',
                'id_parkir' => $result['transaksi']->id_parkir,
                'total' => (int) $result['total'],
                'durasi' => (int) $result['durasi'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
