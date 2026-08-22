<?php $pageTitle='Kelola Pengguna'; ob_start(); ?>
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card sm"><div class="stat-icon">👥</div><div class="stat-info"><h3><?= count($data) ?></h3><p>Total Pengguna</p></div></div>
    <div class="stat-card sm"><div class="stat-icon">🔑</div><div class="stat-info"><h3><?= $totalAdmin ?></h3><p>Admin</p></div></div>
    <div class="stat-card sm"><div class="stat-icon">👨‍🏫</div><div class="stat-info"><h3><?= $totalGuru ?></h3><p>Guru</p></div></div>
    <div class="stat-card sm"><div class="stat-icon">👨‍🎓</div><div class="stat-info"><h3><?= $totalSiswa ?></h3><p>Siswa</p></div></div>
</div>
<div class="card">
    <div class="card-header">
        <h3>Daftar Pengguna</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <div class="search-bar-figma" style="min-width:220px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari username / nama..." oninput="filterTableRows('penggunaTable', this.value)">
            </div>
            <a href="<?= base_url('index.php?page=pengguna&action=daftarAkun') ?>" target="_blank" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;padding:9px 14px;" title="Daftar akun beserta password default untuk simulasi">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Daftar Akun
            </a>
            <a href="<?= base_url('index.php?page=pengguna&action=exportPdf') ?>" target="_blank" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;padding:9px 14px;background:#E5E7EB;color:#1F2937;" title="Rekap data pengguna formal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Rekap Data
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive"><table class="table" id="penggunaTable">
            <thead><tr><th style="width:50px;">No</th><th>Username</th><th>Nama</th><th>Tipe Pengguna</th><th>Status</th><th>Terakhir Aktif</th><th style="width:170px;">Aksi</th></tr></thead>
            <tbody>
                <?php foreach($data as $i=>$u): $roleLabel = str_replace('_',' ', ucwords($u['role'],'_')); ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['nama']) ?></td>
                    <td><span class="role-badge"><?= $roleLabel ?></span></td>
                    <td><span class="badge <?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
                    <td><?= $u['last_login']?date('d/m/Y H:i',strtotime($u['last_login'])):'-' ?></td>
                    <td class="actions">
                        <a href="<?= base_url('index.php?page=pengguna&action=edit&id='.$u['id']) ?>" class="btn btn-sm btn-success">Edit</a>
                        <?php if($u['role']!=='admin'): ?>
                        <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=pengguna&action=destroy&id='.$u['id']) ?>','Yakin ingin menghapus akun <?= htmlspecialchars($u['username']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
