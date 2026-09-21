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

    /** Detail biodata & nilai siswa — read-only monitoring */
    public function siswaDetail() {
        $this->guard();
        $db = getDB();
        $id = (int)($_GET['id'] ?? 0);
        $s = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s LEFT JOIN kelas_siswa ks ON ks.siswa_id=s.id LEFT JOIN kelas k ON ks.kelas_id=k.id WHERE s.id=?"); 
        $s->execute([$id]); 
        $siswa = $s->fetch();
        if(!$siswa){ redirect(base_url('index.php?page=kepsek&action=siswa')); }

        // Data Tahun Ajaran untuk dropdown filter
        $tahunAjaranList = $db->query("SELECT * FROM tahun_ajaran ORDER BY id DESC")->fetchAll();

        // Parameter filter dari GET
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
        $tahunAjaranId = isset($_GET['tahun_ajaran_id']) ? (int)$_GET['tahun_ajaran_id'] : 0;

        if (!isset($_GET['tahun_ajaran_id']) && !empty($tahunAjaranList)) {
            foreach ($tahunAjaranList as $ta) {
                if (($ta['status'] ?? '') === 'aktif') {
                    $tahunAjaranId = (int)$ta['id'];
                    break;
                }
            }
            if ($tahunAjaranId === 0 && !empty($tahunAjaranList)) {
                $tahunAjaranId = (int)$tahunAjaranList[0]['id'];
            }
        }

        // Query data nilai siswa untuk monitoring Kepsek
        $sql = "SELECT n.*, m.nama_mapel, k.nama_kelas, g.nama AS guru_nama 
                FROM nilai n 
                JOIN mapel m ON n.mapel_id = m.id 
                LEFT JOIN kelas k ON n.kelas_id = k.id 
                LEFT JOIN guru g ON n.guru_id = g.id 
                WHERE n.siswa_id = ?";
        $params = [$id];

        if ($bulan > 0) {
            $sql .= " AND n.bulan = ?";
            $params[] = $bulan;
        }
        if ($tahunAjaranId > 0) {
            $sql .= " AND n.tahun_ajaran_id = ?";
            $params[] = $tahunAjaranId;
        }

        $sql .= " ORDER BY m.nama_mapel ASC";
        $st = $db->prepare($sql);
        $st->execute($params);
        $nilaiList = $st->fetchAll();

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
        $p = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 50;
        $offset = ($p - 1) * $perPage;

        $whereSql = " WHERE MONTH(a.tanggal)=? AND YEAR(a.tanggal)=?";
        $params = [$bulan, $tahun];
        if ($guruId) { $whereSql .= " AND a.guru_id=?"; $params[] = $guruId; }

        $ringSql = "SELECT 
                        SUM(CASE WHEN a.status='Hadir' THEN 1 ELSE 0 END) as Hadir,
                        SUM(CASE WHEN a.status='Izin' THEN 1 ELSE 0 END) as Izin,
                        SUM(CASE WHEN a.status='Sakit' THEN 1 ELSE 0 END) as Sakit,
                        SUM(CASE WHEN a.status='Alpa' THEN 1 ELSE 0 END) as Alpa,
                        COUNT(a.id) as total
                    FROM absensi_guru a" . $whereSql;
        $stRing = $db->prepare($ringSql);
        $stRing->execute($params);
        $ringRow = $stRing->fetch();
        $ring = [
            'Hadir' => (int)($ringRow['Hadir'] ?? 0),
            'Izin' => (int)($ringRow['Izin'] ?? 0),
            'Sakit' => (int)($ringRow['Sakit'] ?? 0),
            'Alpa' => (int)($ringRow['Alpa'] ?? 0),
        ];
        $totalRecords = (int)($ringRow['total'] ?? 0);
        $totalPages = max(1, ceil($totalRecords / $perPage));

        $sql = "SELECT a.*, g.nama FROM absensi_guru a JOIN guru g ON a.guru_id=g.id" . $whereSql . " ORDER BY a.tanggal DESC, g.nama LIMIT $perPage OFFSET $offset";
        $st = $db->prepare($sql); $st->execute($params); $data = $st->fetchAll();

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

    /** Detail Kelas (Daftar Siswa di Kelas) — read-only monitoring Kepsek */
    public function kelasDetail($id = null) {
        $this->guard();
        $db = getDB();
        $id = (int)($id ?? $_GET['id'] ?? 0);
        $kelas = $db->prepare("SELECT k.*, g.nama as wali_nama, ta.tahun_ajaran FROM kelas k LEFT JOIN guru g ON k.wali_kelas_id=g.id LEFT JOIN tahun_ajaran ta ON k.tahun_ajaran_id=ta.id WHERE k.id=?");
        $kelas->execute([$id]);
        $kelasInfo = $kelas->fetch();

        if (!$kelasInfo) {
            redirect(base_url('index.php?page=kepsek&action=kelas'));
        }

        $siswaList = $db->prepare("SELECT s.* FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id WHERE ks.kelas_id=? ORDER BY s.nama");
        $siswaList->execute([$id]);
        $siswaKelas = $siswaList->fetchAll();

        $pageTitle = 'Detail Kelas - ' . ($kelasInfo['nama_kelas'] ?? '');
        require VIEW_PATH . '/kepsek/kelas-detail.php';
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

    /** Rekap Absensi Siswa — filter tanggal/kelas/mapel/bulan/tahun (read-only Kepsek) */
    public function absensi() {
        $this->guard();
        $db = getDB();
        $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
        $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
        $kelasId = isset($_GET['kelas_id']) ? (int)$_GET['kelas_id'] : 0;
        $mapelId = isset($_GET['mapel_id']) ? (int)$_GET['mapel_id'] : 0;
        $siswaId = isset($_GET['siswa_id']) ? (int)$_GET['siswa_id'] : 0;
        $tglAwal = $_GET['tgl_awal'] ?? '';
        $tglAkhir = $_GET['tgl_akhir'] ?? '';

        $p = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 50;
        $offset = ($p - 1) * $perPage;

        $kelasList = $db->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();
        $mapelList = $db->query("SELECT id, nama_mapel FROM mapel ORDER BY nama_mapel")->fetchAll();

        // Mode 1: Single Student Detail View
        $detailSiswa = null;
        $detailLogs = [];
        if ($siswaId > 0) {
            $stS = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s LEFT JOIN kelas_siswa ks ON s.id=ks.siswa_id LEFT JOIN kelas k ON ks.kelas_id=k.id WHERE s.id=?");
            $stS->execute([$siswaId]);
            $detailSiswa = $stS->fetch();

            $sqlLogs = "SELECT a.*, m.nama_mapel, g.nama as guru_nama 
                        FROM absensi_siswa a 
                        LEFT JOIN mapel m ON a.mapel_id=m.id 
                        LEFT JOIN guru g ON a.guru_id=g.id 
                        WHERE a.siswa_id=?";
            $paramsLogs = [$siswaId];
            if ($bulan > 0) {
                $sqlLogs .= " AND MONTH(a.tanggal)=?";
                $paramsLogs[] = $bulan;
            }
            if ($tahun > 0) {
                $sqlLogs .= " AND YEAR(a.tanggal)=?";
                $paramsLogs[] = $tahun;
            }
            if ($mapelId > 0) {
                $sqlLogs .= " AND a.mapel_id=?";
                $paramsLogs[] = $mapelId;
            }
            $sqlLogs .= " ORDER BY a.tanggal DESC, m.nama_mapel ASC";
            $stL = $db->prepare($sqlLogs);
            $stL->execute($paramsLogs);
            $detailLogs = $stL->fetchAll();
        }

        // Where conditions
        $whereSql = " WHERE 1=1";
        $params = [];

        if ($tglAwal && $tglAkhir) {
            $whereSql .= " AND a.tanggal BETWEEN ? AND ?";
            $params[] = $tglAwal;
            $params[] = $tglAkhir;
        } elseif ($bulan > 0 && $tahun > 0) {
            $whereSql .= " AND MONTH(a.tanggal)=? AND YEAR(a.tanggal)=?";
            $params[] = $bulan;
            $params[] = $tahun;
        }

        if ($kelasId > 0) { $whereSql .= " AND a.kelas_id=?"; $params[] = $kelasId; }
        if ($mapelId > 0) { $whereSql .= " AND a.mapel_id=?"; $params[] = $mapelId; }

        // Ringkasan status via SQL agregat (super cepat)
        $ringSql = "SELECT 
                        SUM(CASE WHEN a.status='Hadir' THEN 1 ELSE 0 END) as Hadir,
                        SUM(CASE WHEN a.status='Izin' THEN 1 ELSE 0 END) as Izin,
                        SUM(CASE WHEN a.status='Sakit' THEN 1 ELSE 0 END) as Sakit,
                        SUM(CASE WHEN a.status='Alpa' THEN 1 ELSE 0 END) as Alpa,
                        COUNT(a.id) as total
                    FROM absensi_siswa a" . $whereSql;
        $stRing = $db->prepare($ringSql);
        $stRing->execute($params);
        $ringRow = $stRing->fetch();
        $ring = [
            'Hadir' => (int)($ringRow['Hadir'] ?? 0),
            'Izin' => (int)($ringRow['Izin'] ?? 0),
            'Sakit' => (int)($ringRow['Sakit'] ?? 0),
            'Alpa' => (int)($ringRow['Alpa'] ?? 0),
        ];
        $totalRecords = (int)($ringRow['total'] ?? 0);
        $totalPages = max(1, ceil($totalRecords / $perPage));

        // Query data terpaginasi (50 baris per halaman)
        $sql = "SELECT a.tanggal, s.id as siswa_id, s.nama AS siswa_nama, s.nisn, k.nama_kelas, m.nama_mapel, a.status
                FROM absensi_siswa a
                LEFT JOIN siswa s ON a.siswa_id=s.id
                LEFT JOIN kelas k ON a.kelas_id=k.id
                LEFT JOIN mapel m ON a.mapel_id=m.id"
                . $whereSql .
                " ORDER BY a.tanggal DESC, s.nama ASC LIMIT $perPage OFFSET $offset";
        $s = $db->prepare($sql); 
        $s->execute($params);
        $data = $s->fetchAll();

        $pageTitle = 'Rekap Absensi Siswa';
        require VIEW_PATH.'/kepsek/absensi.php';
    }

    /** Rekap Nilai — Dihapus & dialihkan ke Data Siswa karena nilai siswa dapat dilihat langsung di biodata siswa */
    public function nilai() {
        $this->guard();
        redirect(base_url('index.php?page=kepsek&action=siswa'));
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
