@extends('layouts.app')

@section('title', 'Kendaraan Masuk')

@section('content')
    <div class="card" style="max-width:600px">
        <div class="card-header">
            <h3><i class="fas fa-arrow-right" style="color:var(--success);margin-right:8px"></i> Form Kendaraan Masuk</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('transaksi.store-masuk') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Plat Nomor * (Harus Sudah Terdaftar)</label>
                    <input type="text" name="plat_nomor" class="form-control" placeholder="Contoh: B 1234 ABC"
                        value="{{ old('plat_nomor') }}" required style="text-transform:uppercase">
                </div>

                <div class="btn-group" style="margin-top:8px">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Masuk (Auto deteksi tipe & area)
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
