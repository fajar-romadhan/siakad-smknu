-- ============================================================
-- SCRIPT SINKRONISASI DATABASE LENGKAP SIAKAD SMKN NU
-- Tanggal: 10 Agustus 2026
-- Petunjuk: Import file ini di phpMyAdmin pada database `siakad_smknu`
-- Aman dijalankan berulang kali (Idempotent).
-- ============================================================

USE `siakad_smknu`;

-- 1. SINKRONISASI TABEL GURU
DELIMITER $$
DROP PROCEDURE IF EXISTS sync_col_guru $$
CREATE PROCEDURE sync_col_guru()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'nuptk') THEN
        ALTER TABLE `guru` ADD COLUMN `nuptk` VARCHAR(20) DEFAULT NULL AFTER `foto`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'nik') THEN
        ALTER TABLE `guru` ADD COLUMN `nik` VARCHAR(20) DEFAULT NULL AFTER `nuptk`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'nip') THEN
        ALTER TABLE `guru` ADD COLUMN `nip` VARCHAR(30) DEFAULT NULL AFTER `nik`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'status_kepegawaian') THEN
        ALTER TABLE `guru` ADD COLUMN `status_kepegawaian` VARCHAR(50) DEFAULT NULL AFTER `nip`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'jenis_ptk') THEN
        ALTER TABLE `guru` ADD COLUMN `jenis_ptk` VARCHAR(50) DEFAULT NULL AFTER `status_kepegawaian`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'gelar') THEN
        ALTER TABLE `guru` ADD COLUMN `gelar` VARCHAR(50) DEFAULT NULL AFTER `jenis_ptk`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'jenjang_pendidikan') THEN
        ALTER TABLE `guru` ADD COLUMN `jenjang_pendidikan` VARCHAR(20) DEFAULT NULL AFTER `gelar`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'jurusan_prodi') THEN
        ALTER TABLE `guru` ADD COLUMN `jurusan_prodi` VARCHAR(100) DEFAULT NULL AFTER `jenjang_pendidikan`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'tmt_kerja') THEN
        ALTER TABLE `guru` ADD COLUMN `tmt_kerja` DATE DEFAULT NULL AFTER `jurusan_prodi`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'tugas_tambahan') THEN
        ALTER TABLE `guru` ADD COLUMN `tugas_tambahan` VARCHAR(100) DEFAULT NULL AFTER `tmt_kerja`;
    END IF;
    IF EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'guru' AND COLUMN_NAME = 'nis') THEN
        ALTER TABLE `guru` DROP COLUMN `nis`;
    END IF;
END $$
DELIMITER ;
CALL sync_col_guru();
DROP PROCEDURE IF EXISTS sync_col_guru;


