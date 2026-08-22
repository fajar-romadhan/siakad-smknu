<?php $pageTitle = 'Tambah Siswa'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Tambah Data Siswa</h3></div><div class="card-body">
<form method="POST" action="<?= base_url('index.php?page=siswa&action=store') ?>">
    <?php $s = []; require VIEW_PATH.'/admin/siswa/_form.php'; ?>
    <div class="form-group"><label>Tahun Masuk *</label><input type="number" name="tahun_masuk" class="form-control" value="<?= date('Y') ?>" required></div>
    <div class="form-actions">
        <a href="<?= base_url('index.php?page=siswa') ?>" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form></div></div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
