@extends('layouts.app')

@section('title', 'Super Cepat Kilat')

@section('content')
    <div class="card" style="margin-bottom:16px;">
        <div class="card-header">
            <h3 style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-bolt" style="color:#f59e0b;"></i>
                Super Cepat Kilat
            </h3>
        </div>
        <div class="card-body">
            <p style="margin-bottom:10px; color:var(--text-secondary);">
                Input plat sekali untuk proses cepat. Jika status masih parkir maka otomatis keluar, jika belum parkir maka
                otomatis masuk.
            </p>

            @if (session('success'))
                <div class="alert alert-success" style="margin-bottom:12px;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" style="margin-bottom:12px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ url('/operasional/kilat') }}">
                @csrf
                <div class="form-group" style="margin-bottom:12px;">
                    <label for="platKilatInput" class="form-label">Plat Nomor *</label>
                    <input type="text" name="plat_nomor" id="platKilatInput" class="form-control"
                        placeholder="Contoh: B 1234 ABC" value="{{ old('plat_nomor') }}" list="platKilatList"
                        autocomplete="off" required style="text-transform:uppercase">
                    <div id="kilatPlateInfo" class="plate-warning-card is-hidden" role="status" aria-live="polite"
                        style="margin-top:8px;">
                        <i id="kilatPlateInfoIcon" class="fas fa-info-circle"></i>
                        <span id="kilatPlateInfoText"></span>
                    </div>
                    <datalist id="platKilatList">
                        @foreach ($registeredPlates as $plate)
                            <option value="{{ $plate }}"></option>
                        @endforeach
                    </datalist>
                    <small style="display:block;margin-top:6px;color:var(--text-muted);">
                        Jika plat belum ada, silakan daftar dulu di menu kendaraan.
                    </small>
                </div>

                <div class="btn-group" style="display:flex;justify-content:space-between;align-items:center;">
                    <button type="button" class="btn btn-outline" onclick="openTambahKendaraanModal()">
                        <i class="fas fa-plus"></i>
                        Tambah Kendaraan
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitKilatBtn">
                        <i class="fas fa-bolt"></i>
                        Proses Kilat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="tambahKendaraanModal" class="modal-backdrop" style="display:none">
        <div class="modal-card" style="width:min(720px,100%);">
            <h3 style="margin-bottom:12px">Tambah Kendaraan</h3>
            <form method="POST" action="{{ route('kendaraan.store') }}" id="tambahKendaraanForm">
                @csrf
                <input type="hidden" name="kembali_ke_kilat" value="1">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Plat Nomor *</label>
                        <input type="text" name="plat_nomor" id="modalPlatNomorInput" class="form-control" required
                            style="text-transform:uppercase">
                        <div id="modalPlatWarning" class="plate-warning-card is-hidden" role="status" aria-live="polite"
                            style="margin-top:8px;">
                            <i id="modalPlatWarningIcon" class="fas fa-exclamation-triangle"></i>
                            <span id="modalPlatWarningText"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kendaraan *</label>
                        <select name="jenis_kendaraan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                            <option value="bus">Bus</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Akun Owner Kendaraan *</label>
                        <input type="hidden" name="id_user" id="modalSelectedOwnerId" required>
                        <input type="text" id="modalOwnerSearchInput" class="form-control"
                            placeholder="Klik untuk pilih owner..." autocomplete="off" required>
                        <div id="modalOwnerSuggestions" class="suggestions-list" style="display:none"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pemilik</label>
                        <input type="text" name="pemilik" id="modalPemilikInput" class="form-control"
                            placeholder="Terisi otomatis dari owner" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <input type="text" name="warna" class="form-control">
                </div>

                <div class="btn-group" style="justify-content:flex-end">
                    <button type="button" class="btn btn-outline" onclick="closeTambahKendaraanModal()">Batal</button>
                    <button type="submit" id="submitTambahKendaraanBtn" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Aktivitas Kendaraan Terbaru</h3>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Plat</th>
                            <th>Area</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aktivitas as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $row['plat_nomor'] }}</strong></td>
                                <td>{{ $row['area'] }}</td>
                                <td>{{ $row['waktu_masuk'] }}</td>
                                <td>{{ $row['waktu_keluar'] }}</td>
                                <td>
                                    @if ($row['status'] === 'masuk')
                                        <span class="badge badge-info">
                                            <i class="fas fa-circle" style="font-size:6px"></i>
                                            Parkir
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-check" style="font-size:9px"></i>
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Belum ada aktivitas kendaraan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (() => {
            const input = document.getElementById('platKilatInput');
            const submitBtn = document.getElementById('submitKilatBtn');
            const kilatPlateInfo = document.getElementById('kilatPlateInfo');
            const kilatPlateInfoIcon = document.getElementById('kilatPlateInfoIcon');
            const kilatPlateInfoText = document.getElementById('kilatPlateInfoText');
            const ownerOptions = @json($ownerOptions ?? []);
            const existingKendaraanPlates = @json($existingKendaraanPlates ?? []);

            const tambahModal = document.getElementById('tambahKendaraanModal');
            const modalForm = document.getElementById('tambahKendaraanForm');
            const modalPlatInput = document.getElementById('modalPlatNomorInput');
            const modalSubmitBtn = document.getElementById('submitTambahKendaraanBtn');
            const modalWarningBox = document.getElementById('modalPlatWarning');
            const modalWarningIcon = document.getElementById('modalPlatWarningIcon');
            const modalWarningText = document.getElementById('modalPlatWarningText');

            const modalOwnerSearchInput = document.getElementById('modalOwnerSearchInput');
            const modalOwnerSuggestionBox = document.getElementById('modalOwnerSuggestions');
            const modalSelectedOwnerId = document.getElementById('modalSelectedOwnerId');
            const modalPemilikInput = document.getElementById('modalPemilikInput');

            const normalizePlate = (plate) => String(plate || '').toUpperCase().replace(/\s+/g, ' ').trim();

            if (!input || !submitBtn) {
                return;
            }

            const updateKilatPlateInfo = () => {
                const plate = normalizePlate(input.value);

                if (!plate) {
                    kilatPlateInfo?.classList.add('is-hidden');
                    kilatPlateInfo?.classList.remove('is-warning', 'is-info', 'is-success');
                    if (submitBtn) submitBtn.disabled = false;
                    return;
                }

                const found = existingKendaraanPlates.find((item) => normalizePlate(item.plat_nomor) === plate);
                kilatPlateInfo?.classList.remove('is-hidden', 'is-warning', 'is-info', 'is-success');

                if (!found) {
                    kilatPlateInfo?.classList.add('is-warning');
                    if (kilatPlateInfoIcon) kilatPlateInfoIcon.className = 'fas fa-exclamation-triangle';
                    if (kilatPlateInfoText) kilatPlateInfoText.textContent =
                    'Plat belum terdaftar. Daftarkan dulu.';
                    submitBtn.disabled = true;
                    return;
                }

                submitBtn.disabled = false;
                if (found.sedang_parkir) {
                    kilatPlateInfo?.classList.add('is-info');
                    if (kilatPlateInfoIcon) kilatPlateInfoIcon.className = 'fas fa-info-circle';
                    if (kilatPlateInfoText) kilatPlateInfoText.textContent =
                        'Plat terdaftar, sedang parkir. Akan diproses keluar.';
                    return;
                }

                kilatPlateInfo?.classList.add('is-success');
                if (kilatPlateInfoIcon) kilatPlateInfoIcon.className = 'fas fa-check-circle';
                if (kilatPlateInfoText) kilatPlateInfoText.textContent = 'Plat terdaftar. Akan diproses masuk.';
            };

            input.addEventListener('input', () => {
                input.value = input.value.toUpperCase();
                updateKilatPlateInfo();
            });

            input.addEventListener('blur', updateKilatPlateInfo);

            input.form?.addEventListener('submit', () => {
                submitBtn.disabled = true;
            });

            updateKilatPlateInfo();

            const hideModalPlateWarning = () => {
                modalWarningBox.classList.add('is-hidden');
                modalWarningBox.classList.remove('is-warning', 'is-info', 'is-success');
                modalWarningIcon.className = 'fas fa-exclamation-triangle';
                modalWarningText.textContent = '';
                modalSubmitBtn.disabled = false;
            };

            const showModalPlateWarning = (message, type = 'warning') => {
                modalWarningBox.classList.remove('is-hidden', 'is-warning', 'is-info', 'is-success');
                if (type === 'warning') {
                    modalWarningBox.classList.add('is-warning');
                    modalWarningIcon.className = 'fas fa-exclamation-triangle';
                    modalSubmitBtn.disabled = true;
                } else if (type === 'info') {
                    modalWarningBox.classList.add('is-info');
                    modalWarningIcon.className = 'fas fa-info-circle';
                    modalSubmitBtn.disabled = true;
                } else {
                    modalWarningBox.classList.add('is-success');
                    modalWarningIcon.className = 'fas fa-check-circle';
                    modalSubmitBtn.disabled = false;
                }
                modalWarningText.textContent = message;
            };

            const checkModalPlate = () => {
                const plate = normalizePlate(modalPlatInput.value);
                if (!plate) {
                    hideModalPlateWarning();
                    return;
                }

                const found = existingKendaraanPlates.find((item) => normalizePlate(item.plat_nomor) === plate);
                if (!found) {
                    showModalPlateWarning('Plat tersedia.', 'success');
                    return;
                }

                if (found.sedang_parkir) {
                    showModalPlateWarning('Plat sedang parkir.', 'warning');
                    return;
                }

                showModalPlateWarning('Plat sudah terdaftar.', 'info');
            };

            const renderModalOwnerSuggestions = (query = '') => {
                const normalized = query.trim().toLowerCase();
                const filteredOwners = ownerOptions.filter((owner) => {
                    if (!normalized) return true;
                    return owner.nama_lengkap.toLowerCase().includes(normalized) ||
                        owner.username.toLowerCase().includes(normalized);
                });

                if (!filteredOwners.length) {
                    modalOwnerSuggestionBox.innerHTML =
                        '<button type="button" class="suggestion-item" disabled>Owner tidak ada</button>';
                    modalOwnerSuggestionBox.style.display = 'block';
                    return;
                }

                modalOwnerSuggestionBox.innerHTML = filteredOwners.map((owner) => `
                    <button type="button" class="suggestion-item" data-owner-id="${owner.id_user}">
                        ${owner.nama_lengkap} (${owner.username})
                    </button>
                `).join('');

                modalOwnerSuggestionBox.style.display = 'block';
            };

            const selectModalOwner = (idUser) => {
                const owner = ownerOptions.find((item) => Number(item.id_user) === Number(idUser));
                if (!owner) return;

                modalSelectedOwnerId.value = String(owner.id_user);
                modalOwnerSearchInput.value = `${owner.nama_lengkap} (${owner.username})`;
                modalPemilikInput.value = owner.nama_lengkap;
                modalOwnerSuggestionBox.style.display = 'none';
            };

            window.openTambahKendaraanModal = () => {
                tambahModal.style.display = 'flex';
                modalPlatInput.focus();
                checkModalPlate();
            };

            window.closeTambahKendaraanModal = () => {
                tambahModal.style.display = 'none';
                modalForm.reset();
                modalOwnerSuggestionBox.style.display = 'none';
                hideModalPlateWarning();
                modalSelectedOwnerId.value = '';
                modalPemilikInput.value = '';
                modalSubmitBtn.disabled = false;
            };

            modalPlatInput.addEventListener('input', () => {
                modalPlatInput.value = modalPlatInput.value.toUpperCase();
                checkModalPlate();
            });

            modalPlatInput.addEventListener('blur', checkModalPlate);

            modalOwnerSearchInput.addEventListener('focus', () => {
                renderModalOwnerSuggestions(modalOwnerSearchInput.value);
            });

            modalOwnerSearchInput.addEventListener('click', (event) => {
                event.stopPropagation();
                renderModalOwnerSuggestions(modalOwnerSearchInput.value);
            });

            modalOwnerSearchInput.addEventListener('input', () => {
                modalSelectedOwnerId.value = '';
                modalPemilikInput.value = '';
                renderModalOwnerSuggestions(modalOwnerSearchInput.value);
            });

            modalOwnerSuggestionBox.addEventListener('click', (event) => {
                const button = event.target.closest('.suggestion-item[data-owner-id]');
                if (!button) return;
                selectModalOwner(button.getAttribute('data-owner-id'));
            });

            document.addEventListener('click', (event) => {
                if (!tambahModal.contains(event.target)) {
                    modalOwnerSuggestionBox.style.display = 'none';
                }
            });

            modalForm.addEventListener('submit', (event) => {
                checkModalPlate();
                if (modalSubmitBtn.disabled) {
                    event.preventDefault();
                    return;
                }

                if (!modalSelectedOwnerId.value) {
                    event.preventDefault();
                    modalOwnerSearchInput.focus();
                    return;
                }
            });
        })();
    </script>

    <style>
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 90;
            padding: 16px;
        }

        .modal-card {
            width: min(680px, 100%);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 20px;
        }

        .suggestions-list {
            margin-top: 6px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            max-height: 220px;
            overflow-y: auto;
            box-shadow: var(--shadow-md);
        }

        .suggestion-item {
            width: 100%;
            padding: 10px 12px;
            border: 0;
            border-bottom: 1px solid var(--border);
            background: transparent;
            text-align: left;
            color: var(--text-primary);
            cursor: pointer;
        }

        .suggestion-item:last-child {
            border-bottom: 0;
        }

        .suggestion-item:hover:not(:disabled) {
            background: var(--surface-2);
        }

        .suggestion-item:disabled {
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .plate-warning-card {
            display: flex;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid;
            font-size: 12px;
            line-height: 1.4;
            width: 100%;
        }

        .plate-warning-card i {
            margin-top: 2px;
        }

        .plate-warning-card.is-hidden {
            display: none;
        }

        .plate-warning-card.is-warning {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #9a3412;
        }

        .plate-warning-card.is-info {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1e3a8a;
        }

        .plate-warning-card.is-success {
            background: #ecfdf5;
            border-color: #86efac;
            color: #166534;
        }

        #modalPemilikInput[readonly] {
            background: var(--surface-2);
            color: var(--text-secondary);
            cursor: not-allowed;
        }
    </style>
@endsection
