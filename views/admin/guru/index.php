<?php $pageTitle = 'Data Guru'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Data Guru</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <div class="search-bar-figma" style="min-width:220px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="guruSearchInput" placeholder="Cari nama / kode guru..." oninput="filterTableRows('guruTable', this.value)">
            </div>
            <a href="<?= base_url('index.php?page=guru&action=create') ?>" class="btn btn-primary">+ Tambah Guru</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($data)): ?>
            <p class="empty-state">Belum ada data guru. Silakan tambahkan data guru.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table" id="guruTable">
                <thead><tr><th>Nama</th><th>Email</th><th>No. HP</th><th style="width:220px;">Aksi</th></tr></thead>
                <tbody>
                <?php foreach($data as $g): ?>
                <tr>
                    <td><?= htmlspecialchars($g['nama']) ?></td>
                    <td><?= htmlspecialchars($g['email'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($g['no_hp'] ?? '-') ?></td>
                    <td class="actions">
                        <a href="<?= base_url('index.php?page=guru&action=detail&id='.$g['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                        <a href="<?= base_url('index.php?page=guru&action=edit&id='.$g['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                        <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=guru&action=destroy&id='.$g['id']) ?>','Yakin ingin menghapus data guru <?= htmlspecialchars($g['nama']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
