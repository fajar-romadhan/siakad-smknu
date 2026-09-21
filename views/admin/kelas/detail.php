<?php $pageTitle = 'Detail ' . format_kelas($kelas['nama_kelas']); ob_start(); ?>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">🏫</div><div class="stat-info"><h3><?= htmlspecialchars($kelas['nama_kelas']) ?></h3><p>Nama Kelas</p></div></div>
    <div class="stat-card"><div class="stat-icon">👨‍🏫</div><div class="stat-info"><h3 style="font-size:15px;"><?= htmlspecialchars($wali['nama'] ?? '-') ?></h3><p>Wali Kelas</p></div></div>
    <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-info"><h3><?= count($siswaKelas) ?></h3><p>Jumlah Siswa</p></div></div>
</div>
<div class="card">
    <div class="card-header">
        <h3>Daftar Siswa</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <?php if(!empty($siswaKelas)): ?>
            <div class="search-bar-figma" style="min-width:200px;">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Cari siswa..." oninput="filterTableRows('siswaKelasTable', this.value)">
            </div>
            <?php endif; ?>
            <a href="<?= base_url('index.php?page=kelas&action=tambahSiswa&id='.$kelas['id']) ?>" class="btn btn-primary">+ Tambah Siswa</a>
            <a href="<?= base_url('index.php?page=kelas') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <?php if(empty($siswaKelas)): ?>
            <p class="empty-state">Belum ada siswa di kelas ini. Klik tombol Tambah Siswa untuk menambahkan.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table" id="siswaKelasTable"><thead><tr><th style="width:60px;">No</th><th>NISN</th><th>Nama</th><th style="width:180px;">Aksi</th></tr></thead><tbody>
            <?php foreach($siswaKelas as $i => $s): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($s['nisn']) ?></td>
                <td><?= htmlspecialchars($s['nama']) ?></td>
                <td class="actions">
                    <a href="<?= base_url('index.php?page=siswa&action=detail&id='.$s['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                    <a href="#" onclick="confirmDelete('<?= base_url('index.php?page=kelas&action=hapusSiswa&kelas_id='.$kelas['id'].'&siswa_id='.$s['id']) ?>','Hapus <?= htmlspecialchars($s['nama']) ?> dari kelas <?= htmlspecialchars($kelas['nama_kelas']) ?>?'); return false;" class="btn btn-sm btn-danger">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody></table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