-- 2. SINKRONISASI TABEL SISWA
DELIMITER $$
DROP PROCEDURE IF EXISTS sync_col_siswa $$
CREATE PROCEDURE sync_col_siswa()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'nipd') THEN
        ALTER TABLE `siswa` ADD COLUMN `nipd` VARCHAR(20) DEFAULT NULL AFTER `nisn`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'nik') THEN
        ALTER TABLE `siswa` ADD COLUMN `nik` VARCHAR(20) DEFAULT NULL AFTER `nipd`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'rt') THEN
        ALTER TABLE `siswa` ADD COLUMN `rt` VARCHAR(5) DEFAULT NULL AFTER `alamat`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'rw') THEN
        ALTER TABLE `siswa` ADD COLUMN `rw` VARCHAR(5) DEFAULT NULL AFTER `rt`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'kelurahan') THEN
        ALTER TABLE `siswa` ADD COLUMN `kelurahan` VARCHAR(100) DEFAULT NULL AFTER `rw`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'kecamatan') THEN
        ALTER TABLE `siswa` ADD COLUMN `kecamatan` VARCHAR(100) DEFAULT NULL AFTER `kelurahan`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'kode_pos') THEN
        ALTER TABLE `siswa` ADD COLUMN `kode_pos` VARCHAR(10) DEFAULT NULL AFTER `kecamatan`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'penerima_kip') THEN
        ALTER TABLE `siswa` ADD COLUMN `penerima_kip` ENUM('ya','tidak') DEFAULT 'tidak' AFTER `no_hp`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'nomor_kip') THEN
        ALTER TABLE `siswa` ADD COLUMN `nomor_kip` VARCHAR(30) DEFAULT NULL AFTER `penerima_kip`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'sekolah_asal') THEN
        ALTER TABLE `siswa` ADD COLUMN `sekolah_asal` VARCHAR(150) DEFAULT NULL AFTER `nomor_kip`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'jenis_tinggal') THEN
        ALTER TABLE `siswa` ADD COLUMN `jenis_tinggal` VARCHAR(50) DEFAULT NULL AFTER `sekolah_asal`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'anak_ke') THEN
        ALTER TABLE `siswa` ADD COLUMN `anak_ke` INT(11) DEFAULT NULL AFTER `jenis_tinggal`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'jml_saudara') THEN
        ALTER TABLE `siswa` ADD COLUMN `jml_saudara` INT(11) DEFAULT NULL AFTER `anak_ke`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'jarak_sekolah_km') THEN
        ALTER TABLE `siswa` ADD COLUMN `jarak_sekolah_km` DECIMAL(5,2) DEFAULT NULL AFTER `jml_saudara`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ayah_nama') THEN
        ALTER TABLE `siswa` ADD COLUMN `ayah_nama` VARCHAR(100) DEFAULT NULL AFTER `jarak_sekolah_km`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ayah_nik') THEN
        ALTER TABLE `siswa` ADD COLUMN `ayah_nik` VARCHAR(20) DEFAULT NULL AFTER `ayah_nama`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ayah_tahun_lahir') THEN
        ALTER TABLE `siswa` ADD COLUMN `ayah_tahun_lahir` YEAR(4) DEFAULT NULL AFTER `ayah_nik`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ayah_pendidikan') THEN
        ALTER TABLE `siswa` ADD COLUMN `ayah_pendidikan` VARCHAR(20) DEFAULT NULL AFTER `ayah_tahun_lahir`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ayah_pekerjaan') THEN
        ALTER TABLE `siswa` ADD COLUMN `ayah_pekerjaan` VARCHAR(100) DEFAULT NULL AFTER `ayah_pendidikan`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ibu_nama') THEN
        ALTER TABLE `siswa` ADD COLUMN `ibu_nama` VARCHAR(100) DEFAULT NULL AFTER `ayah_pekerjaan`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ibu_nik') THEN
        ALTER TABLE `siswa` ADD COLUMN `ibu_nik` VARCHAR(20) DEFAULT NULL AFTER `ibu_nama`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ibu_tahun_lahir') THEN
        ALTER TABLE `siswa` ADD COLUMN `ibu_tahun_lahir` YEAR(4) DEFAULT NULL AFTER `ibu_nik`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ibu_pendidikan') THEN
        ALTER TABLE `siswa` ADD COLUMN `ibu_pendidikan` VARCHAR(20) DEFAULT NULL AFTER `ibu_tahun_lahir`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = 'ibu_pekerjaan') THEN
        ALTER TABLE `siswa` ADD COLUMN `ibu_pekerjaan` VARCHAR(100) DEFAULT NULL AFTER `ibu_pendidikan`;
    END IF;
END $$
DELIMITER ;
CALL sync_col_siswa();
DROP PROCEDURE IF EXISTS sync_col_siswa;


