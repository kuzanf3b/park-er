@extends('layouts.app')

@section('title', 'Struk Parkir')

@section('content')
    <div class="receipt-card">
        <div class="receipt-header">
            <div style="font-size:28px;margin-bottom:8px"><i class="fas fa-car"></i></div>
            <h2 style="font-size:20px;font-weight:700">STRUK PARKIR</h2>
            <p style="font-size:13px;opacity:0.8">{{ $transaksi->waktu_keluar->format('d F Y, H:i') }}</p>
        </div>

        <div class="receipt-body">
            <div class="receipt-row">
                <span>No. Transaksi</span>
                <strong>#{{ str_pad($transaksi->id_parkir, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>
            <div class="receipt-row">
                <span>Plat Nomor</span>
                <strong>{{ $transaksi->kendaraan->plat_nomor }}</strong>
            </div>
            <div class="receipt-row">
                <span>Jenis Kendaraan</span>
                <span>{{ ucfirst($transaksi->kendaraan->jenis_kendaraan) }}</span>
            </div>
            <div class="receipt-row">
                <span>Area</span>
                <span>{{ $transaksi->areaParkir->nama_area ?? '-' }}</span>
            </div>
            <div class="receipt-row">
                <span>Waktu Masuk</span>
                <span>{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</span>
            </div>
            <div class="receipt-row">
                <span>Waktu Keluar</span>
                <span>{{ $transaksi->waktu_keluar->format('d/m/Y H:i') }}</span>
            </div>
            <div class="receipt-row">
                <span>Durasi</span>
                <strong>{{ $transaksi->durasi }} jam</strong>
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px dashed var(--border)">
                <div class="receipt-row">
                    <span>Biaya Normal ({{ $biayaDetail['jam_normal'] }} jam)</span>
                    <span>Rp {{ number_format($biayaDetail['biaya_normal'], 0, ',', '.') }}</span>
                </div>
                @if ($biayaDetail['biaya_denda'] > 0)
                    <div class="receipt-row" style="color:var(--danger)">
                        <span>Denda ({{ $biayaDetail['jam_denda'] }} jam × 150%)</span>
                        <span>Rp {{ number_format($biayaDetail['biaya_denda'], 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            <div class="receipt-total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</span>
            </div>

            <div style="text-align:center;margin-top:16px;color:var(--text-muted);font-size:12px">
                <p>Petugas: {{ $transaksi->user->nama_lengkap ?? '-' }}</p>
                <p style="margin-top:8px">Terima kasih atas kunjungan Anda</p>
            </div>
        </div>
    </div>

    <div style="text-align:center;margin-top:24px" class="btn-group" style="justify-content:center">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
        <a href="{{ route($backRoute) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection
