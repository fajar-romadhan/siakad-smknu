<?php
require_once BASE_PATH.'/models/NilaiModel.php';
require_once BASE_PATH.'/models/GuruModel.php';
require_once BASE_PATH.'/models/SiswaModel.php';

class NilaiController {
    public function index() {
        $role=Auth::role();
        $db=getDB();
        $bulan=(int)($_GET['bulan']??date('m'));
        $tahun=(int)($_GET['tahun']??date('Y'));
        if ($role === 'admin') {
            $q = trim($_GET['q'] ?? '');
            $kelasId = (int)($_GET['kelas_id'] ?? 0);
            $bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : 0;
            $tahunAjaranId = (int)($_GET['tahun_ajaran_id'] ?? 0);

            $kelasList = $db->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();
            $tahunAjaranList = $db->query("SELECT * FROM tahun_ajaran ORDER BY id DESC")->fetchAll();

            $hasilPencarianSiswa = [];
            if ($q !== '') {
                $sqlSiswa = "SELECT 
                                n.id as nilai_id,
                                n.siswa_id,
                                n.kelas_id,
                                n.mapel_id,
                                n.bulan,
                                n.tahun,
                                n.nilai_tugas,
                                n.nilai_uts,
                                n.nilai_uas,
                                n.nilai_akhir,
                                n.capaian_kompetensi,
                                n.is_validated,
                                s.nama as siswa_nama,
                                s.nisn,
                                k.nama_kelas,
                                m.nama_mapel,
                                g.nama as guru_nama,
                                ta.tahun_ajaran
                            FROM nilai n
                            JOIN siswa s ON n.siswa_id = s.id
                            JOIN kelas k ON n.kelas_id = k.id
                            JOIN mapel m ON n.mapel_id = m.id
                            LEFT JOIN guru g ON n.guru_id = g.id
                            LEFT JOIN tahun_ajaran ta ON n.tahun_ajaran_id = ta.id
                            WHERE (s.nama LIKE ? OR s.nisn LIKE ? OR s.kode_siswa LIKE ?)
                            ORDER BY s.nama, n.bulan DESC";
                $stS = $db->prepare($sqlSiswa);
                $stS->execute(["%$q%", "%$q%", "%$q%"]);
                $hasilPencarianSiswa = $stS->fetchAll();
            }

            $sql = "SELECT 
                        n.kelas_id,
                        n.mapel_id,
                        n.guru_id,
                        n.bulan,
                        n.tahun,
                        n.tahun_ajaran_id,
                        k.nama_kelas,
                        m.nama_mapel,
                        g.nama as guru_nama,
                        ta.tahun_ajaran,
                        COUNT(n.id) as total_siswa,
                        SUM(CASE WHEN n.is_validated = 1 THEN 1 ELSE 0 END) as total_terkunci,
                        SUM(CASE WHEN n.is_validated = 0 THEN 1 ELSE 0 END) as total_draft
                    FROM nilai n
                    JOIN kelas k ON n.kelas_id = k.id
                    JOIN mapel m ON n.mapel_id = m.id
                    LEFT JOIN guru g ON n.guru_id = g.id
                    LEFT JOIN tahun_ajaran ta ON n.tahun_ajaran_id = ta.id
                    WHERE 1=1";
            $params = [];
            if ($kelasId > 0) {
                $sql .= " AND n.kelas_id = ?";
                $params[] = $kelasId;
            }
            if ($bulan > 0) {
                $sql .= " AND n.bulan = ?";
                $params[] = $bulan;
            }
            if ($tahunAjaranId > 0) {
                $sql .= " AND n.tahun_ajaran_id = ?";
                $params[] = $tahunAjaranId;
            }

            $sql .= " GROUP BY n.kelas_id, n.mapel_id, n.guru_id, n.bulan, n.tahun, n.tahun_ajaran_id, k.nama_kelas, m.nama_mapel, g.nama, ta.tahun_ajaran
                      ORDER BY k.nama_kelas, m.nama_mapel, n.bulan DESC";
            $st = $db->prepare($sql);
            $st->execute($params);
            $rekapValidasi = $st->fetchAll();

            $msg = flash('success');
            $error = flash('error');
            require VIEW_PATH . '/admin/nilai/buka-kunci.php';
            return;
        } elseif($role==='guru') {
            $guru=(new GuruModel())->whereOne('user_id',Auth::id());
            $kelasList=$db->prepare("SELECT DISTINCT k.id,k.nama_kelas,m.nama_mapel,m.id as mapel_id FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mapel m ON j.mapel_id=m.id WHERE j.guru_id=? ORDER BY k.nama_kelas");
            $kelasList->execute([$guru['id']]); $kelasList=$kelasList->fetchAll();
            $msg=flash('success'); $error=flash('error');
            require VIEW_PATH.'/guru/nilai/index.php';
        } elseif($role==='siswa') {
            $siswa=(new SiswaModel())->whereOne('user_id',Auth::id());
            $nilaiList=$db->prepare("SELECT n.*,m.nama_mapel,g.nama as guru_nama FROM nilai n JOIN mapel m ON n.mapel_id=m.id JOIN guru g ON n.guru_id=g.id WHERE n.siswa_id=? AND n.bulan=? AND n.tahun=? ORDER BY m.nama_mapel");
            $nilaiList->execute([$siswa['id'],$bulan,$tahun]); $nilaiList=$nilaiList->fetchAll();
            require VIEW_PATH.'/siswa/nilai.php';
        }
    }

