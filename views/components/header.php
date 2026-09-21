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
            <?php 
            $headerFotoUrl = null;
            if (Auth::role() === 'guru') {
                $hGuru = getDB()->prepare("SELECT foto FROM guru WHERE user_id=? LIMIT 1");
                $hGuru->execute([Auth::id()]);
                $hGuruRow = $hGuru->fetch();
                if (!empty($hGuruRow['foto']) && file_exists(BASE_PATH . '/public/uploads/guru/' . $hGuruRow['foto'])) {
                    $headerFotoUrl = base_url('uploads/guru/' . $hGuruRow['foto']);
                }
            }
            ?>
            <div class="profile-avatar" onclick="toggleDropdown()" title="Menu profil" style="<?= $headerFotoUrl ? 'background:none;padding:0;' : '' ?>">
                <?php if ($headerFotoUrl): ?>
                    <img src="<?= $headerFotoUrl ?>" alt="Avatar" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                <?php else: ?>
                    <?= strtoupper(substr(Auth::user('nama'),0,1)) ?>
                <?php endif; ?>
            </div>
            <div class="dropdown-menu" id="profileDropdown">
                <a href="<?= base_url('index.php?page=profil') ?>">Profil</a>
                <a href="<?= base_url('index.php?page=profil&action=password') ?>">Ganti Password</a>
                <a href="<?= base_url('index.php?page=logout') ?>">Keluar</a>
            </div>
        </div>
    </div>
</div>
