<?php
require_once BASE_PATH.'/models/CatatanModel.php';
require_once BASE_PATH.'/models/GuruModel.php';
require_once BASE_PATH.'/models/SiswaModel.php';

class CatatanController {
    public function index() {
        $role=Auth::role(); $db=getDB();
        if($role==='guru') {
            $guru=(new GuruModel())->whereOne('user_id',Auth::id());
            $kelasList=$db->prepare("SELECT DISTINCT k.id,k.nama_kelas,m.nama_mapel,m.id as mapel_id FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mapel m ON j.mapel_id=m.id WHERE j.guru_id=?");
            $kelasList->execute([$guru['id']]); $kelasList=$kelasList->fetchAll();
            $msg=flash('success');
            require VIEW_PATH.'/guru/catatan/index.php';
        } else {
            $siswa=(new SiswaModel())->whereOne('user_id',Auth::id());
            $catatanList=$db->prepare("SELECT c.*,g.nama as guru_nama,m.nama_mapel FROM catatan c JOIN guru g ON c.guru_id=g.id JOIN mapel m ON c.mapel_id=m.id WHERE c.siswa_id=? ORDER BY c.created_at DESC");
            $catatanList->execute([$siswa['id']]); $catatanList=$catatanList->fetchAll();
            require VIEW_PATH.'/siswa/catatan/index.php';
        }
    }
    public function input() {
        $db=getDB();
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        $kelasId=$_GET['kelas_id']; $mapelId=$_GET['mapel_id'];
        $siswaList=$db->prepare("SELECT s.* FROM siswa s JOIN kelas_siswa ks ON s.id=ks.siswa_id WHERE ks.kelas_id=? ORDER BY s.nama");
        $siswaList->execute([$kelasId]); $siswaList=$siswaList->fetchAll();
        $catatanList=$db->prepare("SELECT c.*,s.nama as siswa_nama FROM catatan c JOIN siswa s ON c.siswa_id=s.id WHERE c.guru_id=? AND c.kelas_id=? AND c.mapel_id=? ORDER BY c.created_at DESC");
        $catatanList->execute([$guru['id'],$kelasId,$mapelId]); $catatanList=$catatanList->fetchAll();
        require VIEW_PATH.'/guru/catatan/input.php';
    }
    public function store() {
        $guru=(new GuruModel())->whereOne('user_id',Auth::id());
        (new CatatanModel())->insert(['siswa_id'=>$_POST['siswa_id'],'guru_id'=>$guru['id'],'mapel_id'=>$_POST['mapel_id'],'kelas_id'=>$_POST['kelas_id'],'isi_catatan'=>$_POST['isi_catatan']]);
        flash('success','Catatan berhasil disimpan');
        redirect(base_url('index.php?page=catatan&action=input&kelas_id='.$_POST['kelas_id'].'&mapel_id='.$_POST['mapel_id']));
    }
    public function destroy($id) {
        (new CatatanModel())->delete($id);
        flash('success','Catatan berhasil dihapus');
        redirect(base_url('index.php?page=catatan'));
    }
    public function detail($id) {
        $db=getDB();
        $catatan=$db->prepare("SELECT c.*,g.nama as guru_nama,m.nama_mapel FROM catatan c JOIN guru g ON c.guru_id=g.id JOIN mapel m ON c.mapel_id=m.id WHERE c.id=?");
        $catatan->execute([$id]); $catatan=$catatan->fetch();
        if(Auth::role()==='siswa') require VIEW_PATH.'/siswa/catatan/detail.php';
    }
}
