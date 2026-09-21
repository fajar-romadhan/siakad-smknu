<?php $pageTitle = 'Edit Guru'; ob_start();
function g($guru,$k){ return htmlspecialchars($guru[$k] ?? ''); }
function sel($guru,$k,$v){ return (($guru[$k] ?? '')===$v)?'selected':''; }
?>
<div class="card">
    <div class="card-header"><h3>Edit Biodata Guru</h3></div>
    <div class="card-body">
        <form method="POST" action="<?= base_url('index.php?page=guru&action=update&id='.$guru['id']) ?>" enctype="multipart/form-data">

            <h4 class="form-section-title">Foto Profil</h4>
            <div class="form-group" style="margin-bottom:20px;">
                <label>Foto Profil Guru <small>(Kosongkan jika tidak ingin mengganti foto)</small></label>
                <?php 
                $fotoUrl = (!empty($guru['foto']) && file_exists(BASE_PATH . '/public/uploads/guru/' . $guru['foto'])) 
                           ? base_url('uploads/guru/' . $guru['foto']) 
                           : null;
                ?>
                <?php if ($fotoUrl): ?>
                    <div style="margin-bottom:10px;">
                        <img src="<?= $fotoUrl ?>" alt="Foto Guru" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>

            <h4 class="form-section-title">Identitas</h4>
            <div class="form-row">
                <div class="form-group"><label>Nama *</label><input type="text" name="nama" class="form-control" value="<?= g($guru,'nama') ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>NUPTK <small>(kosongkan jika tidak ada)</small></label><input type="text" name="nuptk" class="form-control" value="<?= g($guru,'nuptk') ?>"></div>
                <div class="form-group"><label>NIK</label><input type="text" name="nik" class="form-control" value="<?= g($guru,'nik') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>NIP <small>(kosongkan jika tidak ada)</small></label><input type="text" name="nip" class="form-control" value="<?= g($guru,'nip') ?>"></div>
                <div class="form-group"><label>Jenis Kelamin *</label><select name="jenis_kelamin" class="form-control"><option value="Laki-Laki" <?= sel($guru,'jenis_kelamin','Laki-Laki') ?>>Laki-Laki</option><option value="Perempuan" <?= sel($guru,'jenis_kelamin','Perempuan') ?>>Perempuan</option></select></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="<?= g($guru,'tempat_lahir') ?>"></div>
                <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="<?= g($guru,'tanggal_lahir') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Agama</label><input type="text" name="agama" class="form-control" value="<?= g($guru,'agama') ?>"></div>
                <div class="form-group"><label>Gelar</label><input type="text" name="gelar" class="form-control" value="<?= g($guru,'gelar') ?>" placeholder="Contoh: S.Pd., M.Kom."></div>
            </div>

            <h4 class="form-section-title">Kepegawaian</h4>
            <div class="form-row">
                <div class="form-group"><label>Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-control">
                        <option value="">- Pilih -</option>
                        <?php foreach(['PNS','PPPK','GTY','GTT','Honorer'] as $o): ?>
                        <option value="<?= $o ?>" <?= sel($guru,'status_kepegawaian',$o) ?>><?= $o ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Jenis PTK</label>
                    <select name="jenis_ptk" class="form-control">
                        <option value="">- Pilih -</option>
                        <?php foreach(['Guru Mapel','Guru Kelas','Guru BK','Kepala Sekolah','Tenaga Administrasi'] as $o): ?>
                        <option value="<?= $o ?>" <?= sel($guru,'jenis_ptk',$o) ?>><?= $o ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Jenjang Pendidikan</label>
                    <select name="jenjang_pendidikan" class="form-control">
                        <option value="">- Pilih -</option>
                        <?php foreach(['SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $o): ?>
                        <option value="<?= $o ?>" <?= sel($guru,'jenjang_pendidikan',$o) ?>><?= $o ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Jurusan / Prodi</label><input type="text" name="jurusan_prodi" class="form-control" value="<?= g($guru,'jurusan_prodi') ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>TMT Kerja</label><input type="date" name="tmt_kerja" class="form-control" value="<?= g($guru,'tmt_kerja') ?>"></div>
                <div class="form-group"><label>Tugas Tambahan <small>(opsional)</small></label><input type="text" name="tugas_tambahan" class="form-control" value="<?= g($guru,'tugas_tambahan') ?>"></div>
            </div>

            <h4 class="form-section-title">Kontak</h4>
            <div class="form-row">
                <div class="form-group"><label>No. HP</label><input type="text" name="no_hp" class="form-control" value="<?= g($guru,'no_hp') ?>"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= g($guru,'email') ?>"></div>
            </div>
            <div class="form-group"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"><?= g($guru,'alamat') ?></textarea></div>

            <div class="form-actions">
                <a href="<?= base_url('index.php?page=guru') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
