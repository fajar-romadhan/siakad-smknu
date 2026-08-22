<?php
require_once BASE_PATH.'/models/PengumumanModel.php';

class PengumumanController {
    private $model;
    public function __construct() { $this->model=new PengumumanModel(); }
    public function index() {
        $role=Auth::role(); $db=getDB();
        if($role==='admin') {
            // Auto update status
            $db->exec("UPDATE pengumuman SET status='aktif' WHERE status='terjadwal' AND tanggal_publish<=CURDATE()");
            $data=$this->model->all('tanggal_publish DESC');
            $totalAktif=$this->model->count("status='aktif'");
            $totalDraft=$this->model->count("status='draft'");
            $totalTerjadwal=$this->model->count("status='terjadwal'");
            $msg=flash('success');
            require VIEW_PATH.'/admin/pengumuman/index.php';
        } else {
            $penerima=$role;
            $pengumumanList=$db->prepare("SELECT * FROM pengumuman WHERE FIND_IN_SET(?,penerima) AND status='aktif' ORDER BY tanggal_publish DESC");
            $pengumumanList->execute([$penerima]); $pengumumanList=$pengumumanList->fetchAll();
            if($role==='guru') require VIEW_PATH.'/guru/pengumuman/index.php';
            else require VIEW_PATH.'/siswa/pengumuman/index.php';
        }
    }
    public function create() { Auth::requireRole('admin'); require VIEW_PATH.'/admin/pengumuman/create.php'; }
    public function store() {
        Auth::requireRole('admin');
        $penerima=implode(',', $_POST['penerima']??[]);
        $status = (strtotime($_POST['tanggal_publish'])>time()) ? 'terjadwal' : 'aktif';
        if(isset($_POST['draft'])) $status='draft';
        $this->model->insert(['judul'=>$_POST['judul'],'isi'=>$_POST['isi'],'tanggal_publish'=>$_POST['tanggal_publish'],'penerima'=>$penerima,'status'=>$status,'created_by'=>Auth::id()]);
        flash('success','Pengumuman berhasil dibuat');
        redirect(base_url('index.php?page=pengumuman'));
    }
    public function detail($id) {
        $p=$this->model->find($id);
        $role=Auth::role();
        if($role==='admin') require VIEW_PATH.'/admin/pengumuman/detail.php';
        elseif($role==='guru') require VIEW_PATH.'/guru/pengumuman/detail.php';
        else require VIEW_PATH.'/siswa/pengumuman/detail.php';
    }
    public function edit($id) { Auth::requireRole('admin'); $p=$this->model->find($id); require VIEW_PATH.'/admin/pengumuman/edit.php'; }
    public function update($id) {
        Auth::requireRole('admin');
        $penerima=implode(',',$_POST['penerima']??[]);
        $status=(strtotime($_POST['tanggal_publish'])>time())?'terjadwal':'aktif';
        if(isset($_POST['draft'])) $status='draft';
        $this->model->update($id,['judul'=>$_POST['judul'],'isi'=>$_POST['isi'],'tanggal_publish'=>$_POST['tanggal_publish'],'penerima'=>$penerima,'status'=>$status]);
        flash('success','Pengumuman berhasil diperbarui');
        redirect(base_url('index.php?page=pengumuman'));
    }
    public function destroy($id) { Auth::requireRole('admin'); $this->model->delete($id); flash('success','Pengumuman berhasil dihapus'); redirect(base_url('index.php?page=pengumuman')); }
}
