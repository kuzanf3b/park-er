@extends('layouts.app')

@section('title', 'Kendaraan Keluar')

@section('content')
<div class="card" style="max-width:560px">
    <div class="card-header">
        <h3><i class="fas fa-sign-out-alt" style="color:var(--warning);margin-right:8px"></i> Proses Kendaraan Keluar</h3>
    </div>
    <div class="card-body">
        <!-- Detail Kendaraan -->
        <div style="background:var(--surface-2);padding:20px;border-radius:var(--radius);margin-bottom:24px">
            <h4 style="font-size:14px;color:var(--text-secondary);margin-bottom:12px">Detail Kendaraan</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div>
                    <div style="font-size:12px;color:var(--text-muted)">Plat Nomor</div>
                    <div style="font-size:16px;font-weight:700">{{ $transaksi->kendaraan->plat_nomor }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:var(--text-muted)">Jenis</div>
                    <div style="font-size:14px;font-weight:500">{{ ucfirst($transaksi->kendaraan->jenis_kendaraan) }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:var(--text-muted)">Area</div>
                    <div style="font-size:14px;font-weight:500">{{ $transaksi->areaParkir->nama_area ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:var(--text-muted)">Waktu Masuk</div>
                    <div style="font-size:14px;font-weight:500">{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Preview Biaya -->
        <div style="background:#eff6ff;padding:20px;border-radius:var(--radius);margin-bottom:24px;border:1px solid #bfdbfe">
            <h4 style="font-size:14px;color:#1e40af;margin-bottom:12px"><i class="fas fa-calculator"></i> Preview Biaya</h4>

            <div class="receipt-row">
                <span>Durasi Parkir</span>
                <strong>{{ $preview['durasi'] }} jam</strong>
            </div>
            <div class="receipt-row">
                <span>Tarif per Jam</span>
                <span>Rp {{ number_format($preview['tarif_per_jam'], 0, ',', '.') }}</span>
            </div>
            <div class="receipt-row">
                <span>Biaya Normal ({{ $preview['jam_normal'] }} jam)</span>
                <span>Rp {{ number_format($preview['biaya_normal'], 0, ',', '.') }}</span>
            </div>
            @if($preview['jam_denda'] > 0)
            <div class="receipt-row" style="color:var(--danger)">
                <span><i class="fas fa-exclamation-triangle"></i> Denda ({{ $preview['jam_denda'] }} jam × 150%)</span>
                <strong>Rp {{ number_format($preview['biaya_denda'], 0, ',', '.') }}</strong>
            </div>
            @endif
            <div class="receipt-total" style="border-top:2px solid #1e40af;color:#1e40af">
                <span>TOTAL</span>
                <span>Rp {{ number_format($preview['total'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Confirm Button -->
        <form method="POST" action="{{ route('transaksi.process-keluar', $transaksi->id_parkir) }}">
            @csrf
            <div class="btn-group">
                <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi kendaraan keluar?')">
                    <i class="fas fa-check-circle"></i> Konfirmasi Keluar
                </button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
