-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2025 at 05:01 PM
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
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `cabang_id` int(11) NOT NULL,
  `nama_cabang` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL COMMENT 'Jumlah hewan yang dapat ditampung',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`cabang_id`, `nama_cabang`, `alamat`, `no_telepon`, `kapasitas`, `created_at`) VALUES
(1, 'Pet Hotel Jakarta Pusat', 'Jl. Thamrin No. 123, Jakarta Pusat', '021-1234567', 50, '2024-01-15 08:00:00'),
(2, 'Pet Hotel Bandung', 'Jl. Dago No. 45, Bandung', '022-7654321', 30, '2024-01-15 08:00:00'),
(3, 'Pet Hotel Surabaya', 'Jl. Tunjungan No. 78, Surabaya', '031-9876543', 40, '2024-01-15 08:00:00');

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

--
-- Dumping data for table `hewan`
--

INSERT INTO `hewan` (`hewan_id`, `customer_id`, `nama_hewan`, `jenis_hewan`, `ras`, `tanggal_lahir`, `catatan_pemilik`) VALUES
(1, 1, 'Molly', 'Kucing', 'Persia', '2020-05-15', 'Alergi seafood, perlu makanan khusus'),
(2, 1, 'Blacky', 'Anjing', 'Golden Retriever', '2019-08-20', 'Tidak ada alergi, suka bermain bola'),
(3, 2, 'Snowy', 'Kucing', 'Anggora', '2021-02-10', 'Perlu vitamin kulit 2x seminggu'),
(4, 2, 'Ciko', 'Burung', 'Lovebird', '2022-01-05', 'Suka buah-buahan segar'),
(5, 6, 'Milo', 'Anjing', 'Poodle', '2020-11-30', 'Baru selesai operasi kaki, perlu perhatian khusus');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `layanan_id` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`layanan_id`, `nama_layanan`, `deskripsi`, `harga`) VALUES
(1, 'Penitipan Standar', 'Penitipan dasar dengan makan 2x sehari, monitoring harian', 100000),
(2, 'Penitipan Premium', 'Penitipan dengan kamar AC, makan 3x, mainan, dan laporan foto harian', 200000),
(3, 'Grooming Basic', 'Mandi, blow dry, potong kuku, bersihkan telinga', 150000),
(4, 'Grooming Full', 'Grooming basic + scissoring, facial, aromatherapy', 250000),
(5, 'Medical Checkup', 'Pemeriksaan kesehatan rutin oleh dokter hewan', 75000);

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

--
-- Dumping data for table `monitoring_harian`
--

