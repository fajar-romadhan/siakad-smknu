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
            $profil=(new SiswaModel())->whereOne('user_id',Auth::id());
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
    public function update() {
        $role=Auth::role();
        if($role==='guru') {
            require_once BASE_PATH.'/models/GuruModel.php';
            $gm=new GuruModel();
            $guru=$gm->whereOne('user_id',Auth::id());
            $gm->update($guru['id'],['nama'=>$_POST['nama'],'alamat'=>$_POST['alamat']??'','no_hp'=>$_POST['no_hp']??'','email'=>$_POST['email']??'']);
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
