<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $table = 'tb_tarif';
    protected $primaryKey = 'id_tarif';

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam',
    ];

    protected function casts(): array
    {
        return [
            'tarif_per_jam' => 'decimal:0',
        ];
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_tarif', 'id_tarif');
    }
}