    public function input() {
        $this->ensureNilaiColumns();
        $db=getDB();
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $kelasId=(int)($_GET['kelas_id']??0); $mapelId=(int)($_GET['mapel_id']??0);
        $bulan=(int)($_GET['bulan']??date('m'));
        $tahun=(int)($_GET['tahun']??date('Y'));

        if(!$this->canTeachMapel($guru['id'],$kelasId,$mapelId)) {
            flash('error','Anda tidak mengajar mata pelajaran ini.');
            redirect(base_url('index.php?page=nilai'));
            return;
        }

        $tahunAjaranId=$this->getTahunAjaranId($kelasId,$mapelId);
        $siswaList=$db->prepare("SELECT s.*,n.nilai_tugas,n.nilai_uts,n.nilai_uas,n.nilai_akhir,n.capaian_kompetensi,n.is_validated,n.id as nilai_id FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id LEFT JOIN nilai n ON n.siswa_id=s.id AND n.mapel_id=? AND n.kelas_id=? AND n.bulan=? AND n.tahun=? AND n.tahun_ajaran_id=? WHERE ks.kelas_id=? ORDER BY s.nama");
        $siswaList->execute([$mapelId,$kelasId,$bulan,$tahun,$tahunAjaranId,$kelasId]); $siswaList=$siswaList->fetchAll();
        $kelas=$db->prepare("SELECT * FROM kelas WHERE id=?"); $kelas->execute([$kelasId]); $kelas=$kelas->fetch();
        $mapel=$db->prepare("SELECT * FROM mapel WHERE id=?"); $mapel->execute([$mapelId]); $mapel=$mapel->fetch();
        $history=$this->getHistory($db,$guru['id'],$kelasId,$mapelId);
        $msg=flash('success'); $error=flash('error');
        require VIEW_PATH.'/guru/nilai/input.php';
    }

