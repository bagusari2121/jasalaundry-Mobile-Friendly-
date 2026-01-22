/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 8.4.3 : Database - sikasir
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sikasir` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `sikasir`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
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

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
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

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
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

/*Data for the table `jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1);

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
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

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('aP91HxMFIB8ywJ6DiVTWJSYdxJdiQVVZHs3KwurC',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ0xhaVNzYnJuYlNIMXJqekxycWJrc1RGclg4UFJTc3RIR0Fuc0phNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1769056127),
('kOPEQ2C2BZluSKfD7VX8r8y2ImVyvLCUQfocmmuj',NULL,'192.168.1.11','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDhRckdFelhYd2FNNno1THNjY2R0a1doY0o2RXRQdlFKazVNUDR4QSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xOTIuMTY4LjEuNzo4MDAwL2xvZ2luIjt9fQ==',1769056716);

/*Table structure for table `stock_logs` */

DROP TABLE IF EXISTS `stock_logs`;

CREATE TABLE `stock_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_outlet` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `tipe` enum('Masuk','Keluar') DEFAULT NULL,
  `jumlah` float DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text,
  `pic` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stok` (`id_produk`),
  KEY `fk_outlet_6` (`id_outlet`),
  KEY `fk_user_10` (`pic`),
  CONSTRAINT `fk_outlet_6` FOREIGN KEY (`id_outlet`) REFERENCES `tm_outlet` (`id`),
  CONSTRAINT `fk_stok` FOREIGN KEY (`id_produk`) REFERENCES `tm_produk` (`id`),
  CONSTRAINT `fk_user_10` FOREIGN KEY (`pic`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `stock_logs` */

insert  into `stock_logs`(`id`,`id_outlet`,`id_produk`,`tipe`,`jumlah`,`tanggal`,`keterangan`,`pic`,`created_at`,`updated_at`) values 
(1,2,2,'Masuk',1000,NULL,'Stok Masuk',3,'2026-01-12 07:45:42','2026-01-12 07:45:42'),
(2,2,2,'Keluar',10,NULL,'Terjual (TRX-YLGDV074609)',3,'2026-01-12 07:46:09','2026-01-12 07:46:09'),
(3,2,2,'Keluar',2,NULL,'Terjual (TRX-EAOUU155327)',3,'2026-01-12 15:53:27','2026-01-12 15:53:27'),
(4,2,2,'Keluar',15,NULL,'Terjual (TRX-B70HG160429)',4,'2026-01-12 16:04:29','2026-01-12 16:04:29');

/*Table structure for table `stok_outlet` */

DROP TABLE IF EXISTS `stok_outlet`;

CREATE TABLE `stok_outlet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_outlet` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_outlet_4` (`id_outlet`),
  KEY `fk_produk_5` (`id_produk`),
  CONSTRAINT `fk_outlet_4` FOREIGN KEY (`id_outlet`) REFERENCES `tm_outlet` (`id`),
  CONSTRAINT `fk_produk_5` FOREIGN KEY (`id_produk`) REFERENCES `tm_produk` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `stok_outlet` */

insert  into `stok_outlet`(`id`,`id_outlet`,`id_produk`,`stok`,`created_at`,`updated_at`) values 
(1,2,2,973,'2026-01-12 07:45:42','2026-01-12 16:04:29');

/*Table structure for table `tm_customer` */

DROP TABLE IF EXISTS `tm_customer`;

CREATE TABLE `tm_customer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_outlet` int DEFAULT NULL,
  `nama_customer` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `is_langganan` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_outlet_customer` (`id_outlet`),
  CONSTRAINT `fk_outlet_customer` FOREIGN KEY (`id_outlet`) REFERENCES `tm_outlet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tm_customer` */

insert  into `tm_customer`(`id`,`id_outlet`,`nama_customer`,`telepon`,`alamat`,`is_langganan`,`created_at`,`updated_at`) values 
(1,1,'Samsul','085732820815','Kediri Jawa Timur',0,'2026-01-05 09:32:59','2026-01-12 08:10:42'),
(2,2,'Coba',NULL,'Kediri Jawa Timur',0,'2026-01-05 13:30:05','2026-01-05 13:30:05'),
(3,1,'Mas Ngawi','085732820815','Ngawi',1,'2026-01-06 08:25:20','2026-01-20 14:18:10'),
(4,2,'Lazam','085732820815','Nganjuk',1,'2026-01-07 11:16:24','2026-01-22 09:19:47'),
(5,1,'Dendi','0857328208158','Kediri Utara',1,'2026-01-12 07:43:06','2026-01-12 07:43:06'),
(6,2,'Mas Bay',NULL,NULL,1,'2026-01-12 12:52:30','2026-01-12 12:52:30'),
(7,2,'Yono Bakrie','0857658155','Jakarta',0,'2026-01-12 15:51:46','2026-01-12 15:51:46'),
(8,2,'Yanto Bruno','085545558633','Ponggok, Blitar',1,'2026-01-12 15:52:43','2026-01-20 14:01:58'),
(9,1,'Pak Edi','085665226636','Blabak',1,'2026-01-20 13:36:27','2026-01-22 07:49:54');

/*Table structure for table `tm_kategori_pengeluaran` */

DROP TABLE IF EXISTS `tm_kategori_pengeluaran`;

CREATE TABLE `tm_kategori_pengeluaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pengeluaran` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tm_kategori_pengeluaran` */

