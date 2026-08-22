<?php $pageTitle='Detail Pengumuman'; ob_start(); ?>
<div class="mobile-card">
    <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary btn-sm" style="margin-bottom:14px;">← Kembali</a>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <span style="font-size:24px;">📢</span>
        <h3 style="font-size:16px;color:var(--gray-800);font-weight:700;flex:1;"><?= htmlspecialchars($p['judul']) ?></h3>
    </div>
    <small style="color:var(--gray-400);font-size:11.5px;">📅 <?= date('d F Y H:i',strtotime($p['tanggal_publish'])) ?> WIB</small>
    <div class="content-text" style="padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);font-size:14px;line-height:1.7;margin-top:12px;"><?= nl2br(htmlspecialchars($p['isi'])) ?></div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
