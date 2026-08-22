<?php $pageTitle='Edit Tahun Ajaran'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Edit Tahun Ajaran</h3></div><div class="card-body">
<form method="POST" action="<?= base_url('index.php?page=tahun_ajaran&action=update&id='.$ta['id']) ?>">
<div class="form-group"><label>Tahun Ajaran</label><input type="text" name="tahun_ajaran" class="form-control" value="<?= $ta['tahun_ajaran'] ?>" required></div>
<div class="form-row"><div class="form-group"><label>Semester</label><select name="semester_aktif" class="form-control"><option value="ganjil" <?= $ta['semester_aktif']==='ganjil'?'selected':'' ?>>Ganjil</option><option value="genap" <?= $ta['semester_aktif']==='genap'?'selected':'' ?>>Genap</option></select></div>
<div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="aktif" <?= $ta['status']==='aktif'?'selected':'' ?>>Aktif</option><option value="nonaktif" <?= $ta['status']==='nonaktif'?'selected':'' ?>>Nonaktif</option></select></div></div>
<div class="form-actions"><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
