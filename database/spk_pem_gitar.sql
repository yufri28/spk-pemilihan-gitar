-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Sep 2023 pada 09.51
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_pem_gitar`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$cqd7fPfNS27kxLP/bf/xA.IcqJCjWuIbJoEb.x0wUeTFyM.qSfeqK');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_gitar` varchar(255) NOT NULL,
  `merek` varchar(255) NOT NULL,
  `jenis_senar` varchar(255) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_gitar`, `merek`, `jenis_senar`, `nama_toko`, `gambar`) VALUES
(1, 'YamahaC40', 'Yamaha', 'Nilon', 'Royal Musik', 'Screenshot (7).png'),
(2, 'JadenJA700', 'Jaden', 'Steel', 'AB Musik', NULL),
(3, 'YamahaF310', 'Yamaha', 'Steel', 'AB Musik', NULL),
(4, 'YamahaCX40', 'Yamaha', 'Nilon', 'RoyalMusik', NULL),
(5, 'YamahaFX310', 'Yamaha', 'Steel', 'ABMusik', NULL),
(6, 'YamahaC70', 'Yamaha', 'Nilon', 'RoyalMusik', NULL),
(7, 'Tanglewood Superfolk', 'Tanglewood', 'Steel', 'ABMusik', NULL),
(8, 'CortSFXME', 'Cort', 'Steel', 'ABMusik', NULL),
(9, 'YamahaCGX102', 'Yamaha', 'Nilon', 'DutaMusik', NULL),
(10, 'YamahaC390A', 'Yamaha', 'Nilon', 'RoyalMusik', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kecocokan_alt_kriteria`
--

CREATE TABLE `kecocokan_alt_kriteria` (
  `id_alt_kriteria` int(11) NOT NULL,
  `f_id_alternatif` int(5) NOT NULL,
  `f_id_kriteria` char(2) NOT NULL,
  `f_id_sub_kriteria` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kecocokan_alt_kriteria`
--

INSERT INTO `kecocokan_alt_kriteria` (`id_alt_kriteria`, `f_id_alternatif`, `f_id_kriteria`, `f_id_sub_kriteria`) VALUES
(2, 1, 'C1', 7),
(3, 1, 'C2', 13),
(4, 1, 'C3', 15),
(5, 1, 'C4', 19),
(7, 2, 'C1', 10),
(8, 2, 'C2', 12),
(9, 2, 'C3', 15),
(10, 2, 'C4', 17),
(12, 3, 'C1', 8),
(13, 3, 'C2', 13),
(14, 3, 'C3', 14),
(15, 3, 'C4', 18),
(17, 4, 'C1', 8),
(18, 4, 'C2', 12),
(19, 4, 'C3', 15),
(20, 4, 'C4', 19),
(22, 5, 'C1', 9),
(23, 5, 'C2', 12),
(24, 5, 'C3', 14),
(25, 5, 'C4', 18),
(27, 6, 'C1', 8),
(28, 6, 'C2', 13),
(29, 6, 'C3', 16),
(30, 6, 'C4', 19),
(32, 7, 'C1', 11),
(33, 7, 'C2', 12),
(34, 7, 'C3', 14),
(35, 7, 'C4', 17),
(37, 8, 'C1', 10),
(38, 8, 'C2', 12),
(39, 8, 'C3', 14),
(40, 8, 'C4', 17),
(42, 9, 'C1', 8),
(43, 9, 'C2', 12),
(44, 9, 'C3', 16),
(45, 9, 'C4', 18),
(47, 10, 'C1', 8),
(48, 10, 'C2', 13),
(49, 10, 'C3', 15),
(50, 10, 'C4', 18);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` char(2) NOT NULL,
  `nama_kriteria` varchar(25) NOT NULL,
  `jenis` enum('Cost','Benefit') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `jenis`) VALUES
('C1', 'Harga', 'Cost'),
('C2', 'Jenis Gitar', 'Benefit'),
('C3', 'Bahan Kayu', 'Benefit'),
('C4', 'Bentuk', 'Benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int(5) NOT NULL,
  `nama_sub_kriteria` varchar(25) NOT NULL,
  `bobot_sub_kriteria` int(5) NOT NULL,
  `f_id_kriteria` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `nama_sub_kriteria`, `bobot_sub_kriteria`, `f_id_kriteria`) VALUES
(7, '1.000.000-1.500.000', 5, 'C1'),
(8, '1.500.000-2.000.000', 4, 'C1'),
(9, '2.000.000-2.500.000', 3, 'C1'),
(10, '2.500.000-3.000.000', 2, 'C1'),
(11, '>3.000.000', 1, 'C1'),
(12, 'Akustik Elektrik', 3, 'C2'),
(13, 'Akustik', 1, 'C2'),
(14, 'Mahoni', 3, 'C3'),
(15, 'Meranti', 2, 'C3'),
(16, 'Nato', 1, 'C3'),
(17, 'Grand', 3, 'C4'),
(18, 'Dreadnought', 2, 'C4'),
(19, 'Klasik', 1, 'C4');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  ADD PRIMARY KEY (`id_alt_kriteria`),
  ADD KEY `f_id_alternatif` (`f_id_alternatif`),
  ADD KEY `f_id_kriteria` (`f_id_kriteria`),
  ADD KEY `f_id_sub_kriteria` (`f_id_sub_kriteria`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `f_id_kriteria` (`f_id_kriteria`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  MODIFY `id_alt_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kecocokan_alt_kriteria`
--
ALTER TABLE `kecocokan_alt_kriteria`
  ADD CONSTRAINT `kecocokan_alt_kriteria_ibfk_1` FOREIGN KEY (`f_id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kecocokan_alt_kriteria_ibfk_2` FOREIGN KEY (`f_id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kecocokan_alt_kriteria_ibfk_3` FOREIGN KEY (`f_id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
