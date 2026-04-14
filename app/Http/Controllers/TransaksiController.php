<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\User;
use App\Services\ParkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    protected ParkingService $parkingService;

    public function __construct(ParkingService $parkingService)
    {
        $this->parkingService = $parkingService;
    }

    public function kilat(Request $request)
    {
        $registeredPlates = Kendaraan::query()
            ->orderBy('plat_nomor')
            ->pluck('plat_nomor')
            ->map(fn($plate) => $this->normalizePlate((string) $plate))
            ->filter()
            ->values();

        $owners = User::where('role', 'owner')
            ->where('status_aktif', true)
            ->orderBy('nama_lengkap')
            ->get(['id_user', 'nama_lengkap', 'username']);

        $ownerOptions = $owners
            ->map(function (User $owner) {
                return [
                    'id_user' => (int) $owner->id_user,
                    'nama_lengkap' => (string) $owner->nama_lengkap,
                    'username' => (string) $owner->username,
                ];
            })
            ->values();

        $existingKendaraanPlates = Kendaraan::query()
            ->withCount([
                'transaksis as parkir_aktif_count' => function ($query) {
                    $query->where('status', 'masuk');
                },
            ])
            ->get(['id_kendaraan', 'plat_nomor'])
            ->map(function (Kendaraan $kendaraan) {
                return [
                    'id_kendaraan' => (int) $kendaraan->id_kendaraan,
                    'plat_nomor' => $this->normalizePlate((string) $kendaraan->plat_nomor),
                    'sedang_parkir' => ((int) $kendaraan->parkir_aktif_count) > 0,
                ];
            })
            ->values();

        $aktivitas = Transaksi::with(['kendaraan', 'areaParkir'])
            ->orderByRaw("CASE WHEN status = 'masuk' THEN 0 ELSE 1 END")
            ->latest('waktu_masuk')
            ->take(40)
            ->get()
            ->map(function (Transaksi $transaksi) {
                return [
                    'plat_nomor' => $transaksi->kendaraan?->plat_nomor ?? '-',
                    'area' => $transaksi->areaParkir?->nama_area ?? '-',
                    'waktu_masuk' => $transaksi->waktu_masuk?->format('d/m/Y H:i') ?? '-',
                    'waktu_keluar' => $transaksi->waktu_keluar?->format('d/m/Y H:i') ?? '-',
                    'status' => $transaksi->status,
                ];
            });

        return view('transaksi.kilat', compact('registeredPlates', 'aktivitas', 'owners', 'ownerOptions', 'existingKendaraanPlates'));
    }

    public function processKilat(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
        ]);

        $plate = $this->normalizePlate((string) $request->plat_nomor);

        $kendaraan = Kendaraan::where('plat_nomor', $plate)->first();
        if (! $kendaraan) {
            return redirect()->route('operasional.kilat')
                ->withInput()
                ->with('error', 'Plat belum terdaftar. Daftarkan kendaraan dulu.');
        }

        $transaksiAktif = Transaksi::where('id_kendaraan', $kendaraan->id_kendaraan)
            ->where('status', 'masuk')
            ->latest('waktu_masuk')
            ->first();

        try {
            if ($transaksiAktif) {
                $this->parkingService->vehicleExit((int) $transaksiAktif->id_parkir, (int) Auth::id());

                return redirect()->route('operasional.kilat')
                    ->with('success', 'Plat ' . $plate . ' berhasil diproses keluar.');
            }

            $area = AreaParkir::where('jenis_kendaraan', $kendaraan->jenis_kendaraan)
                ->where('terisi', '<', DB::raw('kapasitas'))
                ->orderBy('nama_area')
                ->first();

            if (! $area) {
                return redirect()->route('operasional.kilat')
                    ->withInput()
                    ->with('error', 'Tidak ada area tersedia untuk jenis ' . $kendaraan->jenis_kendaraan . '.');
            }

            $this->parkingService->vehicleEntry([
                'plat_nomor' => $plate,
                'jenis_kendaraan' => $kendaraan->jenis_kendaraan,
                'id_area' => (int) $area->id_area,
                'warna' => $kendaraan->warna,
                'pemilik' => $kendaraan->pemilik,
                'id_user' => (int) $kendaraan->id_user,
            ], (int) Auth::id());

            return redirect()->route('operasional.kilat')
                ->with('success', 'Plat ' . $plate . ' berhasil diproses masuk di area ' . $area->nama_area . '.');
        } catch (\Throwable $e) {
            return redirect()->route('operasional.kilat')
                ->withInput()
                ->with('error', $e->getMessage());
        }
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

        $transaksis = $query
            ->orderByRaw("CASE WHEN status = 'masuk' THEN 0 ELSE 1 END")
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.index', compact('transaksis'));
    }

    public function createMasuk()
    {
        $areas = AreaParkir::where('terisi', '<', DB::raw('kapasitas'))->get();
        $owners = User::where('role', 'owner')
            ->where('status_aktif', true)
            ->orderBy('nama_lengkap')
            ->get();

        return view('transaksi.masuk', compact('areas', 'owners'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'id_area' => 'required|exists:tb_area_parkir,id_area',
            'warna' => 'nullable|string|max:50',
            'pemilik' => 'nullable|string|max:100',
            'id_user' => [
                'required',
                Rule::exists('tb_user', 'id_user')->where(function ($query) {
                    $query->where('role', 'owner')->where('status_aktif', true);
                }),
            ],
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
        $backRoute = Auth::user()?->role === 'petugas' ? 'dashboard' : 'transaksi.index';

        if ($transaksi->status !== 'keluar') {
            return redirect()->route($backRoute);
        }

        $tarifPerJam = (int) $transaksi->tarif->tarif_per_jam;
        $biayaDetail = $this->parkingService->hitungBiaya($transaksi->durasi, $tarifPerJam);

        return view('transaksi.receipt', compact('transaksi', 'biayaDetail', 'backRoute'));
    }

    public function aktifJson(Request $request)
    {
        $transaksiQuery = Transaksi::with(['kendaraan', 'areaParkir', 'tarif'])
            ->orderByRaw("CASE WHEN status = 'masuk' THEN 0 ELSE 1 END")
            ->latest('created_at');

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $transaksiQuery->whereHas('kendaraan', function ($query) use ($search) {
                $query->where('plat_nomor', 'like', "%{$search}%")
                    ->orWhere('pemilik', 'like', "%{$search}%");
            });
        }

        $transaksis = $transaksiQuery
            ->get()
            ->map(function (Transaksi $transaksi) {
                $durasiJam = null;
                $biaya = null;

                if ($transaksi->status === 'masuk') {
                    $durasiMenit = $transaksi->waktu_masuk->diffInMinutes(now());
                    $durasiJam = max(1, (int) ceil($durasiMenit / 60));
                    $estimasi = $this->parkingService->hitungBiaya($durasiJam, (int) $transaksi->tarif->tarif_per_jam);
                    $biaya = (int) $estimasi['total'];
                } else {
                    $durasiJam = $transaksi->durasi;
                    $biaya = $transaksi->biaya_total;
                }

                return [
                    'id_parkir' => $transaksi->id_parkir,
                    'plat_nomor' => $transaksi->kendaraan->plat_nomor,
                    'pemilik' => $transaksi->kendaraan->pemilik,
                    'jenis_kendaraan' => $transaksi->kendaraan->jenis_kendaraan,
                    'area' => $transaksi->areaParkir?->nama_area,
                    'waktu_masuk' => $transaksi->waktu_masuk->format('d/m/Y H:i'),
                    'waktu_keluar' => $transaksi->waktu_keluar?->format('d/m/Y H:i'),
                    'durasi_jam' => $durasiJam,
                    'biaya_total' => $biaya,
                    'status' => $transaksi->status,
                    'receipt_url' => route('transaksi.receipt', $transaksi->id_parkir),
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
            'transaksis' => $transaksis,
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
            'id_user' => [
                'required',
                Rule::exists('tb_user', 'id_user')->where(function ($query) {
                    $query->where('role', 'owner')->where('status_aktif', true);
                }),
            ],
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
                'receipt_url' => route('transaksi.receipt', $result['transaksi']->id_parkir),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function normalizePlate(string $plate): string
    {
        return strtoupper(trim((string) preg_replace('/\s+/', ' ', $plate)));
    }
}
