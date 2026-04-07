@extends('layouts.app')

@section('title', 'Dashboard Operasional')

@section('content')
    <div class="card mb-6" style="margin-bottom:24px">
        <div class="card-header"
            style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
            <h3><i class="fas fa-bolt" style="color:var(--warning);margin-right:8px"></i> Operasional Parkir</h3>
            <button type="button" class="btn btn-primary" onclick="openMasukModal()">
                <i class="fas fa-plus"></i> Tambah Kendaraan
            </button>
        </div>
        <div class="card-body" style="padding-top:16px">
            <div class="form-group" style="width:420px;display:flex;justify-content:flex-end;margin-bottom:12px">
                <label class="form-label" for="searchOperasional">Cari Plat / Pemilik</label>
                <input type="text" id="searchOperasional" class="form-control"
                    placeholder="Contoh: B 1234 ABC atau Budi">
            </div>
            <div id="operasionalMessage" style="display:none"></div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Plat Nomor</th>
                            <th>Jenis</th>
                            <th>Area</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Durasi</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aktifRows">
                        @forelse($transaksis as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                                <td>{{ ucfirst($t->kendaraan->jenis_kendaraan ?? '-') }}</td>
                                <td>{{ $t->areaParkir->nama_area ?? '-' }}</td>
                                <td>{{ $t->waktu_masuk->format('d/m/Y H:i') }}</td>
                                <td>{{ $t->waktu_keluar ? $t->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $t->durasi ? $t->durasi . ' jam' : '-' }}</td>
                                <td>
                                    @if ($t->biaya_total)
                                        <strong>Rp {{ number_format($t->biaya_total, 0, ',', '.') }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($t->status === 'masuk')
                                        <span class="badge badge-info"><i class="fas fa-circle" style="font-size:6px"></i>
                                            Parkir</span>
                                    @else
                                        <span class="badge badge-success"><i class="fas fa-check" style="font-size:9px"></i>
                                            Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($t->status === 'masuk')
                                        <button class="btn btn-warning btn-sm" onclick="previewKeluar({{ $t->id_parkir }})">
                                            <i class="fas fa-sign-out-alt"></i> Keluar
                                        </button>
                                    @else
                                        <a href="{{ route('transaksi.receipt', $t->id_parkir) }}"
                                            class="btn btn-outline btn-sm">
                                            <i class="fas fa-receipt"></i> Struk
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <i class="fas fa-parking"></i>
                                        <p>Belum ada transaksi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="masukModal" class="modal-backdrop" style="display:none">
        <div class="modal-card">
            <h3 style="margin-bottom:12px">Kendaraan Masuk</h3>
            <form id="masukForm" onsubmit="submitMasuk(event)">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Plat Nomor *</label>
                        <input type="text" name="plat_nomor" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kendaraan *</label>
                        <select name="jenis_kendaraan" id="jenisKendaraanModal" class="form-control" required
                            onchange="filterAreaOptions()">
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
                        <label class="form-label">Area Parkir *</label>
                        <select name="id_area" id="idAreaModal" class="form-control" required>
                            <option value="">-- Pilih Jenis Dulu --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Pemilik</label>
                    <input type="text" name="pemilik" class="form-control">
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
                <div class="btn-group" style="justify-content:flex-end">
                    <button type="button" class="btn btn-outline" onclick="closeMasukModal()">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="keluarModal" class="modal-backdrop" style="display:none">
        <div class="modal-card">
            <h3 style="margin-bottom:12px">Konfirmasi Kendaraan Keluar</h3>
            <div id="previewKeluarBody" class="mb-4"></div>
            <div class="btn-group" style="justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="closeKeluarModal()">Batal</button>
                <button type="button" class="btn btn-warning" id="confirmKeluarBtn">Konfirmasi Keluar</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        @php
            $cachedAreas = $areas->map(function ($a) {
                return [
                    'id_area' => $a->id_area,
                    'nama_area' => $a->nama_area,
                    'jenis_kendaraan' => $a->jenis_kendaraan,
                    'sisa_kapasitas' => $a->sisaKapasitas(),
                ];
            });
        @endphp
        let cachedAreas = @json($cachedAreas);
        let selectedKeluarId = null;
        let operasionalSearch = '';
        let refreshTimer = null;

        function openMasukModal() {
            document.getElementById('masukModal').style.display = 'flex';
        }

        function closeMasukModal() {
            document.getElementById('masukModal').style.display = 'none';
            document.getElementById('masukForm').reset();
            document.getElementById('idAreaModal').innerHTML = '<option value="">-- Pilih Jenis Dulu --</option>';
        }

        function openKeluarModal() {
            document.getElementById('keluarModal').style.display = 'flex';
        }

        function closeKeluarModal() {
            document.getElementById('keluarModal').style.display = 'none';
            selectedKeluarId = null;
            document.getElementById('previewKeluarBody').innerHTML = '';
        }

        function showMessage(type, text) {
            const box = document.getElementById('operasionalMessage');
            box.className = `alert alert-${type === 'success' ? 'success' : 'error'}`;
            box.innerHTML =
                `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${text}</span>`;
            box.style.display = 'flex';
            setTimeout(() => {
                box.style.display = 'none';
            }, 3500);
        }

        function filterAreaOptions() {
            const jenis = document.getElementById('jenisKendaraanModal').value;
            const areaSelect = document.getElementById('idAreaModal');
            const options = ['<option value="">-- Pilih Area --</option>'];

            cachedAreas
                .filter(area => area.jenis_kendaraan === jenis)
                .forEach(area => {
                    options.push(
                        `<option value="${area.id_area}">${area.nama_area} (Sisa: ${area.sisa_kapasitas})</option>`);
                });

            areaSelect.innerHTML = options.join('');
        }

        async function refreshOperasional() {
            try {
                const query = operasionalSearch ? `?search=${encodeURIComponent(operasionalSearch)}` : '';
                const response = await fetch(`{{ route('operasional.aktif-json') }}${query}`);
                const data = await response.json();
                cachedAreas = data.areas || [];

                const rows = (data.transaksis || []).map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${item.plat_nomor}</strong></td>
                    <td>${item.jenis_kendaraan ? item.jenis_kendaraan.charAt(0).toUpperCase() + item.jenis_kendaraan.slice(1) : '-'}</td>
                    <td>${item.area || '-'}</td>
                    <td>${item.waktu_masuk}</td>
                    <td>${item.waktu_keluar || '-'}</td>
                    <td>${item.durasi_jam ? `${item.durasi_jam} jam` : '-'}</td>
                    <td>${item.biaya_total ? `<strong>Rp ${Number(item.biaya_total).toLocaleString('id-ID')}</strong>` : '-'}</td>
                    <td>
                        ${item.status === 'masuk'
                            ? '<span class="badge badge-info"><i class="fas fa-circle" style="font-size:6px"></i> Parkir</span>'
                            : '<span class="badge badge-success"><i class="fas fa-check" style="font-size:9px"></i> Selesai</span>'}
                    </td>
                    <td>
                        ${item.status === 'masuk'
                            ? `<button class="btn btn-warning btn-sm" onclick="previewKeluar(${item.id_parkir})"><i class="fas fa-sign-out-alt"></i> Keluar</button>`
                            : `<a href="${item.receipt_url}" class="btn btn-outline btn-sm"><i class="fas fa-receipt"></i> Struk</a>`}
                    </td>
                </tr>
            `);

                document.getElementById('aktifRows').innerHTML = rows.length ? rows.join('') : `
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="fas fa-parking"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    </td>
                </tr>
            `;
            } catch (e) {
                showMessage('error', 'Gagal memuat data operasional.');
            }
        }

        async function submitMasuk(event) {
            event.preventDefault();
            const form = document.getElementById('masukForm');
            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route('operasional.store-masuk-json') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menyimpan data kendaraan masuk.');
                }

                closeMasukModal();
                await refreshOperasional();
                showMessage('success', data.message || 'Kendaraan berhasil masuk.');
            } catch (e) {
                showMessage('error', e.message);
            }
        }

        async function previewKeluar(id) {
            try {
                const response = await fetch(`{{ url('/operasional') }}/${id}/preview-keluar`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal memuat preview biaya keluar.');
                }

                selectedKeluarId = id;
                document.getElementById('previewKeluarBody').innerHTML = `
                <div style="padding:12px;border:1px solid var(--border);border-radius:8px;background:var(--surface-2)">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px"><span>Plat Nomor</span><strong>${data.plat_nomor}</strong></div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px"><span>Durasi</span><strong>${data.durasi} jam</strong></div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px"><span>Biaya Normal</span><strong>Rp ${Number(data.biaya_normal).toLocaleString('id-ID')}</strong></div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px"><span>Denda</span><strong>Rp ${Number(data.biaya_denda).toLocaleString('id-ID')}</strong></div>
                    <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px solid var(--border)"><span>Total</span><strong>Rp ${Number(data.total).toLocaleString('id-ID')}</strong></div>
                </div>
            `;
                openKeluarModal();
            } catch (e) {
                showMessage('error', e.message);
            }
        }

        document.getElementById('confirmKeluarBtn').addEventListener('click', async () => {
            if (!selectedKeluarId) return;

            try {
                const response = await fetch(`{{ url('/operasional') }}/${selectedKeluarId}/keluar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal proses kendaraan keluar.');
                }

                closeKeluarModal();
                await refreshOperasional();
                showMessage('success', data.message || 'Kendaraan berhasil keluar.');

                if (data.receipt_url) {
                    const receiptWindow = window.open(data.receipt_url, '_blank');

                    if (!receiptWindow) {
                        window.location.href = data.receipt_url;
                    }
                }
            } catch (e) {
                showMessage('error', e.message);
            }
        });

        document.getElementById('searchOperasional').addEventListener('input', (event) => {
            operasionalSearch = event.target.value.trim();

            if (refreshTimer) {
                clearTimeout(refreshTimer);
            }

            refreshTimer = setTimeout(() => {
                refreshOperasional();
            }, 250);
        });

        refreshOperasional();
        setInterval(refreshOperasional, 15000);
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
    </style>
@endsection
