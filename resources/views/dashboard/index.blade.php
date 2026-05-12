@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid-4 mb-6">
        <div class="stat-card">
            <div class="stat-icon blue">
                <img src="{{ $appLogo ?? asset('favicon.ico') }}" alt="Logo Park-Er">
            </div>
            <div class="stat-info">
                <h4>Kendaraan Aktif</h4>
                <div class="stat-value">{{ $kendaraanAktif }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
                <h4>Pendapatan Hari Ini</h4>
                <div class="stat-value" style="font-size:22px">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-info">
                <h4>Transaksi Hari Ini</h4>
                <div class="stat-value">{{ $totalTransaksiHariIni }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-parking"></i>
            </div>
            <div class="stat-info">
                <h4>Total Area</h4>
                <div class="stat-value">{{ $areaParkir->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Area Parkir Status -->
    <div class="card mb-6">
        <div class="card-header">
            <h3><i class="fas fa-parking" style="color:var(--primary);margin-right:8px"></i> Status Area Parkir</h3>
        </div>
        <div class="card-body">
            <div class="grid-3">
                @foreach ($areaParkir as $area)
                    @php
                        $persen = $area->persentaseTerisi();
                        $colorClass = $persen < 70 ? 'green' : ($persen < 90 ? 'yellow' : 'red');
                    @endphp
                    <div
                        style="padding:16px;border:1px solid var(--border);border-radius:var(--radius);background:var(--surface-2)">
                        <div class="flex justify-between items-center mb-2" style="margin-bottom:8px">
                            <strong style="font-size:14px">{{ $area->nama_area }}</strong>
                            <span
                                class="badge badge-{{ $persen >= 90 ? 'danger' : ($persen >= 70 ? 'warning' : 'success') }}">
                                {{ ucfirst($area->jenis_kendaraan) }}
                            </span>
                        </div>
                        <div
                            style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-secondary);margin-bottom:4px">
                            <span>{{ $area->terisi }} / {{ $area->kapasitas }}</span>
                            <span>{{ $persen }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill {{ $colorClass }}" style="width: {{ $persen }}%"></div>
                        </div>
                        @if ($persen >= 90)
                            <div style="font-size:11px;color:var(--danger);margin-top:6px;font-weight:600">
                                <i class="fas fa-exclamation-triangle"></i> Hampir penuh!
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history" style="color:var(--primary);margin-right:8px"></i> Transaksi Terbaru</h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Area</th>
                        <th>Waktu Masuk</th>
                        <th>Status</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                            <td>{{ ucfirst($t->kendaraan->jenis_kendaraan ?? '-') }}</td>
                            <td>{{ $t->areaParkir->nama_area ?? '-' }}</td>
                            <td>{{ $t->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($t->status === 'masuk')
                                    <span class="badge badge-info"><i class="fas fa-circle" style="font-size:6px"></i>
                                        Parkir</span>
                                @else
                                    <span class="badge badge-success"><i class="fas fa-check" style="font-size:9px"></i>
                                        Keluar</span>
                                @endif
                            </td>
                            <td>
                                @if ($t->biaya_total)
                                    <strong>Rp {{ number_format($t->biaya_total, 0, ',', '.') }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
