@extends('layouts.app')

@section('title', isset($kendaraan) ? 'Edit Kendaraan' : 'Tambah Kendaraan')

@section('content')
    <div class="card" style="max-width:500px">
        <div class="card-header">
            <h3>{{ isset($kendaraan) ? 'Edit' : 'Tambah' }} Kendaraan</h3>
        </div>
        <div class="card-body">
            <form method="POST"
                action="{{ isset($kendaraan) ? route('kendaraan.update', $kendaraan->id_kendaraan) : route('kendaraan.store') }}">
                @csrf
                @if (isset($kendaraan))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label">Plat Nomor *</label>
                    <input type="text" name="plat_nomor" class="form-control"
                        value="{{ old('plat_nomor', $kendaraan->plat_nomor ?? '') }}" required
                        style="text-transform:uppercase">
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kendaraan *</label>
                    <select name="jenis_kendaraan" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach (['motor', 'mobil', 'truk', 'bus'] as $jenis)
                            <option value="{{ $jenis }}"
                                {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan ?? '') === $jenis ? 'selected' : '' }}>
                                {{ ucfirst($jenis) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control"
                            value="{{ old('warna', $kendaraan->warna ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pemilik</label>
                        <input type="text" name="pemilik" class="form-control"
                            value="{{ old('pemilik', $kendaraan->pemilik ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Akun Owner Kendaraan *</label>
                    <select name="id_user" class="form-control" required>
                        <option value="">-- Pilih owner --</option>
                        @foreach ($owners as $owner)
                            <option value="{{ $owner->id_user }}"
                                {{ (string) old('id_user', $kendaraan->id_user ?? '') === (string) $owner->id_user ? 'selected' : '' }}>
                                {{ $owner->nama_lengkap }} ({{ $owner->username }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('kendaraan.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
