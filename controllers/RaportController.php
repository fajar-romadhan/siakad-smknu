<?php
require_once BASE_PATH.'/models/KelasModel.php';
require_once BASE_PATH.'/models/SiswaModel.php';
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * RaportController — Cetak Raport (Admin)
 * Alur: pilih kelas -> pilih siswa (bisa cari) -> lihat nilai tervalidasi -> cetak PDF.
 */
class RaportController {

    /** Halaman awal Raport */
    public function index() {
        $role = Auth::role();
        $db = getDB();
        if ($role === 'admin') {
            Auth::requireRole('admin');
            $this->bulanan();
            return;
        }
        if ($role === 'siswa') {
            $siswa = (new SiswaModel())->whereOne('user_id', Auth::id());
            $kelasId = 0;
            $kelas = null;
            $history = [];
            $semesterLabel = 'Semester';
            $tahunAjaranLabel = '';
            $activeTa = $db->prepare("SELECT * FROM tahun_ajaran WHERE status='aktif' ORDER BY id DESC LIMIT 1");
            $activeTa->execute();
            $ta = $activeTa->fetch();
            if (!$ta) {
                $ta = $db->query("SELECT * FROM tahun_ajaran ORDER BY id DESC LIMIT 1")->fetch();
            }
            // If siswa record is missing (user not linked to siswa), show empty menu with message
            if (!$siswa) {
                // no siswa mapping for this user
                require VIEW_PATH.'/siswa/raport/menu.php';
                return;
            }
            if ($ta) {
                $semester = $ta['semester_aktif'] ?? 'ganjil';
                $tahunAjaranLabel = $ta['tahun_ajaran'];
                $semesterLabel = ucfirst($semester);
            }

            // Build history from actual `nilai` records for this siswa
            $st = $db->prepare("SELECT n.bulan, n.tahun, n.tahun_ajaran_id, COUNT(*) AS total_count, SUM(CASE WHEN n.is_validated=1 THEN 1 ELSE 0 END) AS validated_count, ta.tahun_ajaran FROM nilai n LEFT JOIN tahun_ajaran ta ON n.tahun_ajaran_id = ta.id WHERE n.siswa_id = ? AND n.bulan IS NOT NULL AND n.tahun IS NOT NULL GROUP BY n.tahun, n.bulan, n.tahun_ajaran_id ORDER BY n.tahun DESC, n.bulan DESC");
            $st->execute([$siswa['id']]);
            $rows = $st->fetchAll();
            foreach ($rows as $row) {
                $month = (int)($row['bulan'] ?? 0);
                $year = (int)($row['tahun'] ?? 0);
                $label = $month ? date('F', strtotime('2020-' . $month . '-01')) : '-';
                $available = ((int)$row['validated_count'] === (int)$row['total_count']) && $row['total_count'] > 0;
                $statusText = $available ? 'Siap' : 'Menunggu Validasi Guru';
                $history[] = [
                    'month' => $month,
                    'year' => $year,
                    'label' => $label,
                    'status' => $statusText,
                    'available' => $available,
                    'tahun_ajaran' => $row['tahun_ajaran'] ?? '-',
                ];
            }
            require VIEW_PATH.'/siswa/raport/menu.php';
            return;
        }
        http_response_code(403);
        die('Akses ditolak');
    }

