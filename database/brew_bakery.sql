-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 01:17 PM
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
-- Database: `brew_bakery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `nama`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin Brew Bakery', 'admin@brewbakery.com', '$2y$10$/ih0haemB3TU1o4Ty2j8fOxXlP8h5YNOyu9m8Lh5v48iqUUuUS.1i', '2025-11-17 14:00:35', '2025-11-17 15:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `isi` longtext NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `judul`, `deskripsi`, `isi`, `foto`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Cara Membuat Roti Tawar Lembut', 'Tips membuat roti tawar yang lembut dan empuk', '<p>Untuk membuat roti tawar yang lembut, Anda perlu:</p><ol><li>Gunakan tepung berkualitas tinggi</li><li>Jangan kurangi air</li><li>Fermentasi dengan waktu yang tepat</li><li>Panggang dengan suhu konsisten</li></ol>', '691c2e14eb463_1763454484.jpg', 1, '2025-11-17 14:00:35', '2025-11-18 08:28:04'),
(2, 'Resep Croissant Paling Enak', 'Croissant dengan laminating yang sempurna', '<p>Croissant yang bagus perlu:</p><ol><li>Butter berkualitas premium</li><li>Laminating yang hati-hati</li><li>Fermentasi dingin (cold fermentation)</li><li>Oven dengan suhu tinggi</li></ol>', NULL, 1, '2025-11-17 14:00:35', '2025-11-17 14:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nama`, `deskripsi`, `created_at`) VALUES
(1, 'Roti Tawar', 'Roti tawar premium dengan bahan berkualitas tinggi', '2025-11-17 14:00:35'),
(2, 'Pastry & Croissant', 'Pastry lezat dengan rasa butter yang nikmat', '2025-11-17 14:00:35'),
(3, 'Kue Basah', 'Kue basah pilihan dengan berbagai varian rasa', '2025-11-17 14:00:35'),
(4, 'Donut & Kolak', 'Donut variatif dengan topping menarik', '2025-11-17 14:00:35'),
(5, 'Bolu & Spesial', 'Bolu tradisional dan modern dengan resep spesial', '2025-11-17 14:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `nama`, `email`, `password`, `no_hp`, `alamat`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', 'budi@gmail.com', '$2y$10$eIxZaYVK3fhqgflTFGLF9.PYQV3c8A6L3w.t3oYhKbRDXQA8iLvVu', '082122334455', 'Jl. Merdeka No. 123, Jakarta', NULL, '2025-11-17 14:00:35', '2025-11-17 14:00:35'),
(2, 'Siti Nurhaliza', 'siti@gmail.com', '$2y$10$eIxZaYVK3fhqgflTFGLF9.PYQV3c8A6L3w.t3oYhKbRDXQA8iLvVu', '083455667788', 'Jl. Sudirman No. 456, Depok', NULL, '2025-11-17 14:00:35', '2025-11-17 14:00:35'),
(3, 'dim', 'dim@gmail.com', '$2y$10$9a4/P4.9XBrqwHNV9ZWEjOCPFmILqJnxkd9wNBSvuGYhypyzItNKG', '08123456789', '', '691c309f9bacf_1763455135.jpg', '2025-11-17 14:17:56', '2025-11-18 08:38:55');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `receiver_type` enum('customer','admin') DEFAULT 'customer',
  `dibaca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_user_id`, `to_user_id`, `pesan`, `receiver_type`, `dibaca`, `created_at`) VALUES
