@extends('layouts.app')

@section('title', $isOwner ? 'Riwayat Parkir Saya' : 'Laporan Parkir')

@section('content')
<!-- Filter -->
<div class="card mb-6" style="margin-bottom:24px">
    <div class="card-body" style="padding:16px 24px">
        <form method="GET" action="{{ route('laporan.index') }}" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap">
            <div>
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
            </div>
            <div>
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="grid-3 mb-6" style="margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-info">
            <h4>Total Biaya Parkir</h4>
            <div class="stat-value" style="font-size:20px">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-receipt"></i></div>
        <div class="stat-info">
            <h4>Total Transaksi</h4>
            <div class="stat-value">{{ $totalTransaksi }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-chart-pie"></i></div>
        <div class="stat-info">
            <h4>Rata-rata Biaya / Transaksi</h4>
            <div class="stat-value" style="font-size:20px">Rp {{ $totalTransaksi > 0 ? number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') : 0 }}</div>
        </div>
    </div>
</div>

<!-- Per Jenis -->
<div class="card mb-6" style="margin-bottom:24px">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar" style="color:var(--primary);margin-right:8px"></i> {{ $isOwner ? 'Biaya Parkir per Jenis Kendaraan' : 'Pendapatan per Jenis Kendaraan' }}</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Jenis Kendaraan</th>
                    <th>Jumlah Transaksi</th>
                    <th>{{ $isOwner ? 'Total Biaya' : 'Total Pendapatan' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendapatanPerJenis as $item)
                <tr>
                    <td><span class="badge badge-primary">{{ ucfirst($item->jenis_kendaraan) }}</span></td>
                    <td>{{ $item->jumlah }}</td>
                    <td><strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Detail Transaksi -->
<div class="card">
    <div class="card-header">
        <h3>Detail Transaksi</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Durasi</th>
                    <th>Biaya</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td>{{ $t->id_parkir }}</td>
                    <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                    <td>{{ ucfirst($t->kendaraan->jenis_kendaraan ?? '-') }}</td>
                    <td>{{ $t->waktu_masuk->format('d/m H:i') }}</td>
                    <td>{{ $t->waktu_keluar ? $t->waktu_keluar->format('d/m H:i') : '-' }}</td>
                    <td>{{ $t->durasi }} jam</td>
                    <td><strong>Rp {{ number_format($t->biaya_total, 0, ',', '.') }}</strong></td>
                    <td>{{ $t->user->nama_lengkap ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada data transaksi pada periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $transaksis->links() }}</div>
@endsection