insert  into `tm_kategori_pengeluaran`(`id`,`nama_pengeluaran`,`is_active`,`created_at`,`updated_at`) values 
(1,'Membayar Tagihan Listrik Bulanan',1,'2026-01-06 07:47:19','2026-01-06 07:47:19');

/*Table structure for table `tm_layanan` */

DROP TABLE IF EXISTS `tm_layanan`;

CREATE TABLE `tm_layanan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(255) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `satuan` varchar(10) DEFAULT NULL,
  `estimasi_selesai` float DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tm_layanan` */

insert  into `tm_layanan`(`id`,`nama_layanan`,`harga`,`satuan`,`estimasi_selesai`,`diskon`,`created_at`,`updated_at`) values 
(1,'Setrika',5000,'kg',NULL,15,'2026-01-05 09:30:33','2026-01-12 15:47:23'),
(2,'Cuci Kering Setrika',10000,'kg',NULL,10,'2026-01-12 15:45:48','2026-01-12 15:45:48'),
(3,'Cuci Kering',5000,'kg',NULL,NULL,'2026-01-22 09:03:19','2026-01-22 09:03:19');

/*Table structure for table `tm_outlet` */

DROP TABLE IF EXISTS `tm_outlet`;

CREATE TABLE `tm_outlet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_outlet` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tm_outlet` */

insert  into `tm_outlet`(`id`,`nama_outlet`,`alamat`,`telepon`,`created_at`,`updated_at`) values 
(1,'Ayo Laundry','Jl. Waringin No.84, Blabak, Kec. Kandat, Kabupaten Kediri, Jawa Timur','0821-4849-5252','2026-01-05 09:17:25','2026-01-13 14:20:28'),
(2,'Mari Laundry','Kediri Jawa Timur','08554221355','2026-01-12 07:39:56','2026-01-12 07:39:56');

/*Table structure for table `tm_produk` */

DROP TABLE IF EXISTS `tm_produk`;

CREATE TABLE `tm_produk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_produk` varchar(10) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `harga_beli` int DEFAULT NULL,
  `harga_jual` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `satuan` varchar(10) DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tm_produk` */

insert  into `tm_produk`(`id`,`kode_produk`,`nama_produk`,`harga_beli`,`harga_jual`,`stok`,`satuan`,`diskon`,`created_at`,`updated_at`) values 
(1,'KM-001','Downy Pewangi Lavender',500,1000,NULL,'pcs',0,'2026-01-12 07:43:49','2026-01-12 07:43:49'),
(2,'KM-002','Rinso Deterjen Matic',1500,2000,NULL,'pcs',0,'2026-01-12 07:44:09','2026-01-12 07:44:09');

/*Table structure for table `tm_user` */

DROP TABLE IF EXISTS `tm_user`;

CREATE TABLE `tm_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Admin','Kasir','Owner') DEFAULT NULL,
  `id_outlet` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_outlet` (`id_outlet`),
  CONSTRAINT `fk_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tm_outlet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tm_user` */

insert  into `tm_user`(`id`,`nama_user`,`username`,`email`,`password`,`role`,`id_outlet`,`created_at`,`updated_at`) values 
(1,NULL,NULL,NULL,NULL,NULL,NULL,'2025-10-20 04:03:53','2025-10-20 04:03:53'),
(2,'Admin','admin','pratamamultijasacv@gmail.com','$2y$12$9AhS1eLc.JZc2mRyS5YLkOiqxAbcHYrpKIlH/lrk0R83ktJHIcYzW','Admin',NULL,'2025-10-20 04:04:41','2025-10-20 04:04:41');

/*Table structure for table `ts_deposit` */

DROP TABLE IF EXISTS `ts_deposit`;

CREATE TABLE `ts_deposit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_customer` int DEFAULT NULL,
  `saldo` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_deposit` (`id_customer`),
  CONSTRAINT `id_deposit` FOREIGN KEY (`id_customer`) REFERENCES `tm_customer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ts_deposit` */

insert  into `ts_deposit`(`id`,`id_customer`,`saldo`,`created_at`,`updated_at`) values 
(1,5,130000,'2026-01-20 16:34:17','2026-01-21 15:01:40'),
(2,8,500000,'2026-01-21 10:06:20','2026-01-21 10:30:47'),
(3,3,72250,'2026-01-21 10:21:41','2026-01-22 10:37:19'),
(4,9,11250,'2026-01-22 07:50:13','2026-01-22 10:26:53');

/*Table structure for table `ts_pengeluaran` */

DROP TABLE IF EXISTS `ts_pengeluaran`;

CREATE TABLE `ts_pengeluaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `outlet_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `metode_pembayaran` enum('Cash','QRIS','Transfer','Lain-lain') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text,
  `bukti` varchar(255) DEFAULT NULL,
  `is_rutin` enum('Rutin','Kondisional') DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` enum('Aktif','Batal','Request Batal') DEFAULT NULL,
  `alasan_pembatalan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `cancel_by` int DEFAULT NULL,
  `cancel_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_kategori_pengeluaran` (`kategori_id`),
  KEY `fk_user` (`user_id`),
  KEY `fk_outlet_pengeluaran` (`outlet_id`),
  CONSTRAINT `fk_kategori_pengeluaran` FOREIGN KEY (`kategori_id`) REFERENCES `tm_kategori_pengeluaran` (`id`),
  CONSTRAINT `fk_outlet_pengeluaran` FOREIGN KEY (`outlet_id`) REFERENCES `tm_outlet` (`id`),
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ts_pengeluaran` */

