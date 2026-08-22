<?php $pageTitle='Kelola Jadwal'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Kelola Jadwal</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <?php if(!empty($kelasList)): ?>
            <div class="search-bar-figma" style="min-width:200px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari kelas..." oninput="filterTableRows('jadwalKelasTable', this.value)">
            </div>
            <?php endif; ?>
            <a href="<?= base_url('index.php?page=tahun_ajaran') ?>" class="btn btn-secondary">Kelola Tahun Ajaran</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($kelasList)): ?>
            <p class="empty-state">Belum ada kelas. Buat tahun ajaran dan kelas terlebih dahulu sebelum membuat jadwal.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table" id="jadwalKelasTable"><thead><tr><th>Nama Kelas</th><th>Tahun Ajaran</th><th style="width:280px;">Aksi</th></tr></thead><tbody>
            <?php foreach($kelasList as $k): ?>
            <tr>
                <td><span class="role-badge"><?= htmlspecialchars($k['nama_kelas']) ?></span></td>
                <td><?= htmlspecialchars($k['tahun_ajaran']) ?></td>
                <td class="actions">
                    <a href="<?= base_url('index.php?page=jadwal&action=detail&id='.$k['id']) ?>" class="btn btn-sm btn-info">Detail Jadwal</a>
                    <a href="<?= base_url('index.php?page=jadwal&action=create&kelas_id='.$k['id']) ?>" class="btn btn-sm btn-success">Kelola Jadwal</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody></table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
