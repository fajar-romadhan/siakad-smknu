<?php $pageTitle='Tambah Mata Pelajaran'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Tambah Mata Pelajaran</h3></div><div class="card-body">
<form method="POST" action="<?= base_url('index.php?page=mapel&action=store') ?>">
<div class="form-group"><label>Nama Mata Pelajaran *</label><input type="text" name="nama_mapel" class="form-control" required></div>
<div class="form-row"><div class="form-group"><label>Semester *</label><select name="semester" class="form-control"><option value="ganjil">Ganjil</option><option value="genap">Genap</option></select></div>
<div class="form-group"><label>Tingkat *</label><select name="tingkat" class="form-control"><option value="X">Kelas X</option><option value="XI">Kelas XI</option><option value="XII">Kelas XII</option></select></div></div>
<div class="form-group"><label>Tahun Ajaran *</label><select name="tahun_ajaran_id" class="form-control" required>
<?php foreach($tahunAjaran as $ta): ?><option value="<?= $ta['id'] ?>"><?= $ta['tahun_ajaran'] ?> (<?= ucfirst($ta['semester_aktif']) ?>)</option><?php endforeach; ?></select></div>
<div class="form-actions"><a href="<?= base_url('index.php?page=mapel') ?>" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
