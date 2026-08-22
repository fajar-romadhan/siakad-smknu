<?php $pageTitle='Catatan'; ob_start(); ?>
<div class="greeting">
    <h3>Catatan dari Guru</h3>
    <p><?= count($catatanList) ?> catatan</p>
</div>

<?php if(empty($catatanList)): ?>
    <div class="mobile-card"><p class="empty-state">Belum ada catatan dari guru.</p></div>
<?php else: foreach($catatanList as $c): ?>
<a href="<?= base_url('index.php?page=catatan&action=detail&id='.$c['id']) ?>" class="mobile-card clickable" style="display:block;">
    <div style="display:flex;align-items:start;gap:10px;">
        <span style="font-size:22px;">📋</span>
        <div style="flex:1;">
            <strong style="font-size:14px;color:var(--gray-800);"><?= htmlspecialchars($c['nama_mapel']) ?></strong>
            <p style="font-size:12px;color:var(--gray-500);margin-top:2px;">Dari: <?= htmlspecialchars($c['guru_nama']) ?></p>
            <p style="font-size:12.5px;color:var(--gray-600);line-height:1.5;margin-top:6px;"><?= htmlspecialchars(substr($c['isi_catatan'], 0, 80)) ?><?= strlen($c['isi_catatan'])>80?'...':'' ?></p>
            <small style="color:var(--gray-400);font-size:11px;display:block;margin-top:4px;"><?= date('d/m/Y H:i',strtotime($c['created_at'])) ?></small>
        </div>
    </div>
</a>
<?php endforeach; endif; ?>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
