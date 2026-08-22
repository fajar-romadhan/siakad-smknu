-- ============================================================
-- MIGRASI 06: Hapus kolom NIS dari tabel guru
-- Jalankan di phpMyAdmin -> database siakad_smknu -> Import.
-- Aman dijalankan ulang.
-- ============================================================

DELIMITER $$
DROP PROCEDURE IF EXISTS drop_col_guru_nis $$
CREATE PROCEDURE drop_col_guru_nis()
BEGIN
    IF EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'guru'
          AND COLUMN_NAME = 'nis'
    ) THEN
        ALTER TABLE `guru` DROP COLUMN `nis`;
    END IF;
END $$
DELIMITER ;

CALL drop_col_guru_nis();
DROP PROCEDURE IF EXISTS drop_col_guru_nis;
