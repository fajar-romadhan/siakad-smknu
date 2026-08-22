<?php
require_once BASE_PATH.'/models/SiswaModel.php';
require_once BASE_PATH.'/models/UserModel.php';

class SiswaController {
    private $model;
    public function __construct() { $this->model = new SiswaModel(); }

    // Semua field biodata siswa untuk store & update
    private function biodata() {
        return [
            'nipd'             => $_POST['nipd']             ?? null,
            'nik'              => $_POST['nik']              ?? null,
            'nama'             => trim($_POST['nama'] ?? ''),
            'jenis_kelamin'    => $_POST['jenis_kelamin']    ?? 'Laki-Laki',
            'tempat_lahir'     => $_POST['tempat_lahir']     ?? null,
            'tanggal_lahir'    => $_POST['tanggal_lahir']    ?: null,
            'agama'            => $_POST['agama']            ?? null,
            'alamat'           => $_POST['alamat']           ?? null,
            'rt'               => $_POST['rt']               ?? null,
            'rw'               => $_POST['rw']               ?? null,
            'kelurahan'        => $_POST['kelurahan']        ?? null,
            'kecamatan'        => $_POST['kecamatan']        ?? null,
            'kode_pos'         => $_POST['kode_pos']         ?? null,
            'no_hp'            => $_POST['no_hp']            ?? null,
            'penerima_kip'     => $_POST['penerima_kip']     ?? 'tidak',
            'nomor_kip'        => ($_POST['penerima_kip'] ?? 'tidak')==='ya' ? ($_POST['nomor_kip'] ?? null) : null,
            'sekolah_asal'     => $_POST['sekolah_asal']     ?? null,
            'jenis_tinggal'    => $_POST['jenis_tinggal']    ?? null,
            'anak_ke'          => ($_POST['anak_ke'] ?? '')          !== '' ? $_POST['anak_ke'] : null,
            'jml_saudara'      => ($_POST['jml_saudara'] ?? '')      !== '' ? $_POST['jml_saudara'] : null,
            'jarak_sekolah_km' => ($_POST['jarak_sekolah_km'] ?? '') !== '' ? $_POST['jarak_sekolah_km'] : null,
            // Data Ayah
            'ayah_nama'        => $_POST['ayah_nama']        ?? null,
            'ayah_nik'         => $_POST['ayah_nik']         ?? null,
            'ayah_tahun_lahir' => $_POST['ayah_tahun_lahir'] ?: null,
            'ayah_pendidikan'  => $_POST['ayah_pendidikan']  ?? null,
            'ayah_pekerjaan'   => $_POST['ayah_pekerjaan']   ?? null,
            // Data Ibu
            'ibu_nama'         => $_POST['ibu_nama']         ?? null,
            'ibu_nik'          => $_POST['ibu_nik']          ?? null,
            'ibu_tahun_lahir'  => $_POST['ibu_tahun_lahir']  ?: null,
            'ibu_pendidikan'   => $_POST['ibu_pendidikan']   ?? null,
            'ibu_pekerjaan'    => $_POST['ibu_pekerjaan']    ?? null,
            // kompatibilitas kolom lama
            'wali_siswa'       => $_POST['ayah_nama'] ?? ($_POST['wali_siswa'] ?? null),
        ];
    }

    public function index() {
        Auth::requireRole('admin');
        $data = $this->model->query("SELECT s.*,u.username,k.nama_kelas FROM siswa s LEFT JOIN users u ON s.user_id=u.id LEFT JOIN kelas_siswa ks ON ks.siswa_id=s.id LEFT JOIN kelas k ON k.id=ks.kelas_id ORDER BY s.nama");
        require VIEW_PATH.'/admin/siswa/index.php';
    }
    public function create() {
        Auth::requireRole('admin');
        $msg=flash('success'); $error=flash('error');
        require VIEW_PATH.'/admin/siswa/create.php';
    }
    public function store() {
        Auth::requireRole('admin');
        $kode=$this->model->generateKode('SW');
        $nama=trim($_POST['nama']); $nisn=trim($_POST['nisn']); $tahun=$_POST['tahun_masuk'];
        $um=new UserModel();
        $username=$um->generateUsernameFromNameAndYear($nama, $tahun);
        $pass='siswa123';
        $userId=$um->insert(['username'=>$username,'password'=>password_hash($pass,PASSWORD_DEFAULT),'nama'=>$nama,'role'=>'siswa','status'=>'aktif']);
        $data=array_merge($this->biodata(),[
            'kode_siswa'=>$kode,'user_id'=>$userId,'nisn'=>$nisn,'tahun_masuk'=>$tahun,
        ]);
        $this->model->insert($data);
        flash('success','Data siswa berhasil ditambahkan');
        redirect(base_url('index.php?page=siswa'));
    }
    public function detail($id) {
        Auth::requireRole('admin');
        $siswa=$this->model->find($id);
        if(!$siswa) redirect(base_url('index.php?page=siswa'));
        require VIEW_PATH.'/admin/siswa/detail.php';
    }
    public function edit($id) {
        Auth::requireRole('admin');
        $siswa=$this->model->find($id);
        if(!$siswa) redirect(base_url('index.php?page=siswa'));
        $msg=flash('success');
        require VIEW_PATH.'/admin/siswa/edit.php';
    }
    public function update($id) {
        Auth::requireRole('admin');
        $data=$this->biodata();
        $s=$this->model->find($id);
        if($s && $s['user_id']) (new UserModel())->update($s['user_id'],['nama'=>$data['nama']]);
        $this->model->update($id,$data);
        flash('success','Data siswa berhasil diperbarui');
        redirect(base_url('index.php?page=siswa&action=edit&id='.$id));
    }
    public function destroy($id) {
        Auth::requireRole('admin');
        $s=$this->model->find($id);
        if($s && $s['user_id']) (new UserModel())->delete($s['user_id']);
        $this->model->delete($id);
        flash('success','Data siswa berhasil dihapus');
        redirect(base_url('index.php?page=siswa'));
    }
}
