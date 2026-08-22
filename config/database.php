<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'siakads4_siakad');
define('DB_USER', 'siakads4_siakads4_user');
define('DB_PASS', '@pi7*}M$peO_;7Yo');

function ensureNilaiMonthlySchema(PDO $db): void {
    try {
        $db->query("SELECT 1 FROM `nilai` LIMIT 1");
    } catch (PDOException $e) {
        return;
    }

    $fetchColumns = function(string $tableName) use ($db): array {
        $cols = [];
        try {
            $colSt = $db->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$tableName'");
            foreach ($colSt->fetchAll(PDO::FETCH_COLUMN) as $name) {
                $cols[] = $name;
            }
        } catch (PDOException $e) {}
        return $cols;
    };

    $addColumn = function(string $table, string $column, string $definition) use ($db, $fetchColumns): void {
        $cols = $fetchColumns($table);
        if (!in_array($column, $cols, true)) {
            try {
                $db->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            } catch (PDOException $e) {}
        }
    };

    // Tabel nilai
    $addColumn('nilai', 'capaian_kompetensi', 'TEXT DEFAULT NULL AFTER `nilai_akhir`');
    $addColumn('nilai', 'bulan', 'TINYINT(2) NULL DEFAULT NULL AFTER `nilai_akhir`');
    $addColumn('nilai', 'tahun', 'YEAR(4) NULL DEFAULT NULL AFTER `bulan`');
    $addColumn('nilai', 'tahun_ajaran_id', 'INT(11) NULL DEFAULT NULL AFTER `tahun`');
    $addColumn('nilai', 'is_validated', 'TINYINT(1) NOT NULL DEFAULT 0 AFTER `tahun_ajaran_id`');
    $addColumn('nilai', 'validated_at', 'DATETIME DEFAULT NULL AFTER `is_validated`');

    // Tabel absensi_guru
    $addColumn('absensi_guru', 'is_validated', 'TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`');
    $addColumn('absensi_guru', 'validated_by', 'INT(11) DEFAULT NULL AFTER `is_validated`');
    $addColumn('absensi_guru', 'validated_at', 'DATETIME DEFAULT NULL AFTER `validated_by`');

    // Tabel guru
    $addColumn('guru', 'nuptk', 'VARCHAR(20) DEFAULT NULL AFTER `foto`');
    $addColumn('guru', 'nik', 'VARCHAR(20) DEFAULT NULL AFTER `nuptk`');
    $addColumn('guru', 'nip', 'VARCHAR(30) DEFAULT NULL AFTER `nik`');
    $addColumn('guru', 'status_kepegawaian', 'VARCHAR(50) DEFAULT NULL AFTER `nip`');
    $addColumn('guru', 'jenis_ptk', 'VARCHAR(50) DEFAULT NULL AFTER `status_kepegawaian`');
    $addColumn('guru', 'gelar', 'VARCHAR(50) DEFAULT NULL AFTER `jenis_ptk`');
    $addColumn('guru', 'jenjang_pendidikan', 'VARCHAR(20) DEFAULT NULL AFTER `gelar`');
    $addColumn('guru', 'jurusan_prodi', 'VARCHAR(100) DEFAULT NULL AFTER `jenjang_pendidikan`');
    $addColumn('guru', 'tmt_kerja', 'DATE DEFAULT NULL AFTER `jurusan_prodi`');
    $addColumn('guru', 'tugas_tambahan', 'VARCHAR(100) DEFAULT NULL AFTER `tmt_kerja`');

    // Tabel siswa
    $addColumn('siswa', 'nipd', 'VARCHAR(20) DEFAULT NULL AFTER `nisn`');
    $addColumn('siswa', 'nik', 'VARCHAR(20) DEFAULT NULL AFTER `nipd`');
    $addColumn('siswa', 'rt', 'VARCHAR(5) DEFAULT NULL AFTER `alamat`');
    $addColumn('siswa', 'rw', 'VARCHAR(5) DEFAULT NULL AFTER `rt`');
    $addColumn('siswa', 'kelurahan', 'VARCHAR(100) DEFAULT NULL AFTER `rw`');
    $addColumn('siswa', 'kecamatan', 'VARCHAR(100) DEFAULT NULL AFTER `kelurahan`');
    $addColumn('siswa', 'kode_pos', 'VARCHAR(10) DEFAULT NULL AFTER `kecamatan`');
    $addColumn('siswa', 'penerima_kip', "ENUM('ya','tidak') DEFAULT 'tidak' AFTER `no_hp`");
    $addColumn('siswa', 'nomor_kip', 'VARCHAR(30) DEFAULT NULL AFTER `penerima_kip`');
    $addColumn('siswa', 'sekolah_asal', 'VARCHAR(150) DEFAULT NULL AFTER `nomor_kip`');
    $addColumn('siswa', 'jenis_tinggal', 'VARCHAR(50) DEFAULT NULL AFTER `sekolah_asal`');
    $addColumn('siswa', 'anak_ke', 'INT(11) DEFAULT NULL AFTER `jenis_tinggal`');
    $addColumn('siswa', 'jml_saudara', 'INT(11) DEFAULT NULL AFTER `anak_ke`');
    $addColumn('siswa', 'jarak_sekolah_km', 'DECIMAL(5,2) DEFAULT NULL AFTER `jml_saudara`');
    $addColumn('siswa', 'ayah_nama', 'VARCHAR(100) DEFAULT NULL AFTER `jarak_sekolah_km`');
    $addColumn('siswa', 'ayah_nik', 'VARCHAR(20) DEFAULT NULL AFTER `ayah_nama`');
    $addColumn('siswa', 'ayah_tahun_lahir', 'YEAR(4) DEFAULT NULL AFTER `ayah_nik`');
    $addColumn('siswa', 'ayah_pendidikan', 'VARCHAR(20) DEFAULT NULL AFTER `ayah_tahun_lahir`');
    $addColumn('siswa', 'ayah_pekerjaan', 'VARCHAR(100) DEFAULT NULL AFTER `ayah_pendidikan`');
    $addColumn('siswa', 'ibu_nama', 'VARCHAR(100) DEFAULT NULL AFTER `ayah_pekerjaan`');
    $addColumn('siswa', 'ibu_nik', 'VARCHAR(20) DEFAULT NULL AFTER `ibu_nama`');
    $addColumn('siswa', 'ibu_tahun_lahir', 'YEAR(4) DEFAULT NULL AFTER `ibu_nik`');
    $addColumn('siswa', 'ibu_pendidikan', 'VARCHAR(20) DEFAULT NULL AFTER `ibu_tahun_lahir`');
    $addColumn('siswa', 'ibu_pekerjaan', 'VARCHAR(100) DEFAULT NULL AFTER `ibu_pendidikan`');

    try {
        $db->exec("UPDATE `nilai` SET `bulan` = COALESCE(NULLIF(`bulan`, ''), MONTH(COALESCE(`created_at`, `updated_at`, NOW()))), `tahun` = COALESCE(NULLIF(`tahun`, ''), YEAR(COALESCE(`created_at`, `updated_at`, NOW()))), `tahun_ajaran_id` = COALESCE(`tahun_ajaran_id`, (SELECT k.tahun_ajaran_id FROM kelas k WHERE k.id = `nilai`.`kelas_id` LIMIT 1)) WHERE `bulan` IS NULL OR `tahun` IS NULL OR `tahun_ajaran_id` IS NULL");
        $db->exec("DELETE n1 FROM `nilai` n1 JOIN `nilai` n2 ON n1.siswa_id = n2.siswa_id AND n1.mapel_id = n2.mapel_id AND n1.kelas_id = n2.kelas_id AND n1.bulan = n2.bulan AND n1.tahun = n2.tahun AND COALESCE(n1.tahun_ajaran_id, 0) = COALESCE(n2.tahun_ajaran_id, 0) AND n1.id < n2.id");
    } catch (PDOException $e) {}

    try {
        $db->exec("ALTER TABLE `nilai` DROP INDEX IF EXISTS `unique_nilai`");
    } catch (PDOException $e) {}

    try {
        $db->exec("ALTER TABLE `nilai` DROP INDEX IF EXISTS `unique_nilai_period`");
    } catch (PDOException $e) {}

    // Ensure AUTO_INCREMENT on primary key 'id' for all tables
    $autoIncTables = ['nilai', 'guru', 'siswa', 'absensi_guru', 'absensi_siswa', 'catatan', 'jadwal', 'kelas', 'kelas_siswa', 'mapel', 'pengumuman', 'tahun_ajaran', 'users'];
    foreach ($autoIncTables as $tbl) {
        try {
            $colInfo = $db->query("SELECT EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$tbl' AND COLUMN_NAME = 'id'")->fetch();
            if ($colInfo && strpos(strtolower($colInfo['EXTRA'] ?? ''), 'auto_increment') === false) {
                $db->exec("SET FOREIGN_KEY_CHECKS = 0");
                $db->exec("ALTER TABLE `$tbl` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT");
                $db->exec("SET FOREIGN_KEY_CHECKS = 1");
            }
        } catch (PDOException $e) {
            try { $db->exec("SET FOREIGN_KEY_CHECKS = 1"); } catch (PDOException $ex) {}
        }
    }

    try {
        $indexCheck = $db->query("SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND INDEX_NAME = 'unique_nilai_period'");
        if ((int)$indexCheck->fetchColumn() === 0) {
            $db->exec("ALTER TABLE `nilai` ADD UNIQUE KEY `unique_nilai_period` (`siswa_id`,`kelas_id`,`mapel_id`,`bulan`,`tahun`,`tahun_ajaran_id`)");
        }
    } catch (PDOException $e) {}
}

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            ensureNilaiMonthlySchema($pdo);
        } catch (PDOException $e) { die("DB Error: ".$e->getMessage()); }
    }
    return $pdo;
}
