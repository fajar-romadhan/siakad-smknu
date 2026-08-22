<?php
require_once BASE_PATH.'/models/AbsensiGuruModel.php';
require_once BASE_PATH.'/models/AbsensiSiswaModel.php';
require_once BASE_PATH.'/models/GuruModel.php';
require_once BASE_PATH.'/models/SiswaModel.php';
require_once BASE_PATH.'/models/KelasModel.php';

class AbsensiController {
    public function index() {
        $role=Auth::role();
        if($role==='admin') {
            require VIEW_PATH.'/admin/absensi/index.php';
        } elseif($role==='guru') {
            $db=getDB();
            $guru=(new GuruModel())->whereOne('user_id',Auth::id());
            $riwayatGuru=$db->prepare("SELECT * FROM absensi_guru WHERE guru_id=? ORDER BY tanggal DESC LIMIT 10");
            $riwayatGuru->execute([$guru['id']]); $riwayatGuru=$riwayatGuru->fetchAll();
            $sudahAbsen=$db->prepare("SELECT * FROM absensi_guru WHERE guru_id=? AND tanggal=CURDATE()");
            $sudahAbsen->execute([$guru['id']]); $sudahAbsen=$sudahAbsen->fetch();
            // Get kelas for absensi siswa
            $kelasList=$db->prepare("SELECT DISTINCT k.id,k.nama_kelas FROM jadwal j JOIN kelas k ON j.kelas_id=k.id WHERE j.guru_id=? ORDER BY k.nama_kelas");
            $kelasList->execute([$guru['id']]); $kelasList=$kelasList->fetchAll();
            $msg=flash('success');
            require VIEW_PATH.'/guru/absensi/index.php';
        }
    }
    public function storeGuru() {
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $m=new AbsensiGuruModel();
        $data = [
            'guru_id'=>$guru['id'],
            'tanggal'=>date('Y-m-d'),
            'status'=>$_POST['status'],
        ];
        $this->ensureAbsensiGuruValidationColumns();
        $hasValidatedColumns = $this->absensiGuruHasColumn('is_validated') && $this->absensiGuruHasColumn('validated_by') && $this->absensiGuruHasColumn('validated_at');
        if ($hasValidatedColumns) {
            $data['is_validated'] = 0;
            $data['validated_by'] = null;
            $data['validated_at'] = null;
        }
        try {
            $m->insert($data);
        } catch(Exception $e) {
            $updateSql = "UPDATE absensi_guru SET status=? WHERE guru_id=? AND tanggal=CURDATE()";
            $params = [$_POST['status'], $guru['id']];
            if ($hasValidatedColumns) {
                $updateSql = "UPDATE absensi_guru SET status=?, is_validated=0, validated_by=NULL, validated_at=NULL WHERE guru_id=? AND tanggal=CURDATE()";
            }
            $m->exec($updateSql, $params);
        }
        flash('success','Absensi berhasil disimpan dan menunggu validasi admin');
        redirect(base_url('index.php?page=absensi'));
    }
    public function siswa() {
        $db=getDB();
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $kelasId=$_GET['kelas_id']??null;
        $kelas=(new KelasModel())->find($kelasId);
        $siswaList=$db->prepare("SELECT s.* FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id WHERE ks.kelas_id=? ORDER BY s.nama");
        $siswaList->execute([$kelasId]); $siswaList=$siswaList->fetchAll();
        // Get mapel for this guru in this class
        $mapelList=$db->prepare("SELECT DISTINCT m.id,m.nama_mapel FROM jadwal j JOIN mapel m ON j.mapel_id=m.id WHERE j.guru_id=? AND j.kelas_id=?");
        $mapelList->execute([$guru['id'],$kelasId]); $mapelList=$mapelList->fetchAll();
        $riwayat=$db->prepare("SELECT a.tanggal,s.nama,a.status FROM absensi_siswa a JOIN siswa s ON a.siswa_id=s.id WHERE a.kelas_id=? AND a.guru_id=? ORDER BY a.tanggal DESC,s.nama LIMIT 50");
        $riwayat->execute([$kelasId,$guru['id']]); $riwayat=$riwayat->fetchAll();
        $msg=flash('success');
        require VIEW_PATH.'/guru/absensi/siswa.php';
    }
    public function storeSiswa() {
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $m=new AbsensiSiswaModel();
        $absensi=$_POST['absensi']??[];
        foreach($absensi as $siswaId=>$status) {
            try {
                $m->insert(['siswa_id'=>$siswaId,'kelas_id'=>$_POST['kelas_id'],'mapel_id'=>$_POST['mapel_id'],'guru_id'=>$guru['id'],'tanggal'=>date('Y-m-d'),'status'=>$status]);
            } catch(Exception $e) {
                $m->exec("UPDATE absensi_siswa SET status=? WHERE siswa_id=? AND mapel_id=? AND tanggal=CURDATE()",[$status,$siswaId,$_POST['mapel_id']]);
            }
        }
        flash('success','Absensi siswa berhasil disimpan');
        redirect(base_url('index.php?page=absensi&action=siswa&kelas_id='.$_POST['kelas_id']));
    }
    public function rekapSiswa() {
        Auth::requireRole('admin');
        $db=getDB();
        $tahunAjaranList=$db->query("SELECT * FROM tahun_ajaran ORDER BY tahun_ajaran DESC")->fetchAll();
        $kelasList=$db->query("SELECT * FROM kelas ORDER BY nama_kelas")->fetchAll();
        $rekapData=[];$stats=['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0]; $totalSiswaKelas=0;
        $rekapPerSiswa=[];
        if(isset($_GET['kelas_id']) && isset($_GET['bulan'])) {
            $st=$db->prepare("SELECT a.*,s.nama as siswa_nama,s.nisn FROM absensi_siswa a JOIN siswa s ON a.siswa_id=s.id WHERE a.kelas_id=? AND MONTH(a.tanggal)=? AND YEAR(a.tanggal)=? ORDER BY a.tanggal DESC,s.nama");
            $st->execute([$_GET['kelas_id'],$_GET['bulan'],$_GET['tahun']??date('Y')]);
            $rekapData=$st->fetchAll();
            foreach($rekapData as $r) $stats[$r['status']]++;
            // Agregat per siswa
            $agg=$db->prepare("SELECT s.id,s.nisn,s.nama, SUM(CASE WHEN a.status='Hadir' THEN 1 ELSE 0 END) as h, SUM(CASE WHEN a.status='Izin' THEN 1 ELSE 0 END) as i, SUM(CASE WHEN a.status='Sakit' THEN 1 ELSE 0 END) as sk, SUM(CASE WHEN a.status='Alpa' THEN 1 ELSE 0 END) as al FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id LEFT JOIN absensi_siswa a ON a.siswa_id=s.id AND a.kelas_id=ks.kelas_id AND MONTH(a.tanggal)=? AND YEAR(a.tanggal)=? WHERE ks.kelas_id=? GROUP BY s.id ORDER BY s.nama");
            $agg->execute([$_GET['bulan'],$_GET['tahun']??date('Y'),$_GET['kelas_id']]);
            $rekapPerSiswa=$agg->fetchAll();
            $totalSiswaKelas=count($rekapPerSiswa);
        }
        require VIEW_PATH.'/admin/absensi/rekap-siswa.php';
    }
    public function rekapGuru() {
        Auth::requireRole('admin');
        $db=getDB();
        $rekapData=[]; $stats=['Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        $guruList=$db->query("SELECT id,nama FROM guru ORDER BY nama")->fetchAll();
        $guruId=isset($_GET['guru_id'])?(int)$_GET['guru_id']:0;
        $periode=$_GET['periode']??'bulan'; // 'bulan' atau 'semester'
        $bulan=$_GET['bulan']??date('m'); $tahun=$_GET['tahun']??date('Y');
        $semester=$_GET['semester']??'ganjil';

        // Bangun kondisi periode
        list($cond,$params)=$this->periodeCond($periode,$bulan,$tahun,$semester);
        $sql="SELECT a.*,g.nama FROM absensi_guru a JOIN guru g ON a.guru_id=g.id WHERE $cond";
        if($guruId){ $sql.=" AND a.guru_id=?"; $params[]=$guruId; }
        $sql.=" ORDER BY a.tanggal DESC,g.nama";
        $st=$db->prepare($sql); $st->execute($params); $rekapData=$st->fetchAll();
        foreach($rekapData as $r) $stats[$r['status']]++;
        require VIEW_PATH.'/admin/absensi/rekap-guru.php';
    }

    public function validasiGuru() {
        Auth::requireRole('admin');
        $db = getDB();
        $this->ensureAbsensiGuruValidationColumns();
        $guruList = $db->query("SELECT id,nama FROM guru ORDER BY nama")->fetchAll();
        $guruId = isset($_GET['guru_id']) ? (int)$_GET['guru_id'] : 0;
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');

        $sql = "SELECT a.*, g.nama, u.username AS validated_by_name
                FROM absensi_guru a
                JOIN guru g ON a.guru_id=g.id
                LEFT JOIN users u ON u.id=a.validated_by
                WHERE a.tanggal=?";
        $params = [$tanggal];
        if ($guruId) { $sql .= " AND a.guru_id=?"; $params[] = $guruId; }
        $sql .= " ORDER BY a.tanggal DESC, g.nama";
        $st = $db->prepare($sql);
        $st->execute($params);
        $data = $st->fetchAll();

        $stats = ['total'=>0,'pending'=>0,'validated'=>0,'Hadir'=>0,'Izin'=>0,'Sakit'=>0,'Alpa'=>0];
        foreach ($data as $r) {
            $stats['total']++;
            if ((int)($r['is_validated'] ?? 0) === 1) {
                $stats['validated']++;
            } else {
                $stats['pending']++;
            }
            if (isset($stats[$r['status']])) {
                $stats[$r['status']]++;
            }
        }

        $pageTitle = 'Validasi Absensi Guru';
        require VIEW_PATH.'/admin/absensi/validasi-guru.php';
    }

    public function approveGuru() {
        Auth::requireRole('admin');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $db = getDB();

        if ($id) {
            try {
                $this->ensureAbsensiGuruValidationColumns();
                $db->prepare("UPDATE absensi_guru SET is_validated=1, validated_by=?, validated_at=NOW() WHERE id=?")
                   ->execute([Auth::id(), $id]);
                flash('success','Absensi guru berhasil divalidasi.');
            } catch (Exception $e) {
                flash('error','Gagal validasi absensi guru: '.$e->getMessage());
            }
        } else {
            flash('error','Data absensi tidak ditemukan.');
        }

        $redirect = base_url('index.php?page=absensi&action=validasiGuru');
        $query = [];
        if (!empty($_POST['tanggal'])) $query[] = 'tanggal='.urlencode($_POST['tanggal']);
        if (!empty($_POST['guru_id'])) $query[] = 'guru_id='.urlencode($_POST['guru_id']);
        if ($query) $redirect .= '&'.implode('&',$query);
        redirect($redirect);
    }

    private function absensiGuruHasColumn($column) {
        $db = getDB();
        $st = $db->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'absensi_guru' AND COLUMN_NAME = ?");
        $st->execute([$column]);
        return (int)$st->fetchColumn() > 0;
    }

    private function ensureAbsensiGuruValidationColumns() {
        $db = getDB();
        $columns = [
            'is_validated' => "TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`",
            'validated_by' => "INT(11) DEFAULT NULL AFTER `is_validated`",
            'validated_at' => "DATETIME DEFAULT NULL AFTER `validated_by`",
        ];
        foreach ($columns as $column => $definition) {
            if (!$this->absensiGuruHasColumn($column)) {
                $db->exec("ALTER TABLE `absensi_guru` ADD COLUMN `$column` $definition");
            }
        }
    }

    /** Helper kondisi periode WHERE untuk absensi_guru (a.tanggal). Semester ganjil=Jul-Des, genap=Jan-Jun. */
    private function periodeCond($periode,$bulan,$tahun,$semester){
        if($periode==='semester'){
            if($semester==='ganjil'){ $bulans=[7,8,9,10,11,12]; } else { $bulans=[1,2,3,4,5,6]; }
            $in=implode(',',array_fill(0,count($bulans),'?'));
            $cond="YEAR(a.tanggal)=? AND MONTH(a.tanggal) IN ($in)";
            $params=array_merge([$tahun],$bulans);
        } else {
            $cond="MONTH(a.tanggal)=? AND YEAR(a.tanggal)=?";
            $params=[$bulan,$tahun];
        }
        return [$cond,$params];
    }

    public function exportSiswa() {
        Auth::requireRole('admin');
        $db=getDB();
        $kelas_id=$_GET['kelas_id']??null;
        $bulan=$_GET['bulan']??date('m');
        $tahun=$_GET['tahun']??date('Y');
        $kelas=$db->prepare("SELECT nama_kelas FROM kelas WHERE id=?"); $kelas->execute([$kelas_id]);
        $namaKelas=$kelas->fetchColumn() ?: 'Semua';
        $st=$db->prepare("SELECT a.tanggal,s.nisn,s.nama,a.status FROM absensi_siswa a JOIN siswa s ON a.siswa_id=s.id WHERE a.kelas_id=? AND MONTH(a.tanggal)=? AND YEAR(a.tanggal)=? ORDER BY a.tanggal,s.nama");
        $st->execute([$kelas_id,$bulan,$tahun]);
        $data=$st->fetchAll();
        $fname='Rekap_Absensi_Siswa_'.$namaKelas.'_'.$bulan.'-'.$tahun.'.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$fname.'"');
        echo "\xEF\xBB\xBF"; // BOM UTF-8
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<h3>Rekap Absensi Siswa - Kelas '.$namaKelas.'</h3>';
        echo '<p>Periode: '.date('F',mktime(0,0,0,$bulan)).' '.$tahun.'</p>';
        echo '<table border="1" cellspacing="0" cellpadding="6"><thead><tr style="background:#00923F;color:white;"><th>No</th><th>Tanggal</th><th>NISN</th><th>Nama Siswa</th><th>Status</th></tr></thead><tbody>';
        foreach($data as $i=>$r) {
            echo '<tr>';
            echo '<td>'.($i+1).'</td>';
            echo '<td>'.date('d/m/Y',strtotime($r['tanggal'])).'</td>';
            echo '<td>'.htmlspecialchars($r['nisn']).'</td>';
            echo '<td>'.htmlspecialchars($r['nama']).'</td>';
            echo '<td>'.$r['status'].'</td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit;
    }
    public function exportGuru() {
        Auth::requireRole('admin');
        $db=getDB();
        $guruId=isset($_GET['guru_id'])?(int)$_GET['guru_id']:0;
        $periode=$_GET['periode']??'bulan';
        $bulan=$_GET['bulan']??date('m');
        $tahun=$_GET['tahun']??date('Y');
        $semester=$_GET['semester']??'ganjil';
        list($cond,$params)=$this->periodeCond($periode,$bulan,$tahun,$semester);
        $sql="SELECT a.tanggal,g.nama,a.status FROM absensi_guru a JOIN guru g ON a.guru_id=g.id WHERE $cond";
        if($guruId){ $sql.=" AND a.guru_id=?"; $params[]=$guruId; }
        $sql.=" ORDER BY a.tanggal,g.nama";
        $st=$db->prepare($sql); $st->execute($params);
        $data=$st->fetchAll();
        $periodeLabel = $periode==='semester' ? ('Semester_'.$semester.'_'.$tahun) : ($bulan.'-'.$tahun);
        $fname='Rekap_Absensi_Guru_'.$periodeLabel.'.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$fname.'"');
        echo "\xEF\xBB\xBF";
        echo '<html><head><meta charset="UTF-8"></head><body>';
        echo '<h3>Rekap Absensi Guru</h3>';
        echo '<p>Periode: '.($periode==='semester' ? ('Semester '.ucfirst($semester).' '.$tahun) : (date('F',mktime(0,0,0,$bulan)).' '.$tahun)).'</p>';
        echo '<table border="1" cellspacing="0" cellpadding="6"><thead><tr style="background:#00923F;color:white;"><th>No</th><th>Tanggal</th><th>Nama Guru</th><th>Status</th></tr></thead><tbody>';
        foreach($data as $i=>$r) {
            echo '<tr>';
            echo '<td>'.($i+1).'</td>';
            echo '<td>'.date('d/m/Y',strtotime($r['tanggal'])).'</td>';
            echo '<td>'.htmlspecialchars($r['nama']).'</td>';
            echo '<td>'.$r['status'].'</td>';
            echo '</tr>';
        }
        echo '</tbody></table></body></html>';
        exit;
    }
}