-- 3. SINKRONISASI TABEL ABSENSI GURU
DELIMITER $$
DROP PROCEDURE IF EXISTS sync_col_absensi_guru $$
CREATE PROCEDURE sync_col_absensi_guru()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi_guru' AND COLUMN_NAME = 'is_validated') THEN
        ALTER TABLE `absensi_guru` ADD COLUMN `is_validated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi_guru' AND COLUMN_NAME = 'validated_by') THEN
        ALTER TABLE `absensi_guru` ADD COLUMN `validated_by` INT(11) DEFAULT NULL AFTER `is_validated`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi_guru' AND COLUMN_NAME = 'validated_at') THEN
        ALTER TABLE `absensi_guru` ADD COLUMN `validated_at` DATETIME DEFAULT NULL AFTER `validated_by`;
    END IF;
END $$
DELIMITER ;
CALL sync_col_absensi_guru();
DROP PROCEDURE IF EXISTS sync_col_absensi_guru;


-- 4. SINKRONISASI TABEL NILAI
DELIMITER $$
DROP PROCEDURE IF EXISTS sync_col_nilai $$
CREATE PROCEDURE sync_col_nilai()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = 'capaian_kompetensi') THEN
        ALTER TABLE `nilai` ADD COLUMN `capaian_kompetensi` TEXT DEFAULT NULL AFTER `nilai_akhir`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = 'bulan') THEN
        ALTER TABLE `nilai` ADD COLUMN `bulan` TINYINT(2) NULL DEFAULT NULL AFTER `capaian_kompetensi`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = 'tahun') THEN
        ALTER TABLE `nilai` ADD COLUMN `tahun` YEAR(4) NULL DEFAULT NULL AFTER `bulan`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = 'tahun_ajaran_id') THEN
        ALTER TABLE `nilai` ADD COLUMN `tahun_ajaran_id` INT(11) NULL DEFAULT NULL AFTER `tahun`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = 'is_validated') THEN
        ALTER TABLE `nilai` ADD COLUMN `is_validated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tahun_ajaran_id`;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = 'validated_at') THEN
        ALTER TABLE `nilai` ADD COLUMN `validated_at` DATETIME DEFAULT NULL AFTER `is_validated`;
    END IF;
END $$
DELIMITER ;
CALL sync_col_nilai();
DROP PROCEDURE IF EXISTS sync_col_nilai;


-- 5. UPDATE DATA PERIODE NILAI LAMA
UPDATE `nilai`
SET `bulan` = COALESCE(NULLIF(`bulan`, 0), MONTH(COALESCE(`created_at`, `updated_at`, NOW()))),
    `tahun` = COALESCE(NULLIF(`tahun`, 0), YEAR(COALESCE(`created_at`, `updated_at`, NOW()))),
    `tahun_ajaran_id` = COALESCE(`tahun_ajaran_id`, (SELECT k.tahun_ajaran_id FROM kelas k WHERE k.id = `nilai`.`kelas_id` LIMIT 1))
WHERE `bulan` IS NULL OR `tahun` IS NULL OR `tahun_ajaran_id` IS NULL;

UPDATE `nilai` SET `is_validated`=1, `validated_at`=NOW()
WHERE `nilai_akhir` IS NOT NULL AND `is_validated`=0;


-- 6. BERSIHKAN DUPLIKASI DATA NILAI SEBELUM MEMBUAT INDEX
DELETE n1 FROM `nilai` n1
JOIN `nilai` n2
  ON n1.siswa_id = n2.siswa_id
 AND n1.mapel_id = n2.mapel_id
 AND n1.kelas_id = n2.kelas_id
 AND n1.bulan = n2.bulan
 AND n1.tahun = n2.tahun
 AND COALESCE(n1.tahun_ajaran_id, 0) = COALESCE(n2.tahun_ajaran_id, 0)
 AND n1.id < n2.id;


-- 7. RE-INDEX UNIQUE KEY PERIODE NILAI (MENCEGAH ERROR #1072)
DELIMITER $$
DROP PROCEDURE IF EXISTS sync_index_nilai $$
CREATE PROCEDURE sync_index_nilai()
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND INDEX_NAME = 'unique_nilai') THEN
        ALTER TABLE `nilai` DROP INDEX `unique_nilai`;
    END IF;
    IF EXISTS (SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND INDEX_NAME = 'unique_nilai_period') THEN
        ALTER TABLE `nilai` DROP INDEX `unique_nilai_period`;
    END IF;
    ALTER TABLE `nilai` ADD UNIQUE KEY `unique_nilai_period` (`siswa_id`,`kelas_id`,`mapel_id`,`bulan`,`tahun`,`tahun_ajaran_id`);
END $$
DELIMITER ;
CALL sync_index_nilai();
DROP PROCEDURE IF EXISTS sync_index_nilai;


-- 8. MEMPASTIKAN SEMUA TABEL MEMILIKI AUTO_INCREMENT PADA KOLOM PRIMARY KEY 'id' (MENCEGAH ERROR #1364)
ALTER TABLE `absensi_guru` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `absensi_siswa` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `catatan` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `guru` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jadwal` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kelas` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kelas_siswa` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mapel` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `nilai` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pengumuman` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `siswa` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tahun_ajaran` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;

