<div class="mobile-header">
    <img src="<?= base_url('img/logo.png') ?>" alt="Logo" class="mobile-logo" onerror="this.innerHTML='🏫'">
    <span class="mobile-title">SMK NU MUARA SUGIHAN</span>
    <div class="profile-avatar-sm" onclick="toggleDropdown()">
        <?= strtoupper(substr(Auth::user('nama'),0,1)) ?>
    </div>
    <div class="dropdown-menu mobile-dropdown" id="profileDropdown">
        <a href="<?= base_url('index.php?page=profil') ?>">Profil</a>
        <a href="<?= base_url('index.php?page=logout') ?>">Keluar</a>
    </div>
</div>
