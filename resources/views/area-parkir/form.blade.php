@extends('layouts.app')

@section('title', isset($area) ? 'Edit Area Parkir' : 'Tambah Area Parkir')

@section('content')
    <div class="card" style="max-width:500px">
        <div class="card-header">
            <h3>{{ isset($area) ? 'Edit' : 'Tambah' }} Area Parkir</h3>
        </div>
        <div class="card-body">
            <form method="POST"
                action="{{ isset($area) ? route('area-parkir.update', $area->id_area) : route('area-parkir.store') }}">
                @csrf
                @if (isset($area))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label">Nama Area *</label>
                    <input type="text" name="nama_area" class="form-control"
                        value="{{ old('nama_area', $area->nama_area ?? '') }}" required
                        placeholder="Contoh: Area G - Motor">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Kendaraan *</label>
                        <select name="jenis_kendaraan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach (['motor', 'mobil', 'truk', 'bus'] as $jenis)
                                <option value="{{ $jenis }}"
                                    {{ old('jenis_kendaraan', $area->jenis_kendaraan ?? '') === $jenis ? 'selected' : '' }}>
                                    {{ ucfirst($jenis) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kapasitas *</label>
                        <input type="number" name="kapasitas" class="form-control"
                            min="{{ isset($area) ? $area->terisi : 1 }}"
                            value="{{ old('kapasitas', $area->kapasitas ?? '') }}" required>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('area-parkir.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
