<?php $pageTitle='Tahun Ajaran'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Tahun Ajaran</h3>
        <a href="<?= base_url('index.php?page=tahun_ajaran&action=create') ?>" class="btn btn-primary">+ Tambah Tahun Ajaran</a>
    </div>
    <div class="card-body">
        <?php if(empty($data)): ?>
            <p class="empty-state">Belum ada data tahun ajaran. Silakan tambahkan tahun ajaran terlebih dahulu sebelum membuat mata pelajaran, kelas, dan jadwal.</p>
        <?php else: ?>
        <div class="table-responsive"><table class="table"><thead><tr><th>Tahun Ajaran</th><th>Semester Aktif</th><th>Status</th><th style="width:120px;">Aksi</th></tr></thead><tbody>
            <?php foreach($data as $t): ?>
            <tr>
                <td><strong><?= htmlspecialchars($t['tahun_ajaran']) ?></strong></td>
                <td><span class="badge <?= $t['semester_aktif']==='ganjil'?'aktif':'terjadwal' ?>"><?= ucfirst($t['semester_aktif']) ?></span></td>
                <td><span class="badge <?= $t['status'] ?>"><?= ucfirst($t['status']) ?></span></td>
                <td class="actions"><a href="<?= base_url('index.php?page=tahun_ajaran&action=edit&id='.$t['id']) ?>" class="btn btn-sm btn-success">Edit</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody></table></div>
        <?php endif; ?>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
