-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 20 Des 2025 pada 14.31
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agendain`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `certificates`
--

CREATE TABLE `certificates` (
  `certificate_id` int NOT NULL,
  `participant_id` int NOT NULL,
  `unique_code` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `generated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `certificates`
--

INSERT INTO `certificates` (`certificate_id`, `participant_id`, `unique_code`, `file_path`, `generated_at`) VALUES
(11, 9, 'CERT-B278A3224F-9', 'sertifikat/sertifikat_14_9.pdf', '2025-11-15 14:18:53'),
(12, 10, 'CERT-AE1A7D4A12-10', 'sertifikat/sertifikat_14_10.pdf', '2025-11-15 14:18:53'),
(13, 11, 'CERT-7786E0D9CF-11', 'sertifikat/sertifikat_14_11.pdf', '2025-11-15 14:18:53'),
(14, 12, 'CERT-98F3BACAF4-12', 'sertifikat/sertifikat_14_12.pdf', '2025-11-15 14:18:53'),
(15, 13, 'CERT-9D23873FDD-13', 'sertifikat/sertifikat_14_13.pdf', '2025-11-15 14:18:53'),
(16, 14, 'CERT-06BDAA633C-14', 'sertifikat/sertifikat_14_14.pdf', '2025-11-15 14:18:53'),
(17, 15, 'CERT-DB997C4621-15', 'sertifikat/sertifikat_14_15.pdf', '2025-11-15 14:18:53'),
(18, 16, 'CERT-15FC10CDE1-16', 'sertifikat/sertifikat_14_16.pdf', '2025-11-15 14:18:53'),
(19, 17, 'CERT-95265DB9C6-17', 'sertifikat/sertifikat_14_17.pdf', '2025-11-15 14:18:53'),
(20, 18, 'CERT-B3A839E0B1-18', 'sertifikat/sertifikat_14_18.pdf', '2025-11-15 14:18:53'),
(21, 19, 'CERT-6FAC85C915-19', 'sertifikat/sertifikat_14_19.pdf', '2025-11-15 14:18:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `user_id` int NOT NULL,
  `nama_event` varchar(255) NOT NULL,
  `deskripsi` text,
  `tanggal_mulai` datetime NOT NULL,
  `kategori_event` enum('Kampus','Umum') NOT NULL,
  `gambar_event` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`event_id`, `user_id`, `nama_event`, `deskripsi`, `tanggal_mulai`, `kategori_event`, `gambar_event`, `created_at`) VALUES
