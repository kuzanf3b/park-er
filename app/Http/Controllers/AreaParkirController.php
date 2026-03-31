<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Services\LogService;
use Illuminate\Http\Request;

class AreaParkirController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        $areas = AreaParkir::all();
        return view('area-parkir.index', compact('areas'));
    }

    public function create()
    {
        return view('area-parkir.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:100',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $area = AreaParkir::create([
            'nama_area' => $request->nama_area,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'kapasitas' => $request->kapasitas,
            'terisi' => 0,
        ]);

        $this->logService->log(auth()->user()->id_user, 'Menambah area parkir: ' . $area->nama_area);

        return redirect()->route('area-parkir.index')
            ->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $area = AreaParkir::findOrFail($id);
        return view('area-parkir.form', compact('area'));
    }

    public function update(Request $request, int $id)
    {
        $area = AreaParkir::findOrFail($id);

        $request->validate([
            'nama_area' => 'required|string|max:100',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk,bus',
            'kapasitas' => 'required|integer|min:' . $area->terisi,
        ]);

        $area->update($request->only('nama_area', 'jenis_kendaraan', 'kapasitas'));

        $this->logService->log(auth()->user()->id_user, 'Mengubah area parkir: ' . $area->nama_area);

        return redirect()->route('area-parkir.index')
            ->with('success', 'Area parkir berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $area = AreaParkir::findOrFail($id);

        if ($area->terisi > 0) {
            return back()->with('error', 'Tidak dapat menghapus area yang masih terisi kendaraan.');
        }

        $namaArea = $area->nama_area;
        $area->delete();

        $this->logService->log(auth()->user()->id_user, 'Menghapus area parkir: ' . $namaArea);

        return redirect()->route('area-parkir.index')
            ->with('success', 'Area parkir berhasil dihapus.');
    }
}
