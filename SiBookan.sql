-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2025 at 08:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_kelas`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_ruangan`
--

CREATE TABLE `booking_ruangan` (
  `id_booking` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `durasi` int(11) DEFAULT NULL,
  `jumlah_sks` int(11) NOT NULL,
  `nomor_ruangan` varchar(20) NOT NULL,
  `id_matkul` int(11) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `status_booking` enum('Tersedia','Dipakai') DEFAULT 'Tersedia',
  `id_dosen` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_ruangan`
--

INSERT INTO `booking_ruangan` (`id_booking`, `tanggal`, `jam_mulai`, `durasi`, `jumlah_sks`, `nomor_ruangan`, `id_matkul`, `kelas`, `status_booking`, `id_dosen`, `created_at`, `updated_at`) VALUES
(1, '2025-06-04', '09:30:00', NULL, 1, 'A10.01.15', 2, 'TI23C', '', 1, '2025-06-03 01:15:19', '2025-06-03 01:15:19'),
(2, '2025-06-03', '11:10:00', 50, 1, 'A10.01.13', 1, 'TI23C', 'Dipakai', 1, '2025-06-03 01:25:13', '2025-06-03 01:25:13'),
(3, '2025-06-03', '07:00:00', 100, 2, 'A10.01.01', 2, 'TI23C', 'Dipakai', 1, '2025-06-03 01:26:04', '2025-06-03 01:26:04'),
(4, '2025-06-03', '12:50:00', 150, 3, 'A10.01.02', 1, 'TI23C', 'Dipakai', 1, '2025-06-03 13:26:03', '2025-06-03 13:26:03'),
(5, '2025-06-03', '13:40:00', 100, 2, 'A10.01.04', 2, 'TI23A', 'Dipakai', 1, '2025-06-03 13:45:15', '2025-06-03 13:45:15'),
(6, '2025-06-03', '14:30:00', 200, 4, 'A10.01.08', 2, 'TI23A', 'Dipakai', 1, '2025-06-03 13:46:13', '2025-06-03 13:46:13'),
(10, '2025-06-04', '14:30:00', 100, 2, 'A10.01.05', 3, 'TI23C', 'Dipakai', 2, '2025-06-04 00:56:21', '2025-06-04 00:56:21'),
(11, '2025-06-06', '08:40:00', 100, 2, 'A10.01.14', 2, 'TI23C', 'Dipakai', 1, '2025-06-04 01:06:47', '2025-06-04 01:06:47');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int(11) NOT NULL,
  `username` varchar(1000) NOT NULL,
  `nama_dosen` varchar(100) NOT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `password` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `username`, `nama_dosen`, `nip`, `password`) VALUES
(1, 'ichiboy', 'Rifqi Abdillah', '1234', 'ichiboy'),
(2, 'farhana', 'Farhanna Mar\'ie', NULL, 'farhana');

-- --------------------------------------------------------

--
-- Table structure for table `dosen_mata_kuliah`
--

CREATE TABLE `dosen_mata_kuliah` (
  `id_dosen` int(11) NOT NULL,
  `id_matkul` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosen_mata_kuliah`
--

INSERT INTO `dosen_mata_kuliah` (`id_dosen`, `id_matkul`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_matkul` int(11) NOT NULL,
  `nama_matkul` varchar(100) NOT NULL,
  `kode_matkul` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_matkul`, `nama_matkul`, `kode_matkul`) VALUES
(1, 'PBP', '1'),
(2, 'PBO', '2'),
(3, 'Basis Data', '3'),
(4, 'Statistika', '4');

-- --------------------------------------------------------

--
-- Table structure for table `penanggung_jawab`
--

CREATE TABLE `penanggung_jawab` (
  `nama` varchar(100) NOT NULL,
  `nim` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `kelas` varchar(100) NOT NULL,
  `matkul` varchar(1000) NOT NULL,
  `dosen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penanggung_jawab`
--

INSERT INTO `penanggung_jawab` (`nama`, `nim`, `password`, `kelas`, `matkul`, `dosen`) VALUES
('Muhammad Zulhan Fadhil', '23051204084', 'zulhan', 'TI23C', 'PBO', 'Rifqi Abdillah');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_ruangan`
--
ALTER TABLE `booking_ruangan`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_matkul` (`id_matkul`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- Indexes for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD PRIMARY KEY (`id_dosen`,`id_matkul`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_matkul`),
  ADD UNIQUE KEY `kode_matkul` (`kode_matkul`);

--
-- Indexes for table `penanggung_jawab`
--
ALTER TABLE `penanggung_jawab`
  ADD PRIMARY KEY (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_ruangan`
--
ALTER TABLE `booking_ruangan`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_matkul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_ruangan`
--
ALTER TABLE `booking_ruangan`
  ADD CONSTRAINT `booking_ruangan_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`),
  ADD CONSTRAINT `booking_ruangan_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`);

--
-- Constraints for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD CONSTRAINT `dosen_mata_kuliah_ibfk_1` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_mata_kuliah_ibfk_2` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
