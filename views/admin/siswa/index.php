<?php $pageTitle = 'Data Siswa'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Data Siswa</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <div class="search-bar-figma" style="min-width:220px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari nama / NISN..." oninput="filterTableRows('siswaTable', this.value)">
            </div>
            <a href="<?= base_url('index.php?page=siswa&action=create') ?>" class="btn btn-primary">+ Tambah Siswa</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($data)): ?><p class="empty-state">Belum ada data siswa. Silakan tambahkan data siswa.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table" id="siswaTable"><thead><tr><th>NISN</th><th>Nama</th><th>Kelas</th><th style="width:220px;">Aksi</th></tr></thead><tbody>
        <?php foreach($data as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['nisn']) ?></td>
            <td><?= htmlspecialchars($s['nama']) ?></td>
            <td><?= $s['nama_kelas'] ? '<span class="role-badge">'.htmlspecialchars($s['nama_kelas']).'</span>' : '<span class="text-muted">Belum ada kelas</span>' ?></td>
            <td class="actions">
                <a href="<?= base_url('index.php?page=siswa&action=detail&id='.$s['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                <a href="<?= base_url('index.php?page=siswa&action=edit&id='.$s['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=siswa&action=destroy&id='.$s['id']) ?>','Yakin ingin menghapus data siswa <?= htmlspecialchars($s['nama']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?></tbody></table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
