<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $query = Kendaraan::with('user');

        if (Auth::user()?->role === 'owner') {
            $query->where('id_user', (int) Auth::id());
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                    ->orWhere('pemilik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_kendaraan', $request->jenis);
        }

        $kendaraans = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        $owners = User::where('role', 'owner')
            ->where('status_aktif', true)
            ->orderBy('nama_lengkap')
            ->get();

        return view('kendaraan.form', compact('owners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:tb_kendaraan,plat_nomor',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'warna' => 'nullable|string|max:50',
            'pemilik' => 'nullable|string|max:100',
            'id_user' => [
                'required',
                Rule::exists('tb_user', 'id_user')->where(function ($query) {
                    $query->where('role', 'owner');
                }),
            ],
        ]);

        $owner = User::findOrFail($request->id_user);

        $kendaraan = Kendaraan::create([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna ?? '-',
            'pemilik' => $request->pemilik ?: $owner->nama_lengkap,
            'id_user' => $owner->id_user,
        ]);

        $this->logService->log((int) Auth::id(), 'Mendaftarkan kendaraan: ' . $kendaraan->plat_nomor);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil didaftarkan.');
    }

    public function edit(int $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $owners = User::where('role', 'owner')
            ->where('status_aktif', true)
            ->orderBy('nama_lengkap')
            ->get();

        return view('kendaraan.form', compact('kendaraan', 'owners'));
    }

    public function update(Request $request, int $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:tb_kendaraan,plat_nomor,' . $id . ',id_kendaraan',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'warna' => 'nullable|string|max:50',
            'pemilik' => 'nullable|string|max:100',
            'id_user' => [
                'required',
                Rule::exists('tb_user', 'id_user')->where(function ($query) {
                    $query->where('role', 'owner');
                }),
            ],
        ]);

        $owner = User::findOrFail($request->id_user);

        $kendaraan->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'pemilik' => $request->pemilik ?: $owner->nama_lengkap,
            'id_user' => $owner->id_user,
        ]);

        $this->logService->log((int) Auth::id(), 'Mengubah kendaraan: ' . $kendaraan->plat_nomor);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        if ($kendaraan->isSedangParkir()) {
            return back()->with('error', 'Tidak dapat menghapus kendaraan yang sedang parkir.');
        }

        $platNomor = $kendaraan->plat_nomor;
        $kendaraan->delete();

        $this->logService->log((int) Auth::id(), 'Menghapus kendaraan: ' . $platNomor);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
