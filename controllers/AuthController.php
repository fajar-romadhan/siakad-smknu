<?php
class AuthController {
    public function login() {
        if (Auth::check()) redirect(base_url('index.php?page=dashboard'));
        $error = flash('error');
        require VIEW_PATH.'/auth/login.php';
    }
    public function doLogin() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$username || !$password) { flash('error','Username dan password harus diisi'); redirect(base_url('index.php?page=login')); }
        require_once BASE_PATH.'/models/UserModel.php';
        $m = new UserModel();
        $user = $m->whereOne('username', $username);
        if (!$user || !password_verify($password, $user['password'])) {
            flash('error','Username atau password salah');
            redirect(base_url('index.php?page=login'));
        }
        if ($user['status']==='nonaktif') { flash('error','Akun nonaktif'); redirect(base_url('index.php?page=login')); }
        Auth::login($user);
        redirect(base_url('index.php?page=dashboard'));
    }
}
