-- ============================================================
-- MIGRASI 03: Validasi Nilai (untuk lock nilai & cetak raport)
-- Jalankan di phpMyAdmin -> database siakad_smknu -> Import.
-- Aman dijalankan ulang.
-- ============================================================

DELIMITER $$
DROP PROCEDURE IF EXISTS add_col_nilai $$
CREATE PROCEDURE add_col_nilai(IN colName VARCHAR(64), IN colDef VARCHAR(255))
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = colName
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `nilai` ADD COLUMN `', colName, '` ', colDef);
        PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
    END IF;
END $$
DELIMITER ;

-- is_validated: 0 = belum divalidasi, 1 = sudah divalidasi (masuk raport)
CALL add_col_nilai('is_validated', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `nilai_akhir`");
CALL add_col_nilai('validated_at', "DATETIME DEFAULT NULL AFTER `is_validated`");

DROP PROCEDURE IF EXISTS add_col_nilai;

-- Set nilai lama yang sudah lengkap sebagai tervalidasi (opsional, agar data awal langsung tampil di raport)
UPDATE `nilai` SET `is_validated`=1, `validated_at`=NOW()
WHERE `nilai_akhir` IS NOT NULL AND `is_validated`=0;
