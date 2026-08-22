<?php $pageTitle='Pengumuman'; ob_start(); ?>
<div class="greeting">
    <h3>Pengumuman</h3>
    <p><?= count($pengumumanList) ?> pengumuman aktif</p>
</div>

<?php if(empty($pengumumanList)): ?>
    <div class="mobile-card"><p class="empty-state">Belum ada pengumuman.</p></div>
<?php else: foreach($pengumumanList as $p): ?>
<a href="<?= base_url('index.php?page=pengumuman&action=detail&id='.$p['id']) ?>" class="mobile-card clickable" style="display:block;border-left:4px solid var(--primary);">
    <div style="display:flex;align-items:start;gap:10px;">
        <span style="font-size:22px;">📢</span>
        <div style="flex:1;">
            <strong style="font-size:14px;color:var(--gray-800);"><?= htmlspecialchars($p['judul']) ?></strong>
            <p style="font-size:12.5px;color:var(--gray-600);line-height:1.5;margin-top:6px;"><?= htmlspecialchars(substr(strip_tags($p['isi']), 0, 100)) ?><?= strlen($p['isi'])>100?'...':'' ?></p>
            <small style="color:var(--gray-400);font-size:11px;display:block;margin-top:4px;">📅 <?= date('d F Y',strtotime($p['tanggal_publish'])) ?></small>
        </div>
    </div>
</a>
<?php endforeach; endif; ?>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
