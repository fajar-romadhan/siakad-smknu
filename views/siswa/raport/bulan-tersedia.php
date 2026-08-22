<?php $pageTitle='Riwayat Raport Bulanan'; ob_start(); ?>
<div class="dash-header-row">
    <h2 class="page-title">Raport Saya</h2>
</div>
<div class="card">
    <div class="card-header"><h3>Riwayat Raport Bulanan</h3></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($history)): ?>
                        <tr><td colspan="5" class="empty-state">Belum ada data raport bulanan untuk semester ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($history as $i => $item): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= htmlspecialchars($item['label']) ?></td>
                                <td><?= htmlspecialchars($item['year']) ?></td>
                                <td>
                                    <?php if($item['available']): ?>
                                        <span class="badge aktif"><?= htmlspecialchars($item['status']) ?></span>
                                    <?php else: ?>
                                        <span class="badge draft"><?= htmlspecialchars($item['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($item['available']): ?>
                                        <a href="<?= base_url('index.php?page=raport&action=siswa&bulan='.$item['month'].'&tahun='.$item['year']) ?>" class="btn btn-sm btn-primary">Lihat</a>
                                        <a href="<?= base_url('index.php?page=raport&action=cetak&bulan='.$item['month'].'&tahun='.$item['year']) ?>" class="btn btn-sm btn-secondary" target="_blank">Cetak PDF</a>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>