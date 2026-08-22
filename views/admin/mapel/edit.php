<?php $pageTitle='Edit Mata Pelajaran'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Edit Mata Pelajaran</h3></div><div class="card-body">
<form method="POST" action="<?= base_url('index.php?page=mapel&action=update&id='.$mapel['id']) ?>">
<div class="form-group"><label>Nama *</label><input type="text" name="nama_mapel" class="form-control" value="<?= htmlspecialchars($mapel['nama_mapel']) ?>" required></div>
<div class="form-row"><div class="form-group"><label>Semester</label><select name="semester" class="form-control"><option value="ganjil" <?= $mapel['semester']==='ganjil'?'selected':'' ?>>Ganjil</option><option value="genap" <?= $mapel['semester']==='genap'?'selected':'' ?>>Genap</option></select></div>
<div class="form-group"><label>Tingkat</label><select name="tingkat" class="form-control"><?php foreach(['X','XI','XII'] as $t): ?><option value="<?= $t ?>" <?= $mapel['tingkat']===$t?'selected':'' ?>><?= $t ?></option><?php endforeach; ?></select></div></div>
<div class="form-group"><label>Tahun Ajaran</label><select name="tahun_ajaran_id" class="form-control"><?php foreach($tahunAjaran as $ta): ?><option value="<?= $ta['id'] ?>" <?= $mapel['tahun_ajaran_id']==$ta['id']?'selected':'' ?>><?= $ta['tahun_ajaran'] ?></option><?php endforeach; ?></select></div>
<div class="form-actions"><a href="<?= base_url('index.php?page=mapel') ?>" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
