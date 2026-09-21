-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jul 2026 pada 15.59
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
-- Database: `siakad_smknu`
--

CREATE DATABASE IF NOT EXISTS `siakad_smknu` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `siakad_smknu`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_guru`
--

CREATE TABLE `absensi_guru` (
  `id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `validated_by` int(11) DEFAULT NULL,
  `validated_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi_guru`
--

INSERT INTO `absensi_guru` (`id`, `guru_id`, `tanggal`, `status`, `created_at`) VALUES
(1, 1, '2026-07-15', 'Hadir', '2026-07-21 06:23:22'),
(2, 2, '2026-07-15', 'Hadir', '2026-07-21 06:23:22'),
(3, 3, '2026-07-15', 'Hadir', '2026-07-21 06:23:22'),
(4, 4, '2026-07-15', 'Izin', '2026-07-21 06:23:22'),
(5, 5, '2026-07-15', 'Hadir', '2026-07-21 06:23:22'),
(6, 6, '2026-07-15', 'Hadir', '2026-07-21 06:23:22'),
(7, 1, '2026-07-16', 'Hadir', '2026-07-21 06:23:22'),
(8, 2, '2026-07-16', 'Hadir', '2026-07-21 06:23:22'),
(9, 3, '2026-07-16', 'Sakit', '2026-07-21 06:23:22'),
(10, 4, '2026-07-16', 'Hadir', '2026-07-21 06:23:22'),
(11, 5, '2026-07-16', 'Hadir', '2026-07-21 06:23:22'),
(12, 6, '2026-07-16', 'Hadir', '2026-07-21 06:23:22'),
(13, 1, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(14, 2, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(15, 3, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(16, 4, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(17, 5, '2026-07-17', 'Izin', '2026-07-21 06:23:22'),
(18, 6, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(19, 1, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(20, 2, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(21, 3, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(22, 4, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(23, 5, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(24, 6, '2026-07-18', 'Alpa', '2026-07-21 06:23:22'),
(25, 1, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(26, 2, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(27, 3, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(28, 4, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(29, 5, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(30, 6, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(31, 1, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(32, 2, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(33, 3, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(34, 4, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(35, 5, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(36, 6, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(37, 1, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(38, 2, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(39, 3, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(40, 4, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(41, 5, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(42, 6, '2026-07-21', 'Hadir', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_siswa`
--

CREATE TABLE `absensi_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi_siswa`
--

INSERT INTO `absensi_siswa` (`id`, `siswa_id`, `kelas_id`, `mapel_id`, `guru_id`, `tanggal`, `status`, `created_at`) VALUES
(1, 1, 1, 1, 5, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(2, 2, 1, 1, 5, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(3, 3, 1, 1, 5, '2026-07-17', 'Sakit', '2026-07-21 06:23:22'),
(4, 4, 1, 1, 5, '2026-07-17', 'Hadir', '2026-07-21 06:23:22'),
(5, 1, 1, 1, 5, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(6, 2, 1, 1, 5, '2026-07-18', 'Izin', '2026-07-21 06:23:22'),
(7, 3, 1, 1, 5, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(8, 4, 1, 1, 5, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(9, 1, 1, 1, 5, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(10, 2, 1, 1, 5, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(11, 3, 1, 1, 5, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(12, 4, 1, 1, 5, '2026-07-19', 'Alpa', '2026-07-21 06:23:22'),
(13, 1, 1, 1, 5, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(14, 2, 1, 1, 5, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(15, 3, 1, 1, 5, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(16, 4, 1, 1, 5, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(17, 1, 1, 1, 5, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(18, 2, 1, 1, 5, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(19, 3, 1, 1, 5, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(20, 4, 1, 1, 5, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(21, 5, 2, 5, 1, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(22, 6, 2, 5, 1, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(23, 7, 2, 5, 1, '2026-07-18', 'Hadir', '2026-07-21 06:23:22'),
(24, 8, 2, 5, 1, '2026-07-18', 'Izin', '2026-07-21 06:23:22'),
(25, 5, 2, 5, 1, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(26, 6, 2, 5, 1, '2026-07-19', 'Sakit', '2026-07-21 06:23:22'),
(27, 7, 2, 5, 1, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(28, 8, 2, 5, 1, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(29, 5, 2, 5, 1, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(30, 6, 2, 5, 1, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(31, 7, 2, 5, 1, '2026-07-20', 'Alpa', '2026-07-21 06:23:22'),
(32, 8, 2, 5, 1, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(33, 5, 2, 5, 1, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(34, 6, 2, 5, 1, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(35, 7, 2, 5, 1, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(36, 8, 2, 5, 1, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(37, 9, 3, 6, 2, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(38, 10, 3, 6, 2, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(39, 11, 3, 6, 2, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(40, 12, 3, 6, 2, '2026-07-19', 'Hadir', '2026-07-21 06:23:22'),
(41, 9, 3, 6, 2, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(42, 10, 3, 6, 2, '2026-07-20', 'Izin', '2026-07-21 06:23:22'),
(43, 11, 3, 6, 2, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(44, 12, 3, 6, 2, '2026-07-20', 'Hadir', '2026-07-21 06:23:22'),
(45, 9, 3, 6, 2, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(46, 10, 3, 6, 2, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(47, 11, 3, 6, 2, '2026-07-21', 'Hadir', '2026-07-21 06:23:22'),
(48, 12, 3, 6, 2, '2026-07-21', 'Hadir', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan`
--

CREATE TABLE `catatan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `isi_catatan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `catatan`
--

INSERT INTO `catatan` (`id`, `siswa_id`, `guru_id`, `mapel_id`, `kelas_id`, `isi_catatan`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 1, 1, 'Rangga menunjukkan kemajuan yang baik dalam materi aljabar minggu ini. Terus tingkatkan.', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 3, 5, 1, 1, 'Bagas perlu latihan lebih banyak di soal cerita. Disarankan mengulang materi bab 2.', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 6, 1, 5, 2, 'Putri kurang aktif di kelas Jaringan. Perlu diperhatikan.', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 11, 2, 6, 3, 'Yoga sering telat mengumpulkan tugas. Harap segera diperbaiki menjelang ujian akhir.', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(5, 12, 2, 6, 3, 'Naila menunjukkan skill pemrograman di atas rata-rata. Direkomendasikan ikut lomba web development.', '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `kode_guru` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nuptk` varchar(20) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `status_kepegawaian` varchar(50) DEFAULT NULL,
  `jenis_ptk` varchar(50) DEFAULT NULL,
  `gelar` varchar(50) DEFAULT NULL,
  `jenjang_pendidikan` varchar(20) DEFAULT NULL,
  `jurusan_prodi` varchar(100) DEFAULT NULL,
  `tmt_kerja` date DEFAULT NULL,
  `tugas_tambahan` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id`, `kode_guru`, `user_id`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `email`, `agama`, `tahun_masuk`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'G-001', 10, 'Budi Santoso', 'Laki-Laki', 'Palembang', '1975-01-01', 'Jl. Merdeka No. 12, Palembang', '081234567001', 'budi@smknu.sch.id', 'Islam', '2005', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 'G-002', 11, 'Siti Aisyah', 'Perempuan', 'Jakarta', '1982-03-15', 'Jl. Sudirman No. 45, Palembang', '081234567002', 'siti@smknu.sch.id', 'Islam', '2007', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 'G-003', 12, 'Ahmad Fauzi', 'Laki-Laki', 'Bandung', '1979-08-20', 'Jl. Ahmad Yani No. 8, Palembang', '081234567003', 'ahmad@smknu.sch.id', 'Islam', '2008', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 'G-004', 13, 'Dewi Lestari', 'Perempuan', 'Surabaya', '1985-11-10', 'Jl. Diponegoro No. 22, Palembang', '081234567004', 'dewi@smknu.sch.id', 'Islam', '2012', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(5, 'G-005', 14, 'Rudi Hartono', 'Laki-Laki', 'Yogyakarta', '1990-01-15', 'Jl. Kartini No. 5, Palembang', '081234567005', 'rudi@smknu.sch.id', 'Islam', '2015', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(6, 'G-006', 15, 'Nia Ramadhani', 'Perempuan', 'Medan', '1992-06-20', 'Jl. Cendrawasih No. 17, Palembang', '081234567006', 'nia@smknu.sch.id', 'Islam', '2018', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id`, `kelas_id`, `mapel_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'Senin', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 1, 2, 4, 'Senin', '09:15:00', '10:45:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 1, 3, 6, 'Selasa', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 1, 4, 3, 'Rabu', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(5, 1, 1, 5, 'Kamis', '09:15:00', '10:45:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(6, 2, 5, 1, 'Senin', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(7, 2, 1, 5, 'Selasa', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(8, 2, 2, 4, 'Rabu', '09:15:00', '10:45:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(9, 2, 5, 1, 'Kamis', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(10, 3, 6, 2, 'Senin', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(11, 3, 6, 2, 'Selasa', '09:15:00', '10:45:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(12, 3, 1, 5, 'Rabu', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(13, 3, 6, 2, 'Jumat', '07:30:00', '09:00:00', '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kode_kelas` varchar(20) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `kode_kelas`, `nama_kelas`, `wali_kelas_id`, `tahun_ajaran_id`, `created_at`, `updated_at`) VALUES
(1, 'K-XTKJ', 'X TKJ', 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 'K-XITKJ', 'XI TKJ', 2, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 'K-XIITKJ', 'XII TKJ', 3, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id`, `kelas_id`, `siswa_id`, `created_at`) VALUES
(1, 1, 1, '2026-07-21 06:23:22'),
(2, 1, 2, '2026-07-21 06:23:22'),
(3, 1, 3, '2026-07-21 06:23:22'),
(4, 1, 4, '2026-07-21 06:23:22'),
(5, 2, 5, '2026-07-21 06:23:22'),
(6, 2, 6, '2026-07-21 06:23:22'),
(7, 2, 7, '2026-07-21 06:23:22'),
(8, 2, 8, '2026-07-21 06:23:22'),
(9, 3, 9, '2026-07-21 06:23:22'),
(10, 3, 10, '2026-07-21 06:23:22'),
(11, 3, 11, '2026-07-21 06:23:22'),
(12, 3, 12, '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id` int(11) NOT NULL,
  `kode_mapel` varchar(20) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `semester` enum('ganjil','genap') NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id`, `kode_mapel`, `nama_mapel`, `semester`, `tingkat`, `tahun_ajaran_id`, `created_at`, `updated_at`) VALUES
(1, 'MTK-X', 'Matematika', 'ganjil', 'X', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 'BIN-X', 'Bahasa Indonesia', 'ganjil', 'X', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 'ING-X', 'Bahasa Inggris', 'ganjil', 'X', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 'PAI-X', 'Pendidikan Agama Islam', 'ganjil', 'X', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(5, 'JAR-XI', 'Jaringan Komputer', 'ganjil', 'XI', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(6, 'PRO-XII', 'Pemrograman Web', 'ganjil', 'XII', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT NULL,
  `nilai_uts` decimal(5,2) DEFAULT NULL,
  `nilai_uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `capaian_kompetensi` text DEFAULT NULL,
  `bulan` tinyint(2) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `validated_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id`, `siswa_id`, `mapel_id`, `kelas_id`, `guru_id`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `capaian_kompetensi`, `bulan`, `tahun`, `tahun_ajaran_id`, `is_validated`, `validated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 5, 85.00, 78.00, 88.00, 84.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 2, 1, 1, 5, 78.00, 82.00, 85.00, 82.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 3, 1, 1, 5, 90.00, 88.00, 92.00, 90.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 4, 1, 1, 5, 70.00, 65.00, 72.00, 69.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(5, 1, 2, 1, 4, 80.00, 85.00, 82.00, 82.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(6, 2, 2, 1, 4, 88.00, 90.00, 85.00, 88.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(7, 3, 2, 1, 4, 75.00, 78.00, 80.00, 78.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(8, 4, 2, 1, 4, 82.00, 80.00, 78.00, 80.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(9, 5, 5, 2, 1, 88.00, 85.00, 90.00, 88.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(10, 6, 5, 2, 1, 75.00, 70.00, 78.00, 74.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(11, 7, 5, 2, 1, 92.00, 90.00, 95.00, 92.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(12, 8, 5, 2, 1, 80.00, 82.00, 78.00, 80.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(13, 5, 1, 2, 5, 75.00, 72.00, 70.00, 72.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(14, 6, 1, 2, 5, 68.00, 65.00, 62.00, 65.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(15, 7, 1, 2, 5, 85.00, 88.00, 90.00, 88.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(16, 8, 1, 2, 5, 78.00, 80.00, 82.00, 80.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(17, 9, 6, 3, 2, 90.00, 88.00, 92.00, 90.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(18, 10, 6, 3, 2, 85.00, 80.00, 82.00, 82.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(19, 11, 6, 3, 2, 78.00, 75.00, 80.00, 78.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(20, 12, 6, 3, 2, 95.00, 92.00, 96.00, 94.00, NULL, 7, 2026, 1, 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tanggal_publish` date NOT NULL,
  `penerima` set('guru','siswa') NOT NULL,
  `status` enum('aktif','draft','terjadwal') DEFAULT 'draft',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `tanggal_publish`, `penerima`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Ujian Tengah Semester Ganjil 2024/2025', 'Diberitahukan kepada seluruh siswa bahwa UTS akan dilaksanakan mulai tanggal 15 Oktober 2024 hingga 22 Oktober 2024. Jadwal detail akan dibagikan oleh wali kelas masing-masing.', '2026-07-16', 'guru,siswa', 'aktif', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 'Rapat Dewan Guru', 'Seluruh dewan guru diharapkan hadir dalam rapat evaluasi bulanan pada hari Jumat, 10 November 2024 pukul 13:00 WIB di ruang guru.', '2026-07-19', 'guru', 'aktif', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 'Libur Hari Guru Nasional', 'Sekolah diliburkan pada tanggal 25 November 2024 dalam rangka memperingati Hari Guru Nasional. Kegiatan belajar mengajar dilanjutkan hari berikutnya.', '2026-07-21', 'guru,siswa', 'aktif', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 'Pendaftaran Ekstrakurikuler', 'Pendaftaran ekstrakurikuler semester ganjil telah dibuka. Siswa dapat mendaftar melalui wali kelas paling lambat tanggal 20 November 2024.', '2026-07-24', 'siswa', 'terjadwal', 1, '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `kode_siswa` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nisn` varchar(20) NOT NULL,
  `nipd` varchar(20) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `wali_siswa` varchar(100) DEFAULT NULL,
  `penerima_kip` enum('ya','tidak') DEFAULT 'tidak',
  `nomor_kip` varchar(30) DEFAULT NULL,
  `sekolah_asal` varchar(150) DEFAULT NULL,
  `jenis_tinggal` varchar(50) DEFAULT NULL,
  `anak_ke` int(11) DEFAULT NULL,
  `jml_saudara` int(11) DEFAULT NULL,
  `jarak_sekolah_km` decimal(5,2) DEFAULT NULL,
  `ayah_nama` varchar(100) DEFAULT NULL,
  `ayah_nik` varchar(20) DEFAULT NULL,
  `ayah_tahun_lahir` year(4) DEFAULT NULL,
  `ayah_pendidikan` varchar(20) DEFAULT NULL,
  `ayah_pekerjaan` varchar(100) DEFAULT NULL,
  `ibu_nama` varchar(100) DEFAULT NULL,
  `ibu_nik` varchar(20) DEFAULT NULL,
  `ibu_tahun_lahir` year(4) DEFAULT NULL,
  `ibu_pendidikan` varchar(20) DEFAULT NULL,
  `ibu_pekerjaan` varchar(100) DEFAULT NULL,
  `tahun_masuk` year(4) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `kode_siswa`, `user_id`, `nisn`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `agama`, `wali_siswa`, `tahun_masuk`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'S-001', 20, '0071234567', 'Rangga Prasetyo', 'Laki-Laki', 'Palembang', '2007-05-10', 'Jl. Kapten A. Rivai No. 4', '081298765001', 'Islam', 'Sutrisno Prasetyo', '2024', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(2, 'S-002', 21, '0071234568', 'Melati Ayu', 'Perempuan', 'Palembang', '2007-08-22', 'Jl. Angkatan 45 No. 12', '081298765002', 'Islam', 'Wagiono Susanto', '2024', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(3, 'S-003', 22, '0071234569', 'Bagas Wirawan', 'Laki-Laki', 'OKU Timur', '2007-02-14', 'Desa Belitang, OKU Timur', '081298765003', 'Islam', 'Sugeng Wirawan', '2024', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(4, 'S-004', 23, '0071234570', 'Salsa Nabila', 'Perempuan', 'Palembang', '2007-11-30', 'Jl. Perintis No. 9', '081298765004', 'Islam', 'Hendra Nasution', '2024', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(5, 'S-005', 24, '0061234571', 'Dimas Ariyanto', 'Laki-Laki', 'Palembang', '2006-03-05', 'Jl. Kolonel Atmo No. 33', '081298765005', 'Islam', 'Slamet Ariyanto', '2023', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(6, 'S-006', 25, '0061234572', 'Putri Wulandari', 'Perempuan', 'Palembang', '2006-09-18', 'Jl. Basuki Rahmat No. 7', '081298765006', 'Islam', 'Budiman Wulandari', '2023', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(7, 'S-007', 26, '0061234573', 'Fajar Nugroho', 'Laki-Laki', 'OKI', '2006-12-01', 'Desa Karang Anyar', '081298765007', 'Islam', 'Purnomo Nugroho', '2023', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(8, 'S-008', 27, '0061234574', 'Aulia Rahmawati', 'Perempuan', 'Palembang', '2006-06-27', 'Jl. Sukabangun No. 15', '081298765008', 'Islam', 'Ali Rahman', '2023', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(9, 'S-009', 28, '0051234575', 'Rizky Pratama', 'Laki-Laki', 'Palembang', '2005-04-19', 'Jl. Demang Lebar Daun No. 22', '081298765009', 'Islam', 'Anwar Pratama', '2022', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(10, 'S-010', 29, '0051234576', 'Sinta Dewi', 'Perempuan', 'Palembang', '2005-07-11', 'Jl. Radial No. 8', '081298765010', 'Islam', 'Bambang Suherman', '2022', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(11, 'S-011', 30, '0051234577', 'Yoga Prasetyo', 'Laki-Laki', 'Palembang', '2005-10-25', 'Jl. R. Sukamto No. 44', '081298765011', 'Islam', 'Sutopo Handoko', '2022', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22'),
(12, 'S-012', 31, '0051234578', 'Naila Fitri', 'Perempuan', 'Palembang', '2005-12-08', 'Jl. Veteran No. 3', '081298765012', 'Islam', 'Zainal Abidin', '2022', NULL, '2026-07-21 06:23:22', '2026-07-21 06:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` int(11) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `semester_aktif` enum('ganjil','genap') NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'nonaktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `kode`, `tahun_ajaran`, `semester_aktif`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TA2425G', '2024/2025', 'ganjil', 'aktif', '2026-07-21 06:23:21', '2026-07-21 06:23:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','guru','siswa','kepala_sekolah') NOT NULL DEFAULT 'siswa',
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `foto`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', NULL, 'aktif', '2026-07-21 20:54:40', '2026-06-27 02:59:22', '2026-07-21 13:54:40'),
(3, 'kepsek', '$2y$10$cyxGfA/1PnSnTV9l7jKP9.jFGRv7zx.xCFI0GVeKAK1OO3CSphiim', 'Kepala Sekolah', 'kepala_sekolah', NULL, 'aktif', '2026-07-21 20:14:21', '2026-07-09 06:44:28', '2026-07-21 13:14:21'),
(10, 'budi', '$2y$10$3OAJ55SNDRPXwT4DDtneYOPeRYR8odwAv2AmPHR4PsD9W87o0Ktqa', 'Budi Santoso', 'guru', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(11, 'siti', '$2y$10$3OAJ55SNDRPXwT4DDtneYOPeRYR8odwAv2AmPHR4PsD9W87o0Ktqa', 'Siti Aisyah', 'guru', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(12, 'ahmad', '$2y$10$3OAJ55SNDRPXwT4DDtneYOPeRYR8odwAv2AmPHR4PsD9W87o0Ktqa', 'Ahmad Fauzi', 'guru', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(13, 'dewi', '$2y$10$3OAJ55SNDRPXwT4DDtneYOPeRYR8odwAv2AmPHR4PsD9W87o0Ktqa', 'Dewi Lestari', 'guru', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(14, 'rudi', '$2y$10$3OAJ55SNDRPXwT4DDtneYOPeRYR8odwAv2AmPHR4PsD9W87o0Ktqa', 'Rudi Hartono', 'guru', NULL, 'aktif', '2026-07-21 13:26:09', '2026-07-21 06:23:21', '2026-07-21 06:26:09'),
(15, 'nia', '$2y$10$3OAJ55SNDRPXwT4DDtneYOPeRYR8odwAv2AmPHR4PsD9W87o0Ktqa', 'Nia Ramadhani', 'guru', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(20, 'rangga', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Rangga Prasetyo', 'siswa', NULL, 'aktif', '2026-07-21 13:24:52', '2026-07-21 06:23:21', '2026-07-21 06:24:52'),
(21, 'melati', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Melati Ayu', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(22, 'bagas', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Bagas Wirawan', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(23, 'salsa', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Salsa Nabila', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(24, 'dimas', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Dimas Ariyanto', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(25, 'putri', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Putri Wulandari', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(26, 'fajar', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Fajar Nugroho', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(27, 'aulia', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Aulia Rahmawati', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(28, 'rizky', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Rizky Pratama', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(29, 'sinta', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Sinta Dewi', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(30, 'yoga', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Yoga Prasetyo', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21'),
(31, 'naila', '$2y$10$n1vC/qLKQV..gYU84p.XReB6.BS1KSiVvDadOpI9uPezUuuHqq3/e', 'Naila Fitri', 'siswa', NULL, 'aktif', NULL, '2026-07-21 06:23:21', '2026-07-21 06:23:21');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi_guru`
--
ALTER TABLE `absensi_guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_absen_guru` (`guru_id`,`tanggal`);

--
-- Indeks untuk tabel `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_absen_siswa` (`siswa_id`,`mapel_id`,`tanggal`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `catatan`
--
ALTER TABLE `catatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_guru` (`kode_guru`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kelas` (`kode_kelas`),
  ADD KEY `wali_kelas_id` (`wali_kelas_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`);

--
-- Indeks untuk tabel `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kelas_siswa` (`kelas_id`,`siswa_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indeks untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mapel` (`kode_mapel`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nilai_period` (`siswa_id`,`kelas_id`,`mapel_id`,`bulan`,`tahun`,`tahun_ajaran_id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_siswa` (`kode_siswa`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi_guru`
--
ALTER TABLE `absensi_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `catatan`
--
ALTER TABLE `catatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi_guru`
--
ALTER TABLE `absensi_guru`
  ADD CONSTRAINT `absensi_guru_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  ADD CONSTRAINT `absensi_siswa_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_ibfk_4` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `catatan`
--
ALTER TABLE `catatan`
  ADD CONSTRAINT `catatan_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `catatan_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `catatan_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `catatan_ibfk_4` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD CONSTRAINT `kelas_siswa_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_siswa_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD CONSTRAINT `mapel_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_4` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
