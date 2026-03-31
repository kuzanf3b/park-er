@extends('layouts.app')

@section('title', 'Kendaraan')

@section('content')
<div class="flex justify-between items-center mb-4" style="margin-bottom:24px">
    <div></div>
    @if(auth()->user()->role === 'petugas' || auth()->user()->role === 'admin')
    <a href="{{ route('kendaraan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kendaraan
    </a>
    @endif
</div>

<div class="card mb-6" style="margin-bottom:24px">
    <div class="card-body" style="padding:16px 24px">
        <form method="GET" action="{{ route('kendaraan.index') }}" style="display:flex;gap:12px;align-items:end;flex-wrap:wrap">
            <div style="flex:1;min-width:200px">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Plat nomor / pemilik..." value="{{ request('search') }}">
            </div>
            <div style="min-width:150px">
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-control">
                    <option value="">Semua</option>
                    <option value="motor" {{ request('jenis') === 'motor' ? 'selected' : '' }}>Motor</option>
                    <option value="mobil" {{ request('jenis') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                    <option value="truk" {{ request('jenis') === 'truk' ? 'selected' : '' }}>Truk</option>
                    <option value="bus" {{ request('jenis') === 'bus' ? 'selected' : '' }}>Bus</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                <a href="{{ route('kendaraan.index') }}" class="btn btn-outline btn-sm">Reset</a>
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
                    <th>Warna</th>
                    <th>Pemilik</th>
                    <th>Status</th>
                    @if(auth()->user()->role === 'petugas' || auth()->user()->role === 'admin')
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($kendaraans as $k)
                <tr>
                    <td>{{ $k->id_kendaraan }}</td>
                    <td><strong>{{ $k->plat_nomor }}</strong></td>
                    <td><span class="badge badge-primary">{{ ucfirst($k->jenis_kendaraan) }}</span></td>
                    <td>{{ $k->warna }}</td>
                    <td>{{ $k->pemilik }}</td>
                    <td>
                        @if($k->isSedangParkir())
                            <span class="badge badge-info"><i class="fas fa-circle" style="font-size:6px"></i>Sedang Parkir</span>
                        @else
                            <span class="badge badge-success">Tersedia</span>
                        @endif
                    </td>
                    @if(auth()->user()->role === 'petugas' || auth()->user()->role === 'admin')
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('kendaraan.edit', $k->id_kendaraan) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$k->isSedangParkir())
                                <form method="POST" action="{{ route('kendaraan.destroy', $k->id_kendaraan) }}" style="display:inline" onsubmit="return confirm('Hapus kendaraan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ (auth()->user()->role === 'petugas' || auth()->user()->role === 'admin') ? '7' : '6' }}">
                        <div class="empty-state">
                            <i class="fas fa-car"></i>
                            <p>Belum ada data kendaraan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $kendaraans->links() }}
</div>
@endsection
