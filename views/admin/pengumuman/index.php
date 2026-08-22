<?php $pageTitle='Pengumuman'; ob_start(); $total = $totalAktif + $totalTerjadwal + $totalDraft; ?>
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card sm"><div class="stat-icon">📢</div><div class="stat-info"><h3><?= $total ?></h3><p>Total Pengumuman</p></div></div>
    <div class="stat-card sm"><div class="stat-icon">✅</div><div class="stat-info"><h3><?= $totalAktif ?></h3><p>Aktif</p></div></div>
    <div class="stat-card sm"><div class="stat-icon">⏰</div><div class="stat-info"><h3><?= $totalTerjadwal ?></h3><p>Terjadwal</p></div></div>
    <div class="stat-card sm"><div class="stat-icon">📝</div><div class="stat-info"><h3><?= $totalDraft ?></h3><p>Draft</p></div></div>
</div>
<div class="card">
    <div class="card-header">
        <h3>Daftar Pengumuman</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <?php if(!empty($data)): ?>
            <div class="search-bar-figma" style="min-width:200px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari pengumuman..." oninput="filterTableRows('pengumumanTable', this.value)">
            </div>
            <?php endif; ?>
            <a href="<?= base_url('index.php?page=pengumuman&action=create') ?>" class="btn btn-primary">+ Pengumuman Baru</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($data)): ?>
            <p class="empty-state">Belum ada pengumuman. Klik tombol Pengumuman Baru untuk membuat.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table" id="pengumumanTable">
            <thead><tr><th style="width:50px;">No</th><th>Judul</th><th>Tanggal</th><th>Penerima</th><th>Status</th><th style="width:220px;">Aksi</th></tr></thead>
            <tbody>
                <?php foreach($data as $i=>$p): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= htmlspecialchars($p['judul']) ?></strong></td>
                    <td><?= date('d/m/Y H:i',strtotime($p['tanggal_publish'])) ?></td>
                    <td><?= htmlspecialchars($p['penerima']) ?></td>
                    <td><span class="badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                    <td class="actions">
                        <a href="<?= base_url('index.php?page=pengumuman&action=detail&id='.$p['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                        <a href="<?= base_url('index.php?page=pengumuman&action=edit&id='.$p['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                        <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=pengumuman&action=destroy&id='.$p['id']) ?>','Yakin ingin menghapus pengumuman <?= htmlspecialchars($p['judul']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
