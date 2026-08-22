<?php $pageTitle='Input Catatan'; ob_start();
$db = getDB();
$kelas = $db->prepare("SELECT nama_kelas FROM kelas WHERE id=?"); $kelas->execute([$kelasId]); $kelasNama = $kelas->fetchColumn();
$mapel = $db->prepare("SELECT nama_mapel FROM mapel WHERE id=?"); $mapel->execute([$mapelId]); $mapelNama = $mapel->fetchColumn();
?>
<div class="dash-header-row">
    <h2 class="page-title">Catatan Siswa - Kelas <?= htmlspecialchars($kelasNama) ?> - <?= htmlspecialchars($mapelNama) ?></h2>
    <a href="<?= base_url('index.php?page=catatan') ?>" class="btn btn-secondary">← Kembali</a>
</div>

<div class="dashboard-grid">
    <div class="card">
        <div class="card-header"><h3>Tulis Catatan Baru</h3></div>
        <div class="card-body">
            <?php if(empty($siswaList)): ?>
                <p class="empty-state">Belum ada siswa di kelas ini.</p>
            <?php else: ?>
            <form method="POST" action="<?= base_url('index.php?page=catatan&action=store') ?>">
                <input type="hidden" name="kelas_id" value="<?= $kelasId ?>">
                <input type="hidden" name="mapel_id" value="<?= $mapelId ?>">
                <div class="form-group">
                    <label>Siswa *</label>
                    <select name="siswa_id" class="form-control" required>
                        <option value="">Pilih siswa...</option>
                        <?php foreach($siswaList as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Isi Catatan *</label>
                    <textarea name="isi_catatan" class="form-control" rows="6" placeholder="Tulis catatan untuk siswa (perilaku, prestasi, hal yang perlu perhatian, dll)..." required></textarea>
                </div>
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan Catatan</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Riwayat Catatan (<?= count($catatanList) ?>)</h3>
        </div>
        <div class="card-body">
            <?php if(empty($catatanList)): ?>
                <p class="empty-state">Belum ada catatan untuk kelas & mapel ini.</p>
            <?php else: ?>
            <div style="max-height:520px;overflow-y:auto;">
                <?php foreach($catatanList as $c): ?>
                <div style="padding:12px;border-bottom:1px solid var(--gray-100);margin-bottom:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:6px;">
                        <strong style="font-size:13px;color:var(--gray-800);"><?= htmlspecialchars($c['siswa_nama']) ?></strong>
                        <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=catatan&action=destroy&id='.$c['id']) ?>','Hapus catatan untuk <?= htmlspecialchars($c['siswa_nama']) ?>?'); return false;" class="btn btn-sm btn-danger" style="padding:3px 8px;font-size:10px;">Hapus</a>
                    </div>
                    <p style="font-size:12.5px;color:var(--gray-600);line-height:1.6;margin-bottom:6px;"><?= nl2br(htmlspecialchars($c['isi_catatan'])) ?></p>
                    <small style="color:var(--gray-400);font-size:10.5px;"><?= date('d/m/Y H:i',strtotime($c['created_at'])) ?></small>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
