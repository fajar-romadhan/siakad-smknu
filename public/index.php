<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../config/app.php';
require_once __DIR__.'/../core/Auth.php';

$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Public pages
if ($page === 'login') {
    require_once BASE_PATH.'/controllers/AuthController.php';
    $c = new AuthController();
    if ($_SERVER['REQUEST_METHOD']==='POST') $c->doLogin();
    else $c->login();
    exit;
}
if ($page === 'logout') {
    Auth::logout();
    redirect(base_url('index.php?page=login'));
}

Auth::requireLogin();
$role = Auth::role();

// Route mapping
$routes = [
    'dashboard' => 'DashboardController',
    'guru' => 'GuruController',
    'siswa' => 'SiswaController',
    'mapel' => 'MapelController',
    'kelas' => 'KelasController',
    'jadwal' => 'JadwalController',
    'tahun_ajaran' => 'TahunAjaranController',
    'absensi' => 'AbsensiController',
    'nilai' => 'NilaiController',
    'raport' => 'RaportController',
    'catatan' => 'CatatanController',
    'pengumuman' => 'PengumumanController',
    'pengguna' => 'PenggunaController',
    'profil' => 'ProfilController',
    'kepsek' => 'KepsekController',
];

// Kepala Sekolah: dashboard → KepsekController@dashboard
if ($page === 'dashboard' && $role === 'kepala_sekolah') {
    require_once BASE_PATH.'/controllers/KepsekController.php';
    (new KepsekController())->dashboard();
    exit;
}

// Kepala Sekolah tidak boleh akses halaman CRUD Admin/Guru/Siswa langsung — redirect ke dashboard
if ($role === 'kepala_sekolah' && !in_array($page, ['dashboard','kepsek','profil'])) {
    redirect(base_url('index.php?page=dashboard'));
}

// Special handling for pages that differ by role
if ($page === 'jadwal' && $role !== 'admin' && $role !== 'kepala_sekolah') {
    if ($role === 'guru') {
        require VIEW_PATH.'/guru/jadwal.php';
    } else {
        require VIEW_PATH.'/siswa/jadwal.php';
    }
    exit;
}

if (isset($routes[$page])) {
    require_once BASE_PATH.'/controllers/'.$routes[$page].'.php';
    $controller = new $routes[$page]();
    if (method_exists($controller, $action)) {
        $controller->$action($id);
    } else {
        $controller->index();
    }
} else {
    redirect(base_url('index.php?page=dashboard'));
}
