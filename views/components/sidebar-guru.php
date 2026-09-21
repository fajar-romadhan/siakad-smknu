<div class="sidebar">
    <button class="sidebar-collapse-btn" onclick="toggleSidebarCollapse()" aria-label="Ciutkan menu" title="Ciutkan / Perluas">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <div class="sidebar-logo">
        <img src="<?= base_url('img/logo.png') ?>" alt="Logo" onerror="this.style.display='none'">
        <span class="nav-label-text">SMK NU</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label"><span class="nav-label-text">Menu Guru</span></div>
        <?php $p = $_GET['page'] ?? 'dashboard'; ?>
        <a href="<?= base_url('index.php?page=dashboard') ?>" class="nav-item <?= $p==='dashboard'?'active':'' ?>" data-tip="Dashboard">
            <span class="nav-icon">📊</span> <span class="nav-label-text">Dashboard</span>
        </a>
        <a href="<?= base_url('index.php?page=kelas') ?>" class="nav-item <?= $p==='kelas'?'active':'' ?>" data-tip="Kelas">
            <span class="nav-icon">🏫</span> <span class="nav-label-text">Kelas</span>
        </a>
        <a href="<?= base_url('index.php?page=absensi') ?>" class="nav-item <?= $p==='absensi'?'active':'' ?>" data-tip="Absensi">
            <span class="nav-icon">✅</span> <span class="nav-label-text">Absensi</span>
        </a>
        <a href="<?= base_url('index.php?page=nilai') ?>" class="nav-item <?= $p==='nilai'?'active':'' ?>" data-tip="Nilai">
            <span class="nav-icon">📝</span> <span class="nav-label-text">Nilai</span>
        </a>
        <a href="<?= base_url('index.php?page=catatan') ?>" class="nav-item <?= $p==='catatan'?'active':'' ?>" data-tip="Catatan">
            <span class="nav-icon">📋</span> <span class="nav-label-text">Catatan</span>
        </a>
        <a href="<?= base_url('index.php?page=jadwal') ?>" class="nav-item <?= $p==='jadwal'?'active':'' ?>" data-tip="Jadwal">
            <span class="nav-icon">📅</span> <span class="nav-label-text">Jadwal</span>
        </a>
        <a href="<?= base_url('index.php?page=pengumuman') ?>" class="nav-item <?= $p==='pengumuman'?'active':'' ?>" data-tip="Pengumuman">
            <span class="nav-icon">📢</span> <span class="nav-label-text">Pengumuman</span>
        </a>
        <div class="nav-divider"></div>
        <a href="<?= base_url('index.php?page=profil') ?>" class="nav-item <?= $p==='profil'?'active':'' ?>" data-tip="Profil">
            <span class="nav-icon">👤</span> <span class="nav-label-text">Profil</span>
        </a>
        <a href="#" onclick="confirmLogout('<?= base_url('index.php?page=logout') ?>'); return false;" class="nav-item" data-tip="Logout">
            <span class="nav-icon">🚪</span> <span class="nav-label-text">Logout</span>
        </a>
    </nav>
</div>
