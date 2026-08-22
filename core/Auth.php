<?php
class Auth {
    public static function start() { if(session_status()===PHP_SESSION_NONE) session_start(); }
    public static function login($user) {
        self::start();
        $_SESSION['user_id']=$user['id'];
        $_SESSION['username']=$user['username'];
        $_SESSION['nama']=$user['nama'];
        $_SESSION['role']=$user['role'];
        $_SESSION['foto']=$user['foto'] ?? null;
        getDB()->prepare("UPDATE users SET last_login=NOW() WHERE id=?")->execute([$user['id']]);
    }
    public static function logout() {
        self::start();
        // 1. Kosongkan array session
        $_SESSION = [];
        // 2. Hapus cookie session di browser
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        // 3. Destroy file session di server
        @session_destroy();
    }
    public static function check() { self::start(); return isset($_SESSION['user_id']); }
    public static function user($k=null) { self::start(); return $k ? ($_SESSION[$k]??null) : ($_SESSION??null); }
    public static function id() { return self::user('user_id'); }
    public static function role() { return self::user('role'); }
    public static function requireLogin() { if(!self::check()) redirect(base_url('index.php?page=login')); }
    public static function requireRole($r) { self::requireLogin(); if(self::role()!==$r) { http_response_code(403); die('Akses ditolak'); } }
}
