<?php $pageTitle='Edit Jadwal'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Edit Jadwal</h3></div><div class="card-body">
<?php if(!empty($error)): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
<form method="POST" action="<?= base_url('index.php?page=jadwal&action=update&id='.$jadwal['id']) ?>">
<div class="form-group"><label>Hari</label><select name="hari" class="form-control"><?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h): ?><option value="<?= $h ?>" <?= $jadwal['hari']===$h?'selected':'' ?>><?= $h ?></option><?php endforeach; ?></select></div>
<div class="form-row"><div class="form-group"><label>Jam Mulai</label><input type="time" name="jam_mulai" class="form-control" value="<?= $jadwal['jam_mulai'] ?>" required></div>
<div class="form-group"><label>Jam Selesai</label><input type="time" name="jam_selesai" class="form-control" value="<?= $jadwal['jam_selesai'] ?>" required></div></div>
<div class="form-group"><label>Mapel</label><select name="mapel_id" class="form-control"><?php foreach($mapelList as $m): ?><option value="<?= $m['id'] ?>" <?= $jadwal['mapel_id']==$m['id']?'selected':'' ?>><?= $m['nama_mapel'] ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Guru</label><select name="guru_id" class="form-control"><?php foreach($guruList as $g): ?><option value="<?= $g['id'] ?>" <?= $jadwal['guru_id']==$g['id']?'selected':'' ?>><?= $g['nama'] ?></option><?php endforeach; ?></select></div>
<div class="form-actions"><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
