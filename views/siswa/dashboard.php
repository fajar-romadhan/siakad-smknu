<?php $pageTitle='Dashboard'; ob_start(); 
$total = array_sum($statsAbsen);
$persen = $total>0 ? round($statsAbsen['Hadir']/$total*100) : 100;
?>
<div class="greeting">
    <h3>Halo, <?= htmlspecialchars($siswa['nama'] ?? Auth::user('nama')) ?> 👋</h3>
    <p>Selamat datang kembali</p>
    <?php if($kelas): ?><small>Kelas <?= htmlspecialchars($kelas['nama_kelas']) ?> • Wali: <?= htmlspecialchars($kelas['wali']??'-') ?></small><?php endif; ?>
</div>

<div class="mobile-card">
    <h4>Statistik Kehadiran (1 Semester)</h4>
    <div class="stats-circle">
        <div class="circle" style="--persen:<?= $persen ?>;"><?= $persen ?>%</div>
        <div class="stats-detail">
            <span>✅ Hadir: <strong><?= $statsAbsen['Hadir'] ?></strong></span>
            <span>📄 Izin: <strong><?= $statsAbsen['Izin'] ?></strong></span>
            <span>🏥 Sakit: <strong><?= $statsAbsen['Sakit'] ?></strong></span>
            <span>❌ Alpa: <strong><?= $statsAbsen['Alpa'] ?></strong></span>
        </div>
    </div>
</div>

<div class="mobile-card">
    <h4>Jadwal Hari Ini</h4>
    <?php if(empty($jadwalHari)): ?>
        <p class="text-muted" style="text-align:center;padding:16px 8px;">Tidak ada jadwal hari ini 🎉</p>
    <?php else: foreach($jadwalHari as $j): ?>
        <div class="schedule-item">
            <span class="time"><?= substr($j['jam_mulai'],0,5).' - '.substr($j['jam_selesai'],0,5) ?></span>
            <div>
                <strong><?= htmlspecialchars($j['nama_mapel']) ?></strong>
                <br><small>Guru: <?= htmlspecialchars($j['guru_nama']) ?></small>
            </div>
        </div>
    <?php endforeach; endif; ?>
</div>

<div class="mobile-card">
    <h4>Akses Cepat</h4>
    <div class="quick-links">
        <a href="<?= base_url('index.php?page=nilai') ?>" class="quick-link">📝 Nilai Saya</a>
        <a href="<?= base_url('index.php?page=catatan') ?>" class="quick-link">📋 Catatan Guru</a>
        <a href="<?= base_url('index.php?page=jadwal') ?>" class="quick-link">📅 Jadwal Lengkap</a>
        <a href="<?= base_url('index.php?page=pengumuman') ?>" class="quick-link">📢 Pengumuman</a>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
