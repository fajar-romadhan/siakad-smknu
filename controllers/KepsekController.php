<?php
/**
 * KepsekController — Read-only monitoring untuk Kepala Sekolah
 * Semua method: hanya fetch & render, tidak ada create/update/delete.
 */
class KepsekController {

    private function guard() {
        Auth::requireRole('kepala_sekolah');
    }

    /** Dashboard: statistik ringkas + kehadiran hari ini + distribusi nilai */
    public function dashboard() {
        $this->guard();
        $db = getDB();

        // Statistik utama
        $stat = [
            'guru'   => (int)$db->query("SELECT COUNT(*) FROM guru")->fetchColumn(),
            'siswa'  => (int)$db->query("SELECT COUNT(*) FROM siswa")->fetchColumn(),
            'kelas'  => (int)$db->query("SELECT COUNT(*) FROM kelas")->fetchColumn(),
            'mapel'  => (int)$db->query("SELECT COUNT(*) FROM mapel")->fetchColumn(),
        ];

        // Kehadiran siswa hari ini
        $today = date('Y-m-d');
        $absHari = $db->prepare("SELECT status, COUNT(*) as jml FROM absensi_siswa WHERE tanggal=? GROUP BY status");
        $absHari->execute([$today]);
        $absensiHariIni = ['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        foreach ($absHari->fetchAll() as $r) {
            if (isset($absensiHariIni[$r['status']])) $absensiHariIni[$r['status']] = (int)$r['jml'];
        }
        $totalAbsHari = array_sum($absensiHariIni);
        $persenHadir = $totalAbsHari ? round(($absensiHariIni['Hadir']/$totalAbsHari)*100, 1) : 0;

        // Rata-rata nilai akhir keseluruhan
        $avgNilai = (float)$db->query("SELECT AVG(nilai_akhir) FROM nilai WHERE nilai_akhir IS NOT NULL")->fetchColumn();
        $avgNilai = round($avgNilai, 2);

        // Distribusi nilai (berdasarkan nilai_akhir)
        $distNilai = [
            'A' => (int)$db->query("SELECT COUNT(*) FROM nilai WHERE nilai_akhir>=85")->fetchColumn(),
            'B' => (int)$db->query("SELECT COUNT(*) FROM nilai WHERE nilai_akhir>=75 AND nilai_akhir<85")->fetchColumn(),
            'C' => (int)$db->query("SELECT COUNT(*) FROM nilai WHERE nilai_akhir>=65 AND nilai_akhir<75")->fetchColumn(),
            'D' => (int)$db->query("SELECT COUNT(*) FROM nilai WHERE nilai_akhir<65 AND nilai_akhir IS NOT NULL")->fetchColumn(),
        ];

        // Absensi guru hari ini
        $absGuru = $db->prepare("SELECT status, COUNT(*) as jml FROM absensi_guru WHERE tanggal=? GROUP BY status");
        $absGuru->execute([$today]);
        $absGuruHari = ['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        foreach ($absGuru->fetchAll() as $r) {
            if (isset($absGuruHari[$r['status']])) $absGuruHari[$r['status']] = (int)$r['jml'];
        }

        // Pengumuman terbaru (hanya yang aktif atau terjadwal <= hari ini)
        $pengumuman = $db->query("
            SELECT * FROM pengumuman
            WHERE status IN ('aktif','terjadwal') AND tanggal_publish <= CURDATE()
            ORDER BY tanggal_publish DESC LIMIT 5
        ")->fetchAll();

        $pageTitle = 'Dashboard Kepala Sekolah';
        require VIEW_PATH.'/kepsek/dashboard.php';
    }

    /** Data Guru — read-only */
    public function guru() {
        $this->guard();
        $q = trim($_GET['q'] ?? '');
        $db = getDB();
        if ($q !== '') {
            $s = $db->prepare("SELECT g.*, u.username FROM guru g LEFT JOIN users u ON g.user_id=u.id WHERE g.nama LIKE ? OR g.kode_guru LIKE ? ORDER BY g.nama");
            $s->execute(["%$q%", "%$q%"]);
        } else {
            $s = $db->query("SELECT g.*, u.username FROM guru g LEFT JOIN users u ON g.user_id=u.id ORDER BY g.nama");
        }
        $data = $s->fetchAll();
        $pageTitle = 'Data Guru';
        require VIEW_PATH.'/kepsek/guru.php';
    }

    /** Detail biodata guru — read-only */
    public function guruDetail() {
        $this->guard();
        $db = getDB();
        $id = (int)($_GET['id'] ?? 0);
        $g = $db->prepare("SELECT * FROM guru WHERE id=?"); $g->execute([$id]); $guru = $g->fetch();
        if(!$guru){ redirect(base_url('index.php?page=kepsek&action=guru')); }
        $pageTitle = 'Detail Guru';
        require VIEW_PATH.'/kepsek/guru-detail.php';
    }

    /** Data Siswa — read-only */
    public function siswa() {
        $this->guard();
        $q = trim($_GET['q'] ?? '');
        $db = getDB();
        if ($q !== '') {
            $s = $db->prepare("SELECT s.*, u.username, k.nama_kelas FROM siswa s LEFT JOIN users u ON s.user_id=u.id LEFT JOIN kelas_siswa ks ON ks.siswa_id=s.id LEFT JOIN kelas k ON ks.kelas_id=k.id WHERE s.nama LIKE ? OR s.nisn LIKE ? OR s.kode_siswa LIKE ? ORDER BY s.nama");
            $s->execute(["%$q%", "%$q%", "%$q%"]);
        } else {
            $s = $db->query("SELECT s.*, u.username, k.nama_kelas FROM siswa s LEFT JOIN users u ON s.user_id=u.id LEFT JOIN kelas_siswa ks ON ks.siswa_id=s.id LEFT JOIN kelas k ON ks.kelas_id=k.id ORDER BY s.nama");
        }
        $data = $s->fetchAll();
        $pageTitle = 'Data Siswa';
        require VIEW_PATH.'/kepsek/siswa.php';
    }

    /** Detail biodata siswa — read-only */
    public function siswaDetail() {
        $this->guard();
        $db = getDB();
        $id = (int)($_GET['id'] ?? 0);
        $s = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s LEFT JOIN kelas_siswa ks ON ks.siswa_id=s.id LEFT JOIN kelas k ON ks.kelas_id=k.id WHERE s.id=?"); $s->execute([$id]); $siswa = $s->fetch();
        if(!$siswa){ redirect(base_url('index.php?page=kepsek&action=siswa')); }
        $pageTitle = 'Detail Siswa';
        require VIEW_PATH.'/kepsek/siswa-detail.php';
    }

    /** Rekap Absensi Guru — filter nama guru + periode (read-only) */
    public function absensiGuru() {
        $this->guard();
        $db = getDB();
        $guruList = $db->query("SELECT id,nama FROM guru ORDER BY nama")->fetchAll();
        $guruId = isset($_GET['guru_id']) ? (int)$_GET['guru_id'] : 0;
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $sql = "SELECT a.*, g.nama FROM absensi_guru a JOIN guru g ON a.guru_id=g.id WHERE MONTH(a.tanggal)=? AND YEAR(a.tanggal)=?";
        $params = [$bulan, $tahun];
        if ($guruId) { $sql .= " AND a.guru_id=?"; $params[] = $guruId; }
        $sql .= " ORDER BY a.tanggal DESC, g.nama";
        $st = $db->prepare($sql); $st->execute($params); $data = $st->fetchAll();
        $ring = ['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        foreach ($data as $r) if (isset($ring[$r['status']])) $ring[$r['status']]++;
        $pageTitle = 'Rekap Absensi Guru';
        require VIEW_PATH.'/kepsek/absensi-guru.php';
    }

    /** Data Kelas + jumlah siswa & wali kelas */
    public function kelas() {
        $this->guard();
        $db = getDB();
        $data = $db->query("
            SELECT k.*, g.nama AS wali_nama,
                (SELECT COUNT(*) FROM kelas_siswa WHERE kelas_id=k.id) AS jml_siswa
            FROM kelas k LEFT JOIN guru g ON k.wali_kelas_id=g.id
            ORDER BY k.nama_kelas
        ")->fetchAll();
        $pageTitle = 'Data Kelas';
        require VIEW_PATH.'/kepsek/kelas.php';
    }

    /** Jadwal semua kelas — pilih kelas dulu */
    public function jadwal() {
        $this->guard();
        $db = getDB();
        $kelasList = $db->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();
        $selectedKelas = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : ((int)($kelasList[0]['id'] ?? 0));
        $jadwal = [];
        if ($selectedKelas) {
            $s = $db->prepare("SELECT j.*, m.nama_mapel, g.nama AS guru_nama
                FROM jadwal j
                LEFT JOIN mapel m ON j.mapel_id=m.id
                LEFT JOIN guru g ON j.guru_id=g.id
                WHERE j.kelas_id=?
                ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai");
            $s->execute([$selectedKelas]);
            $jadwal = $s->fetchAll();
        }
        $pageTitle = 'Jadwal Pelajaran';
        require VIEW_PATH.'/kepsek/jadwal.php';
    }

    /** Rekap Absensi — filter tanggal/kelas (absensi_siswa sudah punya kelas_id langsung) */
    public function absensi() {
        $this->guard();
        $db = getDB();
        $tglAwal = $_GET['tgl_awal'] ?? date('Y-m-01');
        $tglAkhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
        $kelasId = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;

        $kelasList = $db->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();

        $sql = "SELECT a.tanggal, s.nama AS siswa_nama, s.nisn, k.nama_kelas, m.nama_mapel, a.status
                FROM absensi_siswa a
                LEFT JOIN siswa s ON a.siswa_id=s.id
                LEFT JOIN kelas k ON a.kelas_id=k.id
                LEFT JOIN mapel m ON a.mapel_id=m.id
                WHERE a.tanggal BETWEEN ? AND ?";
        $params = [$tglAwal, $tglAkhir];
        if ($kelasId) { $sql .= " AND a.kelas_id=?"; $params[] = $kelasId; }
        $sql .= " ORDER BY a.tanggal DESC, s.nama LIMIT 500";
        $s = $db->prepare($sql); $s->execute($params);
        $data = $s->fetchAll();

        // Ringkasan
        $ring = ['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        foreach ($data as $r) if (isset($ring[$r['status']])) $ring[$r['status']]++;

        $pageTitle = 'Rekap Absensi';
        require VIEW_PATH.'/kepsek/absensi.php';
    }

    /** Rekap Nilai — filter kelas/mapel */
    public function nilai() {
        $this->guard();
        $db = getDB();
        $kelasId = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;
        $mapelId = isset($_GET['mapel_id']) ? (int)$_GET['mapel_id'] : 0;

        $kelasList = $db->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();
        $mapelList = $db->query("SELECT id, nama_mapel FROM mapel ORDER BY nama_mapel")->fetchAll();

        $sql = "SELECT n.*, s.nama AS siswa_nama, s.nisn, k.nama_kelas, m.nama_mapel
                FROM nilai n
                LEFT JOIN siswa s ON n.siswa_id=s.id
                LEFT JOIN kelas k ON n.kelas_id=k.id
                LEFT JOIN mapel m ON n.mapel_id=m.id
                WHERE 1=1";
        $params = [];
        if ($kelasId) { $sql .= " AND n.kelas_id=?"; $params[] = $kelasId; }
        if ($mapelId) { $sql .= " AND n.mapel_id=?"; $params[] = $mapelId; }
        $sql .= " ORDER BY s.nama, m.nama_mapel LIMIT 500";
        $s = $db->prepare($sql); $s->execute($params);
        $data = $s->fetchAll();

        // Ringkasan grade (berdasarkan nilai_akhir)
        $ring = ['A'=>0,'B'=>0,'C'=>0,'D'=>0];
        $sum = 0; $cnt = 0;
        foreach ($data as $r) {
            $v = isset($r['nilai_akhir']) ? (float)$r['nilai_akhir'] : null;
            if ($v === null) continue;
            $sum += $v; $cnt++;
            if ($v >= 85) $ring['A']++;
            elseif ($v >= 75) $ring['B']++;
            elseif ($v >= 65) $ring['C']++;
            else $ring['D']++;
        }
        $avg = $cnt ? round($sum/$cnt, 2) : 0;

        $pageTitle = 'Rekap Nilai';
        require VIEW_PATH.'/kepsek/nilai.php';
    }

    /** Pengumuman — read-only */
    public function pengumuman() {
        $this->guard();
        $db = getDB();
        $data = $db->query("SELECT * FROM pengumuman ORDER BY tanggal_publish DESC LIMIT 100")->fetchAll();
        $pageTitle = 'Pengumuman';
        require VIEW_PATH.'/kepsek/pengumuman.php';
    }

    /** default index → dashboard */
    public function index() { $this->dashboard(); }
}
