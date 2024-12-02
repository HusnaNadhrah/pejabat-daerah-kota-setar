-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2024 at 02:04 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_requests`
--

CREATE TABLE `booking_requests` (
  `id` int NOT NULL,
  `tajuk_mesyuarat` varchar(255) NOT NULL,
  `pengerusi` varchar(255) NOT NULL,
  `tarikh` date NOT NULL,
  `masa_mula` time NOT NULL,
  `masa_akhir` time NOT NULL,
  `bilik_gerakan` tinyint(1) DEFAULT '0',
  `bilik_pegawai` tinyint(1) DEFAULT '0',
  `bilik_pkob` tinyint(1) DEFAULT '0',
  `pentadbiran` tinyint(1) DEFAULT '0',
  `pembangunan` tinyint(1) DEFAULT '0',
  `agensi_luar` tinyint(1) DEFAULT '0',
  `masa` time NOT NULL,
  `perakam_suara` tinyint(1) DEFAULT '0',
  `webcam` tinyint(1) DEFAULT '0',
  `laptop` tinyint(1) DEFAULT '0',
  `projector` tinyint(1) DEFAULT '0',
  `pointer` tinyint(1) DEFAULT '0',
  `jumlah_ahli` varchar(255) NOT NULL,
  `makan_pagi` tinyint(1) DEFAULT '0',
  `makan_tengahari` tinyint(1) DEFAULT '0',
  `makan_petang` tinyint(1) DEFAULT '0',
  `bilik_makan` tinyint(1) DEFAULT '0',
  `bilik_makan_vip` tinyint(1) DEFAULT '0',
  `menu` text,
  `kaedah_hidangan` enum('VIP','BUFET','FOOD CONTAINER') DEFAULT NULL,
  `bilangan` int NOT NULL,
  `nama_pemohon` varchar(255) NOT NULL,
  `jabatan_unit` varchar(255) NOT NULL,
  `no_telefon` varchar(20) NOT NULL,
  `tarikh_submit` date NOT NULL,
  `surat_jemputan` text,
  `senarai_jemputan` text,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `booking_requests`
--

INSERT INTO `booking_requests` (`id`, `tajuk_mesyuarat`, `pengerusi`, `tarikh`, `masa_mula`, `masa_akhir`, `bilik_gerakan`, `bilik_pegawai`, `bilik_pkob`, `pentadbiran`, `pembangunan`, `agensi_luar`, `masa`, `perakam_suara`, `webcam`, `laptop`, `projector`, `pointer`, `jumlah_ahli`, `makan_pagi`, `makan_tengahari`, `makan_petang`, `bilik_makan`, `bilik_makan_vip`, `menu`, `kaedah_hidangan`, `bilangan`, `nama_pemohon`, `jabatan_unit`, `no_telefon`, `tarikh_submit`, `surat_jemputan`, `senarai_jemputan`, `status`, `created_at`) VALUES
(2, 'Consequatur tempore', 'In amet laborum Ul', '2006-12-28', '07:48:00', '02:49:00', 1, 0, 1, 1, 1, 0, '18:24:00', 1, 1, 0, 0, 0, 'Et iure ullamco susc', 1, 0, 0, 0, 0, 'Qui consequatur et ', 'FOOD CONTAINER', 43, 'Consequat Quisquam ', 'Pariatur Voluptas e', '49', '1981-04-05', 'In repudiandae delec', 'Cupidatat quia excep', 'APPROVED', '2024-11-17 10:56:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jabatan` enum('pentadbiran','pembangunan','agensi_luar') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `jabatan`, `username`, `password`, `created_at`) VALUES
(1, 'Ali Bin Abu', 'hazim@gmail.com', 'agensi_luar', 'hakas', '$2y$10$HjTwIj29azEMMw34GdBpJuwf8xmRjJ3h6rCaM7uomvjyQoeLskzC6', '2024-11-17 10:50:19'),
(2, 'Arsenio Cline', 'pamyxob@mailinator.com', 'pentadbiran', 'kedybe', '$2y$10$7c12DeT8qUAJk2WnUv/Ehe.0v9gcuDPOR05kkob7hSSdBWOl8BPaa', '2024-11-17 10:51:45'),
(3, 'Ahmad Bin Mujib', 'mujib@gmail.com', 'pembangunan', 'mujib', '$2y$10$wqHp78evwL9bKlq9E8lsg.xZ9mWPf87l.Nz3BtLNavi7XApUoiMom', '2024-11-17 10:56:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_requests`
--
ALTER TABLE `booking_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
