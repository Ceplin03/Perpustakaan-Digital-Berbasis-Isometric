-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Jan 2026 pada 12.27
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `rack_code` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `cover` text DEFAULT NULL,
  `status` enum('available','borrowed') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id`, `judul`, `kategori`, `rack_code`, `deskripsi`, `cover`, `status`, `created_at`) VALUES
(1, 'Naruto', 'Komik', 'B', 'Naruto', 'https://covers.openlibrary.org/b/id/7335243-M.jpg', 'available', '2026-01-29 04:55:16'),
(2, 'Haikyu!', 'Komik', 'B', 'Haikyu!', 'https://covers.openlibrary.org/b/id/14625801-M.jpg', 'available', '2026-01-29 05:16:01'),
(3, 'Big Data Analytics Vol. 1', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/8562042-M.jpg', 'available', '2026-01-31 06:48:31'),
(4, 'Machine Learning Vol. 2', 'Sains & Teknologi', 'A', 'Buku Machine Learning membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/3670721-M.jpg', 'available', '2026-01-31 06:48:31'),
(5, 'Dasar Pemrograman Vol. 3', 'Sains & Teknologi', 'A', 'Buku Dasar Pemrograman membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5824784-M.jpg', 'available', '2026-01-31 06:48:31'),
(6, 'Internet of Things Vol. 4', 'Sains & Teknologi', 'A', 'Buku Internet of Things membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/1892826-M.jpg', 'available', '2026-01-31 06:48:31'),
(7, 'Revolusi Industri 4.0 Vol. 5', 'Sains & Teknologi', 'A', 'Buku Revolusi Industri 4.0 membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/1352902-M.jpg', 'available', '2026-01-31 06:48:31'),
(8, 'Big Data Analytics Vol. 6', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5114988-M.jpg', 'available', '2026-01-31 06:48:31'),
(9, 'Big Data Analytics Vol. 7', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/6100247-M.jpg', 'available', '2026-01-31 06:48:31'),
(10, 'Machine Learning Vol. 8', 'Sains & Teknologi', 'A', 'Buku Machine Learning membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5380912-M.jpg', 'available', '2026-01-31 06:48:31'),
(11, 'Artificial Intelligence Vol. 9', 'Sains & Teknologi', 'A', 'Buku Artificial Intelligence membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5950675-M.jpg', 'available', '2026-01-31 06:48:31'),
(12, 'Dasar Pemrograman Vol. 10', 'Sains & Teknologi', 'A', 'Buku Dasar Pemrograman membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/6550723-M.jpg', 'available', '2026-01-31 06:48:31'),
(13, 'Algoritma & Struktur Data Vol. 11', 'Sains & Teknologi', 'A', 'Buku Algoritma & Struktur Data membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/4096056-M.jpg', 'available', '2026-01-31 06:48:31'),
(14, 'Keamanan Sistem Vol. 12', 'Sains & Teknologi', 'A', 'Buku Keamanan Sistem membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/3723528-M.jpg', 'available', '2026-01-31 06:48:31'),
(15, 'Revolusi Industri 4.0 Vol. 13', 'Sains & Teknologi', 'A', 'Buku Revolusi Industri 4.0 membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/7668947-M.jpg', 'available', '2026-01-31 06:48:31'),
(16, 'Algoritma & Struktur Data Vol. 14', 'Sains & Teknologi', 'A', 'Buku Algoritma & Struktur Data membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/2681514-M.jpg', 'available', '2026-01-31 06:48:31'),
(17, 'Pengantar Teknologi Informasi Vol. 15', 'Sains & Teknologi', 'A', 'Buku Pengantar Teknologi Informasi membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/7241762-M.jpg', 'available', '2026-01-31 06:48:31'),
(18, 'Revolusi Industri 4.0 Vol. 16', 'Sains & Teknologi', 'A', 'Buku Revolusi Industri 4.0 membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/4340481-M.jpg', 'available', '2026-01-31 06:48:31'),
(19, 'Revolusi Industri 4.0 Vol. 17', 'Sains & Teknologi', 'A', 'Buku Revolusi Industri 4.0 membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/3254296-M.jpg', 'available', '2026-01-31 06:48:31'),
(20, 'Algoritma & Struktur Data Vol. 18', 'Sains & Teknologi', 'A', 'Buku Algoritma & Struktur Data membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5452138-M.jpg', 'available', '2026-01-31 06:48:31'),
(21, 'Revolusi Industri 4.0 Vol. 19', 'Sains & Teknologi', 'A', 'Buku Revolusi Industri 4.0 membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5086549-M.jpg', 'available', '2026-01-31 06:48:31'),
(22, 'Big Data Analytics Vol. 20', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/2976467-M.jpg', 'available', '2026-01-31 06:48:31'),
(23, 'Big Data Analytics Vol. 21', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/4379211-M.jpg', 'available', '2026-01-31 06:48:31'),
(24, 'Artificial Intelligence Vol. 22', 'Sains & Teknologi', 'A', 'Buku Artificial Intelligence membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/4206004-M.jpg', 'available', '2026-01-31 06:48:31'),
(25, 'Artificial Intelligence Vol. 23', 'Sains & Teknologi', 'A', 'Buku Artificial Intelligence membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/3179378-M.jpg', 'available', '2026-01-31 06:48:31'),
(26, 'Internet of Things Vol. 24', 'Sains & Teknologi', 'A', 'Buku Internet of Things membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/4604940-M.jpg', 'available', '2026-01-31 06:48:31'),
(27, 'Revolusi Industri 4.0 Vol. 25', 'Sains & Teknologi', 'A', 'Buku Revolusi Industri 4.0 membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5865621-M.jpg', 'available', '2026-01-31 06:48:31'),
(28, 'Big Data Analytics Vol. 26', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/3917688-M.jpg', 'available', '2026-01-31 06:48:31'),
(29, 'Internet of Things Vol. 27', 'Sains & Teknologi', 'A', 'Buku Internet of Things membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/8747831-M.jpg', 'available', '2026-01-31 06:48:31'),
(30, 'Internet of Things Vol. 28', 'Sains & Teknologi', 'A', 'Buku Internet of Things membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/6175235-M.jpg', 'available', '2026-01-31 06:48:31'),
(31, 'Big Data Analytics Vol. 29', 'Sains & Teknologi', 'A', 'Buku Big Data Analytics membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/2125675-M.jpg', 'available', '2026-01-31 06:48:31'),
(32, 'Internet of Things Vol. 30', 'Sains & Teknologi', 'A', 'Buku Internet of Things membahas topik Sains & Teknologi dan ditempatkan pada rak A.', 'https://covers.openlibrary.org/b/id/5247056-M.jpg', 'available', '2026-01-31 06:48:31'),
(33, 'Tokyo Revengers Vol. 1', 'Komik', 'B', 'Buku Tokyo Revengers membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/5984466-M.jpg', 'available', '2026-01-31 06:48:31'),
(34, 'Bleach Vol. 2', 'Komik', 'B', 'Buku Bleach membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/4882159-M.jpg', 'available', '2026-01-31 06:48:31'),
(35, 'Dragon Ball Vol. 3', 'Komik', 'B', 'Buku Dragon Ball membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/8680329-M.jpg', 'available', '2026-01-31 06:48:31'),
(36, 'Jujutsu Kaisen Vol. 4', 'Komik', 'B', 'Buku Jujutsu Kaisen membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/2761694-M.jpg', 'available', '2026-01-31 06:48:31'),
(37, 'Tokyo Revengers Vol. 5', 'Komik', 'B', 'Buku Tokyo Revengers membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/2020789-M.jpg', 'available', '2026-01-31 06:48:31'),
(38, 'One Piece Vol. 6', 'Komik', 'B', 'Buku One Piece membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/3175547-M.jpg', 'available', '2026-01-31 06:48:31'),
(39, 'Bleach Vol. 7', 'Komik', 'B', 'Buku Bleach membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/6777730-M.jpg', 'available', '2026-01-31 06:48:31'),
(40, 'Dragon Ball Vol. 8', 'Komik', 'B', 'Buku Dragon Ball membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/2041067-M.jpg', 'available', '2026-01-31 06:48:31'),
(41, 'One Piece Vol. 9', 'Komik', 'B', 'Buku One Piece membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/4291832-M.jpg', 'available', '2026-01-31 06:48:31'),
(42, 'Bleach Vol. 10', 'Komik', 'B', 'Buku Bleach membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/7742886-M.jpg', 'available', '2026-01-31 06:48:31'),
(43, 'Tokyo Revengers Vol. 11', 'Komik', 'B', 'Buku Tokyo Revengers membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/8268625-M.jpg', 'available', '2026-01-31 06:48:31'),
(44, 'Haikyuu! Vol. 12', 'Komik', 'B', 'Buku Haikyuu! membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/1134487-M.jpg', 'available', '2026-01-31 06:48:31'),
(45, 'One Piece Vol. 13', 'Komik', 'B', 'Buku One Piece membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/4975303-M.jpg', 'available', '2026-01-31 06:48:31'),
(46, 'Jujutsu Kaisen Vol. 14', 'Komik', 'B', 'Buku Jujutsu Kaisen membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/8596623-M.jpg', 'available', '2026-01-31 06:48:31'),
(47, 'Demon Slayer Vol. 15', 'Komik', 'B', 'Buku Demon Slayer membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/3495749-M.jpg', 'available', '2026-01-31 06:48:31'),
(48, 'Attack on Titan Vol. 16', 'Komik', 'B', 'Buku Attack on Titan membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/4099215-M.jpg', 'available', '2026-01-31 06:48:31'),
(49, 'Attack on Titan Vol. 17', 'Komik', 'B', 'Buku Attack on Titan membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/1019540-M.jpg', 'available', '2026-01-31 06:48:31'),
(50, 'My Hero Academia Vol. 18', 'Komik', 'B', 'Buku My Hero Academia membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/3536016-M.jpg', 'available', '2026-01-31 06:48:31'),
(51, 'Bleach Vol. 19', 'Komik', 'B', 'Buku Bleach membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/1417973-M.jpg', 'available', '2026-01-31 06:48:31'),
(52, 'Jujutsu Kaisen Vol. 20', 'Komik', 'B', 'Buku Jujutsu Kaisen membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/3313268-M.jpg', 'available', '2026-01-31 06:48:31'),
(53, 'Haikyuu! Vol. 21', 'Komik', 'B', 'Buku Haikyuu! membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/6447206-M.jpg', 'available', '2026-01-31 06:48:31'),
(54, 'Attack on Titan Vol. 22', 'Komik', 'B', 'Buku Attack on Titan membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/5549255-M.jpg', 'available', '2026-01-31 06:48:31'),
(55, 'One Piece Vol. 23', 'Komik', 'B', 'Buku One Piece membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/5931547-M.jpg', 'available', '2026-01-31 06:48:31'),
(56, 'Demon Slayer Vol. 24', 'Komik', 'B', 'Buku Demon Slayer membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/4435994-M.jpg', 'available', '2026-01-31 06:48:31'),
(57, 'Bleach Vol. 25', 'Komik', 'B', 'Buku Bleach membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/8733047-M.jpg', 'available', '2026-01-31 06:48:31'),
(58, 'Jujutsu Kaisen Vol. 26', 'Komik', 'B', 'Buku Jujutsu Kaisen membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/5550272-M.jpg', 'available', '2026-01-31 06:48:31'),
(59, 'Attack on Titan Vol. 27', 'Komik', 'B', 'Buku Attack on Titan membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/3850439-M.jpg', 'available', '2026-01-31 06:48:31'),
(60, 'Tokyo Revengers Vol. 28', 'Komik', 'B', 'Buku Tokyo Revengers membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/8281863-M.jpg', 'available', '2026-01-31 06:48:31'),
(61, 'My Hero Academia Vol. 29', 'Komik', 'B', 'Buku My Hero Academia membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/1492934-M.jpg', 'available', '2026-01-31 06:48:31'),
(62, 'Demon Slayer Vol. 30', 'Komik', 'B', 'Buku Demon Slayer membahas topik Komik dan ditempatkan pada rak B.', 'https://covers.openlibrary.org/b/id/6487541-M.jpg', 'available', '2026-01-31 06:48:31'),
(63, 'Strategi Pemasaran Vol. 1', 'Bisnis & Ekonomi', 'C', 'Buku Strategi Pemasaran membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/6141771-M.jpg', 'available', '2026-01-31 06:48:31'),
(64, 'Ekonomi Kreatif Vol. 2', 'Bisnis & Ekonomi', 'C', 'Buku Ekonomi Kreatif membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/2371346-M.jpg', 'available', '2026-01-31 06:48:31'),
(65, 'Investasi Pemula Vol. 3', 'Bisnis & Ekonomi', 'C', 'Buku Investasi Pemula membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/7060142-M.jpg', 'available', '2026-01-31 06:48:31'),
(66, 'Bisnis Digital Vol. 4', 'Bisnis & Ekonomi', 'C', 'Buku Bisnis Digital membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1246312-M.jpg', 'available', '2026-01-31 06:48:31'),
(67, 'Manajemen UMKM Vol. 5', 'Bisnis & Ekonomi', 'C', 'Buku Manajemen UMKM membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/8429897-M.jpg', 'available', '2026-01-31 06:48:31'),
(68, 'Startup Business Vol. 6', 'Bisnis & Ekonomi', 'C', 'Buku Startup Business membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/8160998-M.jpg', 'available', '2026-01-31 06:48:31'),
(69, 'Investasi Pemula Vol. 7', 'Bisnis & Ekonomi', 'C', 'Buku Investasi Pemula membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/5152497-M.jpg', 'available', '2026-01-31 06:48:31'),
(70, 'Kewirausahaan Vol. 8', 'Bisnis & Ekonomi', 'C', 'Buku Kewirausahaan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1782183-M.jpg', 'available', '2026-01-31 06:48:31'),
(71, 'Investasi Pemula Vol. 9', 'Bisnis & Ekonomi', 'C', 'Buku Investasi Pemula membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/2540533-M.jpg', 'available', '2026-01-31 06:48:31'),
(72, 'Dasar Manajemen Vol. 10', 'Bisnis & Ekonomi', 'C', 'Buku Dasar Manajemen membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1283152-M.jpg', 'available', '2026-01-31 06:48:31'),
(73, 'Kewirausahaan Vol. 11', 'Bisnis & Ekonomi', 'C', 'Buku Kewirausahaan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/5002662-M.jpg', 'available', '2026-01-31 06:48:31'),
(74, 'Startup Business Vol. 12', 'Bisnis & Ekonomi', 'C', 'Buku Startup Business membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/4394338-M.jpg', 'available', '2026-01-31 06:48:31'),
(75, 'Kewirausahaan Vol. 13', 'Bisnis & Ekonomi', 'C', 'Buku Kewirausahaan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/7761569-M.jpg', 'available', '2026-01-31 06:48:31'),
(76, 'Pengantar Ekonomi Vol. 14', 'Bisnis & Ekonomi', 'C', 'Buku Pengantar Ekonomi membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/6878410-M.jpg', 'available', '2026-01-31 06:48:31'),
(77, 'Bisnis Digital Vol. 15', 'Bisnis & Ekonomi', 'C', 'Buku Bisnis Digital membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/2668493-M.jpg', 'available', '2026-01-31 06:48:31'),
(78, 'Dasar Manajemen Vol. 16', 'Bisnis & Ekonomi', 'C', 'Buku Dasar Manajemen membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1309062-M.jpg', 'available', '2026-01-31 06:48:31'),
(79, 'Pengantar Ekonomi Vol. 17', 'Bisnis & Ekonomi', 'C', 'Buku Pengantar Ekonomi membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1452209-M.jpg', 'available', '2026-01-31 06:48:31'),
(80, 'Manajemen Keuangan Vol. 18', 'Bisnis & Ekonomi', 'C', 'Buku Manajemen Keuangan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1283791-M.jpg', 'available', '2026-01-31 06:48:31'),
(81, 'Ekonomi Kreatif Vol. 19', 'Bisnis & Ekonomi', 'C', 'Buku Ekonomi Kreatif membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/3668063-M.jpg', 'available', '2026-01-31 06:48:31'),
(82, 'Pengantar Ekonomi Vol. 20', 'Bisnis & Ekonomi', 'C', 'Buku Pengantar Ekonomi membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/8083773-M.jpg', 'available', '2026-01-31 06:48:31'),
(83, 'Strategi Pemasaran Vol. 21', 'Bisnis & Ekonomi', 'C', 'Buku Strategi Pemasaran membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/2008687-M.jpg', 'available', '2026-01-31 06:48:31'),
(84, 'Manajemen Keuangan Vol. 22', 'Bisnis & Ekonomi', 'C', 'Buku Manajemen Keuangan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/3618831-M.jpg', 'available', '2026-01-31 06:48:31'),
(85, 'Strategi Pemasaran Vol. 23', 'Bisnis & Ekonomi', 'C', 'Buku Strategi Pemasaran membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/8766016-M.jpg', 'available', '2026-01-31 06:48:31'),
(86, 'Strategi Pemasaran Vol. 24', 'Bisnis & Ekonomi', 'C', 'Buku Strategi Pemasaran membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/5929763-M.jpg', 'available', '2026-01-31 06:48:31'),
(87, 'Manajemen Keuangan Vol. 25', 'Bisnis & Ekonomi', 'C', 'Buku Manajemen Keuangan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/1061266-M.jpg', 'available', '2026-01-31 06:48:31'),
(88, 'Manajemen Keuangan Vol. 26', 'Bisnis & Ekonomi', 'C', 'Buku Manajemen Keuangan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/6890965-M.jpg', 'available', '2026-01-31 06:48:31'),
(89, 'Startup Business Vol. 27', 'Bisnis & Ekonomi', 'C', 'Buku Startup Business membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/3543423-M.jpg', 'available', '2026-01-31 06:48:31'),
(90, 'Manajemen Keuangan Vol. 28', 'Bisnis & Ekonomi', 'C', 'Buku Manajemen Keuangan membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/4205362-M.jpg', 'available', '2026-01-31 06:48:31'),
(91, 'Investasi Pemula Vol. 29', 'Bisnis & Ekonomi', 'C', 'Buku Investasi Pemula membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/2328665-M.jpg', 'available', '2026-01-31 06:48:31'),
(92, 'Strategi Pemasaran Vol. 30', 'Bisnis & Ekonomi', 'C', 'Buku Strategi Pemasaran membahas topik Bisnis & Ekonomi dan ditempatkan pada rak C.', 'https://covers.openlibrary.org/b/id/3795474-M.jpg', 'available', '2026-01-31 06:48:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrows`
--

