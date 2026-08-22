-- ============================================================
-- MIGRASI 02: Kolom Biodata Siswa Lengkap + Data Ayah/Ibu
-- Jalankan di phpMyAdmin -> database siakad_smknu -> Import file ini.
-- Aman dijalankan ulang.
-- ============================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS add_col_siswa $$
CREATE PROCEDURE add_col_siswa(IN colName VARCHAR(64), IN colDef VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'siswa' AND COLUMN_NAME = colName
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `siswa` ADD COLUMN `', colName, '` ', colDef);
        PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
    END IF;
END $$

DELIMITER ;

-- Identitas & alamat rinci
CALL add_col_siswa('nipd',             "VARCHAR(20) DEFAULT NULL AFTER `nisn`");
CALL add_col_siswa('nik',              "VARCHAR(20) DEFAULT NULL AFTER `nipd`");
CALL add_col_siswa('rt',               "VARCHAR(5) DEFAULT NULL AFTER `alamat`");
CALL add_col_siswa('rw',               "VARCHAR(5) DEFAULT NULL AFTER `rt`");
CALL add_col_siswa('kelurahan',        "VARCHAR(100) DEFAULT NULL AFTER `rw`");
CALL add_col_siswa('kecamatan',        "VARCHAR(100) DEFAULT NULL AFTER `kelurahan`");
CALL add_col_siswa('kode_pos',         "VARCHAR(10) DEFAULT NULL AFTER `kecamatan`");
-- Data tambahan
CALL add_col_siswa('penerima_kip',     "ENUM('ya','tidak') DEFAULT 'tidak' AFTER `no_hp`");
CALL add_col_siswa('nomor_kip',        "VARCHAR(30) DEFAULT NULL AFTER `penerima_kip`");
CALL add_col_siswa('sekolah_asal',     "VARCHAR(150) DEFAULT NULL AFTER `nomor_kip`");
CALL add_col_siswa('jenis_tinggal',    "VARCHAR(50) DEFAULT NULL AFTER `sekolah_asal`");
CALL add_col_siswa('anak_ke',          "INT(11) DEFAULT NULL AFTER `jenis_tinggal`");
CALL add_col_siswa('jml_saudara',      "INT(11) DEFAULT NULL AFTER `anak_ke`");
CALL add_col_siswa('jarak_sekolah_km', "DECIMAL(5,2) DEFAULT NULL AFTER `jml_saudara`");
-- Data Ayah
CALL add_col_siswa('ayah_nama',        "VARCHAR(100) DEFAULT NULL AFTER `jarak_sekolah_km`");
CALL add_col_siswa('ayah_nik',         "VARCHAR(20) DEFAULT NULL AFTER `ayah_nama`");
CALL add_col_siswa('ayah_tahun_lahir', "YEAR DEFAULT NULL AFTER `ayah_nik`");
CALL add_col_siswa('ayah_pendidikan',  "VARCHAR(20) DEFAULT NULL AFTER `ayah_tahun_lahir`");
CALL add_col_siswa('ayah_pekerjaan',   "VARCHAR(100) DEFAULT NULL AFTER `ayah_pendidikan`");
-- Data Ibu
CALL add_col_siswa('ibu_nama',         "VARCHAR(100) DEFAULT NULL AFTER `ayah_pekerjaan`");
CALL add_col_siswa('ibu_nik',          "VARCHAR(20) DEFAULT NULL AFTER `ibu_nama`");
CALL add_col_siswa('ibu_tahun_lahir',  "YEAR DEFAULT NULL AFTER `ibu_nik`");
CALL add_col_siswa('ibu_pendidikan',   "VARCHAR(20) DEFAULT NULL AFTER `ibu_tahun_lahir`");
CALL add_col_siswa('ibu_pekerjaan',    "VARCHAR(100) DEFAULT NULL AFTER `ibu_pendidikan`");

DROP PROCEDURE IF EXISTS add_col_siswa;

-- Catatan: kolom lama `wali_siswa` dibiarkan (kompatibilitas). Data ayah/ibu kini terpisah.
