-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 28, 2025 at 05:48 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sim_yudisium`
--

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `id` int NOT NULL,
  `nama_periode` varchar(50) DEFAULT NULL,
  `status` enum('Aktif','Selesai') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `periode`
--

INSERT INTO `periode` (`id`, `nama_periode`, `status`) VALUES
(1, 'Periode I Tahun 2024', 'Aktif'),
(2, 'Periode II Tahun 2024', 'Aktif'),
(3, 'Periode I Tahun 2025', 'Aktif'),
(4, 'Periode II Tahun 2025', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `npm_nik` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Staf BAA','Staf Prodi','Mahasiswa') DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `npm_nik`, `nama`, `email`, `password`, `role`, `prodi`) VALUES
(1, 'admin01', 'Admin Utama', 'eml5YlE3aW5oMDhVSFFONHlGNytXTlJqckJ6VVFLRXM2enFKYTBKbGdkST0=', '$2y$10$0nrluQhMTQBPWdOy.oFf4eXz8POFXCS/4aVLiADOpRUkUchuqlAWK', 'Admin', NULL),
(2, 'baa01', 'Staf BAA Hendra', 'ZGxUWXJ1U2o2SklJV1VFeTR4TTVOU2RJZDRyb2ZXaVlJMTJQR2JNM3lrYz0=', '$2y$10$wpT06XSgSAIF4egDF5Sfm.j9Ukufs5o.OGVdLaCj1qM.MBmXEFLUe', 'Staf BAA', NULL),
(3, 'prodi01', 'Staf Prodi Santi', 'emEzVkEvSlFxd2ltOTlXODYzWlprL0x1YUpkcUowUWNScVl0SUtEYmpDVT0=', '$2y$10$qbOxzmnVvar.kDJ.THWck.JZdtK93/RX.a40Rw2ZwpInQfuJvKC.a', 'Staf Prodi', NULL),
(4, '1234567', 'bais', 'bais@gmail.com', '$2y$10$5pkMocGGgy/A/Wv5sy0pcu6NYloP4r5zOimjun6418aUAH.eWLOm.', 'Mahasiswa', 'Informatika');

-- --------------------------------------------------------

--
-- Table structure for table `yudisium`
--

CREATE TABLE `yudisium` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `periode_id` int DEFAULT NULL,
  `no_sk_yudisium` varchar(50) DEFAULT NULL,
  `tgl_sk` date DEFAULT NULL,
  `tgl_mulai_kuliah` date DEFAULT NULL,
  `tgl_ujian` date DEFAULT NULL,
  `ipk` decimal(4,2) DEFAULT NULL,
  `predikat` varchar(50) DEFAULT NULL,
  `peringkat` int DEFAULT NULL,
  `nina` varchar(50) DEFAULT 'Belum Terbit',
  `status_validasi` enum('Pending','Valid') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `yudisium`
--

INSERT INTO `yudisium` (`id`, `user_id`, `periode_id`, `no_sk_yudisium`, `tgl_sk`, `tgl_mulai_kuliah`, `tgl_ujian`, `ipk`, `predikat`, `peringkat`, `nina`, `status_validasi`) VALUES
(1, 2, 4, 'sk/123/2025', '2025-12-28', '2025-12-28', '2025-12-28', '4.00', 'sangat memuaskan', 1, 'terbit', 'Valid'),
(2, 4, 2, 'sk/12345/2024', '2025-12-28', '2025-12-28', '2025-12-28', '3.75', 'pujian', 3, 'Terbit', 'Valid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `npm_nik` (`npm_nik`);

--
-- Indexes for table `yudisium`
--
ALTER TABLE `yudisium`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `periode_id` (`periode_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `periode`
--
ALTER TABLE `periode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `yudisium`
--
ALTER TABLE `yudisium`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `yudisium`
--
ALTER TABLE `yudisium`
  ADD CONSTRAINT `yudisium_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `yudisium_ibfk_2` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
