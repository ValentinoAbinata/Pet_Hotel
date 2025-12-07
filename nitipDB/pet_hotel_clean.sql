-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2025 at 05:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `hewan`
--

CREATE TABLE `hewan` (
  `hewan_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT 'FK ke users (pemilik)',
  `nama_hewan` varchar(100) NOT NULL,
  `jenis_hewan` varchar(50) DEFAULT NULL,
  `ras` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `catatan_pemilik` text DEFAULT NULL COMMENT 'Alergi, kondisi khusus, dll'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `layanan_id` int(11) NOT NULL,
  `nama_ruang` varchar(100) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`layanan_id`, `nama_ruang`, `nama_layanan`, `deskripsi`, `harga`) VALUES
(1, '101', 'Penitipan Standar', 'Penitipan dasar dengan makan, monitoring harian', 100000),
(2, '102', 'Penitipan Standar', 'Penitipan dasar dengan makan, monitoring harian', 100000),
(3, '103', 'Penitipan Premium', 'Penitipan dengan kamar AC, mainan, dan laporan foto harian', 200000),
(4, 'Ruang Grooming Basic', 'Grooming Basic', 'Mandi, blow dry, potong kuku, bersihkan telinga', 150000),
(5, 'Ruang Grooming Full', 'Grooming Full', 'Grooming basic + scissoring, facial, aromatherapy', 250000),
(6, 'Ruang Medical Checkup', 'Medical Checkup', 'Pemeriksaan kesehatan rutin oleh dokter hewan', 75000);

-- --------------------------------------------------------

--
-- Table structure for table `monitoring_harian`
--

CREATE TABLE `monitoring_harian` (
  `monitoring_id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL COMMENT 'FK ke reservasi yang sedang aktif',
  `staf_id` int(11) DEFAULT NULL COMMENT 'FK ke users (staf admin yang melapor)',
  `tanggal_monitoring` date NOT NULL,
  `catatan_kesehatan` text DEFAULT NULL,
  `catatan_aktivitas` text DEFAULT NULL,
  `foto_video_url` varchar(255) DEFAULT NULL COMMENT 'Link ke media foto/video',
  `target_makanan` tinyint(4) DEFAULT NULL,
  `aktual_makanan` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `notifikasi_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK ke users (penerima notifikasi)',
  `judul` varchar(100) DEFAULT NULL,
  `pesan` text NOT NULL,
  `status_baca` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int(11) NOT NULL,
  `reservasi_id` int(11) NOT NULL COMMENT 'Satu reservasi punya satu tagihan pembayaran',
  `total_biaya` int(11) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status_pembayaran` enum('Pending','Paid','Failed') NOT NULL DEFAULT 'Pending',
  `tanggal_transaksi` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `rekam_medis_id` int(11) NOT NULL,
  `hewan_id` int(11) NOT NULL COMMENT 'FK ke hewan',
  `dokter_id` int(11) DEFAULT NULL COMMENT 'FK ke users (peran dokter)',
  `tanggal_pemeriksaan` datetime NOT NULL,
  `diagnosa` text DEFAULT NULL,
  `tindakan_medis` text DEFAULT NULL,
  `catatan_dokter` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `reservasi_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT 'FK ke users',
  `hewan_id` int(11) NOT NULL COMMENT 'FK ke hewan',
  `tanggal_checkin` datetime NOT NULL,
  `tanggal_checkout` datetime NOT NULL,
  `status_reservasi` enum('Pending','Confirmed','Completed','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservasi_layanan`
--

CREATE TABLE `reservasi_layanan` (
  `reservasi_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `peran` enum('customer','admin','dokter') NOT NULL COMMENT 'Peran pengguna: customer (pemilik), admin (staf/karyawan), dokter',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`hewan_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`layanan_id`);

--
-- Indexes for table `monitoring_harian`
--
ALTER TABLE `monitoring_harian`
  ADD PRIMARY KEY (`monitoring_id`),
  ADD KEY `reservasi_id` (`reservasi_id`),
  ADD KEY `staf_id` (`staf_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`notifikasi_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD UNIQUE KEY `reservasi_id` (`reservasi_id`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`rekam_medis_id`),
  ADD KEY `hewan_id` (`hewan_id`),
  ADD KEY `dokter_id` (`dokter_id`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`reservasi_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `hewan_id` (`hewan_id`),
  ADD KEY `customer_id_2` (`customer_id`);

--
-- Indexes for table `reservasi_layanan`
--
ALTER TABLE `reservasi_layanan`
  ADD PRIMARY KEY (`reservasi_id`,`layanan_id`),
  ADD KEY `layanan_id` (`layanan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hewan`
--
ALTER TABLE `hewan`
  MODIFY `hewan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `layanan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `monitoring_harian`
--
ALTER TABLE `monitoring_harian`
  MODIFY `monitoring_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `notifikasi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `rekam_medis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `reservasi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hewan`
--
ALTER TABLE `hewan`
  ADD CONSTRAINT `hewan_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `monitoring_harian`
--
ALTER TABLE `monitoring_harian`
  ADD CONSTRAINT `monitoring_harian_ibfk_1` FOREIGN KEY (`reservasi_id`) REFERENCES `reservasi` (`reservasi_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `monitoring_harian_ibfk_2` FOREIGN KEY (`staf_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`reservasi_id`) REFERENCES `reservasi` (`reservasi_id`);

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`hewan_id`) REFERENCES `hewan` (`hewan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekam_medis_ibfk_2` FOREIGN KEY (`dokter_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`hewan_id`) REFERENCES `hewan` (`hewan_id`);

--
-- Constraints for table `reservasi_layanan`
--
ALTER TABLE `reservasi_layanan`
  ADD CONSTRAINT `reservasi_layanan_ibfk_1` FOREIGN KEY (`reservasi_id`) REFERENCES `reservasi` (`reservasi_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasi_layanan_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`layanan_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
