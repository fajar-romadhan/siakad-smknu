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
        <div class="nav-label"><span class="nav-label-text">Monitoring</span></div>
        <?php $p = $_GET['page'] ?? 'dashboard'; $a = $_GET['action'] ?? 'index'; ?>
        <a href="<?= base_url('index.php?page=dashboard') ?>" class="nav-item <?= $p==='dashboard'?'active':'' ?>" data-tip="Dashboard">
            <span class="nav-icon">📊</span> <span class="nav-label-text">Dashboard</span>
        </a>
        <a href="<?= base_url('index.php?page=kepsek&action=guru') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='guru')?'active':'' ?>" data-tip="Data Guru">
            <span class="nav-icon">👨‍🏫</span> <span class="nav-label-text">Data Guru</span>
        </a>
        <a href="<?= base_url('index.php?page=kepsek&action=siswa') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='siswa')?'active':'' ?>" data-tip="Data Siswa">
            <span class="nav-icon">👨‍🎓</span> <span class="nav-label-text">Data Siswa</span>
        </a>
        <a href="<?= base_url('index.php?page=kepsek&action=kelas') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='kelas')?'active':'' ?>" data-tip="Data Kelas">
            <span class="nav-icon">🏫</span> <span class="nav-label-text">Data Kelas</span>
        </a>
        <div class="nav-divider"></div>
        <div class="nav-label"><span class="nav-label-text">Rekap</span></div>
        <a href="<?= base_url('index.php?page=kepsek&action=absensi') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='absensi')?'active':'' ?>" data-tip="Rekap Absensi Kelas">
            <span class="nav-icon">✅</span> <span class="nav-label-text">Absensi Kelas</span>
        </a>
        <a href="<?= base_url('index.php?page=kepsek&action=absensiGuru') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='absensiGuru')?'active':'' ?>" data-tip="Rekap Absensi Guru">
            <span class="nav-icon">👨‍🏫</span> <span class="nav-label-text">Absensi Guru</span>
        </a>
        <a href="<?= base_url('index.php?page=kepsek&action=nilai') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='nilai')?'active':'' ?>" data-tip="Rekap Nilai">
            <span class="nav-icon">📝</span> <span class="nav-label-text">Rekap Nilai</span>
        </a>
        <a href="<?= base_url('index.php?page=kepsek&action=pengumuman') ?>" class="nav-item <?= ($p==='kepsek'&&$a==='pengumuman')?'active':'' ?>" data-tip="Pengumuman">
            <span class="nav-icon">📢</span> <span class="nav-label-text">Pengumuman</span>
        </a>
        <div class="nav-divider"></div>
        <a href="<?= base_url('index.php?page=profil') ?>" class="nav-item <?= $p==='profil'?'active':'' ?>" data-tip="Profil">
            <span class="nav-icon">👤</span> <span class="nav-label-text">Profil</span>
        </a>
        <a href="<?= base_url('index.php?page=logout') ?>" class="nav-item" data-tip="Logout">
            <span class="nav-icon">🚪</span> <span class="nav-label-text">Logout</span>
        </a>
    </nav>
</div>
