# 🧪 Audit Checklist Aplikasi Sistem Parkir

## 🎯 Role
Kamu adalah seorang **QA Engineer senior** yang bertugas melakukan audit menyeluruh terhadap aplikasi sistem parkir berbasis web.

## 📌 Tugas
1. Mengecek setiap poin checklist secara detail  
2. Menentukan status:
   - ✅ Sudah benar  
   - ⚠️ Perlu diperbaiki  
   - ❌ Tidak ada / belum dibuat  
3. Memberikan penjelasan singkat  
4. Memberikan saran perbaikan yang konkret  

---

## 🧱 1. STRUKTUR & LAYOUT (UI CONSISTENCY)
- [ ] Sidebar tampil di semua halaman  
- [ ] Header/navbar tidak hilang  
- [ ] Menggunakan template/layout utama  
- [ ] Tidak ada halaman tanpa layout  
- [ ] Tampilan responsive (HP/Laptop)  
- [ ] Navigasi menu jelas  

---

## 📊 2. DATA & TABEL
- [ ] Data ditampilkan dalam tabel rapi  
- [ ] Menggunakan pagination (tidak scroll panjang)  
- [ ] Ada fitur pencarian (search)  
- [ ] Ada filter data  
- [ ] Sorting kolom (opsional)  
- [ ] Tidak ada data duplikat  

---

## 🚗 3. MANAJEMEN KENDARAAN
- [ ] Tambah kendaraan  
- [ ] Edit kendaraan  
- [ ] Hapus kendaraan  
- [ ] Validasi input (plat nomor wajib unik)  
- [ ] Sistem menolak plat nomor duplikat  
- [ ] Jenis kendaraan sesuai (motor/mobil/truk/bus)  

---

## 🅿️ 4. AREA PARKIR
- [ ] Menampilkan kapasitas  
- [ ] Menampilkan jumlah terisi  
- [ ] Tidak melebihi kapasitas  
- [ ] Update otomatis saat kendaraan masuk/keluar  
- [ ] Filter sesuai jenis kendaraan  
- [ ] Jika area dihapus saat masih ada kendaraan → muncul warning  

---

## 💰 5. TARIF PARKIR
- [ ] Tarif sesuai jenis kendaraan  
- [ ] Admin bisa mengubah tarif  
- [ ] Tidak ada duplikat jenis kendaraan  

---

## 🔄 6. TRANSAKSI PARKIR
- [ ] Input kendaraan masuk  
- [ ] Input kendaraan keluar  
- [ ] Input hanya pakai plat nomor  
- [ ] Sistem otomatis ambil jenis kendaraan  
- [ ] Sistem otomatis menentukan tarif  
- [ ] Sistem otomatis menentukan area parkir  
- [ ] Waktu masuk tercatat  
- [ ] Waktu keluar tercatat  
- [ ] Perhitungan durasi otomatis  
- [ ] Perhitungan biaya otomatis  
- [ ] Status masuk/keluar jelas  
- [ ] Tidak bisa keluar tanpa masuk  
- [ ] Validasi waktu (keluar > masuk)  
- [ ] Tidak bisa transaksi jika plat belum terdaftar  

---

## 👤 7. USER & ROLE
- [ ] Sistem login berjalan  
- [ ] Role admin/petugas/owner berfungsi  
- [ ] Pembatasan akses sesuai role  
- [ ] User bisa diaktifkan/nonaktifkan  
- [ ] Password terenkripsi (hash)  

---

## 🔗 8. VALIDASI & INTEGRITAS DATA
- [ ] Foreign key berjalan  
- [ ] Tidak ada data tanpa relasi (orphan)  
- [ ] Field wajib tidak boleh kosong  
- [ ] Format input valid  
- [ ] Validasi relasi transaksi dengan kendaraan  

---

## ⚡ 9. PERFORMA & UX
- [ ] Tidak loading lama  
- [ ] Pagination aktif  
- [ ] Query efisien  
- [ ] Index database digunakan  

---

## 📈 10. LAPORAN
- [ ] Laporan harian  
- [ ] Total pemasukan  
- [ ] Data kendaraan masuk/keluar  
- [ ] Filter berdasarkan tanggal  

---

## 🚨 CHECKLIST KHUSUS MASALAH KRITIS
- [ ] Semua halaman menggunakan layout utama  
- [ ] Tidak ada halaman berdiri sendiri  
- [ ] Semua tabel menggunakan pagination  
- [ ] Tidak ada tampilan data terlalu panjang (scroll)  
- [ ] Form tetap dalam template utama  
- [ ] Validasi input berjalan dengan benar  
- [ ] Tidak ada input manual jenis kendaraan di transaksi  
- [ ] Tidak ada input manual area parkir di transaksi  