<?php
require_once BASE_PATH.'/models/KelasModel.php';
require_once BASE_PATH.'/models/GuruModel.php';
require_once BASE_PATH.'/models/SiswaModel.php';
require_once BASE_PATH.'/models/KelasSiswaModel.php';
require_once BASE_PATH.'/models/TahunAjaranModel.php';

class KelasController {
    private $model;
    public function __construct() { $this->model=new KelasModel(); }
    public function index() {
        Auth::requireRole('admin');
        $db=getDB();
        $data=$db->query("SELECT k.*,g.nama as wali,ta.tahun_ajaran,(SELECT COUNT(*) FROM kelas_siswa WHERE kelas_id=k.id) as jml_siswa FROM kelas k LEFT JOIN guru g ON k.wali_kelas_id=g.id JOIN tahun_ajaran ta ON k.tahun_ajaran_id=ta.id ORDER BY k.nama_kelas")->fetchAll();
        $msg=flash('success');
        require VIEW_PATH.'/admin/kelas/index.php';
    }
    public function create() {
        Auth::requireRole('admin');
        // Hanya guru berstatus GTY / PTY yang BELUM jadi wali kelas manapun
        $guruList=(new GuruModel())->query("SELECT * FROM guru WHERE (status_kepegawaian LIKE '%GTY%' OR status_kepegawaian LIKE '%PTY%') AND id NOT IN (SELECT wali_kelas_id FROM kelas WHERE wali_kelas_id IS NOT NULL) ORDER BY nama ASC");
        $tahunAjaran=(new TahunAjaranModel())->all();
        require VIEW_PATH.'/admin/kelas/create.php';
    }
    public function store() {
        Auth::requireRole('admin');
        $wali=$_POST['wali_kelas_id']?:null;
        // Validasi: wali kelas tidak boleh sudah jadi wali di kelas lain
        if($wali){
            $dup=$this->model->query("SELECT id FROM kelas WHERE wali_kelas_id=?",[$wali]);
            if($dup){
                flash('error','Guru tersebut sudah menjadi wali kelas di kelas lain.');
                redirect(base_url('index.php?page=kelas&action=create'));
            }
        }
        $kode=$this->model->generateKode('KL');
        $this->model->insert(['kode_kelas'=>$kode,'nama_kelas'=>$_POST['nama_kelas'],'wali_kelas_id'=>$wali,'tahun_ajaran_id'=>$_POST['tahun_ajaran_id']]);
        flash('success','Kelas berhasil ditambahkan');
        redirect(base_url('index.php?page=kelas'));
    }
    public function detail($id) {
        Auth::requireRole('admin');
        $db=getDB();
        $kelas=$this->model->find($id);
        $wali=$kelas['wali_kelas_id'] ? (new GuruModel())->find($kelas['wali_kelas_id']) : null;
        $siswaKelas=$db->prepare("SELECT s.* FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id WHERE ks.kelas_id=? ORDER BY s.nama");
        $siswaKelas->execute([$id]); $siswaKelas=$siswaKelas->fetchAll();
        $msg=flash('success');
        require VIEW_PATH.'/admin/kelas/detail.php';
    }
    public function tambahSiswa($id) {
        Auth::requireRole('admin');
        $kelas=$this->model->find($id);
        $db=getDB();
        $search=$_GET['search']??'';
        $sql="SELECT s.*, 
            (SELECT ks.kelas_id FROM kelas_siswa ks WHERE ks.siswa_id=s.id LIMIT 1) AS kelas_id,
            (SELECT k.nama_kelas FROM kelas k JOIN kelas_siswa ks ON k.id=ks.kelas_id WHERE ks.siswa_id=s.id LIMIT 1) AS nama_kelas
            FROM siswa s";
        $params=[];
        if($search) {
            $sql.=" WHERE s.nama LIKE ?";
            $params[]="%$search%";
        }
        $sql.=" ORDER BY s.nama";
        $st=$db->prepare($sql); $st->execute($params); $siswaList=$st->fetchAll();
        require VIEW_PATH.'/admin/kelas/tambah-siswa.php';
    }
    public function simpanSiswa($id) {
        Auth::requireRole('admin');
        $siswaIds=$_POST['siswa_ids']??[];
        $db=getDB();
        $insertedCount=0;
        if($siswaIds) {
            $placeholders=implode(',', array_fill(0, count($siswaIds), '?'));
            $assignedStmt=$db->prepare("SELECT siswa_id FROM kelas_siswa WHERE siswa_id IN ($placeholders)");
            $assignedStmt->execute($siswaIds);
            $assignedIds=$assignedStmt->fetchAll(PDO::FETCH_COLUMN, 0);
            $assignedIds = $assignedIds ?: [];
            $ksm=new KelasSiswaModel();
            foreach($siswaIds as $sid) {
                if(in_array($sid, $assignedIds, true)) {
                    continue;
                }
                try {
                    $ksm->insert(['kelas_id'=>$id,'siswa_id'=>$sid]);
                    $insertedCount++;
                } catch(Exception $e) {
                }
            }
        }
        if($insertedCount > 0) {
            flash('success',$insertedCount.' siswa berhasil ditambahkan ke kelas');
        } else {
            flash('error','Tidak ada siswa berhasil ditambahkan. Pastikan siswa belum terdaftar di kelas ini atau kelas lain.');
        }
        redirect(base_url('index.php?page=kelas&action=detail&id='.$id));
    }
    public function hapusSiswa() {
        Auth::requireRole('admin');
        $kelasId=$_GET['kelas_id']; $siswaId=$_GET['siswa_id'];
        getDB()->prepare("DELETE FROM kelas_siswa WHERE kelas_id=? AND siswa_id=?")->execute([$kelasId,$siswaId]);
        flash('success','Siswa berhasil dihapus dari kelas');
        redirect(base_url('index.php?page=kelas&action=detail&id='.$kelasId));
    }
    public function edit($id) {
        Auth::requireRole('admin');
        $kelas=$this->model->find($id);
        // Guru berstatus GTY / PTY yang belum jadi wali kelas lain, PLUS wali kelas ini sendiri
        $guruList=(new GuruModel())->query("SELECT * FROM guru WHERE (status_kepegawaian LIKE '%GTY%' OR status_kepegawaian LIKE '%PTY%') AND id NOT IN (SELECT wali_kelas_id FROM kelas WHERE wali_kelas_id IS NOT NULL AND id<>?) ORDER BY nama ASC",[$id]);
        $error=flash('error');
        require VIEW_PATH.'/admin/kelas/edit.php';
    }
    public function update($id) {
        Auth::requireRole('admin');
        $wali=$_POST['wali_kelas_id']?:null;
        // Validasi: wali kelas tidak boleh sudah jadi wali di kelas LAIN
        if($wali){
            $dup=$this->model->query("SELECT id FROM kelas WHERE wali_kelas_id=? AND id<>?",[$wali,$id]);
            if($dup){
                flash('error','Guru tersebut sudah menjadi wali kelas di kelas lain.');
                redirect(base_url('index.php?page=kelas&action=edit&id='.$id));
            }
        }
        $this->model->update($id,['nama_kelas'=>$_POST['nama_kelas'],'wali_kelas_id'=>$wali]);
        flash('success','Kelas berhasil diperbarui');
        redirect(base_url('index.php?page=kelas'));
    }
    public function destroy($id) {
        Auth::requireRole('admin'); $this->model->delete($id);
        flash('success','Kelas berhasil dihapus');
        redirect(base_url('index.php?page=kelas'));
    }
}
