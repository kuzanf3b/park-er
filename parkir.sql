-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2026 at 12:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parkir`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_area_parkir`
--

CREATE TABLE `tb_area_parkir` (
  `id_area` int NOT NULL,
  `nama_area` varchar(100) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL DEFAULT 'motor',
  `kapasitas` int NOT NULL DEFAULT '0',
  `terisi` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_area_parkir`
--

INSERT INTO `tb_area_parkir` (`id_area`, `nama_area`, `jenis_kendaraan`, `kapasitas`, `terisi`, `created_at`, `updated_at`) VALUES
(1, 'Area A - Motor', 'motor', 50, 2, '2026-01-27 02:16:03', '2026-02-10 02:22:21'),
(2, 'Area B - Motor', 'motor', 80, 0, '2026-01-27 02:16:03', '2026-01-27 02:21:16'),
(3, 'Area C - Mobil', 'mobil', 50, 3, '2026-01-27 02:16:03', '2026-02-10 02:23:47'),
(4, 'Area D - Mobil', 'mobil', 40, 0, '2026-01-27 02:16:03', '2026-02-10 00:40:57'),
(5, 'Area E - Truk', 'truk', 20, 1, '2026-01-27 02:16:03', '2026-02-10 00:49:55'),
(6, 'Area F - Bus', 'bus', 20, 2, '2026-02-10 00:43:47', '2026-02-10 01:49:30');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kendaraan`
--

CREATE TABLE `tb_kendaraan` (
  `id_kendaraan` int NOT NULL,
  `plat_nomor` varchar(20) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `pemilik` varchar(100) DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_kendaraan`
--

INSERT INTO `tb_kendaraan` (`id_kendaraan`, `plat_nomor`, `jenis_kendaraan`, `warna`, `pemilik`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'B 1234 ABC', 'mobil', '-hitam', '-', 1, '2026-01-27 02:16:03', '2026-02-10 00:36:06'),
(2, 'D 5678 XYZ', 'motor', 'Merah', 'Ani Wijaya', 1, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(3, 'F 9012 DEF', 'mobil', 'Putih', 'Candra Kusuma', 1, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(4, 'B 3456 GHI', 'motor', 'Biru', 'Dewi Lestari', 2, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(5, 'D 7890 JKL', 'motor', '-hitam', '-', 1, '2026-01-27 02:16:03', '2026-02-10 00:49:06'),
(6, 'Y777777', 'bus', '-hitam', '-', 1, '2026-01-27 02:23:52', '2026-02-10 01:49:30'),
(7, 'NNN', 'mobil', '-hitam', '-sakti', 1, '2026-01-27 02:25:25', '2026-02-10 01:52:32'),
(8, 'B 9877 RED', 'bus', '-hitam', '-', 1, '2026-02-10 00:48:54', '2026-02-10 00:48:54'),
(9, 'B 4321 BCA', 'truk', '-hitam', '-', 1, '2026-02-10 00:49:55', '2026-02-10 00:49:55'),
(10, 'CCCC', 'mobil', '-hitam', '-sakti', 1, '2026-02-10 01:59:58', '2026-02-10 01:59:58'),
(11, 'AAA', 'motor', 'PUTIH', 'SAKTI', 1, '2026-02-10 02:15:43', '2026-02-10 02:15:43'),
(12, 'ASU', 'mobil', 'putih', 'Athaya Aurelia Tyora', 1, '2026-02-10 02:23:29', '2026-02-10 02:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `tb_log_aktivitas`
--

CREATE TABLE `tb_log_aktivitas` (
  `id_log` int NOT NULL,
  `id_user` int NOT NULL,
  `aktivitas` text NOT NULL,
  `waktu_aktivitas` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_log_aktivitas`
--

INSERT INTO `tb_log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu_aktivitas`, `created_at`) VALUES
(1, 1, 'Login ke sistem', '2026-01-27 07:55:00', '2026-01-27 02:16:03'),
(2, 1, 'Kendaraan masuk: B 1234 ABC', '2026-01-27 08:00:00', '2026-01-27 02:16:03'),
(3, 2, 'Login ke sistem', '2026-01-27 09:10:00', '2026-01-27 02:16:03'),
(4, 2, 'Kendaraan masuk: D 5678 XYZ', '2026-01-27 09:15:00', '2026-01-27 02:16:03'),
(5, 1, 'Kendaraan masuk: F 9012 DEF', '2026-01-27 10:00:00', '2026-01-27 02:16:03'),
(6, 1, 'Kendaraan keluar: B 1234 ABC - Biaya: Rp 15.000', '2026-01-27 10:30:00', '2026-01-27 02:16:03'),
(7, 2, 'Kendaraan keluar: D 5678 XYZ - Biaya: Rp 4.000', '2026-01-27 11:00:00', '2026-01-27 02:16:03'),
(8, 2, 'Kendaraan masuk: B 3456 GHI', '2026-01-27 11:30:00', '2026-01-27 02:16:03'),
(9, 1, 'Login ke sistem', '2026-01-27 09:17:44', '2026-01-27 02:17:44'),
(10, 1, 'Logout dari sistem', '2026-01-27 09:19:35', '2026-01-27 02:19:35'),
(11, 2, 'Login ke sistem', '2026-01-27 09:19:46', '2026-01-27 02:19:46'),
(12, 2, 'Kendaraan keluar: B 3456 GHI', '2026-01-27 09:21:16', '2026-01-27 02:21:16'),
(13, 2, 'Mengubah kendaraan: D 7890 JKL', '2026-01-27 09:22:08', '2026-01-27 02:22:08'),
(14, 2, 'Logout dari sistem', '2026-01-27 09:22:33', '2026-01-27 02:22:33'),
(15, 3, 'Login ke sistem', '2026-01-27 09:22:55', '2026-01-27 02:22:55'),
(16, 3, 'Logout dari sistem', '2026-01-27 09:23:20', '2026-01-27 02:23:20'),
(17, 1, 'Login ke sistem', '2026-01-27 09:23:28', '2026-01-27 02:23:28'),
(18, 1, 'Menambah kendaraan: Y777777', '2026-01-27 09:23:52', '2026-01-27 02:23:52'),
(19, 1, 'Kendaraan masuk: NNN', '2026-01-27 09:25:25', '2026-01-27 02:25:25'),
(20, 1, 'Kendaraan keluar: NNN', '2026-01-27 09:26:01', '2026-01-27 02:26:01'),
(21, 1, 'Login ke sistem', '2026-02-10 07:22:04', '2026-02-10 00:22:04'),
(22, 1, 'Logout dari sistem', '2026-02-10 07:23:19', '2026-02-10 00:23:19'),
(23, 2, 'Login ke sistem', '2026-02-10 07:23:29', '2026-02-10 00:23:29'),
(24, 2, 'Logout dari sistem', '2026-02-10 07:23:42', '2026-02-10 00:23:42'),
(25, 3, 'Login ke sistem', '2026-02-10 07:23:58', '2026-02-10 00:23:58'),
(26, 3, 'Logout dari sistem', '2026-02-10 07:24:12', '2026-02-10 00:24:12'),
(27, 1, 'Login ke sistem', '2026-02-10 07:24:27', '2026-02-10 00:24:27'),
(28, 1, 'Kendaraan keluar: F 9012 DEF', '2026-02-10 07:33:42', '2026-02-10 00:33:42'),
(29, 1, 'Kendaraan masuk: B 1234 ABC', '2026-02-10 07:36:06', '2026-02-10 00:36:06'),
(30, 1, 'Kendaraan keluar: B 1234 ABC', '2026-02-10 07:48:27', '2026-02-10 00:48:27'),
(31, 1, 'Kendaraan masuk: B 9877 RED', '2026-02-10 07:48:54', '2026-02-10 00:48:54'),
(32, 1, 'Kendaraan masuk: D 7890 JKL', '2026-02-10 07:49:06', '2026-02-10 00:49:06'),
(33, 1, 'Kendaraan masuk: B 1234 ABC', '2026-02-10 07:49:19', '2026-02-10 00:49:19'),
(34, 1, 'Kendaraan masuk: B 4321 BCA', '2026-02-10 07:49:55', '2026-02-10 00:49:55'),
(35, 1, 'Mengubah area parkir: Area A - Motor', '2026-02-10 07:57:39', '2026-02-10 00:57:39'),
(36, 1, 'Kendaraan masuk: Y777777', '2026-02-10 08:49:30', '2026-02-10 01:49:30'),
(37, 1, 'Kendaraan masuk: NNN', '2026-02-10 08:52:32', '2026-02-10 01:52:32'),
(38, 1, 'Kendaraan masuk: CCCC', '2026-02-10 08:59:58', '2026-02-10 01:59:58'),
(39, 1, 'Kendaraan keluar: CCCC', '2026-02-10 09:00:45', '2026-02-10 02:00:45'),
(40, 1, 'Mendaftarkan kendaraan: AAA', '2026-02-10 09:15:43', '2026-02-10 02:15:43'),
(41, 1, 'Kendaraan masuk: AAA', '2026-02-10 09:21:08', '2026-02-10 02:21:08'),
(42, 1, 'Kendaraan keluar: AAA', '2026-02-10 09:22:08', '2026-02-10 02:22:08'),
(43, 1, 'Kendaraan masuk: AAA', '2026-02-10 09:22:21', '2026-02-10 02:22:21'),
(44, 1, 'Mendaftarkan kendaraan: ASU', '2026-02-10 09:23:29', '2026-02-10 02:23:29'),
(45, 1, 'Kendaraan masuk: ASU', '2026-02-10 09:23:47', '2026-02-10 02:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tarif`
--

CREATE TABLE `tb_tarif` (
  `id_tarif` int NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `tarif_per_jam` decimal(10,0) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_tarif`
--

INSERT INTO `tb_tarif` (`id_tarif`, `jenis_kendaraan`, `tarif_per_jam`, `created_at`, `updated_at`) VALUES
(1, 'motor', 2000, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(2, 'mobil', 5000, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(3, 'truk', 10000, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(4, 'bus', 15000, '2026-01-27 02:16:03', '2026-01-27 02:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_parkir` int NOT NULL,
  `id_kendaraan` int NOT NULL,
  `waktu_masuk` datetime NOT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `id_tarif` int DEFAULT NULL,
  `durasi` int DEFAULT NULL COMMENT 'Durasi dalam jam',
  `biaya_total` decimal(12,0) DEFAULT NULL,
  `status` enum('masuk','keluar') NOT NULL DEFAULT 'masuk',
  `id_user` int DEFAULT NULL COMMENT 'Petugas yang mencatat',
  `id_area` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_parkir`, `id_kendaraan`, `waktu_masuk`, `waktu_keluar`, `id_tarif`, `durasi`, `biaya_total`, `status`, `id_user`, `id_area`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-27 08:00:00', '2026-01-27 10:30:00', 2, 3, 15000, 'keluar', 1, 3, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(2, 2, '2026-01-27 09:15:00', '2026-01-27 11:00:00', 1, 2, 4000, 'keluar', 2, 1, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(3, 3, '2026-01-27 10:00:00', '2026-02-10 07:33:42', 2, 334, 1670000, 'keluar', 1, 4, '2026-01-27 02:16:03', '2026-02-10 00:33:42'),
(4, 4, '2026-01-27 11:30:00', '2026-01-27 09:21:16', 1, 3, 6000, 'keluar', 2, 2, '2026-01-27 02:16:03', '2026-01-27 02:21:16'),
(5, 7, '2026-01-27 09:25:25', '2026-01-27 09:26:01', 1, 1, 2000, 'keluar', 1, 3, '2026-01-27 02:25:25', '2026-01-27 02:26:01'),
(6, 1, '2026-02-10 07:36:06', '2026-02-10 07:48:27', 2, 1, 5000, 'keluar', 1, 3, '2026-02-10 00:36:06', '2026-02-10 00:48:27'),
(7, 8, '2026-02-10 07:48:54', NULL, 4, NULL, NULL, 'masuk', 1, 6, '2026-02-10 00:48:54', '2026-02-10 00:48:54'),
(8, 5, '2026-02-10 07:49:06', NULL, 1, NULL, NULL, 'masuk', 1, 1, '2026-02-10 00:49:06', '2026-02-10 00:49:06'),
(9, 1, '2026-02-10 07:49:19', NULL, 2, NULL, NULL, 'masuk', 1, 3, '2026-02-10 00:49:19', '2026-02-10 00:49:19'),
(10, 9, '2026-02-10 07:49:55', NULL, 3, NULL, NULL, 'masuk', 1, 5, '2026-02-10 00:49:55', '2026-02-10 00:49:55'),
(11, 6, '2026-02-10 08:49:30', NULL, 4, NULL, NULL, 'masuk', 1, 6, '2026-02-10 01:49:30', '2026-02-10 01:49:30'),
(12, 7, '2026-02-10 08:52:32', NULL, 2, NULL, NULL, 'masuk', 1, 3, '2026-02-10 01:52:32', '2026-02-10 01:52:32'),
(13, 10, '2026-02-10 08:59:58', '2026-02-10 09:00:45', 2, 1, 5000, 'keluar', 1, 3, '2026-02-10 01:59:58', '2026-02-10 02:00:45'),
(14, 11, '2026-02-10 09:21:08', '2026-02-10 09:22:08', 1, 1, 2000, 'keluar', 1, 1, '2026-02-10 02:21:08', '2026-02-10 02:22:08'),
(15, 11, '2026-02-10 09:22:21', NULL, 1, NULL, NULL, 'masuk', 1, 1, '2026-02-10 02:22:21', '2026-02-10 02:22:21'),
(16, 12, '2026-02-10 09:23:47', NULL, 2, NULL, NULL, 'masuk', 1, 3, '2026-02-10 02:23:47', '2026-02-10 02:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','petugas','owner') NOT NULL DEFAULT 'petugas',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama_lengkap`, `role`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 1, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(2, 'petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Petugas Parkir 1', 'petugas', 1, '2026-01-27 02:16:03', '2026-01-27 02:16:03'),
(3, 'owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Owner Parkir', 'owner', 1, '2026-01-27 02:16:03', '2026-01-27 02:16:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_area_parkir`
--
ALTER TABLE `tb_area_parkir`
  ADD PRIMARY KEY (`id_area`);

--
-- Indexes for table `tb_kendaraan`
--
ALTER TABLE `tb_kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD UNIQUE KEY `plat_nomor` (`plat_nomor`),
  ADD KEY `fk_kendaraan_user` (`id_user`);

--
-- Indexes for table `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_user` (`id_user`),
  ADD KEY `idx_waktu_aktivitas` (`waktu_aktivitas`);

--
-- Indexes for table `tb_tarif`
--
ALTER TABLE `tb_tarif`
  ADD PRIMARY KEY (`id_tarif`),
  ADD UNIQUE KEY `jenis_kendaraan` (`jenis_kendaraan`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_parkir`),
  ADD KEY `fk_transaksi_kendaraan` (`id_kendaraan`),
  ADD KEY `fk_transaksi_tarif` (`id_tarif`),
  ADD KEY `fk_transaksi_user` (`id_user`),
  ADD KEY `fk_transaksi_area` (`id_area`),
  ADD KEY `idx_waktu_masuk` (`waktu_masuk`),
  ADD KEY `idx_waktu_keluar` (`waktu_keluar`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_area_parkir`
--
ALTER TABLE `tb_area_parkir`
  MODIFY `id_area` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_kendaraan`
--
ALTER TABLE `tb_kendaraan`
  MODIFY `id_kendaraan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tb_tarif`
--
ALTER TABLE `tb_tarif`
  MODIFY `id_tarif` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_parkir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_kendaraan`
--
ALTER TABLE `tb_kendaraan`
  ADD CONSTRAINT `fk_kendaraan_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tb_log_aktivitas`
--
ALTER TABLE `tb_log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `fk_transaksi_area` FOREIGN KEY (`id_area`) REFERENCES `tb_area_parkir` (`id_area`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_kendaraan` FOREIGN KEY (`id_kendaraan`) REFERENCES `tb_kendaraan` (`id_kendaraan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_tarif` FOREIGN KEY (`id_tarif`) REFERENCES `tb_tarif` (`id_tarif`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
