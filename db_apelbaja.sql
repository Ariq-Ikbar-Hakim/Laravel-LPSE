-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Jul 2026 pada 09.06
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
-- Database: `db_apelbaja`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `assignment_transfer`
--

CREATE TABLE `assignment_transfer` (
  `id` int(10) UNSIGNED NOT NULL,
  `dari_user_id` int(10) UNSIGNED NOT NULL,
  `ke_user_id` int(10) UNSIGNED NOT NULL,
  `tipe_transfer` enum('ppk','pp') NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `alasan` text NOT NULL,
  `disetujui_oleh` int(10) UNSIGNED DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `disetujui_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `assignment_transfer`
--

INSERT INTO `assignment_transfer` (`id`, `dari_user_id`, `ke_user_id`, `tipe_transfer`, `status`, `alasan`, `disetujui_oleh`, `catatan_admin`, `disetujui_at`, `created_at`) VALUES
(1, 4, 3, 'pp', 'disetujui', 'saya ingin mengajukan nya', 1, 'baik saya akan terima', '2026-06-23 00:29:20', '2026-06-22 17:29:03'),
(2, 7, 4, 'ppk', 'disetujui', 'saya izin bertukar akun', 1, 'baik saya akan setujui', '2026-06-24 21:03:15', '2026-06-24 14:02:39'),
(3, 4, 6, 'ppk', 'disetujui', 'Karena kontrak klausa saya sudah habis', 1, '', '2026-07-13 10:54:28', '2026-07-13 03:53:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'NULL jika aksi sistem',
  `role_saat_aksi` enum('PPK','PP','admin','system') NOT NULL DEFAULT 'system',
  `tabel_terpengaruh` varchar(100) DEFAULT NULL,
  `record_id` int(10) UNSIGNED DEFAULT NULL,
  `aksi` enum('CREATE','READ','UPDATE','DELETE','LOGIN','LOGOUT','UPLOAD','DOWNLOAD','SIGN','APPROVE','REJECT','RETURN','TRANSFER','ROLE_CHANGE','RESET_PASSWORD','PASSWORD_CHANGED','ACCOUNT_VERIFIED','COMMENT') NOT NULL,
  `detail_lama` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Snapshot data sebelum perubahan' CHECK (json_valid(`detail_lama`)),
  `detail_baru` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Snapshot data sesudah perubahan' CHECK (json_valid(`detail_baru`)),
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `role_saat_aksi`, `tabel_terpengaruh`, `record_id`, `aksi`, `detail_lama`, `detail_baru`, `keterangan`, `ip_address`, `user_agent`, `created_at`) VALUES
(2, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:49:34'),
(3, 1, 'admin', 'users', 1, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:49:54'),
(4, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:50:33'),
(5, NULL, 'system', 'users', 3, 'CREATE', NULL, '{\"nip\":\"123456789123456789\",\"nama\":\"Ariq Ikbar Hakim\",\"email\":\"ariq20055@gmail.com\",\"password\":\"$2y$12$87VJl6g0jrKTK\\/7V0DbNxeQ6MD8Cj20j.RrvyBJIujPsmSXf\\/3tIG\",\"no_telp\":\"083830237808\",\"opd\":\"MBG\",\"sub_unit_opd\":\"\",\"jabatan_aktif\":\"PPK\",\"sk_nomor\":\"\",\"sk_mulai\":null,\"sk_sampai\":null,\"keterangan\":\"\"}', 'Pendaftaran akun baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:54:37'),
(6, 3, 'PPK', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:55:53'),
(7, NULL, 'system', 'users', 4, 'CREATE', NULL, '{\"nip\":\"012345678012345678\",\"nama\":\"Akun saya ada dua\",\"email\":\"ariqikbar730@gmail.com\",\"password\":\"$2y$12$WnWRP43GDha2K\\/NNVRUQJ.XZAOxB\\/AIqKgV68wNsq4wVgD\\/9Wrn3i\",\"no_telp\":\"085867276889\",\"opd\":\"Pusat MBG\",\"sub_unit_opd\":\"\",\"jabatan_aktif\":\"PP\",\"sk_nomor\":\"\",\"sk_mulai\":null,\"sk_sampai\":null,\"keterangan\":\"\"}', 'Pendaftaran akun baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:58:16'),
(8, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 13:59:28'),
(9, 3, 'PPK', 'paket', 1, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"63096037\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"ada tambahan lagi\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:01:45'),
(10, 3, 'PPK', 'lampiran', 1, 'UPLOAD', NULL, '{\"paket_id\":1,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"Laporan_PHK_SDG1_Lengkap_Terisi.docx\",\"nama_file\":\"paket_1_1781532137_rev1.docx\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_1_1781532137_rev1.docx\",\"ukuran_file\":1980885,\"mime_type\":\"application\\/vnd.openxmlformats-officedocument.wordprocessingml.document\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:02:17'),
(11, 3, 'PPK', 'lampiran', 2, 'UPLOAD', NULL, '{\"paket_id\":1,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":1,\"nama_asli\":\"Laporan_PHK_SDG1_Lengkap.pdf\",\"nama_file\":\"paket_1_1781532143_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_1_1781532143_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Spesifikasi Teknis v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:02:23'),
(12, 3, 'PPK', 'lampiran', 3, 'UPLOAD', NULL, '{\"paket_id\":1,\"tipe_dokumen\":\"HPS\",\"versi\":1,\"nama_asli\":\"computers-15-00198.pdf\",\"nama_file\":\"paket_1_1781532149_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_1_1781532149_rev1.pdf\",\"ukuran_file\":2284433,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen HPS v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:02:29'),
(13, 3, 'PPK', 'lampiran', 4, 'UPLOAD', NULL, '{\"paket_id\":1,\"tipe_dokumen\":\"Rancangan Kontrak\",\"versi\":1,\"nama_asli\":\"Ringkasan_Jurnal_AlgoritmaGenetika.docx\",\"nama_file\":\"paket_1_1781532155_rev1.docx\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_1_1781532155_rev1.docx\",\"ukuran_file\":113467,\"mime_type\":\"application\\/vnd.openxmlformats-officedocument.wordprocessingml.document\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Rancangan Kontrak v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:02:35'),
(14, 3, 'PPK', 'lampiran', 5, 'UPLOAD', NULL, '{\"paket_id\":1,\"tipe_dokumen\":\"Lainnya\",\"versi\":1,\"nama_asli\":\"26654-79026-2-PB.pdf\",\"nama_file\":\"paket_1_1781532160_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_1_1781532160_rev1.pdf\",\"ukuran_file\":1354065,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Lainnya v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:02:40'),
(15, 4, 'PP', 'users', 4, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:22:18'),
(16, NULL, 'system', 'users', 4, 'RESET_PASSWORD', NULL, NULL, 'Meminta reset password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 14:22:28'),
(17, NULL, 'system', 'users', 4, 'PASSWORD_CHANGED', NULL, NULL, 'Password direset menggunakan token email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:22:18'),
(18, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:22:29'),
(19, 4, 'PP', 'users', 4, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:27:35'),
(20, NULL, 'system', 'users', 4, 'RESET_PASSWORD', NULL, NULL, 'Meminta reset password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:27:46'),
(21, NULL, 'system', 'users', 4, 'PASSWORD_CHANGED', NULL, NULL, 'Password direset menggunakan token email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:29:59'),
(22, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:30:07'),
(23, 4, 'PP', 'users', 4, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:36:54'),
(24, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-15 15:41:39'),
(25, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:14:02'),
(26, 3, 'PPK', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:14:17'),
(27, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:14:29'),
(28, 3, 'PPK', 'paket', 1, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:48:56'),
(29, 3, 'PPK', 'paket', 2, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"dasdaweq\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"coba\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:53:06'),
(30, 3, 'PPK', 'lampiran', 6, 'UPLOAD', NULL, '{\"paket_id\":2,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":1,\"nama_asli\":\"Laporan_PHK_SDG1_Lengkap.pdf\",\"nama_file\":\"paket_2_1781614397_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_2_1781614397_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Spesifikasi Teknis v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:53:17'),
(31, 3, 'PPK', 'paket', 2, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:53:23'),
(32, 4, 'PP', 'paket', 2, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"perlu_revisi\"}', 'Update status paket: Paket dikembalikan ke PPK untuk revisi.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:54:46'),
(33, 3, 'PPK', 'lampiran', 7, 'UPLOAD', NULL, '{\"paket_id\":2,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"Laporan_PHK_SDG1_Lengkap.pdf\",\"nama_file\":\"paket_2_1781614500_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_2_1781614500_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:55:00'),
(34, 3, 'PPK', 'lampiran', 8, 'UPLOAD', NULL, '{\"paket_id\":2,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":2,\"nama_asli\":\"computers-15-00198.pdf\",\"nama_file\":\"paket_2_1781614509_rev2.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_2_1781614509_rev2.pdf\",\"ukuran_file\":2284433,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Spesifikasi Teknis v2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:55:09'),
(35, 3, 'PPK', 'paket', 2, 'UPDATE', '{\"status\":\"perlu_revisi\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 12:55:29'),
(36, 4, 'PP', 'paket', 2, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:00:32'),
(37, 4, 'PP', 'berita_acara', 1, 'SIGN', NULL, '{\"berita_acara_id\":1,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/2\\\",\\\"nama\\\":\\\"Akun saya ada dua\\\",\\\"jabatan\\\":\\\"PP\\\",\\\"tanggal\\\":\\\"2026-06-16 20:00:44\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 20:00:44\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:00:44'),
(38, 3, 'PPK', 'paket', 2, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:03:37'),
(39, 3, 'PPK', 'berita_acara', 1, 'SIGN', NULL, '{\"berita_acara_id\":1,\"user_id\":3,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/2\\\",\\\"nama\\\":\\\"Ariq Ikbar Hakim\\\",\\\"jabatan\\\":\\\"PPK\\\",\\\"tanggal\\\":\\\"2026-06-16 20:03:35\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 20:03:37\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:03:37'),
(40, 3, 'PPK', 'paket', 3, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"123\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"123\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:51:16'),
(41, 3, 'PPK', 'lampiran', 9, 'UPLOAD', NULL, '{\"paket_id\":3,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"paket_2_1781614500_rev1.pdf\",\"nama_file\":\"paket_3_1781617890_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_3_1781617890_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:51:30'),
(42, 3, 'PPK', 'paket', 3, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:51:31'),
(43, 4, 'PP', 'paket', 3, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:53:11'),
(44, 4, 'PP', 'paket', 3, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:53:16'),
(45, 4, 'PP', 'paket', 3, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 13:53:19'),
(46, 3, 'PPK', 'paket', 4, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"12\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"saya\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:01:02'),
(47, 3, 'PPK', 'lampiran', 10, 'UPLOAD', NULL, '{\"paket_id\":4,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"paket_2_1781614500_rev1.pdf\",\"nama_file\":\"paket_4_1781618477_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_4_1781618477_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:01:17'),
(48, 3, 'PPK', 'paket', 4, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:01:19'),
(49, 4, 'PP', 'paket', 4, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:01:46'),
(50, 3, 'PPK', 'paket', 5, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"321\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"a\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:06:06'),
(51, 3, 'PPK', 'lampiran', 11, 'UPLOAD', NULL, '{\"paket_id\":5,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":1,\"nama_asli\":\"Laporan_PHK_SDG1_Lengkap.pdf\",\"nama_file\":\"paket_5_1781618813_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_5_1781618813_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Spesifikasi Teknis v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:06:53'),
(52, 4, 'PP', 'paket', 1, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:07:30'),
(53, 4, 'PP', 'berita_acara', 4, 'SIGN', NULL, '{\"berita_acara_id\":4,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/1\\\",\\\"nama\\\":\\\"Akun saya ada dua\\\",\\\"jabatan\\\":\\\"PP\\\",\\\"tanggal\\\":\\\"2026-06-16 21:07:39\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_4_4_1781618859.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:07:39\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:07:39'),
(54, 4, 'PP', 'paket', 4, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:08:47'),
(55, 4, 'PP', 'paket', 4, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"perlu_revisi\"}', 'Update status paket: Paket dikembalikan ke PPK untuk revisi.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:08:51'),
(56, 3, 'PPK', 'paket', 4, 'UPDATE', '{\"status\":\"perlu_revisi\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:09:03'),
(57, 4, 'PP', 'paket', 4, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:09:33'),
(58, 4, 'PP', 'berita_acara', 3, 'SIGN', NULL, '{\"berita_acara_id\":3,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/4\\\",\\\"nama\\\":\\\"Akun saya ada dua\\\",\\\"jabatan\\\":\\\"PP\\\",\\\"tanggal\\\":\\\"2026-06-16 21:09:41\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_3_4_1781618981.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:09:41\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:09:41'),
(59, 3, 'PPK', 'paket', 4, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:09:55'),
(60, 3, 'PPK', 'berita_acara', 3, 'SIGN', NULL, '{\"berita_acara_id\":3,\"user_id\":3,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/4\\\",\\\"nama\\\":\\\"Ariq Ikbar Hakim\\\",\\\"jabatan\\\":\\\"PPK\\\",\\\"tanggal\\\":\\\"2026-06-16 21:09:55\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_3_3_1781618995.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:09:55\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:09:55'),
(61, 3, 'PPK', 'paket', 5, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:12:03'),
(62, 4, 'PP', 'paket', 5, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:12:17'),
(63, 4, 'PP', 'berita_acara', 5, 'SIGN', NULL, '{\"berita_acara_id\":5,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/5\\\",\\\"nama\\\":\\\"Akun saya ada dua\\\",\\\"jabatan\\\":\\\"PP\\\",\\\"tanggal\\\":\\\"2026-06-16 21:12:25\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_5_4_1781619145.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:12:25\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:12:25'),
(64, 3, 'PPK', 'paket', 5, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:12:36'),
(65, 3, 'PPK', 'berita_acara', 5, 'SIGN', NULL, '{\"berita_acara_id\":5,\"user_id\":3,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/5\\\",\\\"nama\\\":\\\"Ariq Ikbar Hakim\\\",\\\"jabatan\\\":\\\"PPK\\\",\\\"tanggal\\\":\\\"2026-06-16 21:12:36\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_5_3_1781619156.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:12:36\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:12:36'),
(66, 4, 'PP', 'paket', 3, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:20:28'),
(67, 3, 'PPK', 'paket', 6, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"4312\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"saa\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:20:52'),
(68, 3, 'PPK', 'lampiran', 12, 'UPLOAD', NULL, '{\"paket_id\":6,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"paket_2_1781614500_rev1.pdf\",\"nama_file\":\"paket_6_1781619661_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_6_1781619661_rev1.pdf\",\"ukuran_file\":5817378,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:21:01'),
(69, 3, 'PPK', 'lampiran', 13, 'UPLOAD', NULL, '{\"paket_id\":6,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":2,\"nama_asli\":\"Laporan_PHK_SDG1_Lengkap.docx\",\"nama_file\":\"paket_6_1781619669_rev2.docx\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_6_1781619669_rev2.docx\",\"ukuran_file\":2114304,\"mime_type\":\"application\\/vnd.openxmlformats-officedocument.wordprocessingml.document\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:21:09'),
(70, 3, 'PPK', 'paket', 6, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:21:11'),
(71, 4, 'PP', 'paket', 6, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:21:50'),
(72, 4, 'PP', 'berita_acara', 6, 'SIGN', NULL, '{\"berita_acara_id\":6,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/6\\\",\\\"nama\\\":\\\"Akun saya ada dua\\\",\\\"jabatan\\\":\\\"PP\\\",\\\"tanggal\\\":\\\"2026-06-16 21:22:09\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_6_4_1781619729.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:22:09\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:22:09'),
(73, 3, 'PPK', 'paket', 6, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:22:37'),
(74, 3, 'PPK', 'berita_acara', 6, 'SIGN', NULL, '{\"berita_acara_id\":6,\"user_id\":3,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/16\\\\\\/6\\\",\\\"nama\\\":\\\"Ariq Ikbar Hakim\\\",\\\"jabatan\\\":\\\"PPK\\\",\\\"tanggal\\\":\\\"2026-06-16 21:22:37\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_6_3_1781619757.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-16 21:22:37\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:22:37'),
(75, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:24:52'),
(76, 3, 'PPK', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:34:35'),
(77, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 14:36:22'),
(78, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:28:26'),
(79, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:28:47'),
(80, 3, 'PPK', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:29:01'),
(81, 3, 'PPK', 'paket', 7, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"098\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"saya\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:31:31'),
(82, 3, 'PPK', 'lampiran', 14, 'UPLOAD', NULL, '{\"paket_id\":7,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"Laporan_TTD_Dinas_Pengadaan_Barang.pdf\",\"nama_file\":\"paket_7_1781875986_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_7_1781875986_rev1.pdf\",\"ukuran_file\":133835,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:33:06'),
(83, 3, 'PPK', 'paket', 7, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:34:34'),
(84, 4, 'PP', 'paket', 7, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:40:10'),
(85, 4, 'PP', 'berita_acara', 7, 'SIGN', NULL, '{\"berita_acara_id\":7,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/19\\\\\\/7\\\",\\\"nama\\\":\\\"Akun saya ada dua\\\",\\\"jabatan\\\":\\\"PP\\\",\\\"tanggal\\\":\\\"2026-06-19 20:40:36\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_7_4_1781876436.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-19 20:40:37\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:40:37'),
(86, 3, 'PPK', 'paket', 7, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:41:05'),
(87, 3, 'PPK', 'berita_acara', 7, 'SIGN', NULL, '{\"berita_acara_id\":7,\"user_id\":3,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"{\\\"nomor_ba\\\":\\\"BA\\\\\\/2026\\\\\\/06\\\\\\/19\\\\\\/7\\\",\\\"nama\\\":\\\"Ariq Ikbar Hakim\\\",\\\"jabatan\\\":\\\"PPK\\\",\\\"tanggal\\\":\\\"2026-06-19 20:41:05\\\",\\\"hash\\\":\\\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\\\"}\",\"qr_image_path\":\"uploads\\/qr\\/qr_7_3_1781876465.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-19 20:41:05\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:41:06'),
(88, 3, 'PPK', 'paket', 8, 'CREATE', NULL, '{\"ppk_id\":3,\"pp_id\":4,\"kode_rup\":\"908\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"coba\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:47:36'),
(89, 3, 'PPK', 'lampiran', 15, 'UPLOAD', NULL, '{\"paket_id\":8,\"tipe_dokumen\":\"Kerangka Acuan Kerja (KAK)\",\"versi\":1,\"nama_asli\":\"Laporan_TTD_Dinas_Pengadaan_Barang.pdf\",\"nama_file\":\"paket_8_1781876898_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_8_1781876898_rev1.pdf\",\"ukuran_file\":133835,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":3}', 'Upload dokumen Kerangka Acuan Kerja (KAK) v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:48:18'),
(90, 3, 'PPK', 'paket', 8, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:48:22'),
(91, 4, 'PP', 'paket', 8, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:48:46'),
(92, 4, 'PP', 'berita_acara', 8, 'SIGN', NULL, '{\"berita_acara_id\":8,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"http:\\/\\/localhost\\/LPSE\\/uploads\\/signatures\\/sig_8_4_1781876973.png\",\"qr_image_path\":\"uploads\\/qr\\/qr_8_4_1781876973.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-19 20:49:35\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:49:35'),
(93, 3, 'PPK', 'paket', 8, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:50:02'),
(94, 3, 'PPK', 'berita_acara', 8, 'SIGN', NULL, '{\"berita_acara_id\":8,\"user_id\":3,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"http:\\/\\/localhost\\/LPSE\\/uploads\\/signatures\\/sig_8_3_1781877002.png\",\"qr_image_path\":\"uploads\\/qr\\/qr_8_3_1781877002.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-19 20:50:02\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 13:50:07'),
(95, 1, 'admin', 'users', 1, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 14:10:57'),
(96, NULL, 'system', 'users', 5, 'CREATE', NULL, '{\"nip\":\"212121212121212121\",\"nama\":\"Cornet Perso\",\"email\":\"cornetperso6@gmail.com\",\"password\":\"$2y$12$XfwdhvvxY6FgYbfBevLc7uKybAyFdqtM70iE48JHP\\/5nHRFA7PuZe\",\"no_telp\":\"0831231312313\",\"opd\":\"Hukum Kerja\",\"sub_unit_opd\":\"\",\"jabatan_aktif\":\"PPK\",\"sk_nomor\":\"\",\"sk_mulai\":null,\"sk_sampai\":null,\"keterangan\":\"\"}', 'Pendaftaran akun baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 14:11:04'),
(97, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 14:12:07'),
(98, NULL, 'system', 'users', 6, 'CREATE', NULL, '{\"nip\":\"212121212121212121\",\"nama\":\"Cornet Perso\",\"email\":\"cornetperso6@gmail.com\",\"password\":\"$2y$12$YHVaoi7hYHbtJC0l7cFoO.W4VO6gl88pGElOLLjh\\/Rw4rfzEaBXOm\",\"no_telp\":\"0831231231231\",\"opd\":\"Hukum Kerja\",\"sub_unit_opd\":\"\",\"jabatan_aktif\":\"PP\",\"sk_nomor\":\"\",\"sk_mulai\":null,\"sk_sampai\":null,\"keterangan\":\"\"}', 'Pendaftaran akun baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 14:26:59'),
(99, 6, 'PP', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 14:41:35'),
(100, 3, 'PPK', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:09:52'),
(101, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:10:01'),
(102, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:12:04'),
(103, 4, 'PP', 'assignment_transfer', 1, 'TRANSFER', NULL, '{\"paket_id\":3,\"ke_user_id\":3,\"tipe\":\"pp\"}', 'Mengajukan transfer paket ID 3 ke user ID 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:29:03'),
(104, 1, 'admin', 'users', 3, 'ROLE_CHANGE', '{\"jabatan_aktif\":\"PPK\"}', '{\"jabatan_aktif\":\"PP\"}', 'Ubah jabatan dari PPK ke PP via persetujuan transfer paket.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:29:20'),
(105, 1, 'admin', 'paket', 3, 'TRANSFER', '{\"pp_id\":4}', '{\"pp_id\":3}', 'Transfer PP disetujui admin.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:29:20'),
(106, 6, 'PP', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:30:47'),
(107, 3, 'PPK', 'users', 3, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:32:12'),
(108, 3, 'PP', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:32:18'),
(109, 4, 'PP', 'users', 4, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:32:23'),
(110, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:32:28'),
(111, NULL, 'system', 'users', 7, 'CREATE', NULL, '{\"nip\":\"313131313131313131\",\"nama\":\"- Ariq Ikbar Hakim\",\"email\":\"230441100058@student.trunojoyo.ac.id\",\"password\":\"$2y$12$x.cDtK0gujqIHo0lh7QP6u71YLZhg1X8NgnhxiAbDebv8bveHG1gq\",\"no_telp\":\"08123123213\",\"opd\":\"Penjabat Negara\",\"sub_unit_opd\":\"\",\"jabatan_aktif\":\"PPK\",\"sk_nomor\":\"\",\"sk_mulai\":null,\"sk_sampai\":null,\"keterangan\":\"\"}', 'Pendaftaran akun baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 17:51:18'),
(112, 7, 'PPK', 'users', 7, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:06:03'),
(113, 7, 'PPK', 'paket', 9, 'CREATE', NULL, '{\"ppk_id\":7,\"pp_id\":6,\"kode_rup\":\"98312\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"JASA LAINNYA\",\"jenis_kontrak\":\"\",\"keterangan\":\"coba\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:06:52'),
(114, 7, 'PPK', 'lampiran', 16, 'UPLOAD', NULL, '{\"paket_id\":9,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":1,\"nama_asli\":\"computers-15-00198.pdf\",\"nama_file\":\"paket_9_1782151626_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_9_1782151626_rev1.pdf\",\"ukuran_file\":2284433,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":7}', 'Upload dokumen Spesifikasi Teknis v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:07:06'),
(115, 7, 'PPK', 'paket', 9, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:07:10'),
(116, 6, 'PP', 'paket', 9, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:07:42'),
(117, 6, 'PP', 'berita_acara', 9, 'SIGN', NULL, '{\"berita_acara_id\":9,\"user_id\":6,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"http:\\/\\/localhost\\/LPSE\\/uploads\\/signatures\\/sig_9_6_1782151682.png\",\"qr_image_path\":\"uploads\\/qr\\/qr_9_6_1782151682.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-23 01:08:03\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:08:03'),
(118, 7, 'PPK', 'paket', 9, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:08:31'),
(119, 7, 'PPK', 'berita_acara', 9, 'SIGN', NULL, '{\"berita_acara_id\":9,\"user_id\":7,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"http:\\/\\/localhost\\/LPSE\\/uploads\\/signatures\\/sig_9_7_1782151711.png\",\"qr_image_path\":\"uploads\\/qr\\/qr_9_7_1782151711.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-06-23 01:08:31\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 18:08:32'),
(120, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:00:10'),
(121, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:00:14'),
(122, 7, 'PPK', 'users', 7, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:00:20'),
(123, 3, 'PP', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:00:21'),
(124, 6, 'PP', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:01:13'),
(125, 7, 'PPK', 'assignment_transfer', 2, 'TRANSFER', NULL, '{\"ke_user_id\":4,\"tipe\":\"ppk\"}', 'Mengajukan transfer total ke user ID 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:02:39'),
(126, 7, 'PP', 'users', 7, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:03:43'),
(127, 4, 'PPK', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 14:03:47'),
(128, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-07 18:25:25'),
(129, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:48:08'),
(130, 4, 'PPK', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:48:51'),
(131, 4, 'PPK', 'users', 4, 'LOGOUT', NULL, NULL, 'User logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:49:03');
INSERT INTO `audit_logs` (`id`, `user_id`, `role_saat_aksi`, `tabel_terpengaruh`, `record_id`, `aksi`, `detail_lama`, `detail_baru`, `keterangan`, `ip_address`, `user_agent`, `created_at`) VALUES
(132, NULL, 'system', 'users', 4, 'RESET_PASSWORD', NULL, NULL, 'Meminta reset password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:49:24'),
(133, NULL, 'system', 'users', 4, 'PASSWORD_CHANGED', NULL, NULL, 'Password direset menggunakan token email', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:51:24'),
(134, 4, 'PPK', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:51:39'),
(135, 4, 'PPK', 'assignment_transfer', 3, 'TRANSFER', NULL, '{\"ke_user_id\":6,\"tipe\":\"ppk\"}', 'Mengajukan transfer total ke user ID 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:53:29'),
(136, 6, 'PP', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:53:40'),
(137, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:55:20'),
(138, 6, 'PPK', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:55:46'),
(139, 6, 'PPK', 'paket', 10, 'CREATE', NULL, '{\"ppk_id\":6,\"pp_id\":7,\"kode_rup\":\"Bebas\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBN\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"Tidak ada\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 03:57:55'),
(140, 6, 'PPK', 'lampiran', 17, 'UPLOAD', NULL, '{\"paket_id\":10,\"tipe_dokumen\":\"HPS\",\"versi\":1,\"nama_asli\":\"dataset_stroke_dalam_bahasa_indonesia(1)(AutoRecovered).xlsx\",\"nama_file\":\"paket_10_1783915244_rev1.xlsx\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_10_1783915244_rev1.xlsx\",\"ukuran_file\":809748,\"mime_type\":\"application\\/vnd.openxmlformats-officedocument.spreadsheetml.sheet\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":6}', 'Upload dokumen HPS v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:00:44'),
(141, 6, 'PPK', 'paket', 10, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:01:17'),
(142, 3, 'PP', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:05:13'),
(143, 6, 'PPK', 'paket', 11, 'CREATE', NULL, '{\"ppk_id\":6,\"pp_id\":4,\"kode_rup\":\"Bebas\",\"nama_paket\":\"Pengadaan Laptop Dinas Tahun 2026\",\"pagu\":150000000,\"hps\":0,\"metode_pengadaan\":\"E-Purchasing\",\"tahun_anggaran\":2026,\"sumber_dana\":\"APBD\",\"jenis_pengadaan\":\"BARANG\",\"jenis_kontrak\":\"\",\"keterangan\":\"\"}', 'Pembuatan paket baru', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:06:37'),
(144, 6, 'PPK', 'lampiran', 18, 'UPLOAD', NULL, '{\"paket_id\":11,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":1,\"nama_asli\":\"paket_9_1782151626_rev1.pdf\",\"nama_file\":\"paket_11_1783915608_rev1.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_11_1783915608_rev1.pdf\",\"ukuran_file\":2284433,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":6}', 'Upload dokumen Spesifikasi Teknis v1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:06:48'),
(145, 6, 'PPK', 'paket', 11, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:06:50'),
(146, 4, 'PP', 'paket', 11, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"perlu_revisi\"}', 'Update status paket: Paket dikembalikan ke PPK untuk revisi.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:07:18'),
(147, 6, 'PPK', 'lampiran', 19, 'UPLOAD', NULL, '{\"paket_id\":11,\"tipe_dokumen\":\"Spesifikasi Teknis\",\"versi\":2,\"nama_asli\":\"paket_9_1782151626_rev1.pdf\",\"nama_file\":\"paket_11_1783915659_rev2.pdf\",\"file_path\":\"C:\\\\xampp\\\\htdocs\\\\LPSE\\/uploads\\/lampiran\\/paket_11_1783915659_rev2.pdf\",\"ukuran_file\":2284433,\"mime_type\":\"application\\/pdf\",\"is_active\":1,\"status_validasi\":\"menunggu\",\"uploaded_by\":6}', 'Upload dokumen Spesifikasi Teknis v2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:07:39'),
(148, 6, 'PPK', 'paket', 11, 'UPDATE', '{\"status\":\"perlu_revisi\"}', '{\"status\":\"dikirim\"}', 'PPK mengirim usulan paket', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:07:45'),
(149, 4, 'PP', 'paket', 11, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:09:20'),
(150, 4, 'PP', 'berita_acara', 10, 'SIGN', NULL, '{\"berita_acara_id\":10,\"user_id\":4,\"role_penandatangan\":\"PP\",\"urutan\":1,\"qr_data\":\"http:\\/\\/localhost\\/LPSE\\/uploads\\/signatures\\/sig_11_4_1783915811.jpeg\",\"qr_image_path\":\"uploads\\/qr\\/qr_10_4_1783915811.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-07-13 11:10:14\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:10:14'),
(151, 6, 'PPK', 'paket', 11, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara ditandatangani lengkap', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:11:01'),
(152, 6, 'PPK', 'berita_acara', 10, 'SIGN', NULL, '{\"berita_acara_id\":10,\"user_id\":6,\"role_penandatangan\":\"PPK\",\"urutan\":2,\"qr_data\":\"http:\\/\\/localhost\\/LPSE\\/uploads\\/signatures\\/sig_11_6_1783915860.png\",\"qr_image_path\":\"uploads\\/qr\\/qr_10_6_1783915860.png\",\"hash_dokumen\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\",\"signed_at\":\"2026-07-13 11:11:01\",\"ip_address\":\"::1\"}', 'Tanda tangan BA oleh PPK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:11:05'),
(153, 6, 'PPK', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 06:56:21'),
(154, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 06:56:49'),
(155, 3, 'PP', 'users', 3, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 07:05:51'),
(156, 7, 'PP', 'users', 7, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 07:06:35'),
(157, 7, 'PP', 'paket', 10, 'UPDATE', '{\"status\":\"dikirim\"}', '{\"status\":\"disetujui\"}', 'Update status paket: Paket disetujui oleh PP.', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 07:07:05'),
(158, 7, 'PP', 'paket', 10, 'UPDATE', '{\"status\":\"disetujui\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara diunggah secara manual oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 07:49:31'),
(159, 7, 'PP', 'berita_acara', 11, 'UPLOAD', NULL, NULL, 'Upload BA Manual oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 07:49:31'),
(160, 4, 'PP', 'paket', 12, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara Manual di-generate dan ditandatangani oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 08:39:46'),
(161, 4, 'PP', 'berita_acara', 12, 'UPLOAD', NULL, NULL, 'Generate & Sign BA Manual In-App oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 08:39:46'),
(162, 4, 'PP', 'paket', 13, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara Manual di-generate dan ditandatangani oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 09:01:13'),
(163, 4, 'PP', 'berita_acara', 13, 'UPLOAD', NULL, NULL, 'Generate & Sign BA Manual In-App oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-18 09:01:13'),
(164, 4, 'PP', 'users', 4, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 03:24:19'),
(165, 1, 'admin', 'users', 1, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 03:24:33'),
(166, 6, 'PPK', 'users', 6, 'LOGIN', NULL, NULL, 'User berhasil login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 03:24:42'),
(167, 4, 'PP', 'paket', 14, 'UPDATE', '{\"status\":\"draft\"}', '{\"status\":\"selesai\"}', 'Update status paket: Berita Acara Manual di-generate dan ditandatangani oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 04:08:50'),
(168, 4, 'PP', 'berita_acara', 14, 'UPLOAD', NULL, NULL, 'Generate & Sign BA Manual In-App oleh PP', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 04:08:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita_acara`
--

CREATE TABLE `berita_acara` (
  `id` int(10) UNSIGNED NOT NULL,
  `paket_id` int(10) UNSIGNED NOT NULL,
  `nomor_ba` varchar(100) NOT NULL COMMENT 'Nomor surat BA',
  `tanggal_ba` date NOT NULL,
  `konten` longtext DEFAULT NULL COMMENT 'Isi dokumen BA (HTML/text)',
  `hash_konten` varchar(64) DEFAULT NULL COMMENT 'SHA256 dari konten untuk integritas',
  `status` enum('draft','ditandatangani_pp','selesai') NOT NULL DEFAULT 'draft',
  `jenis_ba` enum('otomatis','manual') NOT NULL DEFAULT 'otomatis',
  `file_laporan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `berita_acara`
--

INSERT INTO `berita_acara` (`id`, `paket_id`, `nomor_ba`, `tanggal_ba`, `konten`, `hash_konten`, `status`, `jenis_ba`, `file_laporan`, `created_at`, `updated_at`) VALUES
(1, 2, 'BA/2026/06/16/2', '2026-06-16', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'selesai', 'otomatis', NULL, '2026-06-16 13:00:44', '2026-06-16 13:03:37'),
(2, 3, 'BA/2026/06/16/3', '2026-06-16', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'draft', 'otomatis', NULL, '2026-06-16 13:53:47', '2026-06-16 13:53:47'),
(3, 4, 'BA/2026/06/16/4', '2026-06-16', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'selesai', 'otomatis', 'uploads/berita_acara/BA_Paket_4_1781618995.pdf', '2026-06-16 14:02:00', '2026-06-16 14:09:55'),
(4, 1, 'BA/2026/06/16/1', '2026-06-16', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'ditandatangani_pp', 'otomatis', NULL, '2026-06-16 14:07:39', '2026-06-16 14:07:39'),
(5, 5, 'BA/2026/06/16/5', '2026-06-16', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'selesai', 'otomatis', 'uploads/berita_acara/BA_Paket_5_1781619156.pdf', '2026-06-16 14:12:25', '2026-06-16 14:12:36'),
(6, 6, 'BA/2026/06/16/6', '2026-06-16', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'ditandatangani_pp', 'otomatis', 'uploads/berita_acara/BA_Paket_6_1781619757.pdf', '2026-06-16 14:22:09', '2026-06-16 14:22:37'),
(7, 7, 'BA/2026/06/19/7', '2026-06-19', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'ditandatangani_pp', 'otomatis', 'uploads/berita_acara/BA_Paket_7_1781876466.pdf', '2026-06-19 13:40:36', '2026-06-19 13:41:06'),
(8, 8, 'BA/2026/06/19/8', '2026-06-19', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'ditandatangani_pp', 'otomatis', 'uploads/berita_acara/BA_Paket_8_1781877007.pdf', '2026-06-19 13:49:33', '2026-06-19 13:50:07'),
(9, 9, 'BA/2026/06/23/9', '2026-06-23', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'ditandatangani_pp', 'otomatis', 'uploads/berita_acara/BA_Paket_9_1782151712.pdf', '2026-06-22 18:08:02', '2026-06-22 18:08:32'),
(10, 11, 'BA/2026/07/13/11', '2026-07-13', 'Dokumen Berita Acara Persetujuan Paket', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', 'ditandatangani_pp', 'otomatis', 'uploads/berita_acara/BA_Paket_11_1783915865.pdf', '2026-07-13 04:10:11', '2026-07-13 04:11:05'),
(11, 10, 'Bebas', '2026-07-18', 'Berita Acara Manual', '602d1c17599ef6dbf6cbf83454deb32567bc19cc9ecdf73f9616b41a30b47d13', 'selesai', 'manual', 'uploads/berita_acara/BA_Manual_10_1784360971.pdf', '2026-07-18 07:49:31', '2026-07-18 07:49:31'),
(12, 12, 'BA/2026/07/11/14', '2026-07-18', 'jadi kesepakatan kita bersama', 'e9cf1eb0f23f614413107c13596c0388bece25e6a4897d708b281ca14c82b461', 'selesai', 'manual', 'uploads/berita_acara/BA_Manual_InApp_12_1784363986.pdf', '2026-07-18 08:39:40', '2026-07-18 08:52:25'),
(13, 13, 'BA/2026/07/11/17', '2026-07-18', 'Pada hari ini, tanggal 18 Juli 2026, telah disetujui dokumen persiapan pengadaan paket dengan rincian sebagai berikut:\n\nNama Paket    : Gajah mada\nKode RUP      : 1123\nTahun Anggaran: 2026\nPagu          : Rp 25.000\nHPS           : Rp 900.000\n\nDemikian Berita Acara ini dibuat dan ditandatangani secara elektronik (QR Code) untuk dipergunakan sebagaimana mestinya.', 'd29fc296ccab0445d4fce4bb9d915e86ebc340952597fba6d043b5ad371e9987', 'selesai', 'manual', 'uploads/berita_acara/BA_Manual_InApp_13_1784365273.pdf', '2026-07-18 09:01:12', '2026-07-18 09:01:13'),
(14, 14, 'BA/2026/07/11/15', '2026-07-23', 'Pada hari ini, tanggal 23 Juli 2026, telah disetujui dokumen persiapan pengadaan paket dengan rincian sebagai berikut:\n\nNama Paket    : Sepeda Motor Supra\nKode RUP      : 1968\nTahun Anggaran: 2026\nPagu          : Rp 16.000.000\nHPS           : Rp 9.000.000\n\nDemikian Berita Acara ini dibuat dan ditandatangani secara elektronik (QR Code) untuk dipergunakan sebagaimana mestinya.', '6fbc7fe66fb8b86c2343387554f84c0de76a58eac6fa10ece2ebc71d600753d3', 'selesai', 'manual', 'uploads/berita_acara/BA_Manual_InApp_14_1784779730.pdf', '2026-07-23 04:08:44', '2026-07-23 04:08:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `document_comments`
--

CREATE TABLE `document_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `paket_id` int(10) UNSIGNED NOT NULL,
  `lampiran_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'NULL = komentar umum paket',
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_saat_komentar` enum('PPK','PP','admin') NOT NULL,
  `komentar` text NOT NULL,
  `is_monitoring` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=komentar monitoring admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `document_comments`
--

INSERT INTO `document_comments` (`id`, `paket_id`, `lampiran_id`, `user_id`, `role_saat_komentar`, `komentar`, `is_monitoring`, `created_at`) VALUES
(1, 1, NULL, 3, 'PPK', 'anggaran mau di tambahkan', 0, '2026-06-15 14:03:06'),
(2, 1, NULL, 4, 'PP', 'ada yang kurang', 0, '2026-06-15 14:05:41'),
(3, 1, NULL, 1, 'admin', 'ada yang salah pada hps nya', 1, '2026-06-16 12:49:49'),
(4, 7, NULL, 3, 'PPK', 'test', 0, '2026-06-19 13:35:20'),
(5, 11, NULL, 4, 'PP', 'pp', 0, '2026-07-13 04:08:31'),
(6, 11, NULL, 6, 'PPK', 'baik dari ppk', 0, '2026-07-13 04:08:42'),
(7, 11, NULL, 1, 'admin', 'admin', 1, '2026-07-13 04:08:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lampiran`
--

CREATE TABLE `lampiran` (
  `id` int(10) UNSIGNED NOT NULL,
  `paket_id` int(10) UNSIGNED NOT NULL,
  `tipe_dokumen` varchar(200) NOT NULL COMMENT 'Jenis dokumen (SK PPK, KAK, dll)',
  `versi` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `nama_asli` varchar(255) NOT NULL COMMENT 'Nama file original dari user',
  `nama_file` varchar(255) NOT NULL COMMENT 'Nama file tersimpan di server',
  `file_path` varchar(600) NOT NULL,
  `ukuran_file` bigint(20) UNSIGNED DEFAULT 0 COMMENT 'dalam bytes',
  `mime_type` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=versi aktif saat ini',
  `status_validasi` enum('menunggu','disetujui','revisi') NOT NULL DEFAULT 'menunggu',
  `uploaded_by` int(10) UNSIGNED NOT NULL COMMENT 'user_id yang upload',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `lampiran`
--

INSERT INTO `lampiran` (`id`, `paket_id`, `tipe_dokumen`, `versi`, `nama_asli`, `nama_file`, `file_path`, `ukuran_file`, `mime_type`, `is_active`, `status_validasi`, `uploaded_by`, `created_at`) VALUES
(1, 1, 'Kerangka Acuan Kerja (KAK)', 1, 'Laporan_PHK_SDG1_Lengkap_Terisi.docx', 'paket_1_1781532137_rev1.docx', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_1_1781532137_rev1.docx', 1980885, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1, 'menunggu', 3, '2026-06-15 14:02:17'),
(2, 1, 'Spesifikasi Teknis', 1, 'Laporan_PHK_SDG1_Lengkap.pdf', 'paket_1_1781532143_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_1_1781532143_rev1.pdf', 5817378, 'application/pdf', 1, 'menunggu', 3, '2026-06-15 14:02:23'),
(3, 1, 'HPS', 1, 'computers-15-00198.pdf', 'paket_1_1781532149_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_1_1781532149_rev1.pdf', 2284433, 'application/pdf', 1, 'menunggu', 3, '2026-06-15 14:02:29'),
(4, 1, 'Rancangan Kontrak', 1, 'Ringkasan_Jurnal_AlgoritmaGenetika.docx', 'paket_1_1781532155_rev1.docx', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_1_1781532155_rev1.docx', 113467, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1, 'menunggu', 3, '2026-06-15 14:02:35'),
(5, 1, 'Lainnya', 1, '26654-79026-2-PB.pdf', 'paket_1_1781532160_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_1_1781532160_rev1.pdf', 1354065, 'application/pdf', 1, 'menunggu', 3, '2026-06-15 14:02:40'),
(6, 2, 'Spesifikasi Teknis', 1, 'Laporan_PHK_SDG1_Lengkap.pdf', 'paket_2_1781614397_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_2_1781614397_rev1.pdf', 5817378, 'application/pdf', 0, 'menunggu', 3, '2026-06-16 12:53:17'),
(7, 2, 'Kerangka Acuan Kerja (KAK)', 1, 'Laporan_PHK_SDG1_Lengkap.pdf', 'paket_2_1781614500_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_2_1781614500_rev1.pdf', 5817378, 'application/pdf', 1, 'menunggu', 3, '2026-06-16 12:55:00'),
(8, 2, 'Spesifikasi Teknis', 2, 'computers-15-00198.pdf', 'paket_2_1781614509_rev2.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_2_1781614509_rev2.pdf', 2284433, 'application/pdf', 1, 'menunggu', 3, '2026-06-16 12:55:09'),
(9, 3, 'Kerangka Acuan Kerja (KAK)', 1, 'paket_2_1781614500_rev1.pdf', 'paket_3_1781617890_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_3_1781617890_rev1.pdf', 5817378, 'application/pdf', 1, 'menunggu', 3, '2026-06-16 13:51:30'),
(10, 4, 'Kerangka Acuan Kerja (KAK)', 1, 'paket_2_1781614500_rev1.pdf', 'paket_4_1781618477_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_4_1781618477_rev1.pdf', 5817378, 'application/pdf', 1, 'menunggu', 3, '2026-06-16 14:01:17'),
(11, 5, 'Spesifikasi Teknis', 1, 'Laporan_PHK_SDG1_Lengkap.pdf', 'paket_5_1781618813_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_5_1781618813_rev1.pdf', 5817378, 'application/pdf', 1, 'menunggu', 3, '2026-06-16 14:06:53'),
(12, 6, 'Kerangka Acuan Kerja (KAK)', 1, 'paket_2_1781614500_rev1.pdf', 'paket_6_1781619661_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_6_1781619661_rev1.pdf', 5817378, 'application/pdf', 0, 'menunggu', 3, '2026-06-16 14:21:01'),
(13, 6, 'Kerangka Acuan Kerja (KAK)', 2, 'Laporan_PHK_SDG1_Lengkap.docx', 'paket_6_1781619669_rev2.docx', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_6_1781619669_rev2.docx', 2114304, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1, 'menunggu', 3, '2026-06-16 14:21:09'),
(14, 7, 'Kerangka Acuan Kerja (KAK)', 1, 'Laporan_TTD_Dinas_Pengadaan_Barang.pdf', 'paket_7_1781875986_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_7_1781875986_rev1.pdf', 133835, 'application/pdf', 1, 'menunggu', 3, '2026-06-19 13:33:06'),
(15, 8, 'Kerangka Acuan Kerja (KAK)', 1, 'Laporan_TTD_Dinas_Pengadaan_Barang.pdf', 'paket_8_1781876898_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_8_1781876898_rev1.pdf', 133835, 'application/pdf', 1, 'menunggu', 3, '2026-06-19 13:48:18'),
(16, 9, 'Spesifikasi Teknis', 1, 'computers-15-00198.pdf', 'paket_9_1782151626_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_9_1782151626_rev1.pdf', 2284433, 'application/pdf', 1, 'menunggu', 7, '2026-06-22 18:07:06'),
(17, 10, 'HPS', 1, 'dataset_stroke_dalam_bahasa_indonesia(1)(AutoRecovered).xlsx', 'paket_10_1783915244_rev1.xlsx', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_10_1783915244_rev1.xlsx', 809748, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1, 'menunggu', 6, '2026-07-13 04:00:44'),
(18, 11, 'Spesifikasi Teknis', 1, 'paket_9_1782151626_rev1.pdf', 'paket_11_1783915608_rev1.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_11_1783915608_rev1.pdf', 2284433, 'application/pdf', 0, 'menunggu', 6, '2026-07-13 04:06:48'),
(19, 11, 'Spesifikasi Teknis', 2, 'paket_9_1782151626_rev1.pdf', 'paket_11_1783915659_rev2.pdf', 'C:\\xampp\\htdocs\\LPSE/uploads/lampiran/paket_11_1783915659_rev2.pdf', 2284433, 'application/pdf', 1, 'menunggu', 6, '2026-07-13 04:07:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_paket`
--

CREATE TABLE `log_paket` (
  `id` int(10) UNSIGNED NOT NULL,
  `paket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `nama_pengguna` varchar(150) DEFAULT NULL,
  `aksi` varchar(255) NOT NULL,
  `status_dari` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `lampiran_file` varchar(600) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_paket`
--

INSERT INTO `log_paket` (`id`, `paket_id`, `user_id`, `nama_pengguna`, `aksi`, `status_dari`, `keterangan`, `lampiran_file`, `created_at`) VALUES
(1, 3, 1, 'Administrator', 'Transfer PP Disetujui', 'disetujui', 'Paket dipindahkan ke Ariq Ikbar Hakim. Catatan Admin: baik saya akan terima', NULL, '2026-06-22 17:29:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket`
--

CREATE TABLE `paket` (
  `id` int(10) UNSIGNED NOT NULL,
  `ppk_id` int(10) UNSIGNED NOT NULL COMMENT 'User PPK pembuat',
  `pp_id` int(10) UNSIGNED NOT NULL COMMENT 'User PP yang ditugaskan',
  `kode_rup` varchar(100) NOT NULL,
  `nama_paket` text NOT NULL,
  `pagu` decimal(18,2) DEFAULT 0.00,
  `hps` decimal(18,2) DEFAULT 0.00,
  `metode_pengadaan` varchar(150) DEFAULT NULL COMMENT 'Dari SIRUP',
  `tahun_anggaran` year(4) NOT NULL,
  `sumber_dana` varchar(50) NOT NULL DEFAULT 'APBD',
  `jenis_pengadaan` varchar(100) NOT NULL DEFAULT 'JASA LAINNYA',
  `jenis_kontrak` varchar(100) DEFAULT NULL,
  `url_draft_spse` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('draft','dikirim','kaji_ulang','perlu_revisi','disetujui','selesai','gagal_pemilihan','dibatalkan') DEFAULT 'draft',
  `dilihat_admin_at` datetime DEFAULT NULL,
  `catatan_koreksi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `paket`
--

INSERT INTO `paket` (`id`, `ppk_id`, `pp_id`, `kode_rup`, `nama_paket`, `pagu`, `hps`, `metode_pengadaan`, `tahun_anggaran`, `sumber_dana`, `jenis_pengadaan`, `jenis_kontrak`, `url_draft_spse`, `keterangan`, `status`, `dilihat_admin_at`, `catatan_koreksi`, `created_at`, `updated_at`) VALUES
(1, 3, 7, '63096037', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'ada tambahan lagi', 'disetujui', '2026-06-16 19:49:11', 'Paket disetujui oleh PP.', '2026-06-15 14:01:45', '2026-06-24 14:03:15'),
(2, 3, 7, 'dasdaweq', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'coba', 'selesai', '2026-06-16 19:55:49', 'Berita Acara ditandatangani lengkap', '2026-06-16 12:53:06', '2026-06-24 14:03:15'),
(3, 3, 3, '123', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, '123', 'disetujui', '2026-06-16 20:52:28', 'Paket disetujui oleh PP.', '2026-06-16 13:51:16', '2026-06-22 17:29:20'),
(4, 3, 7, '12', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'saya', 'selesai', NULL, 'Berita Acara ditandatangani lengkap', '2026-06-16 14:01:02', '2026-06-24 14:03:15'),
(5, 3, 7, '321', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'a', 'selesai', NULL, 'Berita Acara ditandatangani lengkap', '2026-06-16 14:06:06', '2026-06-24 14:03:15'),
(6, 3, 7, '4312', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'saa', 'selesai', NULL, 'Berita Acara ditandatangani lengkap', '2026-06-16 14:20:52', '2026-06-24 14:03:15'),
(7, 3, 7, '098', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'saya', 'selesai', '2026-06-19 20:37:41', 'Berita Acara ditandatangani lengkap', '2026-06-19 13:31:31', '2026-06-24 14:03:15'),
(8, 3, 7, '908', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, 'coba', 'selesai', NULL, 'Berita Acara ditandatangani lengkap', '2026-06-19 13:47:36', '2026-06-24 14:03:15'),
(9, 6, 4, '98312', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'JASA LAINNYA', '', NULL, 'coba', 'selesai', '2026-06-23 01:10:48', 'Berita Acara ditandatangani lengkap', '2026-06-22 18:06:52', '2026-07-13 03:54:28'),
(10, 6, 7, 'Bebas', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBN', 'BARANG', '', NULL, 'Tidak ada', 'selesai', '2026-07-13 11:01:41', 'Berita Acara diunggah secara manual oleh PP', '2026-07-13 03:57:55', '2026-07-18 07:49:31'),
(11, 6, 4, 'Bebas', 'Pengadaan Laptop Dinas Tahun 2026', 150000000.00, 0.00, 'E-Purchasing', '2026', 'APBD', 'BARANG', '', NULL, '', 'selesai', '2026-07-13 11:08:16', 'Berita Acara ditandatangani lengkap', '2026-07-13 04:06:37', '2026-07-13 04:11:01'),
(12, 6, 4, '2098', 'Sepeda Motor Supra', 35000000.00, 20000.00, 'Manual (Dibuat PP)', '2026', 'APBD', 'BARANG/JASA', 'Lumsum', NULL, 'Paket ini dibuat otomatis melalui fitur Upload BA Manual oleh PP', 'selesai', NULL, 'Berita Acara Manual di-generate dan ditandatangani oleh PP', '2026-07-18 08:39:40', '2026-07-18 08:39:46'),
(13, 6, 4, '1123', 'Gajah mada', 25000.00, 900000.00, 'Manual (Dibuat PP)', '2026', 'APBD', 'BARANG/JASA', 'Lumsum', NULL, 'Paket ini dibuat otomatis melalui fitur Upload BA Manual oleh PP', 'selesai', NULL, 'Berita Acara Manual di-generate dan ditandatangani oleh PP', '2026-07-18 09:01:12', '2026-07-18 09:01:13'),
(14, 6, 4, '1968', 'Sepeda Motor Supra', 16000000.00, 9000000.00, 'Manual (Dibuat PP)', '2026', 'APBD', 'BARANG/JASA', 'Lumsum', NULL, 'Paket ini dibuat otomatis melalui fitur Upload BA Manual oleh PP', 'selesai', NULL, 'Berita Acara Manual di-generate dan ditandatangani oleh PP', '2026-07-23 04:08:44', '2026-07-23 04:08:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_requests`
--

CREATE TABLE `password_reset_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(64) DEFAULT NULL COMMENT 'Token dikirim ke email (diisi saat admin approve)',
  `status` enum('menunggu','disetujui','digunakan','kadaluarsa') NOT NULL DEFAULT 'menunggu',
  `diminta_at` datetime NOT NULL,
  `disetujui_oleh` int(10) UNSIGNED DEFAULT NULL,
  `disetujui_at` datetime DEFAULT NULL,
  `digunakan_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL COMMENT 'Token valid 24 jam setelah disetujui'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_requests`
--

INSERT INTO `password_reset_requests` (`id`, `user_id`, `token`, `status`, `diminta_at`, `disetujui_oleh`, `disetujui_at`, `digunakan_at`, `expires_at`) VALUES
(1, 4, '50db88c1c99a717c75c50018748a85f211e8b9dd59022d2a97ecdd3f431843a3', 'digunakan', '2026-06-15 21:22:28', 1, '2026-06-15 22:21:05', '2026-06-15 22:22:18', '2026-06-16 22:21:05'),
(2, 4, '93cac4942387978ed92549b002761bf64ba18e74bfc01e4f79e31798ab326766', 'digunakan', '2026-06-15 22:27:46', 1, '2026-06-15 22:28:37', '2026-06-15 22:29:59', '2026-06-16 22:28:37'),
(3, 4, '5811daac6ab9c22254d056398d43c7597dc0450c69a000a637aa699039322b4c', 'digunakan', '2026-07-13 10:49:24', 1, '2026-07-13 10:50:50', '2026-07-13 10:51:24', '2026-07-14 10:50:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_change_requests`
--

CREATE TABLE `role_change_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_tujuan` enum('PPK','PP') NOT NULL,
  `sk_nomor` varchar(150) NOT NULL,
  `sk_tanggal` date NOT NULL,
  `sk_berlaku_dari` date NOT NULL,
  `sk_berlaku_sampai` date DEFAULT NULL,
  `file_sk` varchar(500) NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text DEFAULT NULL,
  `disetujui_oleh` int(10) UNSIGNED DEFAULT NULL,
  `disetujui_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `signatures`
--

CREATE TABLE `signatures` (
  `id` int(10) UNSIGNED NOT NULL,
  `berita_acara_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_penandatangan` enum('PP','PPK') NOT NULL,
  `urutan` tinyint(3) UNSIGNED NOT NULL COMMENT '1=PP dulu, 2=PPK',
  `qr_data` text NOT NULL COMMENT 'JSON data yang di-encode ke QR',
  `qr_image_path` varchar(600) DEFAULT NULL COMMENT 'Path file gambar QR',
  `hash_dokumen` varchar(64) NOT NULL COMMENT 'SHA256 dokumen saat ditandatangani',
  `signed_at` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `signatures`
--

INSERT INTO `signatures` (`id`, `berita_acara_id`, `user_id`, `role_penandatangan`, `urutan`, `qr_data`, `qr_image_path`, `hash_dokumen`, `signed_at`, `ip_address`) VALUES
(1, 1, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/2\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-16 20:00:44\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', '', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 20:00:44', '::1'),
(2, 1, 3, 'PPK', 2, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/2\",\"nama\":\"Ariq Ikbar Hakim\",\"jabatan\":\"PPK\",\"tanggal\":\"2026-06-16 20:03:35\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', '', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 20:03:37', '::1'),
(3, 2, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/3\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-16 20:58:35\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_2_4_1781618315.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 20:58:36', '::1'),
(5, 4, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/1\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-16 21:07:39\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_4_4_1781618859.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:07:39', '::1'),
(6, 3, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/4\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-16 21:09:41\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_3_4_1781618981.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:09:41', '::1'),
(7, 3, 3, 'PPK', 2, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/4\",\"nama\":\"Ariq Ikbar Hakim\",\"jabatan\":\"PPK\",\"tanggal\":\"2026-06-16 21:09:55\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_3_3_1781618995.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:09:55', '::1'),
(8, 5, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/5\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-16 21:12:25\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_5_4_1781619145.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:12:25', '::1'),
(9, 5, 3, 'PPK', 2, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/5\",\"nama\":\"Ariq Ikbar Hakim\",\"jabatan\":\"PPK\",\"tanggal\":\"2026-06-16 21:12:36\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_5_3_1781619156.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:12:36', '::1'),
(10, 6, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/6\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-16 21:22:09\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_6_4_1781619729.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:22:09', '::1'),
(11, 6, 3, 'PPK', 2, '{\"nomor_ba\":\"BA\\/2026\\/06\\/16\\/6\",\"nama\":\"Ariq Ikbar Hakim\",\"jabatan\":\"PPK\",\"tanggal\":\"2026-06-16 21:22:37\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_6_3_1781619757.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-16 21:22:37', '::1'),
(12, 7, 4, 'PP', 1, '{\"nomor_ba\":\"BA\\/2026\\/06\\/19\\/7\",\"nama\":\"Akun saya ada dua\",\"jabatan\":\"PP\",\"tanggal\":\"2026-06-19 20:40:36\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_7_4_1781876436.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-19 20:40:37', '::1'),
(13, 7, 3, 'PPK', 2, '{\"nomor_ba\":\"BA\\/2026\\/06\\/19\\/7\",\"nama\":\"Ariq Ikbar Hakim\",\"jabatan\":\"PPK\",\"tanggal\":\"2026-06-19 20:41:05\",\"hash\":\"f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0\"}', 'uploads/qr/qr_7_3_1781876465.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-19 20:41:05', '::1'),
(14, 8, 4, 'PP', 1, 'http://localhost/LPSE/uploads/signatures/sig_8_4_1781876973.png', 'uploads/qr/qr_8_4_1781876973.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-19 20:49:35', '::1'),
(15, 8, 3, 'PPK', 2, 'http://localhost/LPSE/uploads/signatures/sig_8_3_1781877002.png', 'uploads/qr/qr_8_3_1781877002.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-19 20:50:02', '::1'),
(16, 9, 6, 'PP', 1, 'http://localhost/LPSE/uploads/signatures/sig_9_6_1782151682.png', 'uploads/qr/qr_9_6_1782151682.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-23 01:08:03', '::1'),
(17, 9, 7, 'PPK', 2, 'http://localhost/LPSE/uploads/signatures/sig_9_7_1782151711.png', 'uploads/qr/qr_9_7_1782151711.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-06-23 01:08:31', '::1'),
(18, 10, 4, 'PP', 1, 'http://localhost/LPSE/uploads/signatures/sig_11_4_1783915811.jpeg', 'uploads/qr/qr_10_4_1783915811.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-07-13 11:10:14', '::1'),
(19, 10, 6, 'PPK', 2, 'http://localhost/LPSE/uploads/signatures/sig_11_6_1783915860.png', 'uploads/qr/qr_10_6_1783915860.png', 'f8f6b2a50ac02a2f12dc34c2188abc5829dfe0ea35e73cb6964e2f374fb6bfc0', '2026-07-13 11:11:01', '::1'),
(20, 12, 4, 'PP', 1, 'http://localhost/LPSE/uploads/signatures/sig_manual_1784363980_4.jpeg', 'uploads/qr/qr_12_4_1784363980.png', 'e9cf1eb0f23f614413107c13596c0388bece25e6a4897d708b281ca14c82b461', '2026-07-18 15:39:46', '::1'),
(21, 12, 6, 'PPK', 2, 'http://localhost/LPSE/uploads/signatures/sig_manual_ppk_1784363980_6.jpeg', 'uploads/qr/qr_ppk_12_6_1784363981.png', 'e9cf1eb0f23f614413107c13596c0388bece25e6a4897d708b281ca14c82b461', '2026-07-18 15:39:46', '::1'),
(22, 13, 4, 'PP', 1, 'http://localhost/LPSE/uploads/signatures/sig_manual_1784365272_4.jpeg', 'uploads/qr/qr_13_4_1784365272.png', 'd29fc296ccab0445d4fce4bb9d915e86ebc340952597fba6d043b5ad371e9987', '2026-07-18 16:01:13', '::1'),
(23, 13, 6, 'PPK', 2, 'http://localhost/LPSE/uploads/signatures/sig_manual_ppk_1784365272_6.jpeg', 'uploads/qr/qr_ppk_13_6_1784365273.png', 'd29fc296ccab0445d4fce4bb9d915e86ebc340952597fba6d043b5ad371e9987', '2026-07-18 16:01:13', '::1'),
(24, 14, 4, 'PP', 1, 'http://localhost/LPSE/uploads/signatures/sig_manual_1784779724_4.jpeg', 'uploads/qr/qr_14_4_1784779724.png', '6fbc7fe66fb8b86c2343387554f84c0de76a58eac6fa10ece2ebc71d600753d3', '2026-07-23 11:08:50', '::1'),
(25, 14, 6, 'PPK', 2, 'http://localhost/LPSE/uploads/signatures/sig_manual_ppk_1784779724_6.jpeg', 'uploads/qr/qr_ppk_14_6_1784779726.png', '6fbc7fe66fb8b86c2343387554f84c0de76a58eac6fa10ece2ebc71d600753d3', '2026-07-23 11:08:50', '::1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sk_opd`
--

CREATE TABLE `sk_opd` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `nomor_sk` varchar(150) NOT NULL,
  `tanggal_sk` date NOT NULL,
  `berlaku_dari` date NOT NULL,
  `berlaku_sampai` date DEFAULT NULL,
  `file_sk` varchar(600) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nip` varchar(50) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telp` varchar(25) DEFAULT NULL,
  `opd` varchar(150) DEFAULT NULL COMMENT 'Organisasi Perangkat Daerah',
  `sub_unit_opd` varchar(150) DEFAULT NULL,
  `jabatan_aktif` enum('PPK','PP','admin') NOT NULL DEFAULT 'PPK',
  `sk_nomor` varchar(150) DEFAULT NULL,
  `sk_mulai` date DEFAULT NULL,
  `sk_sampai` date DEFAULT NULL,
  `sk_file` varchar(500) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=menunggu verifikasi, 1=aktif',
  `keterangan` text DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `reset_requested_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nip`, `nama`, `email`, `password`, `no_telp`, `opd`, `sub_unit_opd`, `jabatan_aktif`, `sk_nomor`, `sk_mulai`, `sk_sampai`, `sk_file`, `status_aktif`, `keterangan`, `reset_token`, `reset_token_expires`, `reset_requested_at`, `last_login`, `created_at`, `updated_at`) VALUES
(1, '000000000001', 'Administrator', 'admin@apelbaja.go.id', '$2a$12$Ns1Y94pBhOm6pFSc8MIYxOIZ15d7vIJTmn3jG/DAyo/z7Tz5c2xcu', NULL, 'UKPBJ Provinsi Jawa Timur', NULL, 'admin', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '2026-07-23 10:24:33', '2026-06-15 12:51:00', '2026-07-23 03:24:33'),
(3, '123456789123456789', 'Ariq Ikbar Hakim', 'ariq20055@gmail.com', '$2y$12$87VJl6g0jrKTK/7V0DbNxeQ6MD8Cj20j.RrvyBJIujPsmSXf/3tIG', '083830237808', 'MBG', '', 'PP', '', NULL, NULL, NULL, 1, '', NULL, NULL, NULL, '2026-07-18 14:05:51', '2026-06-15 13:54:37', '2026-07-18 07:05:51'),
(4, '012345678012345678', 'Akun saya ada dua', 'ariqikbar730@gmail.com', '$2y$10$9lW8jmI3OrjDItxOJHCc9.8lhj0HiLVYRcXI0oRByLCHcbrhVUKO.', '085867276889', 'Pusat MBG', '', 'PP', '', NULL, NULL, NULL, 1, '', '5811daac6ab9c22254d056398d43c7597dc0450c69a000a637aa699039322b4c', '2026-07-14 10:50:50', '2026-07-13 10:50:50', '2026-07-23 10:24:19', '2026-06-15 13:58:16', '2026-07-23 03:24:19'),
(6, '212121212121212121', 'Cornet Perso', 'cornetperso6@gmail.com', '$2y$12$YHVaoi7hYHbtJC0l7cFoO.W4VO6gl88pGElOLLjh/Rw4rfzEaBXOm', '0831231231231', 'Hukum Kerja', '', 'PPK', '', NULL, NULL, NULL, 1, '', NULL, NULL, NULL, '2026-07-23 10:24:42', '2026-06-19 14:26:59', '2026-07-23 03:24:42'),
(7, '313131313131313131', '- Ariq Ikbar Hakim', '230441100058@student.trunojoyo.ac.id', '$2y$12$x.cDtK0gujqIHo0lh7QP6u71YLZhg1X8NgnhxiAbDebv8bveHG1gq', '08123123213', 'Penjabat Negara', '', 'PP', '', NULL, NULL, NULL, 1, '', NULL, NULL, NULL, '2026-07-18 14:06:35', '2026-06-22 17:51:18', '2026-07-18 07:06:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role_history`
--

CREATE TABLE `user_role_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_lama` enum('PPK','PP','admin') NOT NULL,
  `role_baru` enum('PPK','PP','admin') NOT NULL,
  `alasan` text DEFAULT NULL,
  `diubah_oleh` int(10) UNSIGNED NOT NULL COMMENT 'user_id admin yang mengubah',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_role_history`
--

INSERT INTO `user_role_history` (`id`, `user_id`, `role_lama`, `role_baru`, `alasan`, `diubah_oleh`, `created_at`) VALUES
(1, 3, 'PPK', 'PP', 'Perubahan otomatis dari approval Transfer Paket ID 3', 1, '2026-06-22 17:29:20'),
(2, 7, 'PPK', 'PP', 'Swap otomatis dari persetujuan Transfer Jabatan & Paket (Transfer ID 2)', 1, '2026-06-24 14:03:15'),
(3, 4, 'PP', 'PPK', 'Swap otomatis dari persetujuan Transfer Jabatan & Paket (Transfer ID 2)', 1, '2026-06-24 14:03:15'),
(4, 4, 'PPK', 'PP', 'Swap otomatis dari persetujuan Transfer Jabatan & Paket (Transfer ID 3)', 1, '2026-07-13 03:54:28'),
(5, 6, 'PP', 'PPK', 'Swap otomatis dari persetujuan Transfer Jabatan & Paket (Transfer ID 3)', 1, '2026-07-13 03:54:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `assignment_transfer`
--
ALTER TABLE `assignment_transfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_at_dari_user_id` (`dari_user_id`),
  ADD KEY `fk_at_ke_user_id` (`ke_user_id`),
  ADD KEY `idx_at_status` (`status`);

--
-- Indeks untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_al_user_id` (`user_id`),
  ADD KEY `idx_al_aksi` (`aksi`),
  ADD KEY `idx_al_tabel` (`tabel_terpengaruh`),
  ADD KEY `idx_al_record_id` (`record_id`),
  ADD KEY `idx_al_created_at` (`created_at`);

--
-- Indeks untuk tabel `berita_acara`
--
ALTER TABLE `berita_acara`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ba_paket_id` (`paket_id`),
  ADD UNIQUE KEY `uq_ba_nomor` (`nomor_ba`);

--
-- Indeks untuk tabel `document_comments`
--
ALTER TABLE `document_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dc_paket_id` (`paket_id`),
  ADD KEY `idx_dc_lampiran` (`lampiran_id`),
  ADD KEY `idx_dc_user_id` (`user_id`);

--
-- Indeks untuk tabel `lampiran`
--
ALTER TABLE `lampiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lamp_paket_id` (`paket_id`),
  ADD KEY `idx_lamp_tipe` (`tipe_dokumen`(100)),
  ADD KEY `idx_lamp_is_active` (`is_active`),
  ADD KEY `idx_lamp_status` (`status_validasi`),
  ADD KEY `idx_lamp_paket_tipe` (`paket_id`,`tipe_dokumen`(100),`is_active`),
  ADD KEY `fk_lamp_uploaded_by` (`uploaded_by`);

--
-- Indeks untuk tabel `log_paket`
--
ALTER TABLE `log_paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lp_paket_id` (`paket_id`),
  ADD KEY `idx_lp_user_id` (`user_id`);

--
-- Indeks untuk tabel `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_paket_ppk_id` (`ppk_id`),
  ADD KEY `idx_paket_pp_id` (`pp_id`),
  ADD KEY `idx_paket_status` (`status`),
  ADD KEY `idx_paket_tahun` (`tahun_anggaran`),
  ADD KEY `idx_paket_kode_rup` (`kode_rup`);

--
-- Indeks untuk tabel `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_prr_token` (`token`),
  ADD KEY `idx_prr_user_id` (`user_id`),
  ADD KEY `idx_prr_status` (`status`),
  ADD KEY `fk_prr_disetujui_oleh` (`disetujui_oleh`);

--
-- Indeks untuk tabel `role_change_requests`
--
ALTER TABLE `role_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rc_user_id` (`user_id`),
  ADD KEY `idx_rc_status` (`status`),
  ADD KEY `fk_rc_admin_id` (`disetujui_oleh`);

--
-- Indeks untuk tabel `signatures`
--
ALTER TABLE `signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sig_ba_user` (`berita_acara_id`,`user_id`),
  ADD KEY `idx_sig_ba_id` (`berita_acara_id`),
  ADD KEY `idx_sig_user_id` (`user_id`);

--
-- Indeks untuk tabel `sk_opd`
--
ALTER TABLE `sk_opd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sk_user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_nip` (`nip`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_jabatan` (`jabatan_aktif`),
  ADD KEY `idx_users_status` (`status_aktif`);

--
-- Indeks untuk tabel `user_role_history`
--
ALTER TABLE `user_role_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_urh_user_id` (`user_id`),
  ADD KEY `fk_urh_diubah_oleh` (`diubah_oleh`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `assignment_transfer`
--
ALTER TABLE `assignment_transfer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT untuk tabel `berita_acara`
--
ALTER TABLE `berita_acara`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `document_comments`
--
ALTER TABLE `document_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `lampiran`
--
ALTER TABLE `lampiran`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `log_paket`
--
ALTER TABLE `log_paket`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `paket`
--
ALTER TABLE `paket`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `role_change_requests`
--
ALTER TABLE `role_change_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `signatures`
--
ALTER TABLE `signatures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `sk_opd`
--
ALTER TABLE `sk_opd`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `user_role_history`
--
ALTER TABLE `user_role_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `assignment_transfer`
--
ALTER TABLE `assignment_transfer`
  ADD CONSTRAINT `fk_at_dari_user_id` FOREIGN KEY (`dari_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_at_ke_user_id` FOREIGN KEY (`ke_user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_al_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `berita_acara`
--
ALTER TABLE `berita_acara`
  ADD CONSTRAINT `fk_ba_paket_id` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `document_comments`
--
ALTER TABLE `document_comments`
  ADD CONSTRAINT `fk_dc_lampiran_id` FOREIGN KEY (`lampiran_id`) REFERENCES `lampiran` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dc_paket_id` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`),
  ADD CONSTRAINT `fk_dc_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `lampiran`
--
ALTER TABLE `lampiran`
  ADD CONSTRAINT `fk_lamp_paket_id` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lamp_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `log_paket`
--
ALTER TABLE `log_paket`
  ADD CONSTRAINT `fk_lp_paket_id` FOREIGN KEY (`paket_id`) REFERENCES `paket` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `paket`
--
ALTER TABLE `paket`
  ADD CONSTRAINT `fk_paket_pp_id` FOREIGN KEY (`pp_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_paket_ppk_id` FOREIGN KEY (`ppk_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  ADD CONSTRAINT `fk_prr_disetujui_oleh` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_prr_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_change_requests`
--
ALTER TABLE `role_change_requests`
  ADD CONSTRAINT `fk_rc_admin_id` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_rc_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `signatures`
--
ALTER TABLE `signatures`
  ADD CONSTRAINT `fk_sig_ba_id` FOREIGN KEY (`berita_acara_id`) REFERENCES `berita_acara` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sig_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `sk_opd`
--
ALTER TABLE `sk_opd`
  ADD CONSTRAINT `fk_sk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_role_history`
--
ALTER TABLE `user_role_history`
  ADD CONSTRAINT `fk_urh_diubah_oleh` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_urh_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
