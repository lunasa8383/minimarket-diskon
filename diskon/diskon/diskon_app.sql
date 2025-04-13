-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Apr 2025 pada 04.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diskon_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `diskon` int(11) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `harga`, `diskon`, `stok`) VALUES
(1, 'Chiki Ball', 8500.00, 5, 4),
(2, 'Susu Ultra', 15000.00, 8, 1),
(3, 'Beng - Beng', 5000.00, 4, 1),
(4, 'Coklat Silverqueen', 32000.00, 2, 8),
(6, 'Nugget', 35000.00, 10, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(25) NOT NULL,
  `jumlah_barang` int(25) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `diskon` int(11) NOT NULL,
  `metode_pembayaran` varchar(100) NOT NULL,
  `tanggal_transaksi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_barang`, `nama_barang`, `jumlah_barang`, `total_harga`, `diskon`, `metode_pembayaran`, `tanggal_transaksi`) VALUES
(35, 6, 3, 'Beng - Beng', 2, 9600.00, 4, 'Cash', '2025-04-13'),
(37, 6, 2, 'Susu Ultra', 8, 110400.00, 8, 'Cash', '2025-04-13'),
(38, 7, 1, 'Chiki Ball', 1, 8075.00, 5, 'Cash', '2025-04-13'),
(39, 7, 4, 'Coklat Silverqueen', 1, 31360.00, 2, 'Cash', '2025-04-13'),
(40, 6, 3, 'Beng - Beng', 1, 4800.00, 4, 'Cash', '2025-04-13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$6EBIk..9PRhLq1Ba0RABMOSqA/JL7RqDzGvDOMamu84W1Iq1YFLcq', 'admin'),
(2, 'user', '$2y$10$tgmImEHTdrr2ui5A/UGQfe8lrFqaQ3BJrhRPpjTEgrHWXqWdvaxdK', 'user'),
(5, 'petugas', '$2y$10$0cfIsKzcAhVn0x6.JIZQmee7vR8dcXEUgXETwU71R3mGZPGEsGdB2', 'admin'),
(6, 'adit', '$2y$10$noSEzbYhQ1Zi/4oSlIhbGeEmIFGgUDC66P2150bjUyIiGxmhPIorK', 'user'),
(7, 'dede', '$2y$10$G8OwmEgKRLw7VjOTr89VIupZ1x7lXDsSDTra9ryjrQsFLsGa5zLW6', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`,`id_barang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
