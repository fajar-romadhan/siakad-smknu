<?php
require_once BASE_PATH.'/models/TahunAjaranModel.php';
class TahunAjaranController {
    private $model;
    public function __construct() { $this->model=new TahunAjaranModel(); }
    public function index() {
        Auth::requireRole('admin');
        $data=$this->model->all('tahun_ajaran DESC');
        $msg=flash('success');
        require VIEW_PATH.'/admin/tahun-ajaran/index.php';
    }
    public function create() { Auth::requireRole('admin'); require VIEW_PATH.'/admin/tahun-ajaran/create.php'; }
    public function store() {
        Auth::requireRole('admin');
        $kode='TA'.str_replace('/','',$_POST['tahun_ajaran']);
        if($_POST['status']==='aktif') getDB()->exec("UPDATE tahun_ajaran SET status='nonaktif'");
        $this->model->insert(['kode'=>$kode,'tahun_ajaran'=>$_POST['tahun_ajaran'],'semester_aktif'=>$_POST['semester_aktif'],'status'=>$_POST['status']]);
        flash('success','Tahun ajaran berhasil ditambahkan');
        redirect(base_url('index.php?page=tahun_ajaran'));
    }
    public function edit($id) { Auth::requireRole('admin'); $ta=$this->model->find($id); require VIEW_PATH.'/admin/tahun-ajaran/edit.php'; }
    public function update($id) {
        Auth::requireRole('admin');
        if($_POST['status']==='aktif') getDB()->exec("UPDATE tahun_ajaran SET status='nonaktif'");
        $this->model->update($id,['tahun_ajaran'=>$_POST['tahun_ajaran'],'semester_aktif'=>$_POST['semester_aktif'],'status'=>$_POST['status']]);
        flash('success','Tahun ajaran berhasil diperbarui');
        redirect(base_url('index.php?page=tahun_ajaran'));
    }
}
