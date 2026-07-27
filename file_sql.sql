/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `smp_inventaris` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `smp_inventaris`;

CREATE TABLE IF NOT EXISTS `barangs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merk_spesifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` enum('DUS','KG','BOTOL','BOX','BUAH','BUNGKUS','UNIT','PACK','PCS','BUKU','RIM','PAD') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BUAH',
  `harga_barang` double NOT NULL,
  `stok_tersedia` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `klasifikasi_kib` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barangs_klasifikasi_kib_foreign` (`klasifikasi_kib`),
  CONSTRAINT `barangs_klasifikasi_kib_foreign` FOREIGN KEY (`klasifikasi_kib`) REFERENCES `kib` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `barangs` (`id`, `nama_barang`, `merk_spesifikasi`, `satuan`, `harga_barang`, `stok_tersedia`, `description`, `klasifikasi_kib`, `created_at`, `updated_at`) VALUES
	(1, 'leptop3', NULL, 'BUAH', 10000000, 3, 'Peralatan dan Mesin', 2, '2026-07-11 08:54:06', '2026-07-25 08:51:38'),
	(2, 'Samsung j2 prime', NULL, 'BUAH', 1200000, 7, 'HP buat monitorng', 2, '2026-07-12 02:30:43', '2026-07-24 09:02:57'),
	(9, 'Paku Payung', NULL, 'DUS', 0, 10, 'Uk: Standard', 1, '2026-07-23 19:00:30', '2026-07-23 19:00:30'),
	(10, 'Paku', NULL, 'KG', 0, 10, 'Payung, 10 Cm', 1, '2026-07-23 19:00:30', '2026-07-23 19:00:30'),
	(11, 'Aseptic Gel 500 ml+Dispenser', NULL, 'BOTOL', 0, 10, 'Jenis : Cairan Antiseptik; Tipe : Hand Hygiene Alcohol Gel; 500 ml', 1, '2026-07-23 19:00:30', '2026-07-23 19:00:30'),
	(12, 'Paku Payung', NULL, 'DUS', 0, 10, 'Uk: Standard', 1, '2026-07-23 19:00:52', '2026-07-23 19:00:52'),
	(13, 'Paku', NULL, 'KG', 0, 10, 'Payung, 10 Cm', 1, '2026-07-23 19:00:52', '2026-07-23 19:00:52'),
	(14, 'Aseptic Gel 500 ml+Dispenser', NULL, 'BOTOL', 0, 10, 'Jenis : Cairan Antiseptik; Tipe : Hand Hygiene Alcohol Gel; 500 ml', 1, '2026-07-23 19:00:52', '2026-07-23 19:00:52'),
	(15, 'Alkohol swab', NULL, 'BOX', 0, 11, '100 pcs /box', 3, '2026-07-23 19:00:52', '2026-07-27 06:26:06'),
	(16, 'KABEL VGA', NULL, 'UNIT', 0, 10, 'Â kabel VGA 10 mtr', 2, '2026-07-23 20:09:30', '2026-07-23 20:09:30'),
	(17, 'Printer EPSON L5290', NULL, 'BUAH', 0, 10, 'Printer multifungsi (Print, Scan, Copy, Fax, WiFi, ADF), Kecepatan: mencapai 33 ppm, Resolusi: 5760 × 1440 dpi', 2, '2026-07-23 20:09:30', '2026-07-23 20:09:30'),
	(18, '	ASUS Vivobook 14', NULL, 'BUAH', 0, 9, 'Layar: 14 inci, resolusi Full HD (1920 x 1080), panel IPS,  Rasio 16:9. Prosesor: Intel i7-1355U. Grafis: Intel UHD/Iris Xe Graphics . RAM: 16GB DDR4 (dengan opsi upgrade). Penyimpanan:512GB M.2 NVMe PCIe 4.0 SSD. Konektivitas: Wi-Fi 6E, Bluetooth 5.3, port USB-C, USB-A, HDMI, dan jack audio. Fitur Keamanan: Sensor sidik jari. Sistem Operasi: Windows 11 Home', 2, '2026-07-23 20:09:30', '2026-07-27 06:22:32'),
	(19, 'Amplop Dinas', NULL, 'BUAH', 0, 14, 'Amplop Dinas', 3, '2026-07-24 10:54:51', '2026-07-24 11:13:19'),
	(20, 'Box File', NULL, 'BUAH', 0, 0, 'Uk. 34,5X25,5X5Cm', 3, '2026-07-26 06:38:40', '2026-07-26 06:38:40'),
	(22, 'Pai', NULL, 'BUAH', 0, 0, 'sasdas', 1, '2026-07-26 20:27:48', '2026-07-26 20:27:48'),
	(28, 'Barang', NULL, 'BUAH', 0, 0, 'sad', 1, '2026-07-26 20:33:20', '2026-07-26 20:33:20');

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `karyawans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_karyawan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nrk` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `users_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawans_nrk_unique` (`nrk`),
  UNIQUE KEY `karyawans_nip_unique` (`nip`),
  KEY `karyawans_users_id_foreign` (`users_id`),
  CONSTRAINT `karyawans_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `karyawans` (`id`, `nama_karyawan`, `nrk`, `nip`, `jabatan`, `created_at`, `updated_at`, `users_id`) VALUES
	(1, '123123', '123123', '123123', NULL, '2026-07-11 08:17:51', '2026-07-11 08:17:51', 1),
	(2, '112233', '112233', '112233', NULL, '2026-07-11 08:18:09', '2026-07-11 08:18:09', 2),
	(3, '321321', '321321', '321321', NULL, '2026-07-11 08:18:32', '2026-07-11 08:18:32', 3),
	(4, 'Donda Banjarnahor', '222019', '199509242025062011', 'pengguna', '2026-07-13 05:15:38', '2026-07-25 19:37:58', 4),
	(5, 'Pengguna 001', '4321', '4321', 'pengguna', '2026-07-23 21:19:03', '2026-07-23 21:19:03', 14),
	(6, 'Admin 001', 'admin001', 'admin001', 'tata usaha', '2026-07-25 05:59:39', '2026-07-25 19:37:36', 15);

CREATE TABLE IF NOT EXISTS `kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_barang` enum('persediaan','aset') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kib` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_kode_kib_foreign` (`kode_kib`),
  CONSTRAINT `kategori_kode_kib_foreign` FOREIGN KEY (`kode_kib`) REFERENCES `kib` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `kategori` (`id`, `nama_kategori`, `jenis_barang`, `kode_kib`, `created_at`, `updated_at`) VALUES
	(1, 'Laptop', 'persediaan', 2, '2026-07-11 08:53:23', '2026-07-12 02:05:53'),
	(2, 'handphone', 'aset', 2, '2026-07-12 01:54:19', '2026-07-12 01:54:19');

