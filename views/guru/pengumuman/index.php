<?php $pageTitle='Pengumuman'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Pengumuman</h2>
    <p style="color:var(--gray-500);font-size:13px;">Pengumuman dari sekolah untuk guru</p>
</div>

<div class="card">
    <div class="card-header"><h3>Daftar Pengumuman</h3></div>
    <div class="card-body">
        <?php if(empty($pengumumanList)): ?>
            <p class="empty-state">Belum ada pengumuman untuk guru.</p>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <?php foreach($pengumumanList as $p): ?>
            <a href="<?= base_url('index.php?page=pengumuman&action=detail&id='.$p['id']) ?>" style="display:block;padding:16px;background:var(--white);border:1px solid var(--stroke-light);border-radius:var(--radius);text-decoration:none;color:var(--gray-700);transition:var(--transition);border-left:4px solid var(--primary);" onmouseover="this.style.background='var(--primary-soft)';this.style.transform='translateX(4px)';" onmouseout="this.style.background='var(--white)';this.style.transform='';">
                <div style="display:flex;justify-content:space-between;align-items:start;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                            <span style="font-size:18px;">📢</span>
                            <strong style="font-size:15px;color:var(--gray-800);"><?= htmlspecialchars($p['judul']) ?></strong>
                        </div>
                        <p style="font-size:12.5px;color:var(--gray-600);line-height:1.5;"><?= htmlspecialchars(substr(strip_tags($p['isi']), 0, 140)) ?><?= strlen($p['isi'])>140?'...':'' ?></p>
                        <small style="color:var(--gray-400);font-size:11.5px;display:block;margin-top:6px;">📅 <?= date('d F Y H:i',strtotime($p['tanggal_publish'])) ?></small>
                    </div>
                    <span style="font-size:20px;color:var(--gray-400);">→</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
