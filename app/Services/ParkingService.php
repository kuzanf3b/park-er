<?php

namespace App\Services;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ParkingService
{
    protected LogService $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Proses kendaraan masuk
     */
    public function vehicleEntry(array $data, int $userId): Transaksi
    {
        return DB::transaction(function () use ($data, $userId) {
            // Cari atau buat kendaraan
            $kendaraan = Kendaraan::firstOrCreate(
                ['plat_nomor' => strtoupper($data['plat_nomor'])],
                [
                    'jenis_kendaraan' => $data['jenis_kendaraan'],
                    'warna' => $data['warna'] ?? '-',
                    'pemilik' => $data['pemilik'] ?? '-',
                    'id_user' => (int) ($data['id_user'] ?? $userId),
                ]
            );

            // Cek apakah kendaraan sedang parkir
            if ($kendaraan->isSedangParkir()) {
                throw new \Exception('Kendaraan dengan plat ' . $kendaraan->plat_nomor . ' masih di area parkir.');
            }

            // Cari area parkir yang sesuai (menggunakan Pessimistic Locking untuk mencegah race condition)
            $area = AreaParkir::lockForUpdate()->find($data['id_area']);
            if (!$area) {
                throw new \Exception('Area parkir tidak ditemukan.');
            }

            // Validasi kapasitas
            if ($area->isFull()) {
                throw new \Exception('Area parkir ' . $area->nama_area . ' sudah penuh.');
            }

            // Validasi jenis kendaraan sesuai area
            if ($area->jenis_kendaraan !== $data['jenis_kendaraan']) {
                throw new \Exception('Jenis kendaraan tidak sesuai dengan area parkir ini.');
            }

            // Ambil tarif
            $tarif = Tarif::where('jenis_kendaraan', $data['jenis_kendaraan'])->first();
            if (!$tarif) {
                throw new \Exception('Tarif untuk jenis kendaraan ini belum diatur.');
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'id_kendaraan' => $kendaraan->id_kendaraan,
                'waktu_masuk' => now(),
                'id_tarif' => $tarif->id_tarif,
                'status' => 'masuk',
                'id_user' => $userId,
                'id_area' => $area->id_area,
            ]);

            // Update kapasitas area
            $area->increment('terisi');

            // Log aktivitas
            $this->logService->log($userId, 'Kendaraan masuk: ' . $kendaraan->plat_nomor);

            return $transaksi;
        });
    }

    /**
     * Proses kendaraan keluar
     */
    public function vehicleExit(int $transaksiId, int $userId): array
    {
        return DB::transaction(function () use ($transaksiId, $userId) {
            $transaksi = Transaksi::with(['kendaraan', 'tarif', 'areaParkir'])->findOrFail($transaksiId);

            if ($transaksi->status !== 'masuk') {
                throw new \Exception('Transaksi ini sudah selesai.');
            }

            $waktuKeluar = now();
            $waktuMasuk = $transaksi->waktu_masuk;

            // Hitung durasi (pembulatan ke atas dengan grace period 5 menit)
            $durasiMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
            $durasiToleransi = max(0, $durasiMenit - 5);
            $durasi = (int) ceil($durasiToleransi / 60);
            if ($durasi < 1) $durasi = 1;

            // Hitung biaya
            $tarifPerJam = (int) $transaksi->tarif->tarif_per_jam;
            $batasNormal = 2; // 2 jam pertama normal
            $dendaMultiplier = 1.5; // +50% denda

            $biayaDetail = $this->hitungBiaya($durasi, $tarifPerJam, $batasNormal, $dendaMultiplier);

            // Update transaksi
            $transaksi->update([
                'waktu_keluar' => $waktuKeluar,
                'durasi' => $durasi,
                'biaya_total' => $biayaDetail['total'],
                'status' => 'keluar',
            ]);

            // Update kapasitas area
            if ($transaksi->areaParkir) {
                $transaksi->areaParkir->decrement('terisi');
            }

            // Log aktivitas
            $this->logService->log(
                $userId,
                'Kendaraan keluar: ' . $transaksi->kendaraan->plat_nomor . ' - Biaya: Rp ' . number_format($biayaDetail['total'], 0, ',', '.')
            );

            return [
                'transaksi' => $transaksi->fresh(['kendaraan', 'tarif', 'areaParkir']),
                'durasi' => $durasi,
                'biaya_normal' => $biayaDetail['biaya_normal'],
                'biaya_denda' => $biayaDetail['biaya_denda'],
                'total' => $biayaDetail['total'],
                'jam_normal' => $biayaDetail['jam_normal'],
                'jam_denda' => $biayaDetail['jam_denda'],
                'tarif_per_jam' => $tarifPerJam,
            ];
        });
    }

    /**
     * Hitung biaya parkir dengan sistem denda
     */
    public function hitungBiaya(int $durasi, int $tarifPerJam, int $batasNormal = 2, float $dendaMultiplier = 1.5): array
    {
        $jamNormal = min($durasi, $batasNormal);
        $jamDenda = max(0, $durasi - $batasNormal);

        $biayaNormal = $jamNormal * $tarifPerJam;
        $biayaDenda = $jamDenda * (int)($tarifPerJam * $dendaMultiplier);

        return [
            'jam_normal' => $jamNormal,
            'jam_denda' => $jamDenda,
            'biaya_normal' => $biayaNormal,
            'biaya_denda' => $biayaDenda,
            'total' => $biayaNormal + $biayaDenda,
        ];
    }

    /**
     * Preview biaya (untuk tampilan sebelum confirm keluar)
     */
    public function previewBiaya(Transaksi $transaksi): array
    {
        $waktuKeluar = now();
        $waktuMasuk = $transaksi->waktu_masuk;

        // Hitung durasi dengan grace period 5 menit
        $durasiMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
        $durasiToleransi = max(0, $durasiMenit - 5);
        $durasi = (int) ceil($durasiToleransi / 60);
        if ($durasi < 1) $durasi = 1;

        $tarifPerJam = (int) $transaksi->tarif->tarif_per_jam;
        $biayaDetail = $this->hitungBiaya($durasi, $tarifPerJam);

        return array_merge($biayaDetail, [
            'durasi' => $durasi,
            'tarif_per_jam' => $tarifPerJam,
            'waktu_masuk' => $waktuMasuk,
            'waktu_keluar_estimasi' => $waktuKeluar,
        ]);
    }
}