CREATE TABLE `borrows` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('borrowed','reserved','returned','cancelled') DEFAULT 'reserved',
  `invoice_code` varchar(50) NOT NULL,
  `late_days` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `borrows`
--

INSERT INTO `borrows` (`id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `status`, `invoice_code`, `late_days`, `created_at`) VALUES
(1, 1, 1, '2026-01-29', '2026-01-30', NULL, 'borrowed', 'INV-20260129-3400', 0, '2026-01-29 05:02:02'),
(2, 5102233, 1, '2026-01-29', '2026-01-31', NULL, 'borrowed', 'INV-20260129-8021', 0, '2026-01-29 05:04:52'),
(3, 1, 2, '2026-01-29', '2026-02-01', '2026-01-29', 'borrowed', 'INV-20260129-1901', 0, '2026-01-29 05:16:17'),
(4, 1, 1, '2026-01-29', '2026-01-30', '2026-01-29', 'returned', 'INV-20260129-1939', 0, '2026-01-29 05:19:52'),
(5, 1, 2, '2026-01-29', '2026-01-30', '2026-01-29', 'cancelled', 'INV-20260129-1121', 0, '2026-01-29 05:20:16'),
(6, 1, 37, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-5795', 0, '2026-01-31 06:50:18'),
(7, 1, 5, '2026-01-31', '2026-02-02', NULL, 'reserved', 'INV-20260131-2500', 0, '2026-01-31 06:57:48'),
(8, 1, 7, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-8904', 0, '2026-01-31 07:00:46'),
(9, 1, 22, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-4314', 0, '2026-01-31 10:49:56'),
(10, 1, 3, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-9017', 0, '2026-01-31 10:50:59'),
(11, 1, 1, '2026-01-31', '2026-02-07', NULL, 'reserved', 'INV-20260131-5274', 0, '2026-01-31 10:56:15'),
(12, 1, 34, '2026-01-31', '2026-02-03', '2026-01-31', 'returned', 'INV-20260131-8379', 0, '2026-01-31 11:00:09'),
(13, 1, 63, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-6702', 0, '2026-01-31 11:02:34'),
(14, 1, 3, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-4923', 0, '2026-01-31 11:02:59'),
(15, 1, 3, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-6098', 0, '2026-01-31 11:22:10'),
(16, 1, 2, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-2501', 0, '2026-01-31 11:22:49'),
(17, 5102233, 33, '2026-01-31', '2026-02-01', NULL, 'reserved', 'INV-20260131-5980', 0, '2026-01-31 11:26:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL COMMENT 'NIS / ID User',
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','siswa') DEFAULT 'siswa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@perpus.sch.id', '$2y$10$msMitEOMKylhqFdHYaB1K.gvx6x4heiNPMt8QX2GbKBBZT3ZY.UL.', 'admin', '2026-01-29 04:53:36'),
(5102233, 'Sepri Iratas', 'sepriiratas430@gmail.com', '$2y$10$Ll9aj0eUP072MwVLlLXH6OVRhPkyQyadiSdV6A2nUje8mWgiG.P8u', 'siswa', '2026-01-29 05:04:29');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_code` (`invoice_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

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
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT untuk tabel `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `borrows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
