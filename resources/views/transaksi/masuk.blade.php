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
                <label class="form-label">Plat Nomor *</label>
                <input type="text" name="plat_nomor" class="form-control" placeholder="Contoh: B 1234 ABC"
                    value="{{ old('plat_nomor') }}" required style="text-transform:uppercase">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis Kendaraan *</label>
                    <select name="jenis_kendaraan" class="form-control" required id="jenisKendaraan">
                        <option value="">-- Pilih --</option>
                        <option value="motor" {{ old('jenis_kendaraan') === 'motor' ? 'selected' : '' }}>Motor</option>
                        <option value="mobil" {{ old('jenis_kendaraan') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="truk" {{ old('jenis_kendaraan') === 'truk' ? 'selected' : '' }}>Truk</option>
                        <option value="bus" {{ old('jenis_kendaraan') === 'bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Area Parkir *</label>
                    <select name="id_area" class="form-control" required id="areaParkir">
                        <option value="">-- Pilih Jenis Dulu --</option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id_area }}"
                            data-jenis="{{ $area->jenis_kendaraan }}"
                            {{ old('id_area') == $area->id_area ? 'selected' : '' }}
                            style="display:none">
                            {{ $area->nama_area }} (Sisa: {{ $area->sisaKapasitas() }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <input type="text" name="warna" class="form-control" placeholder="Contoh: Hitam" value="{{ old('warna') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Pemilik</label>
                    <input type="text" name="pemilik" class="form-control" placeholder="Nama pemilik" value="{{ old('pemilik') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Akun Owner Kendaraan *</label>
                <select name="id_user" class="form-control" required>
                    <option value="">-- Pilih owner --</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id_user }}" {{ (string) old('id_user') === (string) $owner->id_user ? 'selected' : '' }}>
                            {{ $owner->nama_lengkap }} ({{ $owner->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="btn-group" style="margin-top:8px">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Simpan & Masuk
                </button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('jenisKendaraan').addEventListener('change', function() {
        const jenis = this.value;
        const areaSelect = document.getElementById('areaParkir');
        const options = areaSelect.querySelectorAll('option[data-jenis]');

        areaSelect.value = '';
        options.forEach(opt => {
            if (opt.dataset.jenis === jenis) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });

        // Update placeholder
        if (jenis) {
            areaSelect.querySelector('option:first-child').textContent = '-- Pilih Area --';
        } else {
            areaSelect.querySelector('option:first-child').textContent = '-- Pilih Jenis Dulu --';
        }
    });

    // Trigger on page load if old value exists
    const jenisSelect = document.getElementById('jenisKendaraan');
    if (jenisSelect.value) {
        jenisSelect.dispatchEvent(new Event('change'));
    }
</script>
@endsection