    public function store() {
        $this->ensureNilaiColumns();
        $this->ensureNilaiIndexes();
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $m=new NilaiModel();
        $nilaiData=$_POST['nilai']??[];
        $postMapelId=(int)($_POST['mapel_id']??0);
        $postKelasId=(int)($_POST['kelas_id']??0);
        $bulan=(int)($_POST['bulan']??date('m'));
        $tahun=(int)($_POST['tahun']??date('Y'));
        $terkunci=0;

        if(!$this->canTeachMapel($guru['id'],$postKelasId,$postMapelId)) {
            flash('error','Anda tidak mengajar mata pelajaran ini.');
            redirect(base_url('index.php?page=nilai'));
            return;
        }

        $tahunAjaranId=$this->getTahunAjaranId($postKelasId,$postMapelId);
        foreach($nilaiData as $siswaId=>$vals) {
            $existing=$m->findByPeriod($siswaId,$postMapelId,$postKelasId,$bulan,$tahun,$tahunAjaranId);
            if($existing && (int)($existing['is_validated']??0)===1){ $terkunci++; continue; }
            $akhir=null;
            if($vals['tugas']!=='' && $vals['uts']!=='' && $vals['uas']!=='') {
                $akhir=round(($vals['tugas']*0.3+$vals['uts']*0.3+$vals['uas']*0.4),2);
            }
            $data=[
                'nilai_tugas'=>$vals['tugas']?:null,
                'nilai_uts'=>$vals['uts']?:null,
                'nilai_uas'=>$vals['uas']?:null,
                'nilai_akhir'=>$akhir,
                'bulan'=>$bulan,
                'tahun'=>$tahun,
                'tahun_ajaran_id'=>$tahunAjaranId,
                'capaian_kompetensi'=>trim($vals['capaian_kompetensi'] ?? '' )?:null,
                'guru_id'=>$guru['id'],
            ];
            if($existing) {
                $m->update($existing['id'],$data);
            } else {
                $m->upsertByPeriod($data, $siswaId, $postMapelId, $postKelasId, $bulan, $tahun, $tahunAjaranId);
            }
        }
        flash('success','Nilai berhasil disimpan'.($terkunci?" ($terkunci nilai terkunci karena sudah divalidasi, tidak diubah)":''));
        redirect(base_url('index.php?page=nilai&action=input&kelas_id='.$postKelasId.'&mapel_id='.$postMapelId.'&bulan='.$bulan.'&tahun='.$tahun));
    }

    private function ensureNilaiColumns() {
        $this->ensureNilaiCapaianKompetensiColumn();
        $this->ensureNilaiPeriodColumns();
        $this->ensureNilaiTahunAjaranColumn();
        $this->ensureNilaiValidationColumns();
    }

