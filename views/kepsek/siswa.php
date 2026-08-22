<?php $pageTitle = 'Data Siswa'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Data Siswa</h3>
        <form method="get" style="display:flex;gap:8px">
            <input type="hidden" name="page" value="kepsek">
            <input type="hidden" name="action" value="siswa">
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Cari nama / NIS..." class="form-control" style="width:260px">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th style="width:50px">#</th><th>NISN</th><th>Nama</th><th>Kelas</th><th>Jenis Kelamin</th><th style="width:100px">Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--gray-500)">Belum ada data siswa.</td></tr>
            <?php else: foreach ($data as $i => $r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($r['nisn'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['nama']) ?></td>
                    <td><?= htmlspecialchars($r['nama_kelas'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['jenis_kelamin'] ?? '-') ?></td>
                    <td><a href="<?= base_url('index.php?page=kepsek&action=siswaDetail&id='.$r['id']) ?>" class="btn btn-sm btn-primary">Detail</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
