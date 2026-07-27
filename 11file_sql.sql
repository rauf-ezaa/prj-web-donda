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

CREATE TABLE IF NOT EXISTS `kib` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_kib` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `klasifikasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
