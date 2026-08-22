<?php $pageTitle = 'Pengumuman'; ob_start(); ?>
<div class="card">
    <div class="card-header"><h3>Pengumuman</h3><span style="font-size:13px;color:var(--gray-500)"><?= count($data) ?> total</span></div>
    <div class="card-body">
        <?php if (empty($data)): ?>
            <p style="color:var(--gray-500);text-align:center;padding:20px 0">Belum ada pengumuman.</p>
        <?php else: foreach ($data as $p): ?>
            <div style="padding:14px 0;border-bottom:1px solid var(--stroke-light)">
                <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                    <div style="flex:1">
                        <h4 style="font-size:15px;font-weight:600;color:var(--gray-800);margin:0 0 4px"><?= htmlspecialchars($p['judul']) ?></h4>
                        <p style="font-size:14px;color:var(--gray-600);margin:0;line-height:1.6"><?= nl2br(htmlspecialchars($p['isi'] ?? '')) ?></p>
                    </div>
                    <div style="font-size:12px;color:var(--gray-500);white-space:nowrap"><?= date('d M Y', strtotime($p['tanggal_publish'])) ?></div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
