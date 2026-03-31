# 🚗 Role-Based Feature Enhancement - Parking System

## 🎯 Tujuan
Perbarui sistem role pada aplikasi parkir berbasis Laravel agar setiap role memiliki pengalaman penggunaan (UX) yang jelas, efisien, dan sesuai kebutuhan masing-masing.

---

## 👥 Role & Behavior

### 1. Admin (Full Access / Super User)

#### Hak Akses:
- Akses penuh ke seluruh sistem
- CRUD semua data:
  - User
  - Kendaraan
  - Area parkir
  - Tarif
  - Transaksi
- Melihat semua laporan & log aktivitas

#### Catatan:
- Admin dapat melakukan semua aksi tanpa batasan
- Tetap gunakan middleware untuk keamanan

---

### 2. Petugas (Operasional - Single Page Workflow)

#### Konsep Utama:
Semua aktivitas dilakukan dalam **1 halaman utama (dashboard operasional)** tanpa pindah halaman.

#### Fitur:
- 📋 Tabel daftar kendaraan aktif (yang sedang parkir)
- 🔄 Aksi cepat:
  - **Masuk (Check-in)**
  - **Keluar (Check-out)**

---

#### 🚪 Kendaraan Masuk:
- Tombol **"Tambah Kendaraan"**
- Menggunakan **modal popup (bukan halaman baru)**
- Form:
  - Plat nomor
  - Jenis kendaraan
  - Pilih area parkir
- Setelah submit:
  - Data masuk ke `tb_transaksi`
  - Status = `masuk`
  - Update kapasitas area (`terisi`)

---

#### 🚗 Kendaraan Keluar:
- Tombol **"Keluar"** di setiap row kendaraan
- Sistem otomatis:
  - Hitung durasi
  - Hitung biaya + denda (jika ada)
- Tampilkan hasil di:
  - Modal popup / alert sebelum konfirmasi

---

#### ⚡ UX Requirement:
- Tidak boleh reload halaman penuh (gunakan AJAX / Livewire / Alpine.js)
- Fokus ke kecepatan input (minim klik)

---

### 3. Owner (User Experience / Monitoring)

#### Tujuan:
Owner hanya sebagai **pengguna pasif yang memonitor kendaraan miliknya**

---

#### Fitur:

##### 🚗 Status Kendaraan
- Apakah sedang parkir atau tidak
- Jika parkir:
  - Tampilkan waktu masuk
  - Estimasi biaya berjalan

---

##### 📜 History Parkir
- Riwayat kendaraan:
  - Waktu masuk & keluar
  - Durasi
  - Total biaya
- Filter sederhana (tanggal)

---

##### 🅿️ Informasi Area Parkir
- Daftar area parkir (`tb_area_parkir`)
- Informasi:
  - Kapasitas
  - Jumlah terisi
  - Status:
    - Penuh
    - Tersedia

---

#### 🔒 Batasan:
- Owner hanya bisa melihat data miliknya sendiri
- Tidak bisa input transaksi

---

## 🧠 Logic Tambahan

### 1. Status Parkir
- Ditentukan dari:
  - `tb_transaksi.status = masuk / keluar`

---

### 2. Area Kosong
- Hitung:
```sql
kapasitas - terisi