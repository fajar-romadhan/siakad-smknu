<?php $pageTitle='Kelas'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Data Kelas</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <div class="search-bar-figma" style="min-width:200px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari kelas..." oninput="filterTableRows('kelasTable', this.value)">
            </div>
            <a href="<?= base_url('index.php?page=kelas&action=create') ?>" class="btn btn-primary">+ Tambah Kelas</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($data)): ?>
            <p class="empty-state">Belum ada data kelas. Silakan tambahkan kelas terlebih dahulu.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table" id="kelasTable"><thead><tr><th>Nama Kelas</th><th>Wali Kelas</th><th>Jumlah Siswa</th><th>Tahun Ajaran</th><th style="width:220px;">Aksi</th></tr></thead><tbody>
            <?php foreach($data as $k): ?>
            <tr>
                <td><span class="role-badge"><?= htmlspecialchars($k['nama_kelas']) ?></span></td>
                <td><?= htmlspecialchars($k['wali'] ?? '-') ?></td>
                <td><strong><?= $k['jml_siswa'] ?></strong> siswa</td>
                <td><?= htmlspecialchars($k['tahun_ajaran']) ?></td>
                <td class="actions">
                    <a href="<?= base_url('index.php?page=kelas&action=detail&id='.$k['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                    <a href="<?= base_url('index.php?page=kelas&action=edit&id='.$k['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                    <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=kelas&action=destroy&id='.$k['id']) ?>','Yakin ingin menghapus kelas <?= htmlspecialchars($k['nama_kelas']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody></table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
