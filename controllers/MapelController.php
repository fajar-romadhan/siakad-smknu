<?php
require_once BASE_PATH.'/models/MapelModel.php';
require_once BASE_PATH.'/models/TahunAjaranModel.php';

class MapelController {
    private $model;
    public function __construct() { $this->model = new MapelModel(); }
    public function index() {
        Auth::requireRole('admin');
        $data=$this->model->query("SELECT mp.*,ta.tahun_ajaran FROM mapel mp JOIN tahun_ajaran ta ON mp.tahun_ajaran_id=ta.id ORDER BY mp.tingkat,mp.nama_mapel");
        $totalX=$this->model->count("tingkat='X'");
        $totalXI=$this->model->count("tingkat='XI'");
        $totalXII=$this->model->count("tingkat='XII'");
        $msg=flash('success');
        require VIEW_PATH.'/admin/mapel/index.php';
    }
    public function create() {
        Auth::requireRole('admin');
        $tahunAjaran=(new TahunAjaranModel())->all('tahun_ajaran DESC');
        require VIEW_PATH.'/admin/mapel/create.php';
    }
    public function store() {
        Auth::requireRole('admin');
        $kode=$this->model->generateKode('MP');
        $this->model->insert(['kode_mapel'=>$kode,'nama_mapel'=>$_POST['nama_mapel'],'semester'=>$_POST['semester'],'tingkat'=>$_POST['tingkat'],'tahun_ajaran_id'=>$_POST['tahun_ajaran_id']]);
        flash('success','Mata pelajaran berhasil ditambahkan');
        redirect(base_url('index.php?page=mapel'));
    }
    public function edit($id) {
        Auth::requireRole('admin');
        $mapel=$this->model->find($id);
        $tahunAjaran=(new TahunAjaranModel())->all();
        require VIEW_PATH.'/admin/mapel/edit.php';
    }
    public function update($id) {
        Auth::requireRole('admin');
        $this->model->update($id,['nama_mapel'=>$_POST['nama_mapel'],'semester'=>$_POST['semester'],'tingkat'=>$_POST['tingkat'],'tahun_ajaran_id'=>$_POST['tahun_ajaran_id']]);
        flash('success','Mata pelajaran berhasil diperbarui');
        redirect(base_url('index.php?page=mapel'));
    }
    public function destroy($id) {
        Auth::requireRole('admin');
        $this->model->delete($id);
        flash('success','Mata pelajaran berhasil dihapus');
        redirect(base_url('index.php?page=mapel'));
    }
}