    private function ensureNilaiValidationColumns() {
        $db=getDB();
        if (!$this->nilaiHasColumn('is_validated')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `is_validated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `tahun_ajaran_id`");
        }
        if (!$this->nilaiHasColumn('validated_at')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `validated_at` DATETIME DEFAULT NULL AFTER `is_validated`");
        }
    }

    private function nilaiHasColumn($column) {
        $db=getDB();
        $st=$db->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND COLUMN_NAME = ?");
        $st->execute([$column]);
        return (int)$st->fetchColumn() > 0;
    }

    private function ensureNilaiCapaianKompetensiColumn() {
        $db=getDB();
        if (!$this->nilaiHasColumn('capaian_kompetensi')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `capaian_kompetensi` TEXT DEFAULT NULL AFTER `nilai_akhir`");
        }
    }

    private function ensureNilaiPeriodColumns() {
        $db=getDB();
        if (!$this->nilaiHasColumn('bulan')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `bulan` TINYINT(2) NULL DEFAULT NULL AFTER `nilai_akhir`");
        }
        if (!$this->nilaiHasColumn('tahun')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `tahun` YEAR(4) NULL DEFAULT NULL AFTER `bulan`");
        }
        if (!$this->nilaiHasColumn('tahun_ajaran_id')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `tahun_ajaran_id` INT(11) NULL DEFAULT NULL AFTER `tahun`");
        }
        $db->exec("UPDATE `nilai` SET `bulan` = COALESCE(NULLIF(`bulan`, ''), MONTH(COALESCE(`created_at`, `updated_at`, NOW()))), `tahun` = COALESCE(NULLIF(`tahun`, ''), YEAR(COALESCE(`created_at`, `updated_at`, NOW()))), `tahun_ajaran_id` = COALESCE(`tahun_ajaran_id`, (SELECT k.tahun_ajaran_id FROM kelas k WHERE k.id = `nilai`.`kelas_id` LIMIT 1)) WHERE `bulan` IS NULL OR `tahun` IS NULL OR `tahun_ajaran_id` IS NULL");
    }

    private function ensureNilaiTahunAjaranColumn() {
        $db=getDB();
        if (!$this->nilaiHasColumn('tahun_ajaran_id')) {
            $db->exec("ALTER TABLE `nilai` ADD COLUMN `tahun_ajaran_id` INT(11) NULL DEFAULT NULL AFTER `tahun`");
        }
        $db->exec("UPDATE `nilai` SET `tahun_ajaran_id` = COALESCE(`tahun_ajaran_id`, (SELECT k.tahun_ajaran_id FROM kelas k WHERE k.id = `nilai`.`kelas_id` LIMIT 1)) WHERE `tahun_ajaran_id` IS NULL");
    }

    private function ensureNilaiIndexes() {
        $db=getDB();
        try {
            $db->exec("ALTER TABLE `nilai` DROP INDEX `unique_nilai`");
        } catch (Exception $e) {
            // ignore if old unique index does not exist
        }
        try {
            $db->exec("ALTER TABLE `nilai` DROP INDEX `unique_nilai_period`");
        } catch (Exception $e) {
            // ignore if new unique index does not exist
        }

        $indexExists = $db->prepare("SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'nilai' AND INDEX_NAME = 'unique_nilai_period'");
        $indexExists->execute();
        if ((int)$indexExists->fetchColumn() > 0) {
            return;
        }

        $db->exec("ALTER TABLE `nilai` ADD UNIQUE KEY `unique_nilai_period` (`siswa_id`,`kelas_id`,`mapel_id`,`bulan`,`tahun`,`tahun_ajaran_id`)");
    }

    private function getTahunAjaranId($kelasId,$mapelId) {
        $db=getDB();
        $kelas=$db->prepare("SELECT tahun_ajaran_id FROM kelas WHERE id=? LIMIT 1");
        $kelas->execute([$kelasId]);
        $kelasRow=$kelas->fetch();
        if($kelasRow && !empty($kelasRow['tahun_ajaran_id'])) return (int)$kelasRow['tahun_ajaran_id'];
        $mapel=$db->prepare("SELECT tahun_ajaran_id FROM mapel WHERE id=? LIMIT 1");
        $mapel->execute([$mapelId]);
        $mapelRow=$mapel->fetch();
        return $mapelRow ? (int)$mapelRow['tahun_ajaran_id'] : 0;
    }

    private function getHistory($db,$guruId,$kelasId,$mapelId) {
        $years=[];
        $st=$db->prepare("SELECT DISTINCT tahun FROM nilai WHERE guru_id=? AND kelas_id=? AND mapel_id=? ORDER BY tahun DESC");
        $st->execute([$guruId,$kelasId,$mapelId]);
        foreach($st->fetchAll() as $row){ if((int)($row['tahun']??0)>0) $years[]=(int)$row['tahun']; }
        if(empty($years)) $years[]=(int)date('Y');
        $currentYear=(int)date('Y');
        if(!in_array($currentYear,$years,true)) $years[]=$currentYear;
        sort($years);
        $history=[];
        foreach($years as $year){
            for($month=1;$month<=12;$month++){
                $periodSt=$db->prepare("SELECT id,is_validated FROM nilai WHERE guru_id=? AND kelas_id=? AND mapel_id=? AND bulan=? AND tahun=? LIMIT 1");
                $periodSt->execute([$guruId,$kelasId,$mapelId,$month,$year]);
                $period=$periodSt->fetch();
                $history[]=[
                    'bulan'=>$month,
                    'tahun'=>$year,
                    'label'=>date('F', strtotime('2020-'.$month.'-01')),
                    'status'=>$period && (int)($period['is_validated']??0)===1 ? 'Tervalidasi' : ($period ? 'Draft' : 'Belum Diisi'),
                ];
            }
        }
        usort($history, function($a,$b){ if($a['tahun']===$b['tahun']) return $b['bulan'] <=> $a['bulan']; return $b['tahun'] <=> $a['tahun']; });
        return $history;
    }

    private function canTeachMapel($guruId,$kelasId,$mapelId) {
        $db=getDB();
        $st=$db->prepare("SELECT id FROM jadwal WHERE guru_id=? AND kelas_id=? AND mapel_id=? LIMIT 1");
        $st->execute([$guruId,$kelasId,$mapelId]);
        return (bool)$st->fetch();
    }

    /** Guru memvalidasi (mengunci) nilai satu kelas-mapel pada periode tertentu. */
    public function validasi() {
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $kelasId=(int)($_POST['kelas_id']??$_GET['kelas_id']); $mapelId=(int)($_POST['mapel_id']??$_GET['mapel_id']);
        $bulan=(int)($_POST['bulan']??$_GET['bulan']??date('m'));
        $tahun=(int)($_POST['tahun']??$_GET['tahun']??date('Y'));
        $m=new NilaiModel();
        $tahunAjaranId=$this->getTahunAjaranId($kelasId,$mapelId);
        $m->exec("UPDATE nilai SET is_validated=1, validated_at=NOW() WHERE kelas_id=? AND mapel_id=? AND guru_id=? AND bulan=? AND tahun=? AND tahun_ajaran_id=? AND nilai_akhir IS NOT NULL",[$kelasId,$mapelId,$guru['id'],$bulan,$tahun,$tahunAjaranId]);
        flash('success','Nilai berhasil divalidasi & dikunci untuk periode ini.');
        redirect(base_url('index.php?page=nilai&action=input&kelas_id='.$kelasId.'&mapel_id='.$mapelId.'&bulan='.$bulan.'&tahun='.$tahun));
    }

    /** Admin membuka kunci (unlock) nilai satu kelas-mapel atau 1 nilai siswa agar dapat direvisi kembali oleh guru */
    public function bukaKunci() {
        Auth::requireRole('admin');
        $m=new NilaiModel();
        $nilaiId=(int)($_GET['nilai_id']??0);
        $q=trim($_GET['q']??'');
        if ($nilaiId > 0) {
            $m->exec("UPDATE nilai SET is_validated=0, validated_at=NULL WHERE id=?", [$nilaiId]);
            flash('success','Kunci nilai siswa tersebut berhasil dibuka.');
            if ($q !== '') {
                redirect(base_url('index.php?page=nilai&q='.urlencode($q)));
                return;
            }
        }
        $kelasId=(int)($_POST['kelas_id']??$_GET['kelas_id']??0);
        $mapelId=(int)($_POST['mapel_id']??$_GET['mapel_id']??0);
        $bulan=(int)($_POST['bulan']??$_GET['bulan']??date('m'));
        $tahun=(int)($_POST['tahun']??$_GET['tahun']??date('Y'));
        $tahunAjaranId=$this->getTahunAjaranId($kelasId,$mapelId);
        if ($kelasId > 0 && $mapelId > 0) {
            $m->exec("UPDATE nilai SET is_validated=0, validated_at=NULL WHERE kelas_id=? AND mapel_id=? AND bulan=? AND tahun=? AND tahun_ajaran_id=?",[$kelasId,$mapelId,$bulan,$tahun,$tahunAjaranId]);
            flash('success','Kunci nilai berhasil dibuka. Guru dapat mengedit kembali nilai periode ini.');
        }
        $siswaId=(int)($_GET['siswa_id']??0);
        if ($siswaId > 0) {
            redirect(base_url('index.php?page=siswa&action=detail&id='.$siswaId.'&bulan='.$bulan));
        } else {
            redirect(base_url('index.php?page=nilai'.($q !== '' ? '&q='.urlencode($q) : '')));
        }
    }

    /** Cetak rekap nilai (PDF-ready HTML) untuk satu kelas-mapel yang diampu guru */
    public function cetakRekap() {
        $db=getDB();
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $kelasId=(int)($_GET['kelas_id']??0); $mapelId=(int)($_GET['mapel_id']??0);
        $bulan=(int)($_GET['bulan']??date('m'));
        $tahun=(int)($_GET['tahun']??date('Y'));
        $kelas=$db->prepare("SELECT * FROM kelas WHERE id=?"); $kelas->execute([$kelasId]); $kelas=$kelas->fetch();
        $mapel=$db->prepare("SELECT * FROM mapel WHERE id=?"); $mapel->execute([$mapelId]); $mapel=$mapel->fetch();
        $tahunAjaranId=$this->getTahunAjaranId($kelasId,$mapelId);
        $rows=$db->prepare("SELECT s.nisn,s.nama,n.nilai_tugas,n.nilai_uts,n.nilai_uas,n.nilai_akhir,n.is_validated FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id LEFT JOIN nilai n ON n.siswa_id=s.id AND n.mapel_id=? AND n.kelas_id=? AND n.bulan=? AND n.tahun=? AND n.tahun_ajaran_id=? WHERE ks.kelas_id=? ORDER BY s.nama");
        $rows->execute([$mapelId,$kelasId,$bulan,$tahun,$tahunAjaranId,$kelasId]); $rows=$rows->fetchAll();
        require VIEW_PATH.'/guru/nilai/cetak-rekap.php';
    }
}
