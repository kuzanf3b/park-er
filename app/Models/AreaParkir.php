<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    protected $table = 'tb_area_parkir';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nama_area',
        'jenis_kendaraan',
        'kapasitas',
        'terisi',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_area', 'id_area');
    }

    public function sisaKapasitas(): int
    {
        return $this->kapasitas - $this->terisi;
    }

    public function isFull(): bool
    {
        return $this->terisi >= $this->kapasitas;
    }

    public function persentaseTerisi(): float
    {
        if ($this->kapasitas == 0) return 0;
        return round(($this->terisi / $this->kapasitas) * 100, 1);
    }
}
