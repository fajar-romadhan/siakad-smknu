<?php
require_once BASE_PATH.'/models/JadwalModel.php';
require_once BASE_PATH.'/models/KelasModel.php';
require_once BASE_PATH.'/models/MapelModel.php';
require_once BASE_PATH.'/models/GuruModel.php';

class JadwalController {
    private $model;
    public function __construct() { $this->model=new JadwalModel(); }
    public function index() {
        Auth::requireRole('admin');
        $db=getDB();
        $kelasList=$db->query("SELECT k.*,ta.tahun_ajaran FROM kelas k JOIN tahun_ajaran ta ON k.tahun_ajaran_id=ta.id ORDER BY k.nama_kelas")->fetchAll();
        $msg=flash('success');
        require VIEW_PATH.'/admin/jadwal/index.php';
    }
    public function detail($id) {
        Auth::requireRole('admin');
        $db=getDB();
        $kelas=(new KelasModel())->find($id);
        $jadwal=$db->prepare("SELECT j.*,m.nama_mapel,g.nama as guru_nama FROM jadwal j JOIN mapel m ON j.mapel_id=m.id JOIN guru g ON j.guru_id=g.id WHERE j.kelas_id=? ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),j.jam_mulai");
        $jadwal->execute([$id]); $jadwal=$jadwal->fetchAll();
        $hariList=['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        require VIEW_PATH.'/admin/jadwal/detail.php';
    }
    public function create() {
        Auth::requireRole('admin');
        $kelasId=$_GET['kelas_id']??null;
        $kelas=(new KelasModel())->find($kelasId);
        $mapelList=(new MapelModel())->all('nama_mapel ASC');
        $guruList=(new GuruModel())->all('nama ASC');
        require VIEW_PATH.'/admin/jadwal/create.php';
    }
    public function store() {
        Auth::requireRole('admin');
        $db=getDB();
        // Check guru availability
        $st=$db->prepare("SELECT COUNT(*) as c FROM jadwal WHERE guru_id=? AND hari=? AND ((jam_mulai<? AND jam_selesai>?) OR (jam_mulai<? AND jam_selesai>?) OR (jam_mulai>=? AND jam_selesai<=?))");
        $st->execute([$_POST['guru_id'],$_POST['hari'],$_POST['jam_selesai'],$_POST['jam_mulai'],$_POST['jam_selesai'],$_POST['jam_mulai'],$_POST['jam_mulai'],$_POST['jam_selesai']]);
        if($st->fetch()['c']>0) {
            flash('error','Guru tidak tersedia pada waktu tersebut');
            redirect(base_url('index.php?page=jadwal&action=create&kelas_id='.$_POST['kelas_id']));
        }
        $this->model->insert(['kelas_id'=>$_POST['kelas_id'],'mapel_id'=>$_POST['mapel_id'],'guru_id'=>$_POST['guru_id'],'hari'=>$_POST['hari'],'jam_mulai'=>$_POST['jam_mulai'],'jam_selesai'=>$_POST['jam_selesai']]);
        flash('success','Jadwal berhasil ditambahkan');
        redirect(base_url('index.php?page=jadwal&action=detail&id='.$_POST['kelas_id']));
    }
    public function edit($id) {
        Auth::requireRole('admin');
        $jadwal=$this->model->find($id);
        $mapelList=(new MapelModel())->all('nama_mapel ASC');
        $guruList=(new GuruModel())->all('nama ASC');
        $error=flash('error');
        require VIEW_PATH.'/admin/jadwal/edit.php';
    }
    public function update($id) {
        Auth::requireRole('admin');
        $this->model->update($id,['mapel_id'=>$_POST['mapel_id'],'guru_id'=>$_POST['guru_id'],'hari'=>$_POST['hari'],'jam_mulai'=>$_POST['jam_mulai'],'jam_selesai'=>$_POST['jam_selesai']]);
        $j=$this->model->find($id);
        flash('success','Jadwal berhasil diperbarui');
        redirect(base_url('index.php?page=jadwal&action=detail&id='.$j['kelas_id']));
    }
    public function destroy($id) {
        Auth::requireRole('admin');
        $j=$this->model->find($id); $kid=$j['kelas_id'];
        $this->model->delete($id);
        flash('success','Jadwal berhasil dihapus');
        redirect(base_url('index.php?page=jadwal&action=detail&id='.$kid));
    }
    public function checkGuru() {
        header('Content-Type: application/json');
        $db=getDB();
        $st=$db->prepare("SELECT COUNT(*) as c FROM jadwal WHERE guru_id=? AND hari=? AND ((jam_mulai<? AND jam_selesai>?) OR (jam_mulai>=? AND jam_selesai<=?))");
        $st->execute([$_GET['guru_id'],$_GET['hari'],$_GET['jam_selesai'],$_GET['jam_mulai'],$_GET['jam_mulai'],$_GET['jam_selesai']]);
        echo json_encode(['available'=>$st->fetch()['c']==0]);
        exit;
    }
}
