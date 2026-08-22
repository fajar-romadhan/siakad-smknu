-- ============================================================
-- MIGRASI 04: Validasi Absensi Guru
-- Jalankan di phpMyAdmin -> database siakad_smknu -> Import.
-- Aman dijalankan ulang.
-- ============================================================

DELIMITER $$
DROP PROCEDURE IF EXISTS add_col_absensi_guru $$
CREATE PROCEDURE add_col_absensi_guru(IN colName VARCHAR(64), IN colDef VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi_guru' AND COLUMN_NAME = colName
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `absensi_guru` ADD COLUMN `', colName, '` ', colDef);
        PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
    END IF;
END $$
DELIMITER ;

CALL add_col_absensi_guru('is_validated', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`");
CALL add_col_absensi_guru('validated_by', "INT(11) DEFAULT NULL AFTER `is_validated`");
CALL add_col_absensi_guru('validated_at', "DATETIME DEFAULT NULL AFTER `validated_by`");

DROP PROCEDURE IF EXISTS add_col_absensi_guru;
