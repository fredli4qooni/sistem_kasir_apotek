-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_apotek.detail_penjualan
DROP TABLE IF EXISTS `detail_penjualan`;
CREATE TABLE IF NOT EXISTS `detail_penjualan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_penjualan` bigint unsigned NOT NULL,
  `id_obat` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan_saat_transaksi` decimal(15,2) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `diskon_item_persen` decimal(5,2) DEFAULT '0.00',
  `diskon_item_nominal` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_penjualan_id_penjualan_foreign` (`id_penjualan`),
  KEY `detail_penjualan_id_obat_foreign` (`id_obat`),
  CONSTRAINT `detail_penjualan_id_obat_foreign` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `detail_penjualan_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.detail_penjualan: ~1 rows (approximately)
REPLACE INTO `detail_penjualan` (`id`, `id_penjualan`, `id_obat`, `jumlah`, `harga_satuan_saat_transaksi`, `sub_total`, `diskon_item_persen`, `diskon_item_nominal`, `created_at`, `updated_at`) VALUES
	(7, 6, 1, 1, 13000.00, 13000.00, 0.00, 0.00, '2025-06-11 08:14:16', '2025-06-11 08:14:16'),
	(8, 7, 1, 1, 13000.00, 12350.00, 5.00, 0.00, '2025-06-11 08:14:37', '2025-06-11 08:14:37');

-- Dumping structure for table db_apotek.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table db_apotek.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.migrations: ~8 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_05_11_212502_create_obats_table', 1),
	(6, '2025_05_11_212515_create_penjualans_table', 1),
	(7, '2025_05_11_212523_create_detail_penjualans_table', 1),
	(8, '2014_10_12_100000_create_password_resets_table', 2),
	(9, '2025_05_29_023109_add_distributor_and_batch_to_obat_table', 3),
	(10, '2025_06_07_230405_add_discount_to_detail_penjualan_table', 4);

-- Dumping structure for table db_apotek.obat
DROP TABLE IF EXISTS `obat`;
CREATE TABLE IF NOT EXISTS `obat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `satuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distributor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_batch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `harga_jual` decimal(15,2) NOT NULL,
  `stok` int NOT NULL,
  `stok_minimal` int NOT NULL DEFAULT '10',
  `tanggal_kadaluarsa` date NOT NULL,
  `qr_code_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obat_kode_obat_unique` (`kode_obat`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.obat: ~3 rows (approximately)
REPLACE INTO `obat` (`id`, `kode_obat`, `nama_obat`, `deskripsi`, `satuan`, `distributor`, `nomor_batch`, `harga_beli`, `harga_jual`, `stok`, `stok_minimal`, `tanggal_kadaluarsa`, `qr_code_path`, `created_at`, `updated_at`) VALUES
	(1, 'PAR500', 'Paracetamol 500 mg', 'Analgesik, antipiretik', 'Tablet', 'test', '123', 10000.00, 13000.00, 12, 10, '2028-11-30', NULL, '2025-05-11 14:57:09', '2025-06-11 08:14:37'),
	(3, 'AMX500', 'Amoxilin', 'test', 'Tablet', NULL, NULL, 10000.00, 12000.00, 99, 10, '2027-10-19', NULL, '2025-05-12 08:40:44', '2025-05-12 08:51:04'),
	(4, 'PAN100', 'Panadol', 'test', 'Tablet', NULL, NULL, 5000.00, 10000.00, 91, 10, '2027-10-12', NULL, '2025-05-12 08:54:12', '2025-06-07 16:31:47');

-- Dumping structure for table db_apotek.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.password_resets: ~0 rows (approximately)

-- Dumping structure for table db_apotek.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table db_apotek.penjualan
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE IF NOT EXISTS `penjualan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` bigint unsigned NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `kembalian` decimal(15,2) NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penjualan_nomor_transaksi_unique` (`nomor_transaksi`),
  KEY `penjualan_id_user_foreign` (`id_user`),
  CONSTRAINT `penjualan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.penjualan: ~2 rows (approximately)
REPLACE INTO `penjualan` (`id`, `nomor_transaksi`, `id_user`, `total_harga`, `jumlah_bayar`, `kembalian`, `catatan`, `created_at`, `updated_at`) VALUES
	(6, 'INV/202506/001', 1, 13000.00, 20000.00, 7000.00, NULL, '2025-06-11 08:14:16', '2025-06-11 08:14:16'),
	(7, 'INV/202506/002', 1, 12350.00, 40000.00, 27650.00, NULL, '2025-06-11 08:14:37', '2025-06-11 08:14:37');

-- Dumping structure for table db_apotek.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table db_apotek.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('pemilik','kasir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_apotek.users: ~2 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Kasir', 'admin@gmail.com', NULL, '$2y$10$3G3cMdCoWGr1giUriveEqOO96Nysc.mv.57IHnK7rHzuCtbt7Dcs.', 'kasir', NULL, '2025-05-11 14:42:02', '2025-05-11 14:42:02'),
	(2, 'Pemilik Apotek', 'pemilik@apotekberkahibu.com', '2025-05-11 16:15:44', '$2y$10$kNQxH0lmc4oeKzje6j99N.4ZJQrKJZfr.Wz784J9.5nQbW4nrEiIi', 'pemilik', NULL, '2025-05-11 16:15:44', '2025-05-11 16:15:44');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
