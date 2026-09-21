<?php
class ProfilController {
    public function index() {
        $db=getDB(); $role=Auth::role();
        if($role==='guru') {
            require_once BASE_PATH.'/models/GuruModel.php';
            $profil=(new GuruModel())->whereOne('user_id',Auth::id());
            $msg=flash('success');
            require VIEW_PATH.'/guru/profil/index.php';
        } elseif($role==='siswa') {
            require_once BASE_PATH.'/models/SiswaModel.php';
            $siswaModel = new SiswaModel();
            $stP = $db->prepare("SELECT s.*, k.nama_kelas FROM siswa s LEFT JOIN kelas_siswa ks ON s.id=ks.siswa_id LEFT JOIN kelas k ON ks.kelas_id=k.id WHERE s.user_id=? LIMIT 1");
            $stP->execute([Auth::id()]);
            $profil = $stP->fetch();
            if (!$profil) {
                $profil = $siswaModel->whereOne('user_id', Auth::id());
            }
            require VIEW_PATH.'/siswa/profil.php';
        } else {
            $profil=$db->prepare("SELECT * FROM users WHERE id=?"); $profil->execute([Auth::id()]); $profil=$profil->fetch();
            $msg=flash('success');
            require VIEW_PATH.'/admin/profil/index.php';
        }
    }
    public function edit() {
        $role=Auth::role();
        if($role==='guru') {
            require_once BASE_PATH.'/models/GuruModel.php';
            $profil=(new GuruModel())->whereOne('user_id',Auth::id());
            require VIEW_PATH.'/guru/profil/edit.php';
        } elseif($role==='admin') {
            $db=getDB();
            $profil=$db->prepare("SELECT * FROM users WHERE id=?"); $profil->execute([Auth::id()]); $profil=$profil->fetch();
            $error=flash('error');
            require VIEW_PATH.'/admin/profil/edit.php';
        }
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

    public function update() {
        $role=Auth::role();
        if($role==='guru') {
            require_once BASE_PATH.'/models/GuruModel.php';
            $gm=new GuruModel();
            $guru=$gm->whereOne('user_id',Auth::id());
            $foto = $this->handleUploadFoto('foto', $guru['foto'] ?? null);
            $updateData = [
                'nama'=>$_POST['nama'],
                'alamat'=>$_POST['alamat']??'',
                'no_hp'=>$_POST['no_hp']??'',
                'email'=>$_POST['email']??''
            ];
            if ($foto) {
                $updateData['foto'] = $foto;
            }
            $gm->update($guru['id'], $updateData);
            getDB()->prepare("UPDATE users SET nama=? WHERE id=?")->execute([$_POST['nama'],Auth::id()]);
            $_SESSION['nama']=$_POST['nama'];
        } elseif($role==='admin') {
            getDB()->prepare("UPDATE users SET nama=? WHERE id=?")->execute([$_POST['nama'],Auth::id()]);
            $_SESSION['nama']=$_POST['nama'];
        }
        flash('success','Profil berhasil diperbarui');
        redirect(base_url('index.php?page=profil'));
    }
    public function password() {
        $msg=flash('success'); $error=flash('error');
        require VIEW_PATH.'/admin/profil/password.php';
    }
    public function updatePassword() {
        $db=getDB();
        $user=$db->prepare("SELECT * FROM users WHERE id=?"); $user->execute([Auth::id()]); $user=$user->fetch();
        if(!password_verify($_POST['password_lama'],$user['password'])) { flash('error','Password lama salah'); redirect(base_url('index.php?page=profil&action=password')); }
        if($_POST['password_baru']!==$_POST['konfirmasi']) { flash('error','Konfirmasi tidak cocok'); redirect(base_url('index.php?page=profil&action=password')); }
        $db->prepare("UPDATE users SET password=? WHERE id=?")->execute([password_hash($_POST['password_baru'],PASSWORD_DEFAULT),Auth::id()]);
        flash('success','Password berhasil diubah');
        redirect(base_url('index.php?page=profil'));
    }
}
