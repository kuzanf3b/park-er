@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
    <div class="grid-3 mb-6" style="margin-bottom:24px">
        <div class="stat-card">
            <div class="stat-icon blue"><img src="{{ $appLogo ?? asset('favicon.ico') }}" alt="Logo Park-Er"></div>
            <div class="stat-info">
                <h4>Total Kendaraan Saya</h4>
                <div class="stat-value">{{ $kendaraans->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="fas fa-parking"></i></div>
            <div class="stat-info">
                <h4>Sedang Parkir</h4>
                <div class="stat-value">{{ $kendaraanAktif->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-receipt"></i></div>
            <div class="stat-info">
                <h4>History Ditampilkan</h4>
                <div class="stat-value">{{ $history->total() }}</div>
            </div>
        </div>
    </div>

    <div class="card mb-6" style="margin-bottom:24px">
        <div class="card-header">
            <h3><i class="fas fa-satellite-dish" style="color:var(--primary);margin-right:8px"></i>Status Kendaraan Saya
            </h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Waktu Masuk</th>
                        <th>Estimasi Biaya Berjalan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraanAktif as $t)
                        @php
                            $durasiMenit = $t->waktu_masuk->diffInMinutes(now());
                            $durasiJam = max(1, (int) ceil($durasiMenit / 60));
                            $tarif = (int) ($t->tarif->tarif_per_jam ?? 0);
                            $jamNormal = min($durasiJam, 2);
                            $jamDenda = max(0, $durasiJam - 2);
                            $estimasiBiaya = $jamNormal * $tarif + $jamDenda * (int) ($tarif * 1.5);
                        @endphp
                        <tr>
                            <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                            <td>{{ ucfirst($t->kendaraan->jenis_kendaraan ?? '-') }}</td>
                            <td>{{ $t->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td><strong>Rp {{ number_format($estimasiBiaya, 0, ',', '.') }}</strong></td>
                            <td><span class="badge badge-info">Sedang Parkir</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-check-circle"></i>
                                    <p>Tidak ada kendaraan yang sedang parkir saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-6" style="margin-bottom:24px">
        <div class="card-header">
            <h3><i class="fas fa-filter" style="color:var(--primary);margin-right:8px"></i>History Parkir</h3>
        </div>
        <div class="card-body" style="padding-bottom:10px">
            <form method="GET" action="{{ route('dashboard') }}"
                style="display:flex;gap:12px;align-items:end;flex-wrap:wrap">
                <div>
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                </div>
                <div>
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggalAkhir }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Terapkan</button>
            </form>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Plat Nomor</th>
                        <th>Area</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Durasi</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $row)
                        <tr>
                            <td><strong>{{ $row->kendaraan->plat_nomor ?? '-' }}</strong></td>
                            <td>{{ $row->areaParkir->nama_area ?? '-' }}</td>
                            <td>{{ $row->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td>{{ $row->waktu_keluar ? $row->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $row->durasi }} jam</td>
                            <td><strong>Rp {{ number_format($row->biaya_total ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada history parkir pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-parking" style="color:var(--primary);margin-right:8px"></i>Informasi Area Parkir</h3>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Area</th>
                        <th>Jenis Kendaraan</th>
                        <th>Kapasitas</th>
                        <th>Terisi</th>
                        <th>Sisa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($areaParkir as $area)
                        <tr>
                            <td>{{ $area->nama_area }}</td>
                            <td>{{ ucfirst($area->jenis_kendaraan) }}</td>
                            <td>{{ $area->kapasitas }}</td>
                            <td>{{ $area->terisi }}</td>
                            <td>{{ $area->sisaKapasitas() }}</td>
                            <td>
                                @if ($area->isFull())
                                    <span class="badge badge-danger">Penuh</span>
                                @else
                                    <span class="badge badge-success">Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4" style="padding:0 24px 24px">
            {{ $history->links() }}
        </div>
    </div>
@endsection