(14, 1, 1, 'Test message', 'admin', 1, '2025-11-18 04:59:38'),
(27, 3, 1, 'yo', 'admin', 1, '2025-11-18 07:37:27'),
(28, 3, 1, 'ppp', 'admin', 1, '2025-11-18 07:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `dibaca` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `customer_id`, `order_id`, `judul`, `pesan`, `dibaca`, `created_at`) VALUES
(1, 3, 1, 'Pesanan Berhasil Dibuat', 'Pesanan #BBK-20251117171050-952 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-17 16:10:50'),
(2, 3, 1, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-17 16:16:53'),
(3, 3, 1, 'Pembayaran Diterima', 'Pembayaran Anda telah diverifikasi. Pesanan akan segera dikemas.', 0, '2025-11-17 16:23:18'),
(4, 3, 1, 'Pembayaran Diterima', 'Pembayaran Anda telah diverifikasi. Pesanan akan segera dikemas.', 0, '2025-11-17 16:24:39'),
(5, 3, 1, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Verifikasi', 0, '2025-11-17 16:24:57'),
(6, 3, 1, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-17 16:25:07'),
(7, 3, 1, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Pembayaran Diterima', 0, '2025-11-17 16:25:48'),
(8, 3, 1, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Siap Dikirim', 0, '2025-11-17 16:26:15'),
(9, 3, 1, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Selesai', 0, '2025-11-17 16:29:43'),
(10, 3, 2, 'Pesanan Berhasil Dibuat', 'Pesanan #BBK-20251117174434-624 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-17 16:44:34'),
(11, 3, 2, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-17 16:44:51'),
(12, 3, 2, 'Pembayaran Ditolak', 'Alasan: bukti tidak sesuai', 0, '2025-11-17 16:45:58'),
(13, 3, 3, 'Pesanan Berhasil Dibuat', 'Pesanan #BBK-20251118044235-875 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 03:42:35'),
(14, 3, 3, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-18 03:42:57'),
(15, 3, 3, 'Pembayaran Diterima', 'Pembayaran Anda telah diverifikasi. Pesanan akan segera dikemas.', 0, '2025-11-18 03:43:41'),
(16, 3, 3, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Bukti Pembayaran', 0, '2025-11-18 03:43:48'),
(17, 3, 3, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Verifikasi', 0, '2025-11-18 03:43:51'),
(18, 3, 3, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Pembayaran Diterima', 0, '2025-11-18 03:43:57'),
(19, 3, 3, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Siap Dikirim', 0, '2025-11-18 03:44:27'),
(20, 3, 3, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Selesai', 0, '2025-11-18 03:44:33'),
(21, 3, 4, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Verifikasi', 0, '2025-11-18 06:57:20'),
(22, 3, 4, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Bukti Pembayaran', 0, '2025-11-18 06:57:23'),
(23, 3, 4, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Selesai', 0, '2025-11-18 06:57:30'),
(24, 3, 5, 'Pesanan Berhasil Dibuat', 'Pesanan #ORD-20251118080013-2829 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 07:00:13'),
(25, 3, 5, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-18 07:07:35'),
(26, 3, 5, 'Pembayaran Diterima', 'Pembayaran Anda telah diverifikasi. Pesanan akan segera dikemas.', 0, '2025-11-18 07:32:11'),
(27, 3, 5, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Siap Dikirim', 0, '2025-11-18 07:32:18'),
(28, 3, 5, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Selesai', 0, '2025-11-18 07:33:44'),
(29, 3, 6, 'Pesanan Berhasil Dibuat', 'Pesanan #ORD-20251118093322-1332 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 08:33:22'),
(30, 3, 6, 'Pesanan Dibatalkan', 'Pesanan Anda berhasil dibatalkan.', 0, '2025-11-18 08:36:52'),
(31, 3, 7, 'Pesanan Berhasil Dibuat', 'Pesanan #ORD-20251118095917-9781 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 08:59:17'),
(32, 3, 7, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-18 08:59:25'),
(33, 3, 7, 'Pembayaran Diterima', 'Pembayaran Anda telah diverifikasi. Pesanan akan segera dikemas.', 0, '2025-11-18 10:00:39'),
(34, 3, 7, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Verifikasi', 0, '2025-11-18 10:00:51'),
(35, 3, 7, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Pembayaran Ditolak', 0, '2025-11-18 10:00:58'),
(36, 3, 8, 'Pesanan Berhasil Dibuat', 'Pesanan #ORD-20251118110207-7716 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 10:02:07'),
(37, 3, 8, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-18 10:02:21'),
(38, 3, 8, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Pembayaran Diterima', 0, '2025-11-18 10:03:52'),
(39, 3, 8, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Siap Dikirim', 0, '2025-11-18 10:04:23'),
(40, 3, 8, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Selesai', 0, '2025-11-18 10:04:29'),
(41, 3, 9, 'Pesanan Berhasil Dibuat', 'Pesanan #ORD-20251118112723-5958 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 10:27:23'),
(42, 3, 9, 'Pesanan Dibatalkan', 'Pesanan Anda berhasil dibatalkan.', 0, '2025-11-18 10:28:13'),
(43, 3, 10, 'Pesanan Berhasil Dibuat', 'Pesanan #ORD-20251118124122-9292 berhasil dibuat. Silakan upload bukti pembayaran.', 0, '2025-11-18 11:41:22'),
(44, 3, 10, 'Bukti Pembayaran Terkirim', 'Bukti pembayaran Anda telah diterima. Admin akan verifikasi dalam waktu 1x24 jam.', 0, '2025-11-18 11:41:33'),
(45, 3, 10, 'Pembayaran Diterima', 'Pembayaran Anda telah diverifikasi. Pesanan akan segera dikemas.', 0, '2025-11-18 11:45:58'),
(46, 3, 10, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Menunggu Verifikasi', 0, '2025-11-18 11:47:27'),
(47, 3, 10, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Pembayaran Diterima', 0, '2025-11-18 11:48:16'),
(48, 3, 10, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Siap Dikirim', 0, '2025-11-18 11:48:35'),
(49, 3, 10, 'Pesanan Selesai', 'Anda telah mengkonfirmasi penerimaan barang.', 0, '2025-11-18 11:49:21'),
(50, 3, 10, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Siap Dikirim', 0, '2025-11-18 11:49:38'),
(51, 3, 10, 'Status Pesanan Berubah', 'Status pesanan Anda sekarang: Selesai', 0, '2025-11-18 11:49:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `no_pesanan` varchar(50) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `ongkir` decimal(10,2) DEFAULT 0.00,
  `total_bayar` decimal(10,2) NOT NULL,
  `wilayah` varchar(100) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `status` enum('menunggu_bukti','menunggu_verifikasi','diterima','ditolak','siap_kirim','selesai') DEFAULT 'menunggu_bukti',
  `alasan_penolakan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `no_pesanan`, `total_harga`, `ongkir`, `total_bayar`, `wilayah`, `alamat_lengkap`, `status`, `alasan_penolakan`, `created_at`, `updated_at`) VALUES
(1, 3, 'BBK-20251117171050-952', 15000.00, 70000.00, 85000.00, 'Bali', 'jl. ketintang', 'selesai', NULL, '2025-11-17 16:10:50', '2025-11-17 16:29:43'),
(2, 3, 'BBK-20251117174434-624', 15000.00, 10000.00, 25000.00, 'dalam kota', 'jl. ketintang', 'ditolak', 'bukti tidak sesuai', '2025-11-17 16:44:34', '2025-11-17 16:45:58'),
(3, 3, 'BBK-20251118044235-875', 36000.00, 10000.00, 46000.00, 'dalam kota', 'karah', 'selesai', NULL, '2025-11-18 03:42:35', '2025-11-18 03:44:33'),
(4, 3, 'ORD-20251118074659-7249', 600000.00, 10000.00, 610000.00, 'dalam kota', 'jl. ketintang', 'selesai', NULL, '2025-11-18 06:46:59', '2025-11-18 06:57:30'),
(5, 3, 'ORD-20251118080013-2829', 15000.00, 10000.00, 25000.00, 'dalam kota', 'jl. ketintang', 'selesai', NULL, '2025-11-18 07:00:13', '2025-11-18 07:33:44'),
(6, 3, 'ORD-20251118093322-1332', 10000.00, 10000.00, 20000.00, 'dalam kota', 'jl. ketintang', 'ditolak', NULL, '2025-11-18 08:33:22', '2025-11-18 08:36:52'),
(7, 3, 'ORD-20251118095917-9781', 15000.00, 10000.00, 25000.00, 'dalam kota', 'jl. ketintang', 'ditolak', NULL, '2025-11-18 08:59:17', '2025-11-18 10:00:58'),
(8, 3, 'ORD-20251118110207-7716', 25000.00, 10000.00, 35000.00, 'dalam kota', 'jemur sari', 'selesai', NULL, '2025-11-18 10:02:07', '2025-11-18 10:04:29'),
(9, 3, 'ORD-20251118112723-5958', 15000.00, 10000.00, 25000.00, 'dalam kota', 'jl. ketintang', 'ditolak', NULL, '2025-11-18 10:27:23', '2025-11-18 10:28:13'),
(10, 3, 'ORD-20251118124122-9292', 45000.00, 10000.00, 55000.00, 'dalam kota', 'jl. ketintang', 'selesai', NULL, '2025-11-18 11:41:22', '2025-11-18 11:49:58');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `jumlah`, `harga`, `created_at`) VALUES
(1, 1, 1, 1, 15000.00, '2025-11-17 16:10:50'),
(2, 2, 1, 1, 15000.00, '2025-11-17 16:44:34'),
(3, 3, 2, 2, 18000.00, '2025-11-18 03:42:35'),
(4, 4, 1, 40, 15000.00, '2025-11-18 06:46:59'),
(5, 5, 1, 1, 15000.00, '2025-11-18 07:00:13'),
(6, 6, 17, 1, 10000.00, '2025-11-18 08:33:22'),
(7, 7, 1, 1, 15000.00, '2025-11-18 08:59:17'),
(8, 8, 1, 1, 15000.00, '2025-11-18 10:02:07'),
(9, 8, 17, 1, 10000.00, '2025-11-18 10:02:07'),
(10, 9, 1, 1, 15000.00, '2025-11-18 10:27:23'),
(11, 10, 1, 3, 15000.00, '2025-11-18 11:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `payment_proofs`
--

CREATE TABLE `payment_proofs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `bukti_file` varchar(255) NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_proofs`
--

INSERT INTO `payment_proofs` (`id`, `order_id`, `bukti_file`, `verified_at`, `verified_by`, `created_at`) VALUES
(1, 1, '691b4c6361e0c.jpg', '2025-11-17 16:24:39', 1, '2025-11-17 16:16:53'),
(2, 2, '691b5103875e6.png', NULL, NULL, '2025-11-17 16:44:51'),
(3, 3, '691beb41c3679.png', '2025-11-18 03:43:41', 1, '2025-11-18 03:42:57'),
(4, 5, '691c1b371683a_1763449655.jpg', '2025-11-18 07:32:11', 1, '2025-11-18 07:07:35'),
(5, 7, '691c356de0d45_1763456365.jpg', '2025-11-18 10:00:39', 1, '2025-11-18 08:59:25'),
(6, 8, '691c442dac790_1763460141.jpg', NULL, NULL, '2025-11-18 10:02:21'),
(7, 10, '691c5b6de2500_1763466093.jpg', '2025-11-18 11:45:58', 1, '2025-11-18 11:41:33');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `foto_utama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `nama`, `deskripsi`, `harga`, `stok`, `foto_utama`, `created_at`, `updated_at`) VALUES
(1, 1, 'Roti Tawar Putih', 'Roti tawar putih klasik, lembut dan empuk', 15000.00, 43, '691c06a0b5b4c.jpg', '2025-11-17 14:00:35', '2025-11-18 11:41:22'),
(2, 1, 'Roti Tawar Coklat', 'Roti tawar coklat dengan aroma coklat yang wangi', 18000.00, 43, '691c2035ba3c1_1763450933.jpg', '2025-11-17 14:00:35', '2025-11-18 07:28:53'),
(17, 1, 'Roti Tawar manis', 'roti tawar manis dan lezat', 10000.00, 39, '691c14acc97ef_1763447980.jpg', '2025-11-18 06:39:40', '2025-11-18 10:02:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_photos`
--

CREATE TABLE `product_photos` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `ulasan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_costs`
--

CREATE TABLE `shipping_costs` (
  `id` int(11) NOT NULL,
  `wilayah` varchar(100) NOT NULL,
  `ongkir` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_costs`
--

INSERT INTO `shipping_costs` (`id`, `wilayah`, `ongkir`, `created_at`, `updated_at`) VALUES
(16, 'dalam kota', 10000.00, '2025-11-17 16:40:01', '2025-11-17 16:40:09'),
(17, 'Luar kota', 20000.00, '2025-11-18 07:34:44', '2025-11-18 07:34:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_carts_customer` (`customer_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_messages_from` (`from_user_id`),
  ADD KEY `idx_messages_to` (`to_user_id`),
  ADD KEY `idx_messages_receiver_type` (`receiver_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_notifications_customer` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_pesanan` (`no_pesanan`),
  ADD KEY `idx_orders_customer` (`customer_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_items_order` (`order_id`);

--
-- Indexes for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_category` (`category_id`);

--
-- Indexes for table `product_photos`
--
ALTER TABLE `product_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`);

--
-- Indexes for table `shipping_costs`
--
ALTER TABLE `shipping_costs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wilayah` (`wilayah`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_photos`
--
ALTER TABLE `product_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_costs`
--
ALTER TABLE `shipping_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payment_proofs`
--
ALTER TABLE `payment_proofs`
  ADD CONSTRAINT `payment_proofs_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_proofs_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_photos`
--
ALTER TABLE `product_photos`
  ADD CONSTRAINT `product_photos_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