insert  into `ts_pengeluaran`(`id`,`outlet_id`,`tanggal`,`kategori_id`,`nominal`,`metode_pembayaran`,`keterangan`,`bukti`,`is_rutin`,`user_id`,`status`,`alasan_pembatalan`,`cancel_by`,`cancel_at`,`created_at`,`updated_at`) values 
(1,1,'2026-01-06',1,250000,'Cash','Bayar listrik bulan Desember 2025','bukti_pengeluaran/pengeluaran_20260106074756_ZNnaj0.jpg','Rutin',2,'Aktif',NULL,NULL,NULL,'2026-01-06 07:47:57','2026-01-06 07:47:57'),
(2,1,'2026-01-07',1,50000,'Cash','wadawd','bukti_pengeluaran/pengeluaran_20260107091156_Bu8rlB.jpg','Rutin',2,'Batal','Karena Salah Input',2,'2026-01-07 09:12:08','2026-01-07 09:11:57','2026-01-07 10:52:41'),
(3,1,'2026-01-07',1,950000,'Cash','wadawd','bukti_pengeluaran/pengeluaran_20260107105651_Lr8L3y.png','Rutin',2,'Aktif','2qeqe\n[07/01/2026 11:13 - Ditolak]: Coba',2,'2026-01-07 10:57:09','2026-01-07 10:56:51','2026-01-07 11:13:08'),
(4,1,'2026-01-08',1,500000,'Cash','Coba','bukti_pengeluaran/pengeluaran_20260108134808_qE1pWe.png','Rutin',2,'Aktif',NULL,NULL,NULL,'2026-01-08 13:48:08','2026-01-08 13:53:39'),
(5,1,'2026-01-13',1,120000,'Cash','Bayar listrik bulanan','bukti_pengeluaran/pengeluaran_20260113145451_iFQHML.png','Rutin',2,'Aktif',NULL,NULL,NULL,'2026-01-13 14:54:51','2026-01-13 14:54:51'),
(6,1,'2026-01-21',1,3000000,'Cash','Bayar pemasangan listrik baru','bukti_pengeluaran/pengeluaran_20260121075113_oPpwa9.png','Rutin',2,'Aktif',NULL,NULL,NULL,'2026-01-21 07:51:13','2026-01-21 07:51:13');

/*Table structure for table `ts_riwayat_deposit` */

DROP TABLE IF EXISTS `ts_riwayat_deposit`;