CREATE TABLE IF NOT EXISTS `kib` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_kib` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `klasifikasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `kib` (`id`, `kode_kib`, `klasifikasi`, `deskripsi`, `created_at`, `updated_at`) VALUES
	(1, 'Sarpras', 'Sarana dan Prasarana', 'Sarana dan Prasarana yang menunjang proses pendidikan', '2026-07-11 08:16:19', '2026-07-11 08:16:19'),
	(2, 'KIB-B', 'Peralatan dan Mesin', 'Mencata Aset bergerak seperti alat kantor, alat kendaraan, alat studio, dan alat kedokteran.', '2026-07-11 08:16:19', '2026-07-11 08:16:19'),
	(3, 'ATK', 'Alat Tulis Kantor', 'Alat yang menunjan proses kegiatan belajar mengajar', '2026-07-11 08:16:19', '2026-07-11 08:16:19');

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_06_09_083317_create_k_i_b_s_table', 1),
	(5, '2026_06_09_090307_create_personal_access_tokens_table', 1),
	(6, '2026_06_11_144334_create_karyawans_table', 1),
	(7, '2026_06_17_173112_create_barangs_table', 1),
	(8, '2026_06_22_115607_create_kategoris_table', 1),
	(9, '2026_06_23_090221_add_relation_kategori_to_barangs_table', 1),
	(10, '2026_06_24_014649_create_peminjaman_table', 1),
	(11, '2026_06_24_015408_create_detail_peminjaman_tables', 1),
	(12, '2026_07_05_125709_create_persedians_table', 1),
	(13, '2026_07_07_083355_add_harga_total_to_persedians_table', 1),
	(14, '2026_07_08_081649_add_status_to_persedians_table', 1),
	(15, '2026_07_08_163721_add_users_id_to_karyawans_table', 1),
	(16, '2026_07_09_190209_create_permission_tables', 1),
	(17, '2026_07_11_111914_create_permintaans_table', 1),
	(18, '2026_07_11_145650_create_permintaan_details_table', 1),
	(19, '2026_07_12_093330_add_keperluan_to_permintaans_table', 2),
	(21, '2026_07_12_102359_create_pengajuan_table', 3),
	(22, '2026_07_12_102429_create_pengajuan_detail_table', 3),
	(25, '2026_07_12_115228_drop_migration_table_peminjaman', 4),
	(26, '2026_07_12_115539_drop_migration_table_peminjaman', 5),
	(31, '2026_07_12_115721_create_pinjaman_table', 6),
	(32, '2026_07_12_115739_create_detail_pinjam_table', 6),
	(36, '2026_07_13_020558_create_permintaan_detail_table', 7),
	(39, '2026_07_15_151741_add_admin_verification_permintaan_table', 8),
	(40, '2026_07_15_151928_add_admin_verification_to_peminjaman_table', 8),
	(41, '2026_07_20_021706_add_admin_verification_to_pengajuan_table', 9),
	(42, '2026_07_20_155842_delete_estimasi_harga_satuan_from_pengajuan_detail_table', 10),
	(44, '2026_07_20_222857_update_status_pengajuan_to_pengajuan_table', 11),
	(67, '2026_07_22_002631_create_pengembalian_table', 12),
	(68, '2026_07_22_003202_create_pengembalian_items_table', 12),
	(69, '2026_07_22_003407_add_kembali_columns_to_peminjaman_items_table', 13),
	(71, '2026_07_22_004019_add_constrained_columns_to_pengembalians_items_table', 14),
	(72, '2026_07_22_015337_add_sebagian_dikembalikan_to_peminjaman_status', 15),
	(74, '2026_07_22_144919_add_status_dibatalkan_to_peminjaman_table', 16),
	(75, '2026_07_23_153407_update_kode_kib_to_kib_table', 17),
	(76, '2026_07_23_155224_add_column_habis_terpakai', 18),
	(77, '2026_07_23_204733_split_qty_rusak_into_ringan_berat', 19),
	(78, '2026_07_23_213403_add_merk_and_satuan_to_barangs_table', 20),
	(79, '2026_07_23_214748_remove_kategori_id_from_barang', 21),
	(80, '2026_07_24_040257_add_jabatan_to_karyawans_table', 22),
	(81, '2026_07_25_151748_create_pembelians_table', 23),
	(82, '2026_07_25_151842_create_pembelian_items_table', 23),
	(83, '2026_07_25_164543_create_saldo_awal_table', 24),
	(84, '2026_07_25_164639_create_saldo_awal_items_table', 25),
	(85, '2026_07_26_084317_create_periodes_table', 26),
	(86, '2026_07_26_084407_create_stok_opnames_table', 26),
	(87, '2026_07_26_084439_create_stok_opname_item_table', 26),
	(88, '2026_07_26_084514_add_periode_and_source_to_saldo_awals_table', 27);

CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3),
	(2, 'App\\Models\\User', 4),
	(3, 'App\\Models\\User', 14),
	(2, 'App\\Models\\User', 15);

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `pembelians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_diterima` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `status` enum('menunggu_verifikasi_spv','selesai','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_verifikasi_spv',
  `diverifikasi_oleh` bigint unsigned DEFAULT NULL,
  `diverifikasi_at` datetime DEFAULT NULL,
  `alasan_tolak` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pembelians_no_transaksi_unique` (`no_transaksi`),
  KEY `pembelians_dibuat_oleh_foreign` (`dibuat_oleh`),
  KEY `pembelians_diverifikasi_oleh_foreign` (`diverifikasi_oleh`),
  CONSTRAINT `pembelians_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `pembelians_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `pembelians` (`id`, `no_transaksi`, `nama_supplier`, `tanggal_diterima`, `catatan`, `dibuat_oleh`, `status`, `diverifikasi_oleh`, `diverifikasi_at`, `alasan_tolak`, `created_at`, `updated_at`) VALUES
	(1, 'PMB-20260725-001', 'PT ABC', '2026-07-25', 'Kondisi Baru', 2, 'selesai', 1, '2026-07-25 15:51:38', NULL, '2026-07-25 08:47:04', '2026-07-25 08:51:38'),
	(2, 'PMB-20260727-001', 'sa', '2026-07-27', NULL, 2, 'selesai', 1, '2026-07-27 13:26:06', NULL, '2026-07-27 05:50:44', '2026-07-27 06:26:06');

CREATE TABLE IF NOT EXISTS `pembelian_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pembelian_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `qty` int unsigned NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembelian_items_pembelian_id_foreign` (`pembelian_id`),
  KEY `pembelian_items_barang_id_foreign` (`barang_id`),
  CONSTRAINT `pembelian_items_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `pembelian_items_pembelian_id_foreign` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelians` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `pembelian_items` (`id`, `pembelian_id`, `barang_id`, `qty`, `deskripsi`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 'baikk', '2026-07-25 08:47:04', '2026-07-25 08:47:04'),
	(2, 2, 15, 1, NULL, '2026-07-27 05:50:44', '2026-07-27 05:50:44');

CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_peminjaman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `verified_by_admin` bigint unsigned DEFAULT NULL,
  `verified_at_admin` timestamp NULL DEFAULT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_wajib_kembali` date NOT NULL,
  `status` enum('draft','pending','menunggu_spv','approved','rejected','dipinjam','sebagian_dikembalikan','menunggu_konfirmasi_kembali','dikembalikan','selesai','dibatalkan') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `catatan_approval` text COLLATE utf8mb4_unicode_ci,
  `dikembalikan_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `peminjaman_kode_peminjaman_unique` (`kode_peminjaman`),
  KEY `peminjaman_requested_by_foreign` (`requested_by`),
  KEY `peminjaman_approved_by_foreign` (`approved_by`),
  KEY `peminjaman_verified_by_admin_foreign` (`verified_by_admin`),
  CONSTRAINT `peminjaman_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `peminjaman_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  CONSTRAINT `peminjaman_verified_by_admin_foreign` FOREIGN KEY (`verified_by_admin`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `peminjaman` (`id`, `kode_peminjaman`, `requested_by`, `verified_by_admin`, `verified_at_admin`, `keperluan`, `tanggal_pinjam`, `tanggal_wajib_kembali`, `status`, `approved_by`, `approved_at`, `catatan_approval`, `dikembalikan_at`, `created_at`, `updated_at`) VALUES
	(1, 'PJM-20260712-001', 3, NULL, NULL, 'presentasi', '2026-07-13', '2026-07-15', 'rejected', 1, '2026-07-12 20:00:29', 'laptop out of stok sesuai hp aja 1 unit dipinjam', NULL, '2026-07-12 05:15:15', '2026-07-12 20:00:29'),
	(2, 'PJM-20260712-002', 3, NULL, NULL, 'tess', '2026-07-12', '2026-07-21', 'menunggu_konfirmasi_kembali', 1, '2026-07-12 19:35:34', NULL, NULL, '2026-07-12 05:26:10', '2026-07-12 19:37:17'),
	(3, 'PJM-20260712-003', 3, NULL, NULL, 'ww', '2026-07-01', '2026-07-15', 'rejected', 1, '2026-07-12 19:33:47', 'sisanya press banget nanti aja deh yak.', NULL, '2026-07-12 05:28:40', '2026-07-12 19:33:47'),
	(4, 'PJM-20260712-004', 3, NULL, NULL, 'ss', '2026-07-14', '2026-07-17', 'rejected', NULL, NULL, NULL, NULL, '2026-07-12 05:36:28', '2026-07-19 20:04:23'),
	(5, 'PJM-20260713-001', 3, NULL, NULL, 'tess keperluan', '2026-07-13', '2026-07-16', 'approved', NULL, NULL, NULL, NULL, '2026-07-12 21:22:05', '2026-07-19 04:07:13'),
	(6, 'PJM-20260713-002', 3, NULL, NULL, 'Untuk kegiatan pembelajaran', '2026-07-13', '2026-07-13', 'dikembalikan', 1, '2026-07-14 08:26:12', NULL, '2026-07-14 08:33:22', '2026-07-13 04:46:00', '2026-07-14 08:33:22'),
	(7, 'PJM-20260713-003', 3, NULL, NULL, 'sdsad', '2026-07-22', '2026-07-24', 'dipinjam', 1, '2026-07-24 09:04:22', NULL, NULL, '2026-07-13 05:45:58', '2026-07-24 09:04:22'),
	(8, 'PJM-20260722-001', 3, NULL, NULL, 'testing', '2026-07-22', '2026-07-25', 'dipinjam', 1, '2026-07-22 14:40:42', NULL, NULL, '2026-07-22 05:47:38', '2026-07-22 14:40:42'),
	(9, 'PJM-20260722-002', 3, NULL, NULL, 'harus ke menunggu_spv', '2026-07-22', '2026-07-25', 'dipinjam', 1, '2026-07-22 17:15:46', NULL, NULL, '2026-07-22 06:38:45', '2026-07-22 17:15:46'),
	(11, 'PJM-20260722-004', 3, NULL, NULL, 'afrer update pinjem', '2026-07-22', '2026-07-25', 'rejected', 1, '2026-07-22 14:38:07', NULL, NULL, '2026-07-22 08:02:25', '2026-07-22 14:38:07'),
	(12, 'PJM-20260722-005', 3, NULL, NULL, 'sads', '2026-07-22', '2026-07-25', 'rejected', 1, '2026-07-22 14:36:18', NULL, NULL, '2026-07-22 08:02:36', '2026-07-22 14:36:18'),
	(13, 'PJM-20260723-001', 3, NULL, NULL, 'sa', '2026-07-23', '2026-07-26', 'dipinjam', 1, '2026-07-22 17:46:38', NULL, NULL, '2026-07-22 17:37:12', '2026-07-22 17:46:38'),
	(14, 'PJM-20260723-002', 3, NULL, NULL, 'das', '2026-07-23', '2026-07-26', 'dipinjam', 1, '2026-07-22 17:50:01', NULL, NULL, '2026-07-22 17:45:30', '2026-07-22 17:50:01'),
	(15, 'PJM-20260723-003', 3, NULL, NULL, 'ss', '2026-07-23', '2026-07-26', 'dipinjam', 1, '2026-07-22 18:08:34', NULL, NULL, '2026-07-22 18:04:12', '2026-07-22 18:08:34'),
	(16, 'PJM-20260723-004', 3, NULL, NULL, 's', '2026-07-23', '2026-07-26', 'selesai', 1, '2026-07-22 20:54:33', NULL, NULL, '2026-07-22 19:57:29', '2026-07-23 13:08:21'),
	(17, 'PJM-20260723-005', 3, NULL, NULL, 'sad', '2026-07-23', '2026-07-26', 'selesai', 1, '2026-07-22 21:28:56', NULL, NULL, '2026-07-22 21:27:25', '2026-07-23 13:35:53'),
	(18, 'PJM-20260723-006', 3, NULL, NULL, 'rapattt', '2026-07-23', '2026-07-26', 'selesai', 1, '2026-07-23 13:22:02', NULL, NULL, '2026-07-23 13:10:36', '2026-07-23 14:23:53'),
	(19, 'PJM-20260724-001', 3, NULL, NULL, 'REAL TEST', '2026-07-24', '2026-07-27', 'dipinjam', 1, '2026-07-24 10:49:45', NULL, NULL, '2026-07-24 10:46:49', '2026-07-24 10:49:45'),
	(20, 'PJM-20260724-002', 3, NULL, NULL, 'tess habis pakai', '2026-07-24', '2026-07-27', 'selesai', 1, '2026-07-24 11:02:32', NULL, NULL, '2026-07-24 10:58:37', '2026-07-27 06:22:32'),
	(21, 'PJM-20260727-001', 3, NULL, NULL, 'keperluan', '2026-07-27', '2026-07-30', 'menunggu_spv', NULL, NULL, NULL, NULL, '2026-07-27 03:27:38', '2026-07-27 05:58:35'),
	(22, 'PJM-20260727-002', 3, NULL, NULL, 'tes pinjam', '2026-07-27', '2026-07-30', 'menunggu_spv', NULL, NULL, NULL, NULL, '2026-07-27 06:08:01', '2026-07-27 06:09:13'),
	(23, 'PJM-20260727-003', 3, NULL, NULL, 'tes waiting', '2026-07-27', '2026-07-30', 'menunggu_spv', NULL, NULL, NULL, NULL, '2026-07-27 06:18:23', '2026-07-27 06:23:45');

CREATE TABLE IF NOT EXISTS `peminjaman_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah_pinjam` int unsigned NOT NULL,
  `jumlah_disetujui` int unsigned DEFAULT NULL,
  `kondisi_kembali` enum('baik','rusak_ringan','rusak_berat','hilang') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan_kembali` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_detail_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `peminjaman_detail_barang_id_foreign` (`barang_id`),
  CONSTRAINT `peminjaman_detail_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `peminjaman_detail_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `peminjaman_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `qty_pinjam` int unsigned NOT NULL,
  `qty_kembali_baik` int unsigned NOT NULL DEFAULT '0',
  `qty_kembali_rusak_ringan` int unsigned NOT NULL DEFAULT '0',
  `qty_kembali_rusak_berat` int unsigned NOT NULL DEFAULT '0',
  `qty_kembali_hilang` int unsigned NOT NULL DEFAULT '0',
  `qty_kembali_habis_terpakai` int unsigned NOT NULL DEFAULT '0',
  `status` enum('dipinjam','sebagian','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `peminjaman_item_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_items_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `peminjaman_items_barang_id_foreign` (`barang_id`),
  CONSTRAINT `peminjaman_items_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `peminjaman_items_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `peminjaman_items` (`id`, `peminjaman_id`, `barang_id`, `qty_pinjam`, `qty_kembali_baik`, `qty_kembali_rusak_ringan`, `qty_kembali_rusak_berat`, `qty_kembali_hilang`, `qty_kembali_habis_terpakai`, `status`, `created_at`, `updated_at`, `peminjaman_item_id`) VALUES
	(1, 16, 2, 1, 0, 1, 0, 0, 0, 'selesai', '2026-07-22 20:20:00', '2026-07-23 13:08:21', NULL),
	(2, 17, 1, 2, 2, 0, 0, 0, 0, 'selesai', '2026-07-22 21:27:34', '2026-07-23 13:35:53', NULL),
	(3, 17, 2, 3, 0, 3, 0, 0, 0, 'selesai', '2026-07-22 21:27:42', '2026-07-23 13:35:53', NULL),
	(4, 18, 2, 2, 0, 0, 2, 0, 0, 'selesai', '2026-07-23 13:10:57', '2026-07-23 14:23:53', NULL),
	(5, 19, 18, 1, 0, 0, 0, 0, 0, 'dipinjam', '2026-07-24 10:47:14', '2026-07-24 10:47:14', NULL),
	(6, 20, 19, 1, 1, 0, 0, 0, 0, 'selesai', '2026-07-24 11:01:01', '2026-07-24 11:13:19', NULL),
	(7, 20, 15, 1, 1, 0, 0, 0, 0, 'selesai', '2026-07-24 11:01:11', '2026-07-27 06:22:32', NULL),
	(8, 20, 18, 1, 1, 0, 0, 0, 0, 'selesai', '2026-07-24 11:01:21', '2026-07-27 06:22:32', NULL),
	(9, 21, 9, 1, 0, 0, 0, 0, 0, 'dipinjam', '2026-07-27 03:27:48', '2026-07-27 03:27:48', NULL),
	(10, 22, 13, 2, 0, 0, 0, 0, 0, 'dipinjam', '2026-07-27 06:08:16', '2026-07-27 06:08:16', NULL),
	(11, 22, 14, 1, 0, 0, 0, 0, 0, 'dipinjam', '2026-07-27 06:08:29', '2026-07-27 06:08:29', NULL),
	(12, 23, 1, 1, 0, 0, 0, 0, 0, 'dipinjam', '2026-07-27 06:18:43', '2026-07-27 06:18:43', NULL);

CREATE TABLE IF NOT EXISTS `pengajuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_pengajuan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `verified_by_admin` bigint unsigned DEFAULT NULL,
  `verified_at_admin` timestamp NULL DEFAULT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `alasan_pengajuan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','pending','menunggu_spv','approved','rejected','dibatalkan') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `catatan_approval` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengajuan_kode_pengajuan_unique` (`kode_pengajuan`),
  KEY `pengajuan_requested_by_foreign` (`requested_by`),
  KEY `pengajuan_approved_by_foreign` (`approved_by`),
  KEY `pengajuan_verified_by_admin_foreign` (`verified_by_admin`),
  CONSTRAINT `pengajuan_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pengajuan_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pengajuan_verified_by_admin_foreign` FOREIGN KEY (`verified_by_admin`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `pengajuan` (`id`, `kode_pengajuan`, `requested_by`, `verified_by_admin`, `verified_at_admin`, `catatan_admin`, `alasan_pengajuan`, `status`, `approved_by`, `approved_at`, `catatan_approval`, `created_at`, `updated_at`) VALUES
	(6, 'PGJ-20260712-001', 3, NULL, NULL, NULL, 'pengadaan barangnya sebab kurang.', 'approved', 1, '2026-07-12 10:54:03', NULL, '2026-07-12 03:45:57', '2026-07-12 10:54:03'),
	(7, 'PGJ-20260712-002', 3, NULL, NULL, NULL, 'pengadaan hp', 'rejected', 1, '2026-07-12 11:42:04', 'Spesifikasi HP sudah tertinggal.', '2026-07-12 11:40:54', '2026-07-12 11:42:04'),
	(8, 'PGJ-20260712-003', 3, NULL, NULL, NULL, 'Laptop Kurang', 'approved', 1, '2026-07-12 18:47:44', 'sudah ada di persediaan', '2026-07-12 11:44:44', '2026-07-12 18:47:44'),
	(9, 'PGJ-20260713-001', 3, NULL, NULL, NULL, 'kosong testing', 'approved', 1, '2026-07-12 18:47:19', NULL, '2026-07-12 18:45:54', '2026-07-12 18:47:19'),
	(10, 'PGJ-20260713-002', 3, NULL, NULL, NULL, 'ssss', 'approved', 1, '2026-07-22 13:50:37', NULL, '2026-07-12 21:27:37', '2026-07-22 13:50:37'),
	(11, 'PGJ-20260713-003', 3, 2, '2026-07-19 20:22:46', NULL, 'Untuk digunakan sendiri', 'rejected', NULL, NULL, 's', '2026-07-13 04:32:48', '2026-07-19 20:22:46'),
	(12, 'PGJ-20260713-004', 3, 2, '2026-07-19 19:22:29', NULL, 'sada', 'menunggu_spv', NULL, NULL, NULL, '2026-07-13 09:05:51', '2026-07-19 19:22:29'),
	(13, 'PGJ-20260714-001', 3, NULL, NULL, NULL, 'tambah unit', 'dibatalkan', NULL, NULL, NULL, '2026-07-14 07:30:20', '2026-07-20 17:00:58'),
	(14, 'PGJ-20260720-001', 3, NULL, NULL, NULL, 'update duluu wkw', 'approved', 1, '2026-07-22 13:50:44', NULL, '2026-07-20 09:44:39', '2026-07-22 13:50:44'),
	(15, 'PGJ-20260722-001', 3, NULL, NULL, NULL, 'tesss harus ke->spv', 'dibatalkan', NULL, NULL, NULL, '2026-07-22 05:47:26', '2026-07-22 07:21:30'),
	(16, 'PGJ-20260722-002', 3, NULL, NULL, NULL, 'test1 pengajuan sudah update', 'approved', 1, '2026-07-22 13:50:28', NULL, '2026-07-22 08:09:37', '2026-07-22 13:50:28'),
	(17, 'PGJ-20260724-001', 3, NULL, NULL, NULL, 'Untuk digunakan dalam kegiatan Latsar', 'pending', NULL, NULL, NULL, '2026-07-24 08:38:51', '2026-07-24 08:39:30'),
	(18, 'PGJ-20260724-002', 3, NULL, NULL, NULL, 'untuk kbm mapel bahasa indonesia', 'pending', NULL, NULL, NULL, '2026-07-24 09:17:03', '2026-07-24 09:17:57'),
	(19, 'PGJ-20260724-003', 3, NULL, NULL, NULL, 'Untuk kbm bahasa inggris', 'pending', NULL, NULL, NULL, '2026-07-24 09:21:34', '2026-07-24 09:22:14'),
	(20, 'PGJ-20260726-001', 3, NULL, NULL, NULL, 'pengganti projector rusak', 'pending', NULL, NULL, NULL, '2026-07-26 04:43:19', '2026-07-27 05:54:19'),
	(21, 'PGJ-20260727-001', 3, 2, '2026-07-27 06:00:44', NULL, 'laptop masalah', 'menunggu_spv', NULL, NULL, NULL, '2026-07-27 05:59:22', '2026-07-27 06:00:44');

CREATE TABLE IF NOT EXISTS `pengajuan_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengajuan_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned DEFAULT NULL,
  `nama_barang_diajukan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_diajukan` int unsigned NOT NULL,
  `catatan_item` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengajuan_detail_pengajuan_id_foreign` (`pengajuan_id`),
  KEY `pengajuan_detail_barang_id_foreign` (`barang_id`) USING BTREE,
  CONSTRAINT `pengajuan_detail_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `pengajuan_detail` (`id`, `pengajuan_id`, `barang_id`, `nama_barang_diajukan`, `jumlah_diajukan`, `catatan_item`, `created_at`, `updated_at`) VALUES
	(1, 6, 2, 'panjul', 1, NULL, '2026-07-12 04:10:36', '2026-07-12 04:10:36'),
	(2, 6, 1, 'asada', 2, NULL, '2026-07-12 04:10:53', '2026-07-12 04:10:53'),
	(3, 7, 2, 'asada', 3, NULL, '2026-07-12 11:41:05', '2026-07-12 11:41:05'),
	(4, 8, 1, 'asada', 2, NULL, '2026-07-12 11:45:07', '2026-07-12 11:45:07'),
	(5, 9, 1, 'asada', 1, NULL, '2026-07-12 18:46:12', '2026-07-12 18:46:12'),
	(6, 10, 1, 'asada', 2, NULL, '2026-07-12 21:27:47', '2026-07-12 21:27:47'),
	(7, 11, 1, 'asada', 1, NULL, '2026-07-13 04:44:59', '2026-07-13 04:44:59'),
	(8, 12, NULL, 'Lenovo ThinkPad', 4, NULL, '2026-07-13 09:43:01', '2026-07-13 09:43:01'),
	(10, 13, NULL, 'laptop', 1, NULL, '2026-07-20 09:04:55', '2026-07-20 09:04:55'),
	(11, 13, NULL, 'printer a3', 4, NULL, '2026-07-20 09:30:35', '2026-07-20 09:30:35'),
	(26, 14, NULL, 'kop', 3, NULL, '2026-07-22 05:22:40', '2026-07-22 05:22:40'),
	(27, 15, NULL, 'laptop ace', 3, NULL, '2026-07-22 07:16:31', '2026-07-22 07:16:31'),
	(28, 15, NULL, 'esssss batu', 1, NULL, '2026-07-22 07:16:41', '2026-07-22 07:16:41'),
	(30, 16, NULL, 'barang 2', 3, NULL, '2026-07-22 08:11:29', '2026-07-22 08:11:29'),
	(31, 17, NULL, 'Konektor LAN', 1, NULL, '2026-07-24 08:39:24', '2026-07-24 08:39:24'),
	(32, 18, NULL, 'kamus bahasa indonesia', 1, NULL, '2026-07-24 09:17:50', '2026-07-24 09:17:50'),
	(34, 19, NULL, 'Kamus bahasa Inggris', 1, NULL, '2026-07-24 09:22:10', '2026-07-24 09:22:10'),
	(35, 20, NULL, 'laptop abc', 3, NULL, '2026-07-27 05:53:55', '2026-07-27 05:53:55'),
	(36, 20, NULL, 'projector abc', 3, NULL, '2026-07-27 05:54:16', '2026-07-27 05:54:16'),
	(37, 21, NULL, 'laptop', 1, NULL, '2026-07-27 05:59:36', '2026-07-27 05:59:36'),
	(38, 21, NULL, 'hp bermasalah', 2, NULL, '2026-07-27 05:59:48', '2026-07-27 05:59:48');

CREATE TABLE IF NOT EXISTS `pengembalians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint unsigned NOT NULL,
  `dikembalikan_oleh` bigint unsigned NOT NULL,
  `tanggal_pengembalian` datetime NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('menunggu_verifikasi_admin','menunggu_verifikasi_spv','selesai','ditolak_admin','ditolak_spv') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_verifikasi_admin',
  `diverifikasi_admin_oleh` bigint unsigned DEFAULT NULL,
  `diverifikasi_admin_at` datetime DEFAULT NULL,
  `diverifikasi_spv_oleh` bigint unsigned DEFAULT NULL,
  `diverifikasi_spv_at` datetime DEFAULT NULL,
  `alasan_tolak` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengembalians_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `pengembalians_dikembalikan_oleh_foreign` (`dikembalikan_oleh`),
  KEY `pengembalians_diverifikasi_admin_oleh_foreign` (`diverifikasi_admin_oleh`),
  KEY `pengembalians_diverifikasi_spv_oleh_foreign` (`diverifikasi_spv_oleh`),
  CONSTRAINT `pengembalians_dikembalikan_oleh_foreign` FOREIGN KEY (`dikembalikan_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `pengembalians_diverifikasi_admin_oleh_foreign` FOREIGN KEY (`diverifikasi_admin_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `pengembalians_diverifikasi_spv_oleh_foreign` FOREIGN KEY (`diverifikasi_spv_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `pengembalians_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `pengembalians` (`id`, `peminjaman_id`, `dikembalikan_oleh`, `tanggal_pengembalian`, `catatan`, `status`, `diverifikasi_admin_oleh`, `diverifikasi_admin_at`, `diverifikasi_spv_oleh`, `diverifikasi_spv_at`, `alasan_tolak`, `created_at`, `updated_at`) VALUES
	(4, 17, 3, '2026-07-23 04:44:50', NULL, 'selesai', 2, '2026-07-23 12:34:42', 1, '2026-07-23 13:45:25', NULL, '2026-07-22 21:44:50', '2026-07-23 06:45:25'),
	(5, 17, 3, '2026-07-23 13:55:42', 'sad', 'ditolak_admin', 2, '2026-07-23 14:21:24', NULL, NULL, 'Ditolak oleh verifikator', '2026-07-23 06:55:42', '2026-07-23 07:21:24'),
	(6, 16, 3, '2026-07-23 14:16:30', NULL, 'selesai', 2, '2026-07-23 17:54:53', 1, '2026-07-23 20:08:21', NULL, '2026-07-23 07:16:30', '2026-07-23 13:08:21'),
	(7, 17, 3, '2026-07-23 20:30:00', 'HP samsung j2 nya rusak', 'selesai', 2, '2026-07-23 20:34:51', 1, '2026-07-23 20:35:53', NULL, '2026-07-23 13:30:00', '2026-07-23 13:35:53'),
	(8, 18, 3, '2026-07-23 21:06:07', NULL, 'ditolak_admin', 2, '2026-07-23 21:09:27', NULL, NULL, 'Ditolak oleh admin', '2026-07-23 14:06:07', '2026-07-23 14:09:27'),
	(9, 18, 3, '2026-07-23 21:09:45', NULL, 'selesai', 2, '2026-07-23 21:13:04', 1, '2026-07-23 21:23:53', NULL, '2026-07-23 14:09:45', '2026-07-23 14:23:53'),
	(10, 20, 3, '2026-07-24 18:04:43', NULL, 'selesai', 2, '2026-07-24 18:11:53', 1, '2026-07-24 18:13:19', NULL, '2026-07-24 11:04:43', '2026-07-24 11:13:19'),
	(11, 20, 3, '2026-07-27 13:19:17', NULL, 'selesai', 2, '2026-07-27 13:20:56', 1, '2026-07-27 13:22:32', NULL, '2026-07-27 06:19:17', '2026-07-27 06:22:32');

CREATE TABLE IF NOT EXISTS `pengembalian_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengembalian_id` bigint unsigned NOT NULL,
  `qty_baik` int unsigned NOT NULL DEFAULT '0',
  `qty_rusak_ringan` int unsigned NOT NULL DEFAULT '0',
  `qty_rusak_berat` int unsigned NOT NULL DEFAULT '0',
  `qty_rusak` int unsigned NOT NULL DEFAULT '0',
  `qty_hilang` int unsigned NOT NULL DEFAULT '0',
  `qty_habis_terpakai` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `peminjaman_item_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pengembalian_items_pengembalian_id_foreign` (`pengembalian_id`),
  KEY `pengembalian_items_peminjaman_item_id_foreign` (`peminjaman_item_id`),
  CONSTRAINT `pengembalian_items_peminjaman_item_id_foreign` FOREIGN KEY (`peminjaman_item_id`) REFERENCES `peminjaman_items` (`id`),
  CONSTRAINT `pengembalian_items_pengembalian_id_foreign` FOREIGN KEY (`pengembalian_id`) REFERENCES `pengembalians` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `pengembalian_items` (`id`, `pengembalian_id`, `qty_baik`, `qty_rusak_ringan`, `qty_rusak_berat`, `qty_rusak`, `qty_hilang`, `qty_habis_terpakai`, `created_at`, `updated_at`, `peminjaman_item_id`) VALUES
	(4, 4, 1, 0, 0, 0, 0, 0, '2026-07-22 21:44:50', '2026-07-22 21:44:50', 2),
	(5, 4, 0, 2, 0, 2, 0, 0, '2026-07-22 21:44:50', '2026-07-22 21:44:50', 3),
	(6, 6, 0, 1, 0, 1, 0, 0, '2026-07-23 07:16:30', '2026-07-23 07:16:30', 1),
	(7, 7, 1, 0, 0, 0, 0, 0, '2026-07-23 13:30:00', '2026-07-23 13:30:00', 2),
	(8, 7, 0, 1, 0, 1, 0, 0, '2026-07-23 13:30:00', '2026-07-23 13:30:00', 3),
	(9, 8, 0, 0, 0, 0, 0, 0, '2026-07-23 14:06:07', '2026-07-23 14:06:07', 4),
	(10, 9, 0, 0, 2, 0, 0, 0, '2026-07-23 14:09:45', '2026-07-23 14:09:45', 4),
	(11, 10, 1, 0, 0, 0, 0, 0, '2026-07-24 11:04:43', '2026-07-24 11:04:43', 6),
	(12, 11, 1, 0, 0, 0, 0, 0, '2026-07-27 06:19:17', '2026-07-27 06:19:17', 7),
	(13, 11, 1, 0, 0, 0, 0, 0, '2026-07-27 06:19:17', '2026-07-27 06:19:17', 8);

CREATE TABLE IF NOT EXISTS `periodes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('ganjil','genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` smallint unsigned NOT NULL,
  `status` enum('aktif','terkunci') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `dikunci_oleh` bigint unsigned DEFAULT NULL,
  `dikunci_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `periodes_semester_tahun_unique` (`semester`,`tahun`),
  KEY `periodes_dikunci_oleh_foreign` (`dikunci_oleh`),
  CONSTRAINT `periodes_dikunci_oleh_foreign` FOREIGN KEY (`dikunci_oleh`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `periodes` (`id`, `nama`, `semester`, `tahun`, `status`, `dikunci_oleh`, `dikunci_at`, `created_at`, `updated_at`) VALUES
	(1, 'Ganjil 2026', 'ganjil', 2026, 'aktif', NULL, NULL, '2026-07-26 02:59:26', '2026-07-27 01:48:51');

CREATE TABLE IF NOT EXISTS `permintaans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_permintaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_by` bigint unsigned NOT NULL,
  `verified_by_admin` bigint unsigned DEFAULT NULL,
  `verified_at_admin` timestamp NULL DEFAULT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `status_permintaan` enum('draft','pending','menunggu_spv','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permintaans_kode_permintaan_unique` (`kode_permintaan`),
  KEY `permintaans_verified_by_admin_foreign` (`verified_by_admin`),
  CONSTRAINT `permintaans_verified_by_admin_foreign` FOREIGN KEY (`verified_by_admin`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `permintaans` (`id`, `kode_permintaan`, `request_by`, `verified_by_admin`, `verified_at_admin`, `catatan_admin`, `approved_by`, `status_permintaan`, `approved_date`, `created_at`, `updated_at`, `keperluan`) VALUES
	(8, 'PMT-20260713-001', 3, NULL, NULL, NULL, 1, 'menunggu_spv', NULL, '2026-07-12 19:08:41', '2026-07-26 20:19:54', 'mau dipake buat lab'),
	(9, 'PMT-20260713-002', 3, NULL, NULL, NULL, NULL, 'menunggu_spv', NULL, '2026-07-12 21:28:38', '2026-07-24 09:08:36', 'acara MPLS'),
	(10, 'PMT-20260713-003', 3, NULL, NULL, NULL, 1, 'approved', NULL, '2026-07-13 04:35:36', '2026-07-20 06:10:18', 'update pending'),
	(11, 'PMT-20260722-001', 3, NULL, NULL, NULL, NULL, 'menunggu_spv', NULL, '2026-07-22 07:15:45', '2026-07-24 09:00:44', 'updatee'),
	(12, 'PMT-20260722-002', 3, NULL, NULL, NULL, 1, 'approved', NULL, '2026-07-22 08:13:34', '2026-07-24 09:02:57', 'after update'),
	(13, 'PMT-20260724-001', 3, NULL, NULL, NULL, NULL, 'menunggu_spv', NULL, '2026-07-24 08:41:16', '2026-07-26 06:40:16', 'abc');

CREATE TABLE IF NOT EXISTS `permintaan_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permintaan_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah_diminta` int unsigned NOT NULL,
  `jumlah_disetujui` int unsigned DEFAULT NULL,
  `catatan_item` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permintaan_detail_permintaan_id_foreign` (`permintaan_id`),
  KEY `permintaan_detail_barang_id_foreign` (`barang_id`),
  CONSTRAINT `permintaan_detail_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `permintaan_detail_permintaan_id_foreign` FOREIGN KEY (`permintaan_id`) REFERENCES `permintaans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `permintaan_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `permintaan_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah_diminta` int unsigned NOT NULL,
  `jumlah_disetujui` int unsigned DEFAULT NULL,
  `catatan_item` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permintaan_details_permintaan_id_foreign` (`permintaan_id`),
  KEY `permintaan_details_barang_id_foreign` (`barang_id`) USING BTREE,
  CONSTRAINT `permintaan_details_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `permintaan_details_permintaan_id_foreign` FOREIGN KEY (`permintaan_id`) REFERENCES `permintaans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `permintaan_details` (`id`, `permintaan_id`, `barang_id`, `jumlah_diminta`, `jumlah_disetujui`, `catatan_item`, `created_at`, `updated_at`) VALUES
	(1, 8, 1, 3, 2, NULL, '2026-07-12 19:09:20', '2026-07-12 19:17:30'),
	(5, 10, 2, 10, 10, NULL, '2026-07-20 05:55:22', '2026-07-20 06:08:45'),
	(6, 9, 2, 9, NULL, NULL, '2026-07-22 06:51:23', '2026-07-22 06:51:23'),
	(7, 9, 1, 1, NULL, NULL, '2026-07-22 06:51:30', '2026-07-22 06:51:30'),
	(8, 11, 2, 1, NULL, NULL, '2026-07-22 07:15:51', '2026-07-22 07:15:51'),
	(9, 11, 1, 1, NULL, NULL, '2026-07-22 07:15:56', '2026-07-22 07:15:56'),
	(10, 12, 2, 3, 3, NULL, '2026-07-22 08:13:48', '2026-07-24 09:02:57'),
	(11, 13, 9, 1, NULL, NULL, '2026-07-24 10:43:29', '2026-07-24 10:43:29');

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view-pengajuan', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(2, 'create-pengajuan', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(3, 'view-permintaan', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(4, 'create-permintaan', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(5, 'approve-persediaan', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(6, 'approve-peminjaman', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(7, 'approve-pengembalian', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(8, 'view-peminjaman', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(9, 'create-peminjaman', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(10, 'view-pengembalian', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(11, 'create-pengembalian', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(12, 'manage-barang', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(13, 'manage-kategori', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(14, 'manage-kib', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(15, 'manage-pegawai', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(16, 'view-laporan', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44');

CREATE TABLE IF NOT EXISTS `persedians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `barang_id` bigint unsigned NOT NULL,
  `asal_dana` enum('bos','bop') COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `harga_satuan_unit` double DEFAULT '123.4567',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `harga_total` double NOT NULL,
  `approval_status` enum('menunggu','diterima','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `catatan_approval` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `persedians` (`id`, `barang_id`, `asal_dana`, `qty`, `tanggal_masuk`, `harga_satuan_unit`, `created_at`, `updated_at`, `harga_total`, `approval_status`, `catatan_approval`) VALUES
	(1, 2, 'bop', 10, '2026-07-12', 1500000, '2026-07-12 02:31:37', '2026-07-12 20:37:23', 15000000, 'diterima', NULL),
	(2, 1, 'bos', 5, '2026-07-13', 14000000, '2026-07-12 22:12:12', '2026-07-12 22:12:51', 70000000, 'diterima', NULL);

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'spv', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(2, 'admin', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44'),
	(3, 'staf', 'web', '2026-07-11 08:16:44', '2026-07-11 08:16:44');

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(1, 2),
	(3, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(10, 2),
	(16, 2),
	(1, 3),
	(2, 3),
	(3, 3),
	(4, 3),
	(8, 3),
	(9, 3),
	(10, 3),
	(11, 3);

CREATE TABLE IF NOT EXISTS `saldo_awal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned DEFAULT NULL,
  `no_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pencatatan` date NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `sumber` enum('manual','dari_opname') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `stok_opname_id` bigint unsigned DEFAULT NULL,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `status` enum('menunggu_verifikasi_spv','selesai','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu_verifikasi_spv',
  `diverifikasi_oleh` bigint unsigned DEFAULT NULL,
  `diverifikasi_at` datetime DEFAULT NULL,
  `alasan_tolak` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saldo_awal_no_transaksi_unique` (`no_transaksi`),
  KEY `saldo_awal_dibuat_oleh_foreign` (`dibuat_oleh`),
  KEY `saldo_awal_diverifikasi_oleh_foreign` (`diverifikasi_oleh`),
  KEY `saldo_awal_periode_id_foreign` (`periode_id`),
  KEY `saldo_awal_stok_opname_id_foreign` (`stok_opname_id`),
  CONSTRAINT `saldo_awal_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `saldo_awal_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `saldo_awal_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`),
  CONSTRAINT `saldo_awal_stok_opname_id_foreign` FOREIGN KEY (`stok_opname_id`) REFERENCES `stok_opnames` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `saldo_awal` (`id`, `periode_id`, `no_transaksi`, `tanggal_pencatatan`, `catatan`, `sumber`, `stok_opname_id`, `dibuat_oleh`, `status`, `diverifikasi_oleh`, `diverifikasi_at`, `alasan_tolak`, `created_at`, `updated_at`) VALUES
	(2, NULL, 'SLA-20260725-001', '2026-07-25', NULL, 'manual', NULL, 2, 'menunggu_verifikasi_spv', NULL, NULL, NULL, '2026-07-25 12:39:51', '2026-07-25 12:39:51'),
	(3, NULL, 'SLA-20260727-001', '2026-07-27', NULL, 'manual', NULL, 2, 'ditolak', 1, '2026-07-27 13:26:35', 'Ditolak oleh supervisor', '2026-07-27 01:50:38', '2026-07-27 06:26:35'),
	(4, NULL, 'SLA-20260727-002', '2026-07-27', NULL, 'manual', NULL, 2, 'ditolak', 1, '2026-07-27 13:26:27', 'Ditolak oleh supervisor', '2026-07-27 01:51:17', '2026-07-27 06:26:27');

CREATE TABLE IF NOT EXISTS `saldo_awal_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `saldo_awal_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `qty` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saldo_awal_items_saldo_awal_id_barang_id_unique` (`saldo_awal_id`,`barang_id`),
  KEY `saldo_awal_items_barang_id_foreign` (`barang_id`),
  CONSTRAINT `saldo_awal_items_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `saldo_awal_items_saldo_awal_id_foreign` FOREIGN KEY (`saldo_awal_id`) REFERENCES `saldo_awal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `saldo_awal_items` (`id`, `saldo_awal_id`, `barang_id`, `qty`, `created_at`, `updated_at`) VALUES
	(1, 2, 18, 18, '2026-07-25 12:39:51', '2026-07-25 12:39:51'),
	(2, 2, 15, 7, '2026-07-25 12:39:51', '2026-07-25 12:39:51'),
	(3, 3, 18, 3, '2026-07-27 01:50:38', '2026-07-27 01:50:38'),
	(4, 3, 20, 10, '2026-07-27 01:50:38', '2026-07-27 01:50:38'),
	(5, 3, 19, 7, '2026-07-27 01:50:38', '2026-07-27 01:50:38'),
	(6, 3, 17, 1, '2026-07-27 01:50:38', '2026-07-27 01:50:38'),
	(7, 4, 18, 3, '2026-07-27 01:51:17', '2026-07-27 01:51:17'),
	(8, 4, 20, 10, '2026-07-27 01:51:17', '2026-07-27 01:51:17'),
	(9, 4, 19, 7, '2026-07-27 01:51:17', '2026-07-27 01:51:17'),
	(10, 4, 17, 1, '2026-07-27 01:51:17', '2026-07-27 01:51:17');

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('ayuKDWebq5Us8ItGATGaHjteMviR3a967q6TjYJJ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYlVjb1RoM01iUXJxajNtY3V0NjN0VjZTNkJDZjJ0UmdmVnNHTDY4UCI7czo1OiJhbGVydCI7YTowOnt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zcHYvc2FsZG8tYXdhbCI7czo1OiJyb3V0ZSI7czoyMDoic3B2LnNhbGRvLWF3YWwuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1785133596),
	('ck05ShhwaeHVnelKONV9sklnZTcSTnobx71H57bx', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYVZKVmtTem9xS1dvckN0Uk5WdWp5V2RGd0VQY2RGcExST2NNaVRMdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zYWxkby1hd2FsLzIiO3M6NToicm91dGUiO3M6MTU6InNhbGRvLWF3YWwuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czo1OiJhbGVydCI7YTowOnt9fQ==', 1785134212),
	('sEAAmmjbbrLziBz5JQLaXSXPQDaOuFbehOMe3ZuK', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNjlNQkNBVEZVbm1RWDVyNkllTUJMZnJGbmpvOVlLdWZBc2FaaExRZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yaXdheWF0LXNheWEiO3M6NToicm91dGUiO3M6MTM6InJpd2F5YXQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6NToiYWxlcnQiO2E6MDp7fX0=', 1785133372);

CREATE TABLE IF NOT EXISTS `stok_opnames` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned NOT NULL,
  `no_bast` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_bast` date DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `dibuat_oleh` bigint unsigned NOT NULL,
  `status` enum('draft','menunggu_verifikasi_spv','selesai','dibatalkan_spv') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `diverifikasi_oleh` bigint unsigned DEFAULT NULL,
  `diverifikasi_at` datetime DEFAULT NULL,
  `catatan_cancel` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stok_opnames_periode_id_foreign` (`periode_id`),
  KEY `stok_opnames_dibuat_oleh_foreign` (`dibuat_oleh`),
  KEY `stok_opnames_diverifikasi_oleh_foreign` (`diverifikasi_oleh`),
  CONSTRAINT `stok_opnames_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `stok_opnames_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id`),
  CONSTRAINT `stok_opnames_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `stok_opnames` (`id`, `periode_id`, `no_bast`, `tanggal_bast`, `catatan`, `dibuat_oleh`, `status`, `diverifikasi_oleh`, `diverifikasi_at`, `catatan_cancel`, `created_at`, `updated_at`) VALUES
	(5, 1, '001/BAST-OPNAME/GANJIL2026', '2026-07-26', NULL, 2, 'selesai', 1, '2026-07-26 10:27:25', NULL, '2026-07-26 03:03:45', '2026-07-26 03:27:25'),
	(6, 1, '001/BAST-OPNAME/GANJIL2026-01', '2026-07-27', NULL, 2, 'selesai', 1, '2026-07-27 10:29:40', NULL, '2026-07-27 01:52:15', '2026-07-27 03:29:40');

CREATE TABLE IF NOT EXISTS `stok_opname_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stok_opname_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `stok_sistem` int NOT NULL,
  `stok_fisik` int DEFAULT NULL,
  `selisih` int NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stok_opname_items_stok_opname_id_barang_id_unique` (`stok_opname_id`,`barang_id`),
  KEY `stok_opname_items_barang_id_foreign` (`barang_id`),
  CONSTRAINT `stok_opname_items_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  CONSTRAINT `stok_opname_items_stok_opname_id_foreign` FOREIGN KEY (`stok_opname_id`) REFERENCES `stok_opnames` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `stok_opname_items` (`id`, `stok_opname_id`, `barang_id`, `stok_sistem`, `stok_fisik`, `selisih`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, 5, 1, 3, 3, 0, 'hilang 1', '2026-07-26 03:03:45', '2026-07-26 03:27:06'),
	(2, 5, 2, 7, 7, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(3, 5, 9, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(4, 5, 10, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(5, 5, 11, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(6, 5, 12, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(7, 5, 13, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(8, 5, 14, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(9, 5, 15, 9, 9, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(10, 5, 16, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(11, 5, 17, 10, 10, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(12, 5, 18, 8, 8, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(13, 5, 19, 14, 14, 0, NULL, '2026-07-26 03:03:45', '2026-07-26 03:13:34'),
	(14, 6, 1, 3, 3, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:43'),
	(15, 6, 2, 7, 7, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:43'),
	(16, 6, 9, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:43'),
	(17, 6, 10, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:43'),
	(18, 6, 11, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:43'),
	(19, 6, 12, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(20, 6, 13, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(21, 6, 14, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(22, 6, 15, 9, 9, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(23, 6, 16, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(24, 6, 17, 10, 10, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(25, 6, 18, 8, 8, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(26, 6, 19, 14, 14, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(27, 6, 20, 0, 0, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(28, 6, 22, 0, 0, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44'),
	(29, 6, 28, 0, 0, 0, NULL, '2026-07-27 01:52:15', '2026-07-27 03:26:44');

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_karyawan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `users` (`id`, `nama_karyawan`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'supervisor', '123123', NULL, '$2y$12$V53WZDNzrsiI4V0OKVlbueDBvDDMtggikHBZ4fW1mneeEA0mhTzsu', NULL, '2026-07-11 08:17:51', '2026-07-11 08:17:51'),
	(2, 'Admin', '112233', NULL, '$2y$12$DPX3i1NKVC/.03EiLk1fAeIv8JElpwIE74XNhJNvAee6eVkPFxM1m', NULL, '2026-07-11 08:18:09', '2026-07-11 08:18:09'),
	(3, 'Pengguna', '321321', NULL, '$2y$12$FE96bEJ1ejjlvzE22WWP1eBumrUn120YCdxathH9R5JUz7uRFzvpq', NULL, '2026-07-11 08:18:32', '2026-07-11 08:18:32'),
	(4, 'Donda Banjarnahor', '222019', NULL, '$2y$12$ndOH60BEP50kxiSuNc4WZuIK2hTE0m6NeNemtXnEAd.mMkSpS/ZqG', NULL, '2026-07-13 05:15:38', '2026-07-25 19:37:58'),
	(5, 'Pengguna 001', '111222', NULL, '$2y$12$0NiTZqVh4UQ2yJH5jiE5K.3flYgn1ezgCyzHh78SdovKlkkvqZPZK', NULL, '2026-07-23 20:56:00', '2026-07-23 20:56:00'),
	(6, 'Pengguna 001', '111222111', NULL, '$2y$12$H2lOsUVYfvVjRjqWmQlK.eZhk3uofJ1/Voml4x9zHjcf.xSfDJzY6', NULL, '2026-07-23 20:59:00', '2026-07-23 20:59:00'),
	(12, 'Pengguna 001', '12341234', NULL, '$2y$12$4zOk8D7vicvPzNCTjPeIsumK1JUfP25ioEAOyDfuVQLl/j3n5p8Lm', NULL, '2026-07-23 21:11:24', '2026-07-23 21:11:24'),
	(13, 'Pengguna 001', '1234', NULL, '$2y$12$RF6s2Digwt3jom6QwfCXlOzMNZAY7ZPVhvlsR2ImqvyGSZ6GNP8NW', NULL, '2026-07-23 21:13:04', '2026-07-23 21:13:04'),
	(14, 'Pengguna 001', '4321', NULL, '$2y$12$dX8dLM6iMuttbADszbp86.nKNF1zEoZolA0Aqf9whzqqDE0L3Vuvq', NULL, '2026-07-23 21:19:03', '2026-07-23 21:19:03'),
	(15, 'Admin 001', 'admin001', NULL, '$2y$12$YQs1KHlWjuiJYVbg04jj0ueAZ9emKrI1RHKUqgAPpvlqmgJntWqdS', NULL, '2026-07-25 05:59:39', '2026-07-25 19:37:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
