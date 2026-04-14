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
                    <div class="plat-check-row">
                        <input type="text" name="plat_nomor" id="platNomorInput" class="form-control"
                            value="{{ old('plat_nomor', $kendaraan->plat_nomor ?? '') }}" required
                            style="text-transform:uppercase">
                        <div id="platDuplicateAlert" class="plat-warning-card is-hidden" role="status" aria-live="polite">
                            <i id="platDuplicateIcon" class="fas fa-exclamation-triangle"></i>
                            <div class="plat-warning-copy">
                                <strong id="platDuplicateTitle"></strong>
                                <span id="platDuplicateMessage"></span>
                            </div>
                        </div>
                    </div>
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
                    <button type="submit" id="submitKendaraanBtn" class="btn btn-primary"><i class="fas fa-save"></i>
                        Simpan</button>
                    <a href="{{ route('kendaraan.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (() => {
            const platInput = document.getElementById('platNomorInput');
            const submitBtn = document.getElementById('submitKendaraanBtn');
            const duplicateAlert = document.getElementById('platDuplicateAlert');
            const duplicateIcon = document.getElementById('platDuplicateIcon');
            const duplicateTitle = document.getElementById('platDuplicateTitle');
            const duplicateMessage = document.getElementById('platDuplicateMessage');
            const existingKendaraanPlates = @json($existingKendaraanPlates ?? []);
            let debounceTimer;

            if (!platInput || !submitBtn || !duplicateAlert || !duplicateIcon || !duplicateTitle || !duplicateMessage) {
                return;
            }

            const hideDuplicateAlert = () => {
                duplicateAlert.classList.add('is-hidden');
                duplicateAlert.classList.remove('is-parkir', 'is-registered', 'is-available');
                duplicateIcon.className = 'fas fa-exclamation-triangle';
                duplicateTitle.textContent = '';
                duplicateMessage.textContent = '';
                submitBtn.disabled = false;
            };

            const showDuplicateAlert = (title, message, type = 'registered', blockSubmit = true) => {
                duplicateAlert.classList.remove('is-hidden', 'is-parkir', 'is-registered', 'is-available');
                if (type === 'parkir') {
                    duplicateAlert.classList.add('is-parkir');
                    duplicateIcon.className = 'fas fa-ban';
                } else if (type === 'available') {
                    duplicateAlert.classList.add('is-available');
                    duplicateIcon.className = 'fas fa-check-circle';
                } else {
                    duplicateAlert.classList.add('is-registered');
                    duplicateIcon.className = 'fas fa-info-circle';
                }
                duplicateTitle.textContent = title;
                duplicateMessage.textContent = message;
                submitBtn.disabled = blockSubmit;
            };

            const normalizePlate = (plate) => plate.toUpperCase().replace(/\s+/g, ' ').trim();

            const checkPlate = () => {
                const plate = normalizePlate(platInput.value);

                if (!plate) {
                    hideDuplicateAlert();
                    return;
                }

                const kendaraanTerdaftar = existingKendaraanPlates.find((item) => {
                    return normalizePlate(item.plat_nomor || '') === plate;
                });

                if (kendaraanTerdaftar) {
                    if (kendaraanTerdaftar.sedang_parkir) {
                        showDuplicateAlert(
                            'Sedang Parkir',
                            'Plat sedang parkir.',
                            'parkir'
                        );
                    } else {
                        showDuplicateAlert(
                            'Sudah Terdaftar',
                            'Plat sudah terdaftar.',
                            'registered'
                        );
                    }
                    return;
                }

                showDuplicateAlert('Tersedia', 'Plat bisa dipakai.', 'available', false);
            };

            platInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(checkPlate, 350);
            });

            platInput.addEventListener('blur', checkPlate);

            platInput.closest('form')?.addEventListener('submit', (event) => {
                if (submitBtn.disabled) {
                    event.preventDefault();
                }
            });

            checkPlate();
        })();
    </script>

    <style>
        .plat-check-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 8px;
        }

        .plat-warning-card {
            display: flex;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid;
            font-size: 12px;
            line-height: 1.4;
        }

        .plat-warning-card i {
            margin-top: 2px;
            font-size: 14px;
        }

        .plat-warning-card.is-hidden {
            display: none;
        }

        .plat-warning-card.is-parkir {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .plat-warning-card.is-registered {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1e40af;
        }

        .plat-warning-card.is-available {
            background: #ecfdf5;
            border-color: #86efac;
            color: #166534;
        }

        .plat-warning-copy {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .plat-warning-card {
            width: 100%;
        }
    </style>
@endsection
