<?php
require_once BASE_PATH.'/models/GuruModel.php';
require_once BASE_PATH.'/models/UserModel.php';

class GuruController {
    private $model;
    public function __construct() { $this->model = new GuruModel(); }

    // Daftar kolom biodata guru (selain kode/user/timestamp) untuk store & update
    private function biodata() {
        return [
            'nuptk'              => $_POST['nuptk']              ?? null,
            'nik'                => $_POST['nik']                ?? null,
            'nip'                => $_POST['nip']                ?? null,
            'nama'               => trim($_POST['nama'] ?? ''),
            'jenis_kelamin'      => $_POST['jenis_kelamin']      ?? 'Laki-Laki',
            'tempat_lahir'       => $_POST['tempat_lahir']       ?? null,
            'tanggal_lahir'      => $_POST['tanggal_lahir']      ?: null,
            'alamat'             => $_POST['alamat']             ?? null,
            'no_hp'              => $_POST['no_hp']              ?? null,
            'email'              => $_POST['email']              ?? null,
            'agama'              => $_POST['agama']              ?? null,
            'status_kepegawaian' => $_POST['status_kepegawaian'] ?? null,
            'jenis_ptk'          => $_POST['jenis_ptk']          ?? null,
            'gelar'              => $_POST['gelar']              ?? null,
            'jenjang_pendidikan' => $_POST['jenjang_pendidikan'] ?? null,
            'jurusan_prodi'      => $_POST['jurusan_prodi']      ?? null,
            'tmt_kerja'          => $_POST['tmt_kerja']          ?: null,
            'tugas_tambahan'     => $_POST['tugas_tambahan']     ?? null,
        ];
    }

    public function index() {
        Auth::requireRole('admin');
        $data = $this->model->query("SELECT g.*,u.username FROM guru g LEFT JOIN users u ON g.user_id=u.id ORDER BY g.nama");
        require VIEW_PATH.'/admin/guru/index.php';
    }
    public function create() {
        Auth::requireRole('admin');
        $msg = flash('success'); $error = flash('error');
        require VIEW_PATH.'/admin/guru/create.php';
    }
    private function handleUploadFoto($inputName = 'foto', $existingFoto = null) {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return $existingFoto;
        }

        $file = $_FILES[$inputName];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowedExts, true)) {
            flash('error', 'Format foto harus berupa JPG, JPEG, PNG, atau WEBP.');
            return $existingFoto;
        }

        if ($file['size'] > 3 * 1024 * 1024) {
            flash('error', 'Ukuran file foto maksimal 3MB.');
            return $existingFoto;
        }

        $uploadDir = BASE_PATH . '/public/uploads/guru';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $filename = 'guru_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            if ($existingFoto && file_exists($uploadDir . '/' . $existingFoto) && is_file($uploadDir . '/' . $existingFoto)) {
                @unlink($uploadDir . '/' . $existingFoto);
            }
            return $filename;
        }

        return $existingFoto;
    }

    public function store() {
        Auth::requireRole('admin');
        $kode  = $this->model->generateKode('G-');
        $nama  = trim($_POST['nama']);
        $tahun = $_POST['tahun_masuk'];
        $um = new UserModel();
        $username = $um->generateUsernameFromNameAndYear($nama, $tahun);
        $pass = 'guru123';
        $userId = $um->insert([
            'username' => $username,
            'password' => password_hash($pass,PASSWORD_DEFAULT),
            'nama'     => $nama,
            'role'     => 'guru',
            'status'   => 'aktif'
        ]);
        $foto = $this->handleUploadFoto('foto', null);
        $data = array_merge($this->biodata(), [
            'kode_guru'   => $kode,
            'user_id'     => $userId,
            'tahun_masuk' => $tahun,
            'foto'        => $foto,
        ]);
        $this->model->insert($data);
        flash('success','Data guru berhasil ditambahkan');
        redirect(base_url('index.php?page=guru'));
    }
    public function detail($id) {
        Auth::requireRole('admin');
        $guru = $this->model->find($id);
        if(!$guru) redirect(base_url('index.php?page=guru'));
        require VIEW_PATH.'/admin/guru/detail.php';
    }
    public function edit($id) {
        Auth::requireRole('admin');
        $guru = $this->model->find($id);
        if(!$guru) redirect(base_url('index.php?page=guru'));
        $msg = flash('success');
        require VIEW_PATH.'/admin/guru/edit.php';
    }
    public function update($id) {
        Auth::requireRole('admin');
        $guru = $this->model->find($id);
        $foto = $this->handleUploadFoto('foto', $guru['foto'] ?? null);
        $data = array_merge($this->biodata(), [
            'foto' => $foto,
        ]);
        if ($guru && $guru['user_id']) {
            (new UserModel())->update($guru['user_id'], ['nama'=>$data['nama']]);
        }
        $this->model->update($id, $data);
        flash('success','Data guru berhasil diperbarui');
        redirect(base_url('index.php?page=guru&action=edit&id='.$id));
    }
    public function destroy($id) {
        Auth::requireRole('admin');
        $guru=$this->model->find($id);
        if($guru && $guru['user_id']) (new UserModel())->delete($guru['user_id']);
        $this->model->delete($id);
        flash('success','Data guru berhasil dihapus');
        redirect(base_url('index.php?page=guru'));
    }
}