(14, 8, 'Techno', 'Ada deh', '2025-10-29 22:00:00', 'Kampus', '', '2025-11-15 14:01:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `participants`
--

CREATE TABLE `participants` (
  `participant_id` int NOT NULL,
  `event_id` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kategori_peserta` enum('Mahasiswa','Umum') NOT NULL,
  `nim` varchar(50) DEFAULT NULL,
  `universitas` varchar(255) DEFAULT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `status_kehadiran` tinyint(1) DEFAULT '0',
  `registered_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `participants`
--

INSERT INTO `participants` (`participant_id`, `event_id`, `nama_lengkap`, `email`, `kategori_peserta`, `nim`, `universitas`, `instansi`, `nomor_telepon`, `status_kehadiran`, `registered_at`) VALUES
(9, 14, 'A', 'A@gmail.com', 'Mahasiswa', '1', 'A', NULL, NULL, 1, '2025-11-15 14:01:54'),
(10, 14, 'B', 'B@gmail.com', 'Mahasiswa', '2', 'B', NULL, NULL, 1, '2025-11-15 14:03:04'),
(11, 14, 'C', 'C@gmail.com', 'Mahasiswa', '3', 'C', NULL, NULL, 1, '2025-11-15 14:04:09'),
(12, 14, 'D', 'D@gmail.com', 'Mahasiswa', '4', 'D', NULL, NULL, 1, '2025-11-15 14:05:30'),
(13, 14, 'E', 'F@gmail.com', 'Mahasiswa', '6', 'F', NULL, NULL, 1, '2025-11-15 14:08:34'),
(14, 14, 'F', 'E@gmail.com', 'Mahasiswa', '7', 'F', NULL, NULL, 1, '2025-11-15 14:12:30'),
(15, 14, 'G', 'G@gmail.com', 'Mahasiswa', '8', 'G', NULL, NULL, 1, '2025-11-15 14:13:31'),
(16, 14, 'H', 'H@gmil.com', 'Mahasiswa', '8', 'H', NULL, NULL, 1, '2025-11-15 14:15:58'),
(17, 14, 'I', 'I@gmail.com', 'Mahasiswa', '9', 'I', NULL, NULL, 1, '2025-11-15 14:16:21'),
(18, 14, 'J', 'J@gmail.com', 'Mahasiswa', '10', 'J', NULL, NULL, 1, '2025-11-15 14:16:49'),
(19, 14, 'K', 'K@gmail.com', 'Mahasiswa', '11', 'K', NULL, NULL, 1, '2025-11-15 14:17:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `participant_accounts`
--

CREATE TABLE `participant_accounts` (
  `account_id` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `participant_accounts`
--

INSERT INTO `participant_accounts` (`account_id`, `nama_lengkap`, `email`, `password`, `created_at`) VALUES
(1, 'Amba', 'amba@gmail.com', '$2y$10$FRcrpmRQ7Hvk1DKSpqDs1uRTjqrYfX9tLCziOtr99FHf6Pg7pdfSC', '2025-10-23 14:01:54'),
(2, 'A', 'A@gmail.com', '$2y$10$MsLHqMzMAU1kIMclc15rRufqsSM8mdcNCDQct2obdi51gS2dbwZl2', '2025-11-15 14:01:54'),
(3, 'B', 'B@gmail.com', '$2y$10$mG7cbHQzA2XZ.3zwhVXjyuRt09yH6Zn2VdhLJ5pHb3sioLOAI5waS', '2025-11-15 14:03:04'),
(4, 'C', 'C@gmail.com', '$2y$10$FTQGUEq4gPSzsd1gNLeSE.rM/EUjCH64i5NmRNi8fHp9x7c/Z63Ua', '2025-11-15 14:04:09'),
(5, 'D', 'D@gmail.com', '$2y$10$lUk.8wapoLBWncT0Ys9G0.1Rs9/qelf3vqPO2MHyzWcqPYlLQanwS', '2025-11-15 14:05:30'),
(6, 'E', 'F@gmail.com', '$2y$10$ygoY1cOmEmj/Wl.PXutG5.uNgB5jO3y2.QAZg5s4ivl90FFC4Vd02', '2025-11-15 14:08:34'),
(7, 'F', 'E@gmail.com', '$2y$10$57LHM3ySCTeoVoiOcG0kpeYS49WNAVCCvxkYPzE9hBfK.TutmaZCC', '2025-11-15 14:12:30'),
(8, 'G', 'G@gmail.com', '$2y$10$2UiRmDR3nY67.sU8qdTqi.qoagpK6XwtkvszQWEAL/kc7frxrnZ.q', '2025-11-15 14:13:31'),
(9, 'H', 'H@gmil.com', '$2y$10$SLVKym.XrWJW28cYRW8Xhutw3MT0S0pSpng8.MNt4XVeP6GEP88HW', '2025-11-15 14:15:58'),
(10, 'I', 'I@gmail.com', '$2y$10$e3dbfCdO8Vjv.axFtEGhnuKUUGkvzz/7jYWYUHMVg4yD8UjIVaJvq', '2025-11-15 14:16:21'),
(11, 'J', 'J@gmail.com', '$2y$10$jGDc/Pk5J8A9RIns7I7YJuEKF/S/9Wtb/CzH2bLlGG0TmDJToHUcS', '2025-11-15 14:16:49'),
(12, 'K', 'K@gmail.com', '$2y$10$AROz1yPwaFmLxNdEAPAQBeFcJno1YokEykAv/cpB9FaLKyleRT90i', '2025-11-15 14:17:35'),
(13, 'RE', 're@gmail.com', '$2y$10$JJekM8e5/C0HoJ6KMVlnqu.7N9M4AVl9t.ZFWawW.EIj2A/mSzWly', '2025-11-16 12:35:33'),
(14, 'ahmad', 'ahmad@gmail.com', '$2y$10$LYNZpmrdNscw6ysTIR.eUeQETOpI2YCMeFMnN5vhRP8fk85d.8Jb2', '2025-11-16 12:37:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `nama_organisasi` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `nama_organisasi`, `email`, `password`, `created_at`) VALUES
(8, 'BEM ITG', 'bem@itg.ac.id', '$2y$10$hUI/jq9ehymJO97YzBjSIu2SOVmJsS9ot0PRIkgDL0YXLifaQB7f2', '2025-11-15 14:00:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`certificate_id`),
  ADD UNIQUE KEY `unique_code` (`unique_code`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indeks untuk tabel `participant_accounts`
--
ALTER TABLE `participant_accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `certificates`
--
ALTER TABLE `certificates`
  MODIFY `certificate_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `participants`
--
ALTER TABLE `participants`
  MODIFY `participant_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `participant_accounts`
--
ALTER TABLE `participant_accounts`
  MODIFY `account_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`participant_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