    public function bulanan() {
        $role = Auth::role();
        if ($role === 'admin') {
            Auth::requireRole('admin');
            $db=getDB();
            $tahunAjaranList=$db->query("SELECT * FROM tahun_ajaran ORDER BY tahun_ajaran DESC")->fetchAll();
            $kelasList=$db->query("SELECT k.*,ta.tahun_ajaran FROM kelas k JOIN tahun_ajaran ta ON k.tahun_ajaran_id=ta.id ORDER BY k.nama_kelas")->fetchAll();
            $kelasId=isset($_GET['kelas_id'])?(int)$_GET['kelas_id']:0;
            $bulan=isset($_GET['bulan'])?(int)$_GET['bulan']:date('m');
            $tahun=isset($_GET['tahun'])?(int)$_GET['tahun']:date('Y');
            $search=trim($_GET['search']??'');
            $kelas=null; $siswaList=[];
            if($kelasId){
                $kelas=(new KelasModel())->find($kelasId);
                $sql="SELECT s.* FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id WHERE ks.kelas_id=?";
                $params=[$kelasId];
                if($search){ $sql.=" AND (s.nama LIKE ? OR s.nisn LIKE ?)"; $params[]="%$search%"; $params[]="%$search%"; }
                $sql.=" ORDER BY s.nama";
                $st=$db->prepare($sql); $st->execute($params); $siswaList=$st->fetchAll();
            }
            require VIEW_PATH.'/admin/raport/bulanan.php';
            return;
        }
        if ($role === 'siswa') {
            $siswa=(new SiswaModel())->whereOne('user_id',Auth::id());
            $db=getDB();
            $bulan=isset($_GET['bulan'])?(int)$_GET['bulan']:date('m');
            $tahun=isset($_GET['tahun'])?(int)$_GET['tahun']:date('Y');
            $ks=$db->prepare("SELECT ks.kelas_id,k.nama_kelas FROM kelas_siswa ks JOIN kelas k ON ks.kelas_id=k.id WHERE ks.siswa_id=? LIMIT 1");
            $ks->execute([$siswa['id']]); $ksRow=$ks->fetch();
            $nilai=[]; $adaBelumValidasi=true; $kelas=null; $wali=null; $absensi=['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
            if($ksRow){
                $kelas=(new KelasModel())->find($ksRow['kelas_id']);
                $data=$this->ambilNilai($db,$siswa['id'],$ksRow['kelas_id'],'bulanan',$bulan,$tahun);
                extract($data);
            }
            require VIEW_PATH.'/siswa/raport/bulanan.php';
            return;
        }
        http_response_code(403);
        die('Akses ditolak');
    }

    public function siswa() {
        $role=Auth::role();
        $mode='bulanan';
        if ($role==='admin') {
            Auth::requireRole('admin');
            $db=getDB();
            $siswaId=(int)($_GET['siswa_id']??0);
            $kelasId=(int)($_GET['kelas_id']??0);
            $bulan=isset($_GET['bulan'])?(int)$_GET['bulan']:date('m');
            $tahun=isset($_GET['tahun'])?(int)$_GET['tahun']:date('Y');
            $data=$this->ambilNilai($db,$siswaId,$kelasId,$mode,$bulan,$tahun);
            extract($data); // $siswa,$kelas,$nilai,$adaBelumValidasi,$wali,$absensi
            require VIEW_PATH.'/admin/raport/preview.php';
            return;
        }
        if ($role==='siswa') {
            $db=getDB();
            $siswa=(new SiswaModel())->whereOne('user_id',Auth::id());
            $siswaId=$siswa['id'];
            $kelasId=0; $kelas=null; $wali=null; $absensi=['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
            $ks=$db->prepare("SELECT ks.kelas_id,k.nama_kelas FROM kelas_siswa ks JOIN kelas k ON ks.kelas_id=k.id WHERE ks.siswa_id=? LIMIT 1");
            $ks->execute([$siswaId]); $ksRow=$ks->fetch();
            $bulan=isset($_GET['bulan'])?(int)$_GET['bulan']:date('m');
            $tahun=isset($_GET['tahun'])?(int)$_GET['tahun']:date('Y');
            if($ksRow){
                $kelasId=$ksRow['kelas_id'];
                $kelas=(new KelasModel())->find($kelasId);
                $data=$this->ambilNilai($db,$siswaId,$kelasId,$mode,$bulan,$tahun);
                extract($data);
            } else {
                $nilai=[]; $adaBelumValidasi=true;
            }
            // Use the admin preview view so layout is identical for siswa
            require VIEW_PATH.'/admin/raport/preview.php';
            return;
        }
        http_response_code(403);
        die('Akses ditolak');
    }

    public function cetak() {
        $role = Auth::role();
        $db=getDB();
        $siswaId=(int)($_GET['siswa_id']??0);
        $kelasId=(int)($_GET['kelas_id']??0);
        $mode='bulanan';
        $bulan=isset($_GET['bulan'])?(int)$_GET['bulan']:date('m');
        $tahun=isset($_GET['tahun'])?(int)$_GET['tahun']:date('Y');
        if ($role === 'admin') {
            Auth::requireRole('admin');
        } elseif ($role === 'siswa') {
            $siswa=(new SiswaModel())->whereOne('user_id',Auth::id());
            if (!$siswa) {
                http_response_code(403);
                die('Akses ditolak');
            }
            $siswaId=$siswa['id'];
            $ks=$db->prepare("SELECT ks.kelas_id FROM kelas_siswa ks WHERE ks.siswa_id=? LIMIT 1");
            $ks->execute([$siswaId]);
            $ksRow=$ks->fetch();
            $kelasId=$ksRow['kelas_id']??0;
        } else {
            http_response_code(403);
            die('Akses ditolak');
        }

        $data=$this->ambilNilai($db,$siswaId,$kelasId,$mode,$bulan,$tahun);
        extract($data);

        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanLabel = $bulanNama[(int)$bulan] ?? date('F', strtotime('2020-' . $bulan . '-01'));
        $filename = 'Raport_Bulanan_' . preg_replace('/[^A-Za-z0-9._-]+/', '_', trim((string)($siswa['nama'] ?? 'Siswa'))) . '_' . $bulanLabel . '_' . $tahun . '.pdf';

        // Prepare logo absolute file URI for Dompdf and make it available to the view
        $logoPath = realpath(BASE_PATH . '/public/img/logo.png');
        if (!$logoPath || !file_exists($logoPath)) {
            die("Logo tidak ditemukan : " . BASE_PATH . '/public/img/logo.png');
        }
        $logo = 'file:///' . str_replace('\\', '/', $logoPath);

        ob_start();
        require VIEW_PATH.'/admin/raport/cetak.php';
        $html = ob_get_clean();

        if ($html === '') {
            http_response_code(500);
            die('Tidak ada konten PDF yang dapat dibuat.');
        }

        $dompdfAvailable = class_exists('Dompdf\\Dompdf');
        if (!$dompdfAvailable) {
            if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
                require_once BASE_PATH . '/vendor/autoload.php';
                $dompdfAvailable = class_exists('Dompdf\\Dompdf');
            }
        }

        if (!$dompdfAvailable) {
            http_response_code(500);
            die('Dompdf belum tersedia.');
        }

        // Configure Dompdf with recommended options
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->setChroot(BASE_PATH);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    private function getTahunAjaranId($kelasId,$mapelId=0) {
        $db=getDB();
        $kelas=$db->prepare("SELECT tahun_ajaran_id FROM kelas WHERE id=? LIMIT 1");
        $kelas->execute([$kelasId]);
        $kelasRow=$kelas->fetch();
        if($kelasRow && !empty($kelasRow['tahun_ajaran_id'])) return (int)$kelasRow['tahun_ajaran_id'];
        if($mapelId){
            $mapel=$db->prepare("SELECT tahun_ajaran_id FROM mapel WHERE id=? LIMIT 1");
            $mapel->execute([$mapelId]);
            $mapelRow=$mapel->fetch();
            return $mapelRow ? (int)$mapelRow['tahun_ajaran_id'] : 0;
        }
        return 0;
    }

    private function nilaiHasColumn($column) {
        $db=getDB();
        $st=$db->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = ?");
        $st->execute([$column]);
        return (int)$st->fetchColumn() > 0;
    }

    private function getActiveTahunAjaranLabel($db) {
        $st=$db->prepare("SELECT * FROM tahun_ajaran WHERE status='aktif' ORDER BY id DESC LIMIT 1");
        $st->execute();
        $ta=$st->fetch();
        if (!$ta) {
            $fallback=$db->query("SELECT * FROM tahun_ajaran ORDER BY id DESC LIMIT 1");
            $ta=$fallback->fetch();
        }
        return $ta['tahun_ajaran'] ?? '-';
    }

    private function getAbsensiSummary($db, $siswaId, $kelasId, $bulan, $tahun) {
        $summary=['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        if (!$siswaId || !$kelasId) {
            return $summary;
        }
        $st=$db->prepare("SELECT status, COUNT(*) c FROM absensi_siswa WHERE siswa_id=? AND kelas_id=? AND MONTH(tanggal)=? AND YEAR(tanggal)=? GROUP BY status");
        $st->execute([$siswaId,$kelasId,(int)$bulan,(int)$tahun]);
        foreach ($st->fetchAll() as $row) {
            if (isset($summary[$row['status']])) {
                $summary[$row['status']] = (int)$row['c'];
            }
        }
        return $summary;
    }

    private function ensureNilaiCapaianKompetensiColumn() {
        $db=getDB();
        if (!$this->nilaiHasColumn('capaian_kompetensi')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `capaian_kompetensi` TEXT DEFAULT NULL AFTER `nilai_akhir`");
        }
        if (!$this->nilaiHasColumn('bulan')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `bulan` TINYINT(2) NULL DEFAULT NULL AFTER `nilai_akhir`");
        }
        if (!$this->nilaiHasColumn('tahun')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `tahun` YEAR(4) NULL DEFAULT NULL AFTER `bulan`");
        }
        if (!$this->nilaiHasColumn('tahun_ajaran_id')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `tahun_ajaran_id` INT(11) NULL DEFAULT NULL AFTER `tahun`");
        }
        if (!$this->nilaiHasColumn('is_validated')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `is_validated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tahun_ajaran_id`");
        }
        if (!$this->nilaiHasColumn('validated_at')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `validated_at` DATETIME DEFAULT NULL AFTER `is_validated`");
        }
        $db->exec("UPDATE `nilai` SET `bulan` = COALESCE(NULLIF(`bulan`, ''), MONTH(COALESCE(`created_at`, `updated_at`, NOW()))), `tahun` = COALESCE(NULLIF(`tahun`, ''), YEAR(COALESCE(`created_at`, `updated_at`, NOW()))), `tahun_ajaran_id` = COALESCE(`tahun_ajaran_id`, (SELECT k.tahun_ajaran_id FROM kelas k WHERE k.id = `nilai`.`kelas_id` LIMIT 1)) WHERE `bulan` IS NULL OR `tahun` IS NULL OR `tahun_ajaran_id` IS NULL");
    }

    /** Helper: ambil semua nilai siswa + info kelas/wali + flag validasi */
    private function ambilNilai($db,$siswaId,$kelasId,$mode='semester',$bulan=null,$tahun=null){
        $this->ensureNilaiCapaianKompetensiColumn();
        $siswa=(new SiswaModel())->find($siswaId);
        $kelas=(new KelasModel())->find($kelasId);
        $wali=null;
        if($kelas && $kelas['wali_kelas_id']){
            $w=$db->prepare("SELECT * FROM guru WHERE id=?"); $w->execute([$kelas['wali_kelas_id']]); $wali=$w->fetch();
        }
        $bulan=(int)($bulan ?? date('m'));
        $tahun=(int)($tahun ?? date('Y'));
        // Semua nilai siswa di kelas ini yang sudah tervalidasi
        $sql="SELECT n.*,m.nama_mapel,m.semester,m.tingkat FROM nilai n JOIN mapel m ON n.mapel_id=m.id WHERE n.siswa_id=? AND n.kelas_id=? AND n.is_validated=1";
        $params=[$siswaId,$kelasId];
        if($mode==='bulanan' && $bulan && $tahun){
            $tahunAjaranId=$this->getTahunAjaranId($kelasId,0);
            $sql.=" AND n.bulan=? AND n.tahun=? AND n.tahun_ajaran_id=?";
            $params[]=$bulan;
            $params[]=$tahun;
            $params[]=$tahunAjaranId;
        }
        $sql.=" ORDER BY m.nama_mapel";
        $st=$db->prepare($sql);
        $st->execute($params);
        $nilai=$st->fetchAll();
        // Determine if there are still unvalidated subjects for this student in the selected period.
        // We compare number of validated nilai with number of distinct mapel scheduled for the class.
        $mapelCountSt = $db->prepare("SELECT COUNT(DISTINCT mapel_id) c FROM jadwal WHERE kelas_id=?");
        $mapelCountSt->execute([$kelasId]);
        $mapelCount = (int)$mapelCountSt->fetchColumn();
        $validatedCount = count($nilai);
        $adaBelumValidasi = $mapelCount>0 && $validatedCount < $mapelCount;
        $absensi=$this->getAbsensiSummary($db,$siswaId,$kelasId,$bulan,$tahun);
        $tahunPelajaranLabel=$this->getActiveTahunAjaranLabel($db);
        $status=[];
        return compact('siswa','kelas','wali','nilai','adaBelumValidasi','absensi','tahunPelajaranLabel','status','bulan','tahun');
    }
}
