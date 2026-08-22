<?php $pageTitle='Detail Catatan'; ob_start(); ?>
<div class="mobile-card">
    <a href="<?= base_url('index.php?page=catatan') ?>" class="btn btn-secondary btn-sm" style="margin-bottom:14px;">← Kembali</a>
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <div class="profile-avatar-sm" style="width:44px;height:44px;font-size:18px;background:var(--primary);color:white;"><?= strtoupper(substr($catatan['guru_nama'],0,1)) ?></div>
        <div>
            <strong style="font-size:14px;color:var(--gray-800);"><?= htmlspecialchars($catatan['guru_nama']) ?></strong>
            <p style="font-size:11.5px;color:var(--gray-500);">Guru <?= htmlspecialchars($catatan['nama_mapel']) ?></p>
        </div>
    </div>
    <small style="color:var(--gray-400);font-size:11px;">📅 <?= date('d F Y H:i',strtotime($catatan['created_at'])) ?></small>
    <div class="content-text mt-1" style="padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);font-size:14px;line-height:1.7;margin-top:12px;"><?= nl2br(htmlspecialchars($catatan['isi_catatan'])) ?></div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
