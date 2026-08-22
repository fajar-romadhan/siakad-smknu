<div class="top-header">
    <div class="header-left">
        <h2><?= $pageTitle ?? 'Dashboard' ?></h2>
    </div>
    <div class="header-center">
        <h1>SMK NU MUARA SUGIHAN</h1>
    </div>
    <div class="header-right">
        <span>Selamat datang, <?= htmlspecialchars(Auth::user('nama')) ?></span>
        <div class="profile-dropdown">
            <div class="profile-avatar" onclick="toggleDropdown()" title="Menu profil">
                <?= strtoupper(substr(Auth::user('nama'),0,1)) ?>
            </div>
            <div class="dropdown-menu" id="profileDropdown">
                <a href="<?= base_url('index.php?page=profil') ?>">Profil</a>
                <a href="<?= base_url('index.php?page=profil&action=password') ?>">Ganti Password</a>
                <a href="<?= base_url('index.php?page=logout') ?>">Keluar</a>
            </div>
        </div>
    </div>
</div>