CREATE TABLE `ts_riwayat_deposit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_customer` int DEFAULT NULL,
  `nominal` int DEFAULT NULL,
  `saldo_akhir` int DEFAULT NULL,
  `keterangan` text,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ts_riwayat_deposit` (`id_customer`),
  KEY `fk_id_user` (`id_user`),
  CONSTRAINT `fk_customer_saldo` FOREIGN KEY (`id_customer`) REFERENCES `tm_customer` (`id`),
  CONSTRAINT `fk_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ts_riwayat_deposit` */

insert  into `ts_riwayat_deposit`(`id`,`id_customer`,`nominal`,`saldo_akhir`,`keterangan`,`id_user`,`created_at`,`updated_at`) values 
(1,5,120000,120000,'Top Up Deposit',2,'2026-01-20 16:34:17','2026-01-20 16:34:17'),
(2,5,20000,140000,'Top Up Deposit',2,'2026-01-20 16:35:10','2026-01-20 16:35:10'),
(3,5,125000,325000,'Top Up Saldo',2,'2026-01-21 09:59:11','2026-01-21 09:59:11'),
(4,5,-300000,25000,'Pengurangan Saldo',2,'2026-01-21 09:59:24','2026-01-21 09:59:24'),
(5,5,25000,50000,'Top Up Deposit',2,'2026-01-21 10:00:27','2026-01-21 10:00:27'),
(6,5,125000,175000,'Top Up Deposit',2,'2026-01-21 10:01:35','2026-01-21 10:01:35'),
(7,8,200000,200000,'Top Up Deposit',2,'2026-01-21 10:06:20','2026-01-21 10:06:20'),
(8,8,75000,275000,'Tambah Saldo',2,'2026-01-21 10:15:29','2026-01-21 10:15:29'),
(9,3,150000,150000,'Top Up Deposit',2,'2026-01-21 10:21:41','2026-01-21 10:21:41'),
(10,8,225000,500000,'Tambah Saldo',4,'2026-01-21 10:30:47','2026-01-21 10:30:47'),
(11,3,-135000,15000,'Untuk TransaksiTRX-KYJAN150450',2,'2026-01-21 15:04:50','2026-01-21 15:04:50'),
(12,3,-12750,2250,'Untuk Transaksi TRX-MFSIK150716',2,'2026-01-21 15:07:16','2026-01-21 15:07:16'),
(13,9,25000,25000,'Top Up Deposit',2,'2026-01-22 07:50:13','2026-01-22 07:50:13'),
(14,3,17750,20000,'Tambah Saldo',2,'2026-01-22 07:56:22','2026-01-22 07:56:22'),
(15,3,-12750,7250,'Untuk Transaksi TRX-SR85S075648',2,'2026-01-22 07:56:48','2026-01-22 07:56:48'),
(16,9,50000,75000,'Tambah Saldo',2,'2026-01-22 10:26:43','2026-01-22 10:26:43'),
(17,3,200000,207250,'Tambah Saldo',2,'2026-01-22 10:37:10','2026-01-22 10:37:10');

/*Table structure for table `ts_transaksi` */

DROP TABLE IF EXISTS `ts_transaksi`;

CREATE TABLE `ts_transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(20) DEFAULT NULL,
  `id_customer` int DEFAULT NULL,
  `id_outlet` int DEFAULT NULL,
  `tanggal_transaksi` datetime DEFAULT NULL,
  `total_transaksi` int DEFAULT NULL,
  `total_diskon` float DEFAULT NULL,
  `estimasi_selesai` datetime DEFAULT NULL,
  `pic` int DEFAULT NULL,
  `jumlah_bayar` int DEFAULT NULL,
  `metode_pembayaran` enum('Cash','QRIS','Lain-lain') DEFAULT NULL,
  `tgl_pelunasan` datetime DEFAULT NULL,
  `status_pembayaran` enum('Lunas','Belum Lunas') DEFAULT NULL,
  `status_transaksi` enum('Selesai','Proses','Pesanan Masuk','Diambil','Request Dihapus','Dihapus','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alasan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_customer` (`id_customer`),
  KEY `fk_outlet_2` (`id_outlet`),
  KEY `fk_pic` (`pic`),
  CONSTRAINT `fk_customer` FOREIGN KEY (`id_customer`) REFERENCES `tm_customer` (`id`),
  CONSTRAINT `fk_outlet_2` FOREIGN KEY (`id_outlet`) REFERENCES `tm_outlet` (`id`),
  CONSTRAINT `fk_pic` FOREIGN KEY (`pic`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ts_transaksi` */

insert  into `ts_transaksi`(`id`,`kode_transaksi`,`id_customer`,`id_outlet`,`tanggal_transaksi`,`total_transaksi`,`total_diskon`,`estimasi_selesai`,`pic`,`jumlah_bayar`,`metode_pembayaran`,`tgl_pelunasan`,`status_pembayaran`,`status_transaksi`,`alasan`,`created_at`,`updated_at`) values 
(1,'TRX-G8IUB093536',1,1,'2025-12-05 09:35:36',3000,NULL,'2026-01-05 12:32:00',2,5000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 09:35:36','2026-01-05 09:35:36'),
(2,'TRX-LJEW8132304',1,1,'2026-01-05 13:23:04',10000,NULL,'2026-01-05 14:22:00',2,20000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:23:04','2026-01-05 13:23:04'),
(3,'TRX-U0AWA132402',1,1,'2026-01-05 13:24:02',2000,NULL,'2026-01-05 13:23:00',2,100000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:24:02','2026-01-05 13:24:02'),
(4,'TRX-UU9UE132510',1,1,'2025-12-12 13:25:10',340000,NULL,'2026-01-05 13:23:00',2,2000000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:25:10','2026-01-05 13:25:10'),
(5,'TRX-SAUYJ132907',1,1,'2026-01-05 13:29:07',90000,NULL,'2026-01-05 13:28:00',2,100000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:29:07','2026-01-05 13:29:07'),
(6,'TRX-71ZGC132913',1,1,'2026-01-05 13:29:13',90000,NULL,'2026-01-05 13:28:00',2,100000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:29:13','2026-01-05 13:29:13'),
(7,'TRX-AY4GS132924',1,1,'2026-01-05 13:29:24',90000,NULL,'2026-01-05 13:28:00',2,100000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:29:24','2026-01-05 13:29:24'),
(8,'TRX-ICBMN133018',2,1,'2026-01-05 13:30:18',80000,NULL,'2026-01-05 13:29:00',2,80000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:30:18','2026-01-05 13:30:18'),
(9,'TRX-J7Q1W133050',2,1,'2026-01-05 13:30:50',2000,NULL,'2026-01-05 13:30:00',2,10000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:30:50','2026-01-05 13:30:50'),
(10,'TRX-M5Y7O133426',1,1,'2026-01-05 13:34:26',2000,NULL,'2026-01-05 13:34:00',2,5000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-05 13:34:26','2026-01-05 13:34:26'),
(11,'TRX-PZLKX133546',2,1,'2026-01-05 13:35:46',300000,NULL,'2026-01-05 13:35:00',2,300000,'Cash',NULL,'Lunas','Pesanan Masuk',' - Admin Bagus','2026-01-05 13:35:46','2026-01-07 09:52:40'),
(12,'TRX-I6WJG082544',3,1,'2026-01-06 08:25:44',10000,NULL,'2026-01-06 10:25:00',2,10000,'Cash',NULL,'Lunas','Cancelled',' - Admin Bagus','2026-01-06 08:25:44','2026-01-07 09:52:13'),
(13,'TRX-DDJQY111522',3,1,'2026-01-07 11:15:22',200000,NULL,'2026-01-07 01:15:00',2,200000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-07 11:15:22','2026-01-07 11:15:22'),
(14,'TRX-OPWCA111643',2,1,'2026-01-07 11:16:43',1000000,NULL,'2026-01-07 01:16:00',2,1000000,'QRIS',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-07 11:16:43','2026-01-07 11:16:43'),
(15,'TRX-N3Z0V161153',3,1,'2026-01-07 16:11:53',1000000,NULL,'2026-01-07 16:11:00',2,1000000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-07 16:11:53','2026-01-07 16:11:53'),
(16,'TRX-NT4WZ110003',3,1,'2026-01-08 11:00:03',2000000,NULL,'2026-01-08 12:59:00',2,2000000,'QRIS',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-08 11:00:03','2026-01-08 11:00:03'),
(17,'TRX-YLGDV074609',5,2,'2026-01-12 07:46:09',60000,NULL,'2026-01-12 09:45:00',3,60000,'QRIS',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 07:46:09','2026-01-12 07:46:09'),
(18,'TRX-PCEU3084102',1,1,'2026-01-12 08:41:02',31400,NULL,'2026-01-12 10:40:00',2,32000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 08:41:02','2026-01-12 08:41:02'),
(19,'TRX-VPHAH085107',3,1,'2026-01-12 08:51:07',35300,NULL,'2026-01-12 10:50:00',2,36000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 08:51:07','2026-01-12 08:51:07'),
(20,'TRX-A0THE114339',1,2,'2026-01-12 11:43:39',81140,NULL,'2026-01-12 13:42:00',3,82000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 11:43:39','2026-01-12 11:43:39'),
(21,'TRX-QPZFH114416',3,2,'2026-01-12 11:44:16',126980,NULL,'2026-01-12 13:43:00',3,130000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 11:44:16','2026-01-12 11:44:16'),
(22,'TRX-JOLA1120348',5,2,'2026-01-12 12:03:48',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:48','2026-01-12 12:03:48'),
(23,'TRX-QFSL4120357',5,2,'2026-01-12 12:03:57',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:57','2026-01-12 12:03:57'),
(24,'TRX-OR300120357',5,2,'2026-01-12 12:03:57',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:57','2026-01-12 12:03:57'),
(25,'TRX-UQQLA120358',5,2,'2026-01-12 12:03:58',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(26,'TRX-WJISV120358',5,2,'2026-01-12 12:03:58',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(27,'TRX-QGB3H120358',5,2,'2026-01-12 12:03:58',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(28,'TRX-PTXNQ120358',5,2,'2026-01-12 12:03:58',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(29,'TRX-BJI7T120359',5,2,'2026-01-12 12:03:59',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash','2026-01-19 12:45:02','Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(30,'TRX-1VGVT120359',5,2,'2026-01-12 12:03:59',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(31,'TRX-UI0I6120359',5,2,'2026-01-12 12:03:59',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(32,'TRX-QTMNU120359',5,2,'2026-01-12 12:03:59',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(33,'TRX-PIP7V120400',5,2,'2026-01-12 12:04:00',80642,NULL,'2026-01-12 14:02:00',3,81000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:04:00','2026-01-12 12:04:00'),
(34,'TRX-UNLSF120452',5,2,'2026-01-12 12:04:52',81304,NULL,'2026-01-12 15:04:00',3,82000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:04:52','2026-01-12 12:04:52'),
(35,'TRX-OIIA7125253',6,2,'2026-01-12 12:52:53',31780,NULL,'2026-01-12 14:52:00',3,32000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:52:53','2026-01-12 12:52:53'),
(36,'TRX-LW8ZM125759',6,2,'2026-01-12 12:57:59',27640,NULL,'2026-01-12 14:57:00',3,30000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 12:57:59','2026-01-12 12:57:59'),
(37,'TRX-CWUO2130057',5,2,'2026-01-12 13:00:57',35920,NULL,'2026-01-12 15:57:00',3,36000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 13:00:57','2026-01-12 13:00:57'),
(38,'TRX-AXCZ9130216',1,2,'2026-01-12 13:02:16',150580,NULL,'2026-01-12 15:01:00',3,160000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 13:02:16','2026-01-12 13:02:16'),
(39,'TRX-8SM1H130222',1,2,'2026-01-12 13:02:22',150580,NULL,'2026-01-12 15:01:00',3,160000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 13:02:22','2026-01-12 13:02:22'),
(40,'TRX-CEMDP130233',1,2,'2026-01-12 13:02:33',150580,NULL,'2026-01-12 15:01:00',3,160000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 13:02:33','2026-01-12 13:02:33'),
(41,'TRX-O3O2G130248',5,2,'2026-01-12 13:02:48',2340,NULL,'2026-01-12 13:02:00',3,5000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 13:02:48','2026-01-12 13:02:48'),
(42,'TRX-EGLGS130612',1,2,'2026-01-12 13:06:12',50000,NULL,'2026-01-12 15:05:00',3,50000,'Cash','2026-01-19 12:10:58','Lunas','Pesanan Masuk',NULL,'2026-01-12 13:06:12','2026-01-19 12:08:39'),
(43,'TRX-EAOUU155327',7,2,'2026-01-12 15:53:27',22000,NULL,'2026-01-12 17:52:00',3,22000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 15:53:27','2026-01-12 15:53:27'),
(44,'TRX-RGL14155835',8,2,'2026-01-12 15:58:35',212500,NULL,'2026-01-12 17:57:00',4,215000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 15:58:35','2026-01-12 15:58:35'),
(45,'TRX-B70HG160429',7,2,'2026-01-12 16:04:29',93750,NULL,'2026-01-12 18:03:00',4,100000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-12 16:04:29','2026-01-12 16:04:29'),
(46,'TRX-PYTMC141805',1,1,'2026-01-13 14:18:05',106250,NULL,'2026-01-13 16:17:00',2,110000,'Cash','2026-01-19 13:13:09','Lunas','Pesanan Masuk',NULL,'2026-01-13 14:18:05','2026-01-13 14:18:05'),
(47,'TRX-NQVAC141948',5,1,'2026-01-13 14:19:48',63750,NULL,'2026-01-13 16:17:00',2,70000,'Cash',NULL,'Lunas','Pesanan Masuk',NULL,'2026-01-13 14:19:48','2026-01-13 14:19:48'),
(48,'TRX-UGMDL114631',5,1,'2026-01-19 11:46:31',9000,NULL,'2026-01-19 01:46:00',2,10000,'Cash','2026-01-20 08:23:04','Lunas','Pesanan Masuk',NULL,'2026-01-19 11:46:31','2026-01-20 08:23:04'),
(49,'TRX-PW4Y0115435',5,1,'2026-01-19 11:54:35',233750,NULL,'2026-01-19 01:54:00',2,234000,'Cash','2026-01-19 12:10:04','Lunas','Pesanan Masuk',NULL,'2026-01-19 11:54:35','2026-01-19 12:10:04'),
(50,'TRX-YNHZN115554',1,1,'2026-01-19 11:55:54',17000,NULL,'2026-01-19 11:56:00',2,20000,'Cash','2026-01-19 11:55:54','Lunas','Pesanan Masuk',NULL,'2026-01-19 11:55:54','2026-01-19 11:55:54'),
(51,'TRX-PN0OX081947',5,1,'2026-01-20 08:19:47',135000,NULL,'2026-01-20 10:19:00',2,150000,'Cash','2026-01-21 07:49:23','Lunas','Pesanan Masuk',NULL,'2026-01-20 08:19:47','2026-01-21 07:49:23'),
(52,'TRX-5Q4XD090808',1,1,'2026-01-20 09:08:08',61625,NULL,'2026-01-20 00:07:00',2,65000,'Cash','2026-01-20 09:08:08','Lunas','Pesanan Masuk',NULL,'2026-01-20 09:08:08','2026-01-20 09:08:08'),
(53,'TRX-RJMI9150140',5,1,'2026-01-21 15:01:40',45000,NULL,'2026-01-21 16:59:00',2,50000,'Cash','2026-01-21 15:01:40','Lunas','Pesanan Masuk',NULL,'2026-01-21 15:01:40','2026-01-21 15:01:40'),
(54,'TRX-THCW0150450',3,1,'2026-01-21 15:04:50',135000,NULL,'2026-01-21 18:05:00',2,135000,'Cash','2026-01-22 10:37:19','Lunas','Pesanan Masuk',NULL,'2026-01-21 15:04:50','2026-01-22 10:37:19'),
(55,'TRX-I0NX2150716',3,1,'2026-01-21 15:07:16',12750,NULL,'2026-01-21 22:05:00',2,12750,'Cash','2026-01-21 15:07:16','Lunas','Pesanan Masuk',NULL,'2026-01-21 15:07:16','2026-01-21 15:07:16'),
(56,'TRX-OKXIK075528',3,1,'2026-01-22 07:55:28',4250,NULL,'2026-01-22 09:55:00',2,5000,'Cash','2026-01-22 07:55:28','Lunas','Pesanan Masuk',NULL,'2026-01-22 07:55:28','2026-01-22 07:55:28'),
(57,'TRX-2BRAQ075648',3,1,'2026-01-22 07:56:48',12750,NULL,'2026-01-22 09:56:00',2,12750,'Cash','2026-01-22 07:56:48','Lunas','Pesanan Masuk',NULL,'2026-01-22 07:56:48','2026-01-22 07:56:48'),
(58,'TRX-J0FPE090633',9,1,'2026-01-22 09:06:33',63750,NULL,'2026-01-22 11:06:00',5,63750,'Cash','2026-01-22 10:26:53','Lunas','Pesanan Masuk',NULL,'2026-01-22 09:06:33','2026-01-22 10:26:53'),
(59,'TRX-S5LRH091012',6,2,'2026-01-22 09:10:12',12000,NULL,'2026-01-22 12:09:00',4,15000,'Cash','2026-01-22 11:10:46','Lunas','Pesanan Masuk',NULL,'2026-01-22 09:10:12','2026-01-22 11:10:46'),
(60,'TRX-0ASFZ102759',1,1,'2026-01-22 10:27:59',85000,NULL,'2026-01-22 12:27:00',2,85000,'Cash','2026-01-22 10:36:46','Lunas','Pesanan Masuk',NULL,'2026-01-22 10:27:59','2026-01-22 10:36:46');

/*Table structure for table `ts_transaksi_detail` */

DROP TABLE IF EXISTS `ts_transaksi_detail`;

CREATE TABLE `ts_transaksi_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int DEFAULT NULL,
  `jenis` enum('Produk','Layanan') DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `nama_layanan` varchar(255) DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produk` (`nama_produk`),
  KEY `fk_layanan` (`nama_layanan`),
  KEY `fk_transaksi` (`id_transaksi`),
  CONSTRAINT `fk_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `ts_transaksi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `ts_transaksi_detail` */

insert  into `ts_transaksi_detail`(`id`,`id_transaksi`,`jenis`,`nama_produk`,`nama_layanan`,`qty`,`diskon`,`harga`,`subtotal`,`created_at`,`updated_at`) values 
(1,1,'Layanan','Setrika',NULL,1.5,0,2000,3000,'2026-01-05 09:35:36','2026-01-05 09:35:36'),
(2,2,'Layanan','Setrika',NULL,5,0,2000,10000,'2026-01-05 13:23:04','2026-01-05 13:23:04'),
(3,3,'Layanan','Setrika',NULL,1,0,2000,2000,'2026-01-05 13:24:02','2026-01-05 13:24:02'),
(4,4,'Layanan','Setrika',NULL,170,0,2000,340000,'2026-01-05 13:25:10','2026-01-05 13:25:10'),
(5,5,'Layanan','Setrika',NULL,45,0,2000,90000,'2026-01-05 13:29:07','2026-01-05 13:29:07'),
(6,6,'Layanan','Setrika',NULL,45,0,2000,90000,'2026-01-05 13:29:13','2026-01-05 13:29:13'),
(7,7,'Layanan','Setrika',NULL,45,0,2000,90000,'2026-01-05 13:29:24','2026-01-05 13:29:24'),
(8,8,'Layanan','Setrika',NULL,40,0,2000,80000,'2026-01-05 13:30:18','2026-01-05 13:30:18'),
(9,9,'Layanan','Setrika',NULL,1,0,2000,2000,'2026-01-05 13:30:50','2026-01-05 13:30:50'),
(10,10,'Layanan','Setrika',NULL,1,0,2000,2000,'2026-01-05 13:34:26','2026-01-05 13:34:26'),
(11,11,'Layanan','Setrika',NULL,150,0,2000,300000,'2026-01-05 13:35:46','2026-01-05 13:35:46'),
(12,12,'Layanan','Setrika',NULL,5,0,2000,10000,'2026-01-06 08:25:44','2026-01-06 08:25:44'),
(13,13,'Layanan','Setrika',NULL,100,0,2000,200000,'2026-01-07 11:15:22','2026-01-07 11:15:22'),
(14,14,'Layanan','Setrika',NULL,500,0,2000,1000000,'2026-01-07 11:16:43','2026-01-07 11:16:43'),
(15,15,'Layanan','Setrika',NULL,500,0,2000,1000000,'2026-01-07 16:11:53','2026-01-07 16:11:53'),
(16,16,'Layanan','Setrika',NULL,1000,0,2000,2000000,'2026-01-08 11:00:03','2026-01-08 11:00:03'),
(17,17,'Layanan','Setrika',NULL,20,0,2000,40000,'2026-01-12 07:46:09','2026-01-12 07:46:09'),
(18,17,'Produk','Rinso Deterjen Matic',NULL,10,0,2000,20000,'2026-01-12 07:46:09','2026-01-12 07:46:09'),
(19,18,'Layanan','Setrika',NULL,15.7,0,2000,31400,'2026-01-12 08:41:02','2026-01-12 08:41:02'),
(20,19,'Layanan','Setrika',NULL,17.65,0,2000,35300,'2026-01-12 08:51:07','2026-01-12 08:51:07'),
(21,20,'Layanan','Setrika',NULL,40.57,0,2000,81140,'2026-01-12 11:43:39','2026-01-12 11:43:39'),
(22,21,'Layanan','Setrika',NULL,63.49,0,2000,126980,'2026-01-12 11:44:16','2026-01-12 11:44:16'),
(23,22,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:48','2026-01-12 12:03:48'),
(24,23,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:57','2026-01-12 12:03:57'),
(25,24,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:57','2026-01-12 12:03:57'),
(26,25,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(27,26,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(28,27,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(29,28,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:58','2026-01-12 12:03:58'),
(30,29,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(31,30,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(32,31,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(33,32,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:03:59','2026-01-12 12:03:59'),
(34,33,'Layanan','Setrika',NULL,40.321,0,2000,80642,'2026-01-12 12:04:00','2026-01-12 12:04:00'),
(35,34,'Layanan','Setrika',NULL,40.652,0,2000,81304,'2026-01-12 12:04:52','2026-01-12 12:04:52'),
(36,35,'Layanan','Setrika',NULL,15.89,0,2000,31780,'2026-01-12 12:52:53','2026-01-12 12:52:53'),
(37,36,'Layanan','Setrika',NULL,13.82,0,2000,27640,'2026-01-12 12:57:59','2026-01-12 12:57:59'),
(38,37,'Layanan','Setrika',NULL,17.96,0,2000,35920,'2026-01-12 13:00:57','2026-01-12 13:00:57'),
(39,38,'Layanan','Setrika',NULL,75.29,0,2000,150580,'2026-01-12 13:02:16','2026-01-12 13:02:16'),
(40,39,'Layanan','Setrika',NULL,75.29,0,2000,150580,'2026-01-12 13:02:22','2026-01-12 13:02:22'),
(41,40,'Layanan','Setrika',NULL,75.29,0,2000,150580,'2026-01-12 13:02:33','2026-01-12 13:02:33'),
(42,41,'Layanan','Setrika',NULL,1.17,0,2000,2340,'2026-01-12 13:02:48','2026-01-12 13:02:48'),
(43,42,'Layanan','Setrika',NULL,25,0,2000,50000,'2026-01-12 13:06:12','2026-01-12 13:06:12'),
(44,43,'Layanan','Cuci Kering Setrika',NULL,2,10,10000,18000,'2026-01-12 15:53:27','2026-01-12 15:53:27'),
(45,43,'Produk','Rinso Deterjen Matic',NULL,2,0,2000,4000,'2026-01-12 15:53:27','2026-01-12 15:53:27'),
(46,44,'Layanan','Setrika',NULL,50,15,5000,212500,'2026-01-12 15:58:35','2026-01-12 15:58:35'),
(47,45,'Layanan','Setrika',NULL,15,15,5000,63750,'2026-01-12 16:04:29','2026-01-12 16:04:29'),
(48,45,'Produk','Rinso Deterjen Matic',NULL,15,0,2000,30000,'2026-01-12 16:04:29','2026-01-12 16:04:29'),
(49,46,'Layanan','Setrika',NULL,25,15,5000,106250,'2026-01-13 14:18:05','2026-01-13 14:18:05'),
(50,47,'Layanan','Setrika',NULL,15,15,5000,63750,'2026-01-13 14:19:48','2026-01-13 14:19:48'),
(51,48,'Layanan','Cuci Kering Setrika',NULL,1,10,10000,9000,'2026-01-19 11:46:31','2026-01-19 11:46:31'),
(52,49,'Layanan','Setrika',NULL,55,15,5000,233750,'2026-01-19 11:54:35','2026-01-19 11:54:35'),
(53,50,'Layanan','Setrika',NULL,4,15,5000,17000,'2026-01-19 11:55:54','2026-01-19 11:55:54'),
(54,51,'Layanan','Cuci Kering Setrika',NULL,15,10,10000,135000,'2026-01-20 08:19:47','2026-01-20 08:19:47'),
(55,52,'Layanan','Setrika',NULL,14.5,15,5000,61625,'2026-01-20 09:08:08','2026-01-20 09:08:08'),
(56,53,'Layanan','Cuci Kering Setrika',NULL,5,10,10000,45000,'2026-01-21 15:01:40','2026-01-21 15:01:40'),
(57,54,'Layanan','Cuci Kering Setrika',NULL,15,10,10000,135000,'2026-01-21 15:04:50','2026-01-21 15:04:50'),
(58,55,'Layanan','Setrika',NULL,3,15,5000,12750,'2026-01-21 15:07:16','2026-01-21 15:07:16'),
(59,56,'Layanan','Setrika',NULL,1,15,5000,4250,'2026-01-22 07:55:28','2026-01-22 07:55:28'),
(60,57,'Layanan','Setrika',NULL,3,15,5000,12750,'2026-01-22 07:56:48','2026-01-22 07:56:48'),
(61,58,'Layanan','Setrika',NULL,15,15,5000,63750,'2026-01-22 09:06:33','2026-01-22 09:06:33'),
(62,59,'Layanan','Cuci Kering',NULL,2.4,0,5000,12000,'2026-01-22 09:10:12','2026-01-22 09:10:12'),
(63,60,'Layanan','Setrika',NULL,20,15,5000,85000,'2026-01-22 10:27:59','2026-01-22 10:27:59');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('Admin','Kasir','Owner') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_outlet` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `fk_outlet_3` (`id_outlet`),
  CONSTRAINT `fk_outlet_3` FOREIGN KEY (`id_outlet`) REFERENCES `tm_outlet` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`no_telepon`,`password`,`remember_token`,`role`,`id_outlet`,`created_at`,`updated_at`) values 
(1,'Admin','admin@gmail.com',NULL,'5565555','$2y$12$B4JL3OBeSlk74QAA3Md53eifcd9RiNKZBr6QHGu98PJbXtWxR/3k.',NULL,'Owner',1,'2025-10-20 04:04:10','2026-01-12 08:05:27'),
(2,'Admin Bagus','bagusari0385@gmail.com',NULL,'085732820815','$2y$12$DTFcCTPU77LiwJUEVvhgVezcuhHhmAECzzM7yz10SQA1w6xZs/ZUC',NULL,'Admin',1,'2025-10-24 08:46:31','2026-01-05 09:17:57'),
(3,'Admin Mari Laundry','marilaundry@gmail.com',NULL,'085730851154','$2y$12$6kkWayHVCndGgPDijiEs/uMgFF4VG0kDopTEWfYLP1blV4Rpz/yGe',NULL,'Admin',2,'2026-01-12 07:40:42','2026-01-12 07:40:42'),
(4,'Kasir Mari Laundry','kasirmarilaundry@gmail.com',NULL,'085732820815','$2y$12$f/kpIe7zDH4ljX/Pq7rThuaPspNU9shNiPiIDzuWFyYX98oXQdNaa',NULL,'Kasir',2,'2026-01-12 15:44:51','2026-01-21 10:30:20'),
(5,'Kasir Ayo Laundry','kasirayolaundry@gmail.com',NULL,'085732820815','$2y$12$uUrexO9i4FMYrPAfAxcYUO5BagSZbNgc04c7JOrKf6GKLKtiSc.WK',NULL,'Kasir',1,'2026-01-21 10:28:58','2026-01-21 10:29:36');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
