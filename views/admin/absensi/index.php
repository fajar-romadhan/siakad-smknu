<?php $pageTitle='Absensi'; ob_start(); ?>
<div class="stats-grid">
    <a href="<?= base_url('index.php?page=absensi&action=rekapSiswa') ?>" class="stat-card clickable"><div class="stat-icon">🏫</div><div class="stat-info"><h3>Rekap Absensi Kelas</h3><p>Lihat rekap absensi per kelas</p></div></a>
    <a href="<?= base_url('index.php?page=absensi&action=rekapGuru') ?>" class="stat-card clickable"><div class="stat-icon">👨‍🏫</div><div class="stat-info"><h3>Rekap Guru</h3><p>Lihat rekap absensi guru</p></div></a>
    <a href="<?= base_url('index.php?page=absensi&action=validasiGuru') ?>" class="stat-card clickable"><div class="stat-icon">🛡️</div><div class="stat-info"><h3>Validasi Absensi Guru</h3><p>Setujui absensi guru yang diinput sendiri</p></div></a>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
