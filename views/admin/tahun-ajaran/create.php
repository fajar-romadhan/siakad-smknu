<?php $pageTitle='Tambah Tahun Ajaran'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Tambah Tahun Ajaran</h3></div><div class="card-body">
<form method="POST" action="<?= base_url('index.php?page=tahun_ajaran&action=store') ?>">
<div class="form-group"><label>Tahun Ajaran *</label><input type="text" name="tahun_ajaran" class="form-control" placeholder="contoh: 2025/2026" required></div>
<div class="form-row"><div class="form-group"><label>Semester Aktif *</label><select name="semester_aktif" class="form-control"><option value="ganjil">Ganjil</option><option value="genap">Genap</option></select></div>
<div class="form-group"><label>Status *</label><select name="status" class="form-control"><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div></div>
<div class="form-actions"><a href="<?= base_url('index.php?page=tahun_ajaran') ?>" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
