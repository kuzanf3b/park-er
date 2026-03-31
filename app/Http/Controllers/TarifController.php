<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Services\LogService;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        $tarifs = Tarif::all();
        return view('tarif.index', compact('tarifs'));
    }

    public function edit(int $id)
    {
        $tarif = Tarif::findOrFail($id);
        return view('tarif.form', compact('tarif'));
    }

    public function update(Request $request, int $id)
    {
        $tarif = Tarif::findOrFail($id);

        $request->validate([
            'tarif_per_jam' => 'required|integer|min:0',
        ]);

        $tarif->update(['tarif_per_jam' => $request->tarif_per_jam]);

        $this->logService->log(auth()->user()->id_user, 'Mengubah tarif ' . $tarif->jenis_kendaraan . ': Rp ' . number_format($tarif->tarif_per_jam, 0, ',', '.'));

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif berhasil diperbarui.');
    }
}
