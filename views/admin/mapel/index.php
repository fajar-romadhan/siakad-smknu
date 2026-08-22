<?php $pageTitle='Mata Pelajaran'; ob_start(); ?>
<div class="stats-grid">
    <div class="stat-card sm"><div class="stat-info"><h3><?= count($data) ?></h3><p>Total Mapel</p></div></div>
    <div class="stat-card sm"><div class="stat-info"><h3><?= $totalX ?></h3><p>Kelas X</p></div></div>
    <div class="stat-card sm"><div class="stat-info"><h3><?= $totalXI ?></h3><p>Kelas XI</p></div></div>
    <div class="stat-card sm"><div class="stat-info"><h3><?= $totalXII ?></h3><p>Kelas XII</p></div></div>
</div>
<div class="card">
    <div class="card-header">
        <h3>Daftar Mata Pelajaran</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <div class="search-bar-figma" style="min-width:200px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari mata pelajaran..." oninput="filterTableRows('mapelTable', this.value)">
            </div>
            <a href="<?= base_url('index.php?page=mapel&action=create') ?>" class="btn btn-primary">+ Tambah Mapel</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($data)): ?>
            <p class="empty-state">Belum ada mata pelajaran. Buat tahun ajaran terlebih dahulu.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table" id="mapelTable"><thead><tr><th>Kode</th><th>Nama Mapel</th><th>Semester</th><th>Tingkat</th><th>Tahun Ajaran</th><th style="width:160px;">Aksi</th></tr></thead><tbody>
            <?php foreach($data as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['kode_mapel']) ?></td>
                <td><?= htmlspecialchars($m['nama_mapel']) ?></td>
                <td><span class="badge <?= $m['semester']==='ganjil'?'aktif':'terjadwal' ?>"><?= ucfirst($m['semester']) ?></span></td>
                <td><span class="role-badge">Kelas <?= $m['tingkat'] ?></span></td>
                <td><?= htmlspecialchars($m['tahun_ajaran']) ?></td>
                <td class="actions">
                    <a href="<?= base_url('index.php?page=mapel&action=edit&id='.$m['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                    <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=mapel&action=destroy&id='.$m['id']) ?>','Yakin ingin menghapus mata pelajaran <?= htmlspecialchars($m['nama_mapel']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody></table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
