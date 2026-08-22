<?php $pageTitle='Tambah Jadwal'; ob_start(); $error=flash('error'); ?>
<div class="card"><div class="card-header"><h3>Tambah Jadwal - <?= $kelas['nama_kelas'] ?></h3></div><div class="card-body">
<?php if($error): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
<form method="POST" action="<?= base_url('index.php?page=jadwal&action=store') ?>">
<input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">
<div class="form-group"><label>Hari *</label><select name="hari" class="form-control" required>
<?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h): ?><option value="<?= $h ?>"><?= $h ?></option><?php endforeach; ?></select></div>
<div class="form-row"><div class="form-group"><label>Jam Mulai *</label><input type="time" name="jam_mulai" class="form-control" required></div>
<div class="form-group"><label>Jam Selesai *</label><input type="time" name="jam_selesai" class="form-control" required></div></div>
<div class="form-group"><label>Mata Pelajaran *</label><select name="mapel_id" class="form-control" required><option value="">Pilih</option>
<?php foreach($mapelList as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?> (<?= $m['tingkat'] ?>)</option><?php endforeach; ?></select></div>
<div class="form-group"><label>Guru Pengajar *</label><select name="guru_id" class="form-control" id="guruSelect" required><option value="">Pilih</option>
<?php foreach($guruList as $g): ?><option value="<?= $g['id'] ?>"><?= $g['nama'] ?></option><?php endforeach; ?></select>
<small id="guruStatus"></small></div>
<div class="form-actions"><a href="<?= base_url('index.php?page=jadwal&action=detail&id='.$kelas['id']) ?>" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
