@extends('layouts.app')

@section('title', 'Tarif Parkir')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <h3><i class="fas fa-tags" style="color:var(--primary);margin-right:8px"></i> Tarif Parkir</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Jenis Kendaraan</th>
                    <th>Tarif per Jam</th>
                    <th>Tarif Denda (>2 jam)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tarifs as $tarif)
                <tr>
                    <td>
                        <span class="badge badge-primary">{{ ucfirst($tarif->jenis_kendaraan) }}</span>
                    </td>
                    <td><strong>Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</strong></td>
                    <td style="color:var(--danger)">Rp {{ number_format($tarif->tarif_per_jam * 1.5, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('tarif.edit', $tarif->id_tarif) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body" style="padding:12px 24px;background:var(--surface-2);border-top:1px solid var(--border)">
        <p style="font-size:12px;color:var(--text-muted)">
            <i class="fas fa-info-circle"></i> Denda berlaku untuk durasi parkir melebihi 2 jam pertama. Tarif denda = 150% dari tarif normal per jam.
        </p>
    </div>
</div>
@endsection