INSERT INTO `monitoring_harian` (`monitoring_id`, `reservasi_id`, `staf_id`, `tanggal_monitoring`, `catatan_kesehatan`, `catatan_aktivitas`, `foto_video_url`, `target_makanan`, `aktual_makanan`) VALUES
(1, 1, 3, '2024-02-01', 'Kondisi sehat, nafsu makan baik', 'Aktif bermain dengan mainan bola', '/uploads/monitoring/molly_day1.jpg', NULL, NULL),
(2, 1, 3, '2024-02-02', 'Masih dalam kondisi sehat', 'Sedikit malas bergerak, mungkin karena cuaca', '/uploads/monitoring/molly_day2.jpg', NULL, NULL),
(3, 2, 5, '2024-02-10', 'Sedikit stres karena lingkungan baru', 'Perlu waktu penyesuaian, berikan mainan favorit', '/uploads/monitoring/snowy_day1.jpg', NULL, NULL),
(4, 2, 5, '2024-02-11', 'Sudah mulai terbiasa, nafsu makan membaik', 'Lebih aktif bermain dengan kucing lain', '/uploads/monitoring/snowy_day2.jpg', NULL, NULL);

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

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`notifikasi_id`, `user_id`, `judul`, `pesan`, `status_baca`, `created_at`) VALUES
(1, 1, 'Reservasi Selesai', 'Reservasi untuk Molly telah selesai. Terima kasih telah menggunakan layanan Pet Hotel!', 1, '2024-02-05 12:30:00'),
(2, 2, 'Update Monitoring Harian', 'Snowy hari ini sudah mulai aktif bermain dan nafsu makan membaik. Lihat foto terbaru di aplikasi.', 0, '2024-02-11 09:15:00'),
(3, 6, 'Pembayaran Tertunda', 'Pembayaran untuk reservasi Milo masih tertunda. Silakan selesaikan pembayaran untuk konfirmasi reservasi.', 0, '2024-01-26 10:00:00'),
(4, 1, 'Promo Spesial', 'Dapatkan diskon 20% untuk reservasi berikutnya! Berlaku hingga akhir bulan.', 0, '2024-02-06 08:00:00');

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

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `reservasi_id`, `total_biaya`, `metode_pembayaran`, `status_pembayaran`, `tanggal_transaksi`) VALUES
(1, 1, 400000, 'Transfer Bank', 'Paid', '2024-02-01 15:30:00'),
(2, 2, 1075000, 'Credit Card', 'Paid', '2024-02-10 11:15:00'),
(3, 3, 1225000, 'Pending', 'Pending', '2024-01-25 14:20:00');

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

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`rekam_medis_id`, `hewan_id`, `dokter_id`, `tanggal_pemeriksaan`, `diagnosa`, `tindakan_medis`, `catatan_dokter`) VALUES
(1, 5, 4, '2024-01-20 10:00:00', 'Patah tulang kaki belakang', 'Operasi pemasangan pen, pemberian obat anti nyeri', 'Perlu istirahat total selama 2 minggu, kontrol rutin setiap 3 hari'),
(2, 1, 4, '2024-02-01 09:00:00', 'Sehat, alergi seafood terkontrol', 'Pemberian vitamin dan pemeriksaan rutin', 'Hindari makanan mengandung seafood, berikan makanan hypoallergenic'),
(3, 3, 4, '2024-02-10 11:00:00', 'Stres ringan karena lingkungan baru', 'Pemberian pheromone therapy dan observasi', 'Monitor perkembangan, berikan lingkungan yang tenang');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `reservasi_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT 'FK ke users',
  `hewan_id` int(11) NOT NULL COMMENT 'FK ke hewan',
  `cabang_id` int(11) NOT NULL COMMENT 'FK ke cabang (lokasi penitipan)',
  `tanggal_checkin` datetime NOT NULL,
  `tanggal_checkout` datetime NOT NULL,
  `status_reservasi` enum('Pending','Confirmed','Completed','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`reservasi_id`, `customer_id`, `hewan_id`, `cabang_id`, `tanggal_checkin`, `tanggal_checkout`, `status_reservasi`) VALUES
(1, 1, 1, 1, '2024-02-01 14:00:00', '2024-02-05 12:00:00', 'Completed'),
(2, 2, 3, 2, '2024-02-10 10:00:00', '2024-02-15 12:00:00', 'Confirmed'),
(3, 6, 5, 3, '2024-02-20 09:00:00', '2024-02-25 12:00:00', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi_layanan`
--

CREATE TABLE `reservasi_layanan` (
  `reservasi_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi_layanan`
--

INSERT INTO `reservasi_layanan` (`reservasi_id`, `layanan_id`) VALUES
(1, 1),
(2, 2),
(2, 3),
(3, 2),
(3, 5);

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama_lengkap`, `email`, `password_hash`, `no_telepon`, `alamat`, `peran`, `created_at`) VALUES
(1, 'Budi Santoso', 'budi@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'Jl. Merdeka No. 10, Jakarta', 'customer', '2024-01-15 08:00:00'),
(2, 'Sari Indah', 'sari@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081298765432', 'Jl. Sudirman No. 25, Jakarta', 'customer', '2024-01-15 08:00:00'),
(3, 'Admin Utama', 'admin@petHotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '021-5551234', 'Jl. Thamrin No. 123, Jakarta', 'admin', '2024-01-15 08:00:00'),
(4, 'Dr. Andi Wijaya', 'drandi@petHotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081355566677', 'Jl. Dago No. 45, Bandung', 'dokter', '2024-01-15 08:00:00'),
(5, 'Staf Admin Bandung', 'admin.bandung@petHotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '022-4445555', 'Jl. Dago No. 45, Bandung', 'admin', '2024-01-15 08:00:00'),
(6, 'Rina Melati', 'rina@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081377788899', 'Jl. Pemuda No. 15, Surabaya', 'customer', '2024-01-15 08:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`cabang_id`);

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
  ADD KEY `cabang_id` (`cabang_id`);

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
-- AUTO_INCREMENT for table `cabang`
--
ALTER TABLE `cabang`
  MODIFY `cabang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`hewan_id`) REFERENCES `hewan` (`hewan_id`),
  ADD CONSTRAINT `reservasi_ibfk_3` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`cabang_id`);

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
