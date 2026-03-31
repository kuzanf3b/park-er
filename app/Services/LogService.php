<?php

namespace App\Services;

use App\Models\LogAktivitas;

class LogService
{
    public function log(int $userId, string $aktivitas): void
    {
        LogAktivitas::create([
            'id_user' => $userId,
            'aktivitas' => $aktivitas,
            'waktu_aktivitas' => now(),
            'created_at' => now(),
        ]);
    }
}
