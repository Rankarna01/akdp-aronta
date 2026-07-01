-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 23, 2026 at 05:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db-aronta`
--

-- --------------------------------------------------------

--
-- Table structure for table `armada`
--

CREATE TABLE `armada` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_bus` varchar(255) NOT NULL,
  `nomor_pintu` varchar(10) DEFAULT NULL,
  `plat_nomor` varchar(255) NOT NULL,
  `tipe_bus` enum('Economy','Executive') NOT NULL,
  `total_kursi` int(11) NOT NULL,
  `status` enum('Aktif','Maintenance','Non-Aktif') NOT NULL DEFAULT 'Aktif',
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `armada`
--

INSERT INTO `armada` (`id`, `nama_bus`, `nomor_pintu`, `plat_nomor`, `tipe_bus`, `total_kursi`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Aronta 827', '827', 'BK 2010', 'Economy', 30, 'Aktif', 'armada/Hx2V9aqm7luaCxqtLUYZ5kYNR9x8bdWf1132ysj6.png', '2026-06-21 06:45:21', '2026-06-21 06:45:21'),
(2, 'Aronta 825', '826', 'B1210', 'Executive', 15, 'Aktif', 'armada/J842juMySmg1ZyDpqaryWbfSJzoBM2LjV7c45bwu.jpg', '2026-06-22 07:10:29', '2026-06-22 07:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rute_id` bigint(20) UNSIGNED NOT NULL,
  `armada_id` bigint(20) UNSIGNED NOT NULL,
  `supir_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_berangkat` time NOT NULL,
  `waktu_tiba` time DEFAULT NULL,
  `harga_tiket` int(11) NOT NULL,
  `status` enum('Menunggu','Berangkat','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `rute_id`, `armada_id`, `supir_id`, `tanggal`, `waktu_berangkat`, `waktu_tiba`, `harga_tiket`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-06-21', '22:00:00', NULL, 35000, 'Selesai', '2026-06-21 06:46:03', '2026-06-21 06:47:03'),
(2, 2, 2, 1, '2026-06-22', '20:00:00', NULL, 30000, 'Menunggu', '2026-06-22 07:24:17', '2026-06-22 07:24:17'),
(3, 1, 1, 2, '2026-06-22', '21:00:00', NULL, 20000, 'Menunggu', '2026-06-22 07:32:12', '2026-06-22 07:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kursi`
--

CREATE TABLE `kursi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `armada_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_kursi` varchar(10) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kursi`
--

INSERT INTO `kursi` (`id`, `armada_id`, `nomor_kursi`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(2, 1, '2', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(3, 1, '3', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(4, 1, '4', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(5, 1, '5', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(6, 1, '6', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(7, 1, '7', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(8, 1, '8', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(9, 1, '9', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(10, 1, '10', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(11, 1, '11', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(12, 1, '12', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(13, 1, '13', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(14, 1, '14', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(15, 1, '15', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(16, 1, '16', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(17, 1, '17', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(18, 1, '18', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(19, 1, '19', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(20, 1, '20', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(21, 1, '21', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(22, 1, '22', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(23, 1, '23', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(24, 1, '24', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(25, 1, '25', 'Aktif', '2026-06-21 06:45:30', '2026-06-21 06:45:30'),
(26, 2, '1', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(27, 2, '2', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(28, 2, '3', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(29, 2, '4', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(30, 2, '5', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(31, 2, '6', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(32, 2, '7', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(33, 2, '8', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(34, 2, '9', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(35, 2, '10', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(36, 2, '11', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(37, 2, '12', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(38, 2, '13', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(39, 2, '14', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44'),
(40, 2, '15', 'Aktif', '2026-06-22 07:15:44', '2026-06-22 07:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `modul` varchar(255) NOT NULL,
  `aksi` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran_masters`
--

CREATE TABLE `metode_pembayaran_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_bank` varchar(255) NOT NULL,
  `nomor_rekening` varchar(255) NOT NULL,
  `atas_nama` varchar(255) NOT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `metode_pembayaran_masters`
--

INSERT INTO `metode_pembayaran_masters` (`id`, `nama_bank`, `nomor_rekening`, `atas_nama`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DANA', '085835116946', 'ARONTA PERSADA', 'Aktif', '2026-06-21 06:45:49', '2026-06-21 06:45:49');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_19_165243_create_armadas_table', 1),
(5, '2026_05_19_171510_create_supir_table', 1),
(6, '2026_05_20_035718_create_rute_table', 1),
(7, '2026_05_20_043630_create_kursi_table', 1),
(8, '2026_05_20_045310_create_jadwal_table', 1),
(9, '2026_05_20_061404_create_monitoring_perjalanan_table', 1),
(10, '2026_05_20_062429_create_penumpang_table', 1),
(11, '2026_05_20_070248_create_tiket_table', 1),
(12, '2026_05_20_070954_create_pembayaran_table', 1),
(13, '2026_05_22_105645_create_log_aktivitas_table', 1),
(14, '2026_05_24_164514_add_catatan_titik_to_tiket_table', 1),
(15, '2026_05_25_032436_add_user_id_to_penumpang_table', 1),
(16, '2026_05_25_041806_add_nomor_pintu_to_armada_table', 1),
(17, '2026_05_25_043210_add_gambar_to_armada_table', 1),
(18, '2026_05_25_045450_drop_unique_kode_tiket_from_tiket_table', 1),
(19, '2026_05_25_082417_create_metode_pembayaran_masters_table', 1),
(20, '2026_06_22_050822_add_user_id_to_tiket_table', 2),
(21, '2026_06_22_130542_add_tipe_bus_to_rute_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `monitoring_perjalanan`
--

CREATE TABLE `monitoring_perjalanan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Persiapan','Dalam Perjalanan','Kendala','Sampai') NOT NULL DEFAULT 'Persiapan',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monitoring_perjalanan`
--

INSERT INTO `monitoring_perjalanan` (`id`, `jadwal_id`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dalam Perjalanan', NULL, '2026-06-21 06:46:42', '2026-06-21 06:46:42'),
(2, 1, 'Dalam Perjalanan', NULL, '2026-06-21 06:46:53', '2026-06-21 06:46:53'),
(3, 1, 'Sampai', NULL, '2026-06-21 06:47:03', '2026-06-21 06:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tiket_id` bigint(20) UNSIGNED NOT NULL,
  `metode_pembayaran` varchar(255) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Lunas','Ditolak') NOT NULL DEFAULT 'Pending',
  `tanggal_bayar` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `tiket_id`, `metode_pembayaran`, `jumlah_bayar`, `bukti_transfer`, `status`, `tanggal_bayar`, `created_at`, `updated_at`) VALUES
(1, 1, 'DANA', 20000, 'bukti_pembayaran/3xY40EYZulg5B9YZLQfls6P85ea111Nw4XRO52pb.png', 'Pending', '2026-06-22 07:32:46', '2026-06-22 07:32:46', '2026-06-22 07:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `penumpang`
--

CREATE TABLE `penumpang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penumpang`
--

INSERT INTO `penumpang` (`id`, `user_id`, `nik`, `nama`, `jenis_kelamin`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 5, '1218092812812021', 'Randy Karna', 'Laki-laki', '085835116946', NULL, '2026-06-22 07:25:39', '2026-06-22 07:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `rute`
--

CREATE TABLE `rute` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kota_asal` varchar(255) NOT NULL,
  `kota_tujuan` varchar(255) NOT NULL,
  `tipe_bus` enum('Economy','Executive') NOT NULL DEFAULT 'Economy',
  `harga_dasar` bigint(20) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rute`
--

INSERT INTO `rute` (`id`, `kota_asal`, `kota_tujuan`, `tipe_bus`, `harga_dasar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Medan', 'Kabanjahe', 'Economy', 20000, 'Aktif', '2026-06-21 06:44:20', '2026-06-22 06:23:06'),
(2, 'Medan', 'Kabanjahe', 'Executive', 30000, 'Aktif', '2026-06-22 06:23:18', '2026-06-22 06:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hBF01dWzjie5KP8csLt2A3gPzKz5CnVMURDU6sQn', 5, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJmb1Fwc1RHMVM1MHM5VFdoN1VreExPeXNieVV5V0s4QUJsUVNNTUJqIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL2N1c3RvbWVyXC90aWtldC1zYXlhIiwicm91dGUiOiJjdXN0b21lci50aWtldC1zYXlhLmluZGV4In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo1fQ==', 1782113571),
('HCj0SrgtWf3eXR9LAHbLksma1elmLgwAdPowqGS7', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI0SU56S09FNnozVnc5eUN2ZlVidzlQd0pYZzdvYU9FMnl3QTc1RkVFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvZGFzaGJvYXJkIiwicm91dGUiOiJhZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1782222390),
('reHtxTCa2z7sB91JNdpgSv2ASrGQu9lt60HSnuCS', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIxc1NCRENVTmUwVmhXNkVKVnlSa2FNTnlSQlFqVjdpczJhOFdCUno4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOiJsYW5kaW5nIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1782192882);

-- --------------------------------------------------------

--
-- Table structure for table `supir`
--

CREATE TABLE `supir` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `no_sim` varchar(20) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','Cuti','Non-Aktif') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supir`
--

INSERT INTO `supir` (`id`, `user_id`, `no_ktp`, `no_sim`, `no_hp`, `alamat`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '1234567890123456', '123456', '085835116946', 'Medan magnet', 'profile_supir/Gu0WCPdLCqRpnYpWEzVbVHurKwlHjzCyBr7aUolT.png', 'Aktif', '2026-06-21 06:44:57', '2026-06-21 06:44:57'),
(2, 6, '1973917391731', '1212121', '085712345670', 'Indonesia\r\nSumatra', 'profile_supir/oucXGlIsQxyJKvL9W0Bjyey548yLq37sd4hgeP5R.png', 'Aktif', '2026-06-22 07:31:54', '2026-06-22 07:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_tiket` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jadwal_id` bigint(20) UNSIGNED NOT NULL,
  `penumpang_id` bigint(20) UNSIGNED NOT NULL,
  `kursi_id` bigint(20) UNSIGNED NOT NULL,
  `catatan_titik` varchar(255) DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `status_pembayaran` enum('Unpaid','Pending','Paid','Failed') NOT NULL DEFAULT 'Unpaid',
  `status_tiket` enum('Aktif','Digunakan','Dibatalkan') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`id`, `kode_tiket`, `user_id`, `jadwal_id`, `penumpang_id`, `kursi_id`, `catatan_titik`, `harga`, `status_pembayaran`, `status_tiket`, `created_at`, `updated_at`) VALUES
(1, 'ACP82725', 5, 3, 1, 25, NULL, 20000, 'Pending', 'Aktif', '2026-06-22 07:32:41', '2026-06-22 07:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','driver','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin Aronta', 'admin@aronta.com', NULL, '$2y$12$xSAPkuSh8F5oof.dQfZXAe96eq9.VAuLWO9ieIVMEuR13BEZGtrcG', 'super_admin', NULL, '2026-06-21 06:13:06', '2026-06-21 06:13:06'),
(2, 'Supir Budi', 'supir@aronta.com', NULL, '$2y$12$asjaWLbQ1o/keuCH.Rd0FugTVKPq5YKvgJNJ0CKyWNM2La/rm0Lw.', 'driver', NULL, '2026-06-21 06:13:06', '2026-06-21 06:13:06'),
(3, 'Customer Setia', 'customer@aronta.com', NULL, '$2y$12$Mc8gcxCSAd9AGXlwENhK1utW7nAsrJ6YvrwLCay.WVrAwGY.4vcoO', 'customer', NULL, '2026-06-21 06:13:07', '2026-06-21 06:13:07'),
(4, 'Jonathan', 'jonathan@aronta.com', NULL, '$2y$12$rGeUgQBMYq4roxp82TraWe7WtkfA/5VjjfqBFOnSUUBH9SDCdc9om', 'driver', NULL, '2026-06-21 06:44:57', '2026-06-21 06:44:57'),
(5, 'Randy Karna', 'randy@gmail.com', NULL, '$2y$12$eUaU1UwNBeTX2zIxjxgx2eY16GCWHaKT6kEMiLuKkP1RPtKqEFBuq', 'customer', NULL, '2026-06-22 07:25:39', '2026-06-22 07:25:39'),
(6, 'Ran_karna10', 'randy01@gmail.com', NULL, '$2y$12$tzMYFfDpdQKBibxX1U08/erj/Qy0KBg3DFiFB0BKuqCrOrFfaaOhW', 'driver', NULL, '2026-06-22 07:31:54', '2026-06-22 07:31:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `armada`
--
ALTER TABLE `armada`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `armada_plat_nomor_unique` (`plat_nomor`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_rute_id_foreign` (`rute_id`),
  ADD KEY `jadwal_armada_id_foreign` (`armada_id`),
  ADD KEY `jadwal_supir_id_foreign` (`supir_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kursi`
--
ALTER TABLE `kursi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kursi_armada_id_nomor_kursi_unique` (`armada_id`,`nomor_kursi`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_aktivitas_user_id_foreign` (`user_id`);

--
-- Indexes for table `metode_pembayaran_masters`
--
ALTER TABLE `metode_pembayaran_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitoring_perjalanan`
--
ALTER TABLE `monitoring_perjalanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `monitoring_perjalanan_jadwal_id_foreign` (`jadwal_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayaran_tiket_id_foreign` (`tiket_id`);

--
-- Indexes for table `penumpang`
--
ALTER TABLE `penumpang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penumpang_nik_unique` (`nik`),
  ADD KEY `penumpang_user_id_foreign` (`user_id`);

--
-- Indexes for table `rute`
--
ALTER TABLE `rute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `supir`
--
ALTER TABLE `supir`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supir_no_ktp_unique` (`no_ktp`),
  ADD UNIQUE KEY `supir_no_sim_unique` (`no_sim`),
  ADD KEY `supir_user_id_foreign` (`user_id`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tiket_jadwal_id_foreign` (`jadwal_id`),
  ADD KEY `tiket_penumpang_id_foreign` (`penumpang_id`),
  ADD KEY `tiket_kursi_id_foreign` (`kursi_id`),
  ADD KEY `tiket_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `armada`
--
ALTER TABLE `armada`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kursi`
--
ALTER TABLE `kursi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `metode_pembayaran_masters`
--
ALTER TABLE `metode_pembayaran_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `monitoring_perjalanan`
--
ALTER TABLE `monitoring_perjalanan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penumpang`
--
ALTER TABLE `penumpang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rute`
--
ALTER TABLE `rute`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supir`
--
ALTER TABLE `supir`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_armada_id_foreign` FOREIGN KEY (`armada_id`) REFERENCES `armada` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_rute_id_foreign` FOREIGN KEY (`rute_id`) REFERENCES `rute` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_supir_id_foreign` FOREIGN KEY (`supir_id`) REFERENCES `supir` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kursi`
--
ALTER TABLE `kursi`
  ADD CONSTRAINT `kursi_armada_id_foreign` FOREIGN KEY (`armada_id`) REFERENCES `armada` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `monitoring_perjalanan`
--
ALTER TABLE `monitoring_perjalanan`
  ADD CONSTRAINT `monitoring_perjalanan_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_tiket_id_foreign` FOREIGN KEY (`tiket_id`) REFERENCES `tiket` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penumpang`
--
ALTER TABLE `penumpang`
  ADD CONSTRAINT `penumpang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `supir`
--
ALTER TABLE `supir`
  ADD CONSTRAINT `supir_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tiket`
--
ALTER TABLE `tiket`
  ADD CONSTRAINT `tiket_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_kursi_id_foreign` FOREIGN KEY (`kursi_id`) REFERENCES `kursi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_penumpang_id_foreign` FOREIGN KEY (`penumpang_id`) REFERENCES `penumpang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tiket_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
