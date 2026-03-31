@extends('layouts.app')

@section('title', 'Edit Tarif')

@section('content')
<div class="card" style="max-width:400px">
    <div class="card-header">
        <h3>Edit Tarif — {{ ucfirst($tarif->jenis_kendaraan) }}</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tarif.update', $tarif->id_tarif) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Jenis Kendaraan</label>
                <input type="text" class="form-control" value="{{ ucfirst($tarif->jenis_kendaraan) }}" disabled>
            </div>

            <div class="form-group">
                <label class="form-label">Tarif per Jam (Rp) *</label>
                <input type="number" name="tarif_per_jam" class="form-control" min="0" value="{{ old('tarif_per_jam', $tarif->tarif_per_jam) }}" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('tarif.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
