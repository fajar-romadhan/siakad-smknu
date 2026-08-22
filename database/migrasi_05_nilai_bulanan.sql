-- ============================================================
-- MIGRASI 05: Nilai Bulanan
-- Jalankan di phpMyAdmin atau MySQL client.
-- Aman dijalankan berulang kali.
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

CALL add_col_nilai('capaian_kompetensi', "TEXT DEFAULT NULL AFTER `nilai_akhir`");
CALL add_col_nilai('bulan', "TINYINT(2) NULL DEFAULT NULL AFTER `nilai_akhir`");
CALL add_col_nilai('tahun', "YEAR(4) NULL DEFAULT NULL AFTER `bulan`");
CALL add_col_nilai('tahun_ajaran_id', "INT(11) NULL DEFAULT NULL AFTER `tahun`");

UPDATE `nilai`
SET `bulan` = COALESCE(NULLIF(`bulan`, ''), MONTH(COALESCE(`created_at`, `updated_at`, NOW()))),
    `tahun` = COALESCE(NULLIF(`tahun`, ''), YEAR(COALESCE(`created_at`, `updated_at`, NOW()))),
    `tahun_ajaran_id` = COALESCE(`tahun_ajaran_id`, (SELECT k.tahun_ajaran_id FROM kelas k WHERE k.id = `nilai`.`kelas_id` LIMIT 1))
WHERE `bulan` IS NULL OR `tahun` IS NULL OR `tahun_ajaran_id` IS NULL;

DELETE n1 FROM `nilai` n1
JOIN `nilai` n2
  ON n1.siswa_id = n2.siswa_id
 AND n1.mapel_id = n2.mapel_id
 AND n1.kelas_id = n2.kelas_id
 AND n1.bulan = n2.bulan
 AND n1.tahun = n2.tahun
 AND COALESCE(n1.tahun_ajaran_id, 0) = COALESCE(n2.tahun_ajaran_id, 0)
 AND n1.id < n2.id;

ALTER TABLE `nilai`
  DROP INDEX IF EXISTS `unique_nilai`,
  ADD UNIQUE KEY `unique_nilai_period` (`siswa_id`,`mapel_id`,`kelas_id`,`bulan`,`tahun`,`tahun_ajaran_id`);

DROP PROCEDURE IF EXISTS add_col_nilai;
