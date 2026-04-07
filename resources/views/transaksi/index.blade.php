@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="flex justify-between items-center mb-4" style="margin-bottom:24px">
        <div></div>
        <a href="{{ route('transaksi.masuk') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Kendaraan Masuk
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-6" style="margin-bottom:24px">
        <div class="card-body" style="padding:16px 24px">
            <form method="GET" action="{{ route('transaksi.index') }}"
                style="display:flex;gap:12px;align-items:end;flex-wrap:wrap">
                <div style="flex:1;min-width:200px">
                    <label class="form-label">Cari Plat Nomor</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari plat nomor..."
                        value="{{ request('search') }}">
                </div>
                <div style="min-width:150px">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="masuk" {{ request('status') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Area</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Durasi</th>
                        <th>Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                            <td>{{ ucfirst($t->kendaraan->jenis_kendaraan ?? '-') }}</td>
                            <td>{{ $t->areaParkir->nama_area ?? '-' }}</td>
                            <td>{{ $t->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td>{{ $t->waktu_keluar ? $t->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $t->durasi ? $t->durasi . ' jam' : '-' }}</td>
                            <td>
                                @if ($t->biaya_total)
                                    <strong>Rp {{ number_format($t->biaya_total, 0, ',', '.') }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($t->status === 'masuk')
                                    <span class="badge badge-info"><i class="fas fa-circle" style="font-size:6px"></i>
                                        Parkir</span>
                                @else
                                    <span class="badge badge-success"><i class="fas fa-check" style="font-size:9px"></i>
                                        Selesai</span>
                                @endif
                            </td>
                            <td>
                                @if ($t->status === 'masuk')
                                    <a href="{{ route('transaksi.keluar', $t->id_parkir) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </a>
                                @else
                                    <a href="{{ route('transaksi.receipt', $t->id_parkir) }}"
                                        class="btn btn-outline btn-sm">
                                        <i class="fas fa-receipt"></i> Struk
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
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

    <div class="mt-4">
        {{ $transaksis->links() }}
    </div>
@endsection
