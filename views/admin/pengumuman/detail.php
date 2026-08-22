<?php $pageTitle='Detail Pengumuman'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3><?= htmlspecialchars($p['judul']) ?></h3>
        <div style="display:flex;gap:8px;">
            <a href="<?= base_url('index.php?page=pengumuman&action=edit&id='.$p['id']) ?>" class="btn btn-success">Edit</a>
            <a href="<?= base_url('index.php?page=pengumuman') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-grid" style="margin-bottom:20px;padding:14px;background:var(--gray-50);border-radius:var(--radius-sm);">
            <div class="detail-item"><label>Tanggal Publish</label><span><?= date('d F Y H:i',strtotime($p['tanggal_publish'])) ?> WIB</span></div>
            <div class="detail-item"><label>Penerima</label><span><?= htmlspecialchars($p['penerima']) ?></span></div>
            <div class="detail-item"><label>Status</label><span><span class="badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></span></div>
            <div class="detail-item"><label>Dibuat</label><span><?= $p['created_at']?date('d F Y H:i',strtotime($p['created_at'])):'-' ?></span></div>
        </div>
        <div class="content-text" style="padding:16px;background:var(--white);border:1px solid var(--stroke-light);border-radius:var(--radius-sm);"><?= nl2br(htmlspecialchars($p['isi'])) ?></div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
