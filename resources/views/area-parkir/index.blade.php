@extends('layouts.app')

@section('title', 'Area Parkir')

@section('content')
    <div class="flex justify-between items-center mb-4" style="margin-bottom:24px">
        <div></div>
        <a href="{{ route('area-parkir.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Area
        </a>
    </div>

    <div class="grid-3 mb-6" style="margin-bottom:24px">
        @foreach ($areas as $area)
            @php
                $persen = $area->persentaseTerisi();
                $colorClass = $persen < 70 ? 'green' : ($persen < 90 ? 'yellow' : 'red');
            @endphp
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-between items-center" style="margin-bottom:12px">
                        <h4 style="font-size:15px;font-weight:600">{{ $area->nama_area }}</h4>
                        <span class="badge badge-primary">{{ ucfirst($area->jenis_kendaraan) }}</span>
                    </div>

                    <div style="font-size:32px;font-weight:700;color:var(--text);margin-bottom:4px">
                        {{ $area->terisi }}<span style="font-size:16px;font-weight:400;color:var(--text-muted)"> /
                            {{ $area->kapasitas }}</span>
                    </div>

                    <div class="progress-bar" style="margin-bottom:12px">
                        <div class="progress-fill {{ $colorClass }}" style="width: {{ $persen }}%"></div>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:12px;color:var(--text-muted)">Sisa: {{ $area->sisaKapasitas() }} slot</span>
                        <div class="btn-group">
                            <a href="{{ route('area-parkir.edit', $area->id_area) }}" class="btn btn-warning btn-sm"><i
                                    class="fas fa-edit"></i></a>
                            @if ($area->terisi == 0)
                                <form method="POST" action="{{ route('area-parkir.destroy', $area->id_area) }}"
                                    style="display:inline" onsubmit="return confirm('Hapus area ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if ($persen >= 90)
                        <div
                            style="margin-top:8px;padding:8px;background:#fef2f2;border-radius:6px;font-size:12px;color:var(--danger);font-weight:600">
                            <i class="fas fa-exclamation-triangle"></i> Area hampir penuh!
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 1.5rem">
        {{ $areas->links() }}
    </div>
@endsection
