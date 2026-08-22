<?php $pageTitle='Detail Pengumuman'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3><?= htmlspecialchars($p['judul']) ?></h3>
        <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <div style="padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);margin-bottom:20px;font-size:12.5px;color:var(--gray-600);display:flex;gap:20px;flex-wrap:wrap;">
            <span>📅 <strong><?= date('d F Y H:i',strtotime($p['tanggal_publish'])) ?></strong> WIB</span>
            <span>Penerima: <strong><?= htmlspecialchars($p['penerima']) ?></strong></span>
        </div>
        <div class="content-text" style="padding:16px;background:var(--white);border:1px solid var(--stroke-light);border-radius:var(--radius-sm);font-size:14px;line-height:1.8;"><?= nl2br(htmlspecialchars($p['isi'])) ?></div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
