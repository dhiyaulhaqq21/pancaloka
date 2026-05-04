-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Bulan Mei 2026 pada 20.22
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pancaloka`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `judul`, `penulis`, `deskripsi`, `cover`, `created_at`) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Kisah inspiratif anak Belitung', 'laskar.jpg', '2026-05-03 05:35:13'),
(2, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Sejarah dan perjuangan', 'bumi.jpg', '2026-05-03 05:35:13'),
(3, 'Atomic Habits', 'James Clear', 'Perubahan kecil berdampak besar', 'atomic.jpg', '2026-05-03 05:35:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `diskusi`
--

CREATE TABLE `diskusi` (
  `id` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `buku_id` int(11) DEFAULT NULL,
  `dibuat_oleh` int(11) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `diskusi`
--

INSERT INTO `diskusi` (`id`, `judul`, `deskripsi`, `buku_id`, `dibuat_oleh`, `tanggal`, `created_at`) VALUES
(1, 'Diskusi Laskar Pelangi', 'Membahas nilai pendidikan', 1, 2, '2026-05-03 12:35:13', '2026-05-03 05:35:13'),
(2, 'Bedah Buku Bumi Manusia', 'Sejarah dan kritik sosial', 2, 1, '2026-05-03 12:35:13', '2026-05-03 05:35:13'),
(3, 'Atomic Habits Weekly Talk', 'Membentuk kebiasaan baik', 3, 2, '2026-05-03 12:35:13', '2026-05-03 05:35:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `diskusi_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `isi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`id`, `diskusi_id`, `user_id`, `isi`, `created_at`) VALUES
(1, 1, 3, 'Buku ini sangat menginspirasi!', '2026-05-03 05:35:13'),
(2, 1, 4, 'Setuju, terutama tentang pendidikan', '2026-05-03 05:35:13'),
(3, 2, 5, 'Banyak pelajaran sejarah penting', '2026-05-03 05:35:13'),
(4, 3, 3, 'Saya sudah coba tips dari buku ini', '2026-05-03 05:35:13'),
(5, 3, 4, 'Sangat relevan untuk kehidupan sehari-hari', '2026-05-03 05:35:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `like_diskusi`
--

CREATE TABLE `like_diskusi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `diskusi_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `like_diskusi`
--

INSERT INTO `like_diskusi` (`id`, `user_id`, `diskusi_id`, `created_at`) VALUES
(1, 3, 1, '2026-05-03 05:35:13'),
(2, 4, 1, '2026-05-03 05:35:13'),
(3, 5, 2, '2026-05-03 05:35:13'),
(4, 3, 3, '2026-05-03 05:35:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `like_komentar`
--

CREATE TABLE `like_komentar` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `komentar_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `like_komentar`
--

INSERT INTO `like_komentar` (`id`, `user_id`, `komentar_id`, `created_at`) VALUES
(1, 3, 2, '2026-05-03 05:35:13'),
(2, 4, 1, '2026-05-03 05:35:13'),
(3, 5, 1, '2026-05-03 05:35:13'),
(4, 3, 5, '2026-05-03 05:35:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `komentar` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ulasan`
--

INSERT INTO `ulasan` (`id`, `buku_id`, `user_id`, `rating`, `komentar`, `created_at`) VALUES
(1, 1, 3, 5, 'Sangat bagus dan menyentuh', '2026-05-03 05:35:13'),
(2, 1, 4, 4, 'Ceritanya inspiratif', '2026-05-03 05:35:13'),
(3, 2, 5, 5, 'Wajib dibaca', '2026-05-03 05:35:13'),
(4, 3, 3, 4, 'Praktis dan mudah dipahami', '2026-05-03 05:35:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','staf','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Pancaloka', 'admin@pancaloka.com', '$2y$10$eooIhkIJB8cRx8YBCjqTUOtBTH8NFNSdD5/dLrn6tABywN5TODqpe', 'admin', '2026-05-03 05:35:13'),
(2, 'Staf Diskusi', 'staf@pancaloka.com', '$2y$10$xkAfO91mB6/HF2hLV8s0I.mQYmb4lmhG0IyzIblOSz7NjqUUeDpCO', 'staf', '2026-05-03 05:35:13'),
(3, 'Budi Santoso', 'budi@mail.com', '$2y$10$xvlOv2mOTnJqonrtrVjO2uVbJT3Yc8BFoEC11XhAFppmXJ.EDMcR.', 'user', '2026-05-03 05:35:13'),
(4, 'Siti Aminah', 'siti@mail.com', '$2y$10$xvlOv2mOTnJqonrtrVjO2uVbJT3Yc8BFoEC11XhAFppmXJ.EDMcR.', 'user', '2026-05-03 05:35:13'),
(5, 'Andi Wijaya', 'andi@mail.com', '$2y$10$xvlOv2mOTnJqonrtrVjO2uVbJT3Yc8BFoEC11XhAFppmXJ.EDMcR.', 'user', '2026-05-03 05:35:13'),
(7, 'jajang', 'jajang@mail.com', '$2y$10$PboVrtIMi1JXEYO9LMogs.GXigjYAF24SwXVEEm7lOpzb0bQIeE7i', 'user', '2026-05-03 10:43:19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `diskusi`
--
ALTER TABLE `diskusi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_diskusi_buku` (`buku_id`),
  ADD KEY `fk_diskusi_user` (`dibuat_oleh`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_komentar_diskusi` (`diskusi_id`),
  ADD KEY `fk_komentar_user` (`user_id`);

--
-- Indeks untuk tabel `like_diskusi`
--
ALTER TABLE `like_diskusi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`diskusi_id`),
  ADD KEY `fk_like_diskusi_diskusi` (`diskusi_id`);

--
-- Indeks untuk tabel `like_komentar`
--
ALTER TABLE `like_komentar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`komentar_id`),
  ADD KEY `fk_like_komentar_komentar` (`komentar_id`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buku_id` (`buku_id`,`user_id`),
  ADD KEY `fk_ulasan_user` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `diskusi`
--
ALTER TABLE `diskusi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `like_diskusi`
--
ALTER TABLE `like_diskusi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `like_komentar`
--
ALTER TABLE `like_komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `diskusi`
--
ALTER TABLE `diskusi`
  ADD CONSTRAINT `fk_diskusi_buku` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_diskusi_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `fk_komentar_diskusi` FOREIGN KEY (`diskusi_id`) REFERENCES `diskusi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_komentar_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `like_diskusi`
--
ALTER TABLE `like_diskusi`
  ADD CONSTRAINT `fk_like_diskusi_diskusi` FOREIGN KEY (`diskusi_id`) REFERENCES `diskusi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_like_diskusi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `like_komentar`
--
ALTER TABLE `like_komentar`
  ADD CONSTRAINT `fk_like_komentar_komentar` FOREIGN KEY (`komentar_id`) REFERENCES `komentar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_like_komentar_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `fk_ulasan_buku` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ulasan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
