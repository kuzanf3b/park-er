<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_parkir';

    protected $fillable = [
        'id_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'id_tarif',
        'durasi',
        'biaya_total',
        'status',
        'id_user',
        'id_area',
    ];

    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
            'waktu_keluar' => 'datetime',
            'biaya_total' => 'decimal:0',
        ];
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_kendaraan');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function areaParkir()
    {
        return $this->belongsTo(AreaParkir::class, 'id_area', 'id_area');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'masuk');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'keluar');
    }
}
