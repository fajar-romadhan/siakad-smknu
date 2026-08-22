-- ============================================================
-- MIGRASI 01: Kolom Biodata Guru Lengkap (kompatibel semua versi MySQL/MariaDB)
-- Jalankan di phpMyAdmin -> pilih database project kamu -> tab SQL -> Go.
-- Aman dijalankan ulang (cek dulu ke information_schema).
-- ============================================================

DELIMITER $$

DROP PROCEDURE IF EXISTS add_col_guru $$
CREATE PROCEDURE add_col_guru(IN colName VARCHAR(64), IN colDef VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'guru'
          AND COLUMN_NAME = colName
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `guru` ADD COLUMN `', colName, '` ', colDef);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END $$

DELIMITER ;

CALL add_col_guru('nuptk',              "VARCHAR(20) DEFAULT NULL");
CALL add_col_guru('nik',                "VARCHAR(20) DEFAULT NULL");
CALL add_col_guru('nip',                "VARCHAR(30) DEFAULT NULL");
CALL add_col_guru('status_kepegawaian', "VARCHAR(50) DEFAULT NULL");
CALL add_col_guru('jenis_ptk',          "VARCHAR(50) DEFAULT NULL");
CALL add_col_guru('gelar',              "VARCHAR(50) DEFAULT NULL");
CALL add_col_guru('jenjang_pendidikan', "VARCHAR(20) DEFAULT NULL");
CALL add_col_guru('jurusan_prodi',      "VARCHAR(100) DEFAULT NULL");
CALL add_col_guru('tmt_kerja',          "DATE DEFAULT NULL");
CALL add_col_guru('tugas_tambahan',     "VARCHAR(100) DEFAULT NULL");

DROP PROCEDURE IF EXISTS add_col_guru;

-- Catatan: NUPTK, NIP, tugas_tambahan boleh dikosongkan (nullable).
