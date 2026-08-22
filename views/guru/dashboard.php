<?php $pageTitle = 'Dashboard Guru'; ob_start();
// Hitung stats guru
$db = getDB();
require_once BASE_PATH.'/models/GuruModel.php';
$guru = (new GuruModel())->whereOne('user_id', Auth::id());
$totalMapel = 0; $totalKelas = 0; $absenBulan = 0;
if ($guru) {
    $q1 = $db->prepare("SELECT COUNT(DISTINCT mapel_id) FROM jadwal WHERE guru_id=?");
    $q1->execute([$guru['id']]);
    $totalMapel = (int)$q1->fetchColumn();
    $q2 = $db->prepare("SELECT COUNT(DISTINCT kelas_id) FROM jadwal WHERE guru_id=?");
    $q2->execute([$guru['id']]);
    $totalKelas = (int)$q2->fetchColumn();
    $q3 = $db->prepare("SELECT COUNT(*) FROM absensi_guru WHERE guru_id=? AND MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE()) AND status='Hadir'");
    $q3->execute([$guru['id']]);
    $absenBulan = (int)$q3->fetchColumn();
}
?>
<div class="dash-header-row">
    <h2 class="page-title">Halo, <?= htmlspecialchars(Auth::user('nama')) ?> 👋</h2>
    <div class="search-bar-figma">
        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" placeholder="Search">
    </div>
</div>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">📚</div><div class="stat-info"><h3><?= $totalMapel ?></h3><p>Mata Pelajaran Diampu</p></div></div>
    <div class="stat-card"><div class="stat-icon">🏫</div><div class="stat-info"><h3><?= $totalKelas ?></h3><p>Kelas Mengajar</p></div></div>
    <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-info"><h3><?= $absenBulan ?></h3><p>Hadir Bulan Ini</p></div></div>
    <div class="stat-card"><div class="stat-icon">📅</div><div class="stat-info"><h3><?= count($jadwalHari) ?></h3><p>Jadwal Hari Ini</p></div></div>
</div>
<div class="dashboard-grid">
    <div class="card">
        <div class="card-header"><h3>Jadwal Hari Ini</h3></div>
        <div class="card-body">
            <?php if(empty($jadwalHari)): ?>
                <p class="empty-state">Tidak ada jadwal mengajar hari ini. Nikmati waktu istirahat! ☕</p>
            <?php else: ?>
            <div class="table-responsive"><table class="table table-sm">
                <thead><tr><th style="width:140px;">Jam</th><th>Mata Pelajaran</th><th>Kelas</th></tr></thead>
                <tbody>
                    <?php foreach($jadwalHari as $j): ?>
                    <tr>
                        <td><span class="role-badge"><?= substr($j['jam_mulai'],0,5).' - '.substr($j['jam_selesai'],0,5) ?></span></td>
                        <td><strong><?= htmlspecialchars($j['nama_mapel']) ?></strong></td>
                        <td><?= htmlspecialchars($j['nama_kelas']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Akses Cepat</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <a href="<?= base_url('index.php?page=absensi') ?>" class="quick-link" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:20px;background:var(--primary-soft);border-radius:var(--radius);text-decoration:none;color:var(--gray-700);border:1px solid rgba(var(--primary-rgb),.1);transition:var(--transition);"><span style="font-size:32px;">✅</span><strong style="font-size:13px;">Input Absensi</strong></a>
                <a href="<?= base_url('index.php?page=nilai') ?>" class="quick-link" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:20px;background:var(--primary-soft);border-radius:var(--radius);text-decoration:none;color:var(--gray-700);border:1px solid rgba(var(--primary-rgb),.1);transition:var(--transition);"><span style="font-size:32px;">📝</span><strong style="font-size:13px;">Input Nilai</strong></a>
                <a href="<?= base_url('index.php?page=catatan') ?>" class="quick-link" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:20px;background:var(--primary-soft);border-radius:var(--radius);text-decoration:none;color:var(--gray-700);border:1px solid rgba(var(--primary-rgb),.1);transition:var(--transition);"><span style="font-size:32px;">📋</span><strong style="font-size:13px;">Catatan</strong></a>
                <a href="<?= base_url('index.php?page=jadwal') ?>" class="quick-link" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:20px;background:var(--primary-soft);border-radius:var(--radius);text-decoration:none;color:var(--gray-700);border:1px solid rgba(var(--primary-rgb),.1);transition:var(--transition);"><span style="font-size:32px;">📅</span><strong style="font-size:13px;">Jadwal</strong></a>
            </div>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
