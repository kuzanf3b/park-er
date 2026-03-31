<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'tb_kendaraan';
    protected $primaryKey = 'id_kendaraan';

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'warna',
        'pemilik',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_kendaraan', 'id_kendaraan');
    }

    public function transaksiAktif()
    {
        return $this->hasOne(Transaksi::class, 'id_kendaraan', 'id_kendaraan')
                    ->where('status', 'masuk');
    }

    public function isSedangParkir(): bool
    {
        return $this->transaksis()->where('status', 'masuk')->exists();
    }
}
