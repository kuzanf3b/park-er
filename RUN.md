# 🚗 Parking Management System - Advanced Prompt

## 🎯 Tujuan
Bangun sebuah **web aplikasi manajemen parkir berbasis Laravel (PHP)** menggunakan pendekatan **clean architecture + MVC Laravel best practices** dengan memanfaatkan file database `.sql` yang telah disediakan.

Database mencakup tabel:
- `tb_area_parkir`
- `tb_kendaraan`
- `tb_transaksi`
- `tb_tarif`
- `tb_user`
- `tb_log_aktivitas`

Sistem harus mampu menangani operasional parkir secara real-time dengan logika bisnis yang jelas dan konsisten.

---

## ⚙️ Spesifikasi Teknis

- **Framework**: Laravel (versi terbaru)
- **Database**: MySQL (gunakan struktur dari file `.sql`)
- **Authentication**: Laravel Breeze / Sanctum
- **Frontend**: Blade + TailwindCSS
- **Arsitektur**: MVC + Service Layer Pattern

---

## 👥 Role & Hak Akses

### 1. Admin
- CRUD user (petugas & owner)
- Kelola tarif parkir
- Kelola area parkir

### 2. Petugas
- Input kendaraan masuk
- Input kendaraan keluar
- Melihat transaksi aktif

### 3. Owner
- Melihat laporan transaksi
- Statistik pendapatan
- Monitoring aktivitas sistem

Gunakan middleware berbasis role untuk pembatasan akses.

---

## 🧠 Business Logic

### 1. Kendaraan Masuk
- Simpan `waktu_masuk`
- Status = `masuk`
- Update `terisi` pada `tb_area_parkir`

### 2. Kendaraan Keluar
- Hitung durasi parkir (dalam jam, pembulatan ke atas)
- Ambil tarif dari `tb_tarif`
- Hitung `biaya_total`

---

### 3. Sistem Denda
- Tentukan batas waktu normal (contoh: 2 jam pertama)
- Jika durasi melebihi batas:
  - Tambahkan denda per jam (misalnya +50% dari tarif normal)
- Denda harus langsung tampil di UI saat transaksi keluar

---

### 4. Validasi Sistem
- Tidak boleh kendaraan keluar tanpa data masuk
- Tidak boleh melebihi kapasitas area parkir
- Sinkronisasi jumlah `terisi` harus konsisten

---

### 5. Logging Aktivitas
Semua aktivitas disimpan ke `tb_log_aktivitas`:
- Login / Logout
- Kendaraan masuk / keluar
- Perubahan data

---

## 📊 Fitur Tambahan

### Dashboard
- Total kendaraan aktif
- Total pendapatan hari ini
- Statistik sederhana

### Notifikasi
- Alert jika area parkir hampir penuh

### Search & Filter
- Berdasarkan plat nomor
- Status parkir

---

## 🎨 UI/UX Requirements

- Minimalis & modern
- Responsif (mobile-friendly)
- Fokus pada kecepatan input (khusus petugas)
- Gunakan warna netral + aksen elegan

---

## 🧩 Output yang Diharapkan

- Struktur folder Laravel yang rapi
- Model + relasi Eloquent
- Controller + Service Layer
- Middleware role-based access
- Blade views
- Implementasi logika parkir & denda

---

## 🚨 Best Practices

- Gunakan Eloquent relationships
- Hindari logic di controller (gunakan Service)
- Gunakan Form Request Validation
- Gunakan database transaction untuk operasi penting

---

## 🧪 Bonus (Opsional)

- Unit test untuk perhitungan tarif & denda
- API endpoint untuk integrasi eksternal (misalnya gate otomatis / IoT)

---

## ⚠️ Catatan Tambahan

- Pertimbangkan penambahan field `denda` jika ingin disimpan di database
- Pastikan desain database mendukung skalabilitas
- Hindari inkonsistensi data pada `tb_transaksi` dan `tb_area_parkir`

---