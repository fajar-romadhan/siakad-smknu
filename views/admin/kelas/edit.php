<?php $pageTitle='Edit Kelas'; ob_start(); ?>
<div class="card"><div class="card-header"><h3>Edit Kelas</h3></div><div class="card-body">
<?php if(!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="POST" action="<?= base_url('index.php?page=kelas&action=update&id='.$kelas['id']) ?>">
<div class="form-group"><label>Nama Kelas</label><input type="text" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required></div>
<div class="form-group"><label>Wali Kelas</label><select name="wali_kelas_id" class="form-control"><option value="">- Belum ada wali -</option>
<?php foreach($guruList as $g): ?><option value="<?= $g['id'] ?>" <?= $kelas['wali_kelas_id']==$g['id']?'selected':'' ?>><?= $g['nama'] ?></option><?php endforeach; ?></select>
<small style="color:var(--gray-400)">Hanya guru berstatus GTY/PTY yang belum menjadi wali kelas di kelas lain yang tampil.</small></div>
<div class="form-actions"><a href="<?= base_url('index.php?page=kelas') ?>" class="btn btn-secondary">Batal</a><button type="submit" class="btn btn-primary">Simpan</button></div>
</form></div></div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
