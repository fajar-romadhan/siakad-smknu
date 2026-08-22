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
        <div class="nav-label"><span class="nav-label-text">Menu</span></div>
        <?php $p = $_GET['page'] ?? 'dashboard'; ?>
        <a href="<?= base_url('index.php?page=dashboard') ?>" class="nav-item <?= $p==='dashboard'?'active':'' ?>" data-tip="Dashboard">
            <span class="nav-icon">📊</span> <span class="nav-label-text">Dashboard</span>
        </a>
        <a href="<?= base_url('index.php?page=guru') ?>" class="nav-item <?= $p==='guru'?'active':'' ?>" data-tip="Data Guru">
            <span class="nav-icon">👨‍🏫</span> <span class="nav-label-text">Data Guru</span>
        </a>
        <a href="<?= base_url('index.php?page=siswa') ?>" class="nav-item <?= $p==='siswa'?'active':'' ?>" data-tip="Data Siswa">
            <span class="nav-icon">👨‍🎓</span> <span class="nav-label-text">Data Siswa</span>
        </a>
        <a href="<?= base_url('index.php?page=mapel') ?>" class="nav-item <?= $p==='mapel'?'active':'' ?>" data-tip="Mata Pelajaran">
            <span class="nav-icon">📚</span> <span class="nav-label-text">Mata Pelajaran</span>
        </a>
        <a href="<?= base_url('index.php?page=kelas') ?>" class="nav-item <?= $p==='kelas'?'active':'' ?>" data-tip="Kelas">
            <span class="nav-icon">🏫</span> <span class="nav-label-text">Kelas</span>
        </a>
        <a href="<?= base_url('index.php?page=jadwal') ?>" class="nav-item <?= $p==='jadwal'?'active':'' ?>" data-tip="Jadwal">
            <span class="nav-icon">📅</span> <span class="nav-label-text">Jadwal</span>
        </a>
        <a href="<?= base_url('index.php?page=tahun_ajaran') ?>" class="nav-item <?= $p==='tahun_ajaran'?'active':'' ?>" data-tip="Tahun Ajaran">
            <span class="nav-icon">📆</span> <span class="nav-label-text">Tahun Ajaran</span>
        </a>
        <a href="<?= base_url('index.php?page=absensi') ?>" class="nav-item <?= $p==='absensi'?'active':'' ?>" data-tip="Absensi">
            <span class="nav-icon">✅</span> <span class="nav-label-text">Absensi</span>
        </a>
        <a href="<?= base_url('index.php?page=raport') ?>" class="nav-item <?= $p==='raport'?'active':'' ?>" data-tip="Cetak Raport">
            <span class="nav-icon">📄</span> <span class="nav-label-text">Cetak Raport</span>
        </a>
        <a href="<?= base_url('index.php?page=pengumuman') ?>" class="nav-item <?= $p==='pengumuman'?'active':'' ?>" data-tip="Pengumuman">
            <span class="nav-icon">📢</span> <span class="nav-label-text">Pengumuman</span>
        </a>
        <a href="<?= base_url('index.php?page=pengguna') ?>" class="nav-item <?= $p==='pengguna'?'active':'' ?>" data-tip="Kelola Pengguna">
            <span class="nav-icon">👥</span> <span class="nav-label-text">Kelola Pengguna</span>
        </a>
        <div class="nav-divider"></div>
        <a href="<?= base_url('index.php?page=logout') ?>" class="nav-item" data-tip="Logout">
            <span class="nav-icon">🚪</span> <span class="nav-label-text">Logout</span>
        </a>
    </nav>
</div>
