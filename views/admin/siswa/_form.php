<?php
/**
 * Partial form biodata siswa — dipakai create & edit.
 * Variabel $s = array data siswa (kosong saat create).
 */
if (!isset($s)) $s = [];
function sv($s,$k){ return htmlspecialchars($s[$k] ?? ''); }
function ssel($s,$k,$v){ return (($s[$k] ?? '')===$v)?'selected':''; }
?>
<h4 class="form-section-title">Identitas Siswa</h4>
<div class="form-row">
    <div class="form-group"><label>NISN *</label>
        <?php if(isset($s['id'])): ?>
            <input type="text" class="form-control" value="<?= sv($s,'nisn') ?>" disabled>
        <?php else: ?>
            <input type="text" name="nisn" class="form-control" required value="<?= sv($s,'nisn') ?>">
        <?php endif; ?>
    </div>
    <div class="form-group"><label>NIPD</label><input type="text" name="nipd" class="form-control" value="<?= sv($s,'nipd') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>NIK</label><input type="text" name="nik" class="form-control" value="<?= sv($s,'nik') ?>"></div>
    <div class="form-group"><label>Nama Lengkap *</label><input type="text" name="nama" class="form-control" required value="<?= sv($s,'nama') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Jenis Kelamin *</label><select name="jenis_kelamin" class="form-control"><option value="Laki-Laki" <?= ssel($s,'jenis_kelamin','Laki-Laki') ?>>Laki-Laki</option><option value="Perempuan" <?= ssel($s,'jenis_kelamin','Perempuan') ?>>Perempuan</option></select></div>
    <div class="form-group"><label>Agama</label><input type="text" name="agama" class="form-control" value="<?= sv($s,'agama') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="<?= sv($s,'tempat_lahir') ?>"></div>
    <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="<?= sv($s,'tanggal_lahir') ?>"></div>
</div>

<h4 class="form-section-title">Alamat</h4>
<div class="form-group"><label>Alamat (Jalan/Dusun)</label><textarea name="alamat" class="form-control" rows="2"><?= sv($s,'alamat') ?></textarea></div>
<div class="form-row">
    <div class="form-group"><label>RT</label><input type="text" name="rt" class="form-control" value="<?= sv($s,'rt') ?>"></div>
    <div class="form-group"><label>RW</label><input type="text" name="rw" class="form-control" value="<?= sv($s,'rw') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Kelurahan/Desa</label><input type="text" name="kelurahan" class="form-control" value="<?= sv($s,'kelurahan') ?>"></div>
    <div class="form-group"><label>Kecamatan</label><input type="text" name="kecamatan" class="form-control" value="<?= sv($s,'kecamatan') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Kode Pos</label><input type="text" name="kode_pos" class="form-control" value="<?= sv($s,'kode_pos') ?>"></div>
    <div class="form-group"><label>No. HP</label><input type="text" name="no_hp" class="form-control" value="<?= sv($s,'no_hp') ?>"></div>
</div>

<h4 class="form-section-title">Data Tambahan</h4>
<div class="form-row">
    <div class="form-group"><label>Penerima KIP</label>
        <select name="penerima_kip" id="penerima_kip" class="form-control" onchange="toggleKip()">
            <option value="tidak" <?= ssel($s,'penerima_kip','tidak') ?>>Tidak</option>
            <option value="ya" <?= ssel($s,'penerima_kip','ya') ?>>Ya</option>
        </select>
    </div>
    <div class="form-group" id="kip_wrap"><label>Nomor KIP</label><input type="text" name="nomor_kip" class="form-control" value="<?= sv($s,'nomor_kip') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Sekolah Asal</label><input type="text" name="sekolah_asal" class="form-control" value="<?= sv($s,'sekolah_asal') ?>"></div>
    <div class="form-group"><label>Jenis Tinggal</label>
        <select name="jenis_tinggal" class="form-control">
            <option value="">- Pilih -</option>
            <?php foreach(['Bersama orang tua','Wali','Kos','Asrama','Panti Asuhan','Lainnya'] as $o): ?>
            <option value="<?= $o ?>" <?= ssel($s,'jenis_tinggal',$o) ?>><?= $o ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group"><label>Anak ke-</label><input type="number" name="anak_ke" class="form-control" min="1" value="<?= sv($s,'anak_ke') ?>"></div>
    <div class="form-group"><label>Jumlah Saudara Kandung</label><input type="number" name="jml_saudara" class="form-control" min="0" value="<?= sv($s,'jml_saudara') ?>"></div>
</div>
<div class="form-group"><label>Jarak Rumah ke Sekolah (KM)</label><input type="number" step="0.1" name="jarak_sekolah_km" class="form-control" value="<?= sv($s,'jarak_sekolah_km') ?>"></div>

<h4 class="form-section-title">Data Ayah</h4>
<div class="form-row">
    <div class="form-group"><label>Nama Ayah</label><input type="text" name="ayah_nama" class="form-control" value="<?= sv($s,'ayah_nama') ?>"></div>
    <div class="form-group"><label>NIK Ayah</label><input type="text" name="ayah_nik" class="form-control" value="<?= sv($s,'ayah_nik') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Tahun Lahir</label><input type="number" name="ayah_tahun_lahir" class="form-control" min="1900" max="<?= date('Y') ?>" value="<?= sv($s,'ayah_tahun_lahir') ?>"></div>
    <div class="form-group"><label>Jenjang Pendidikan</label>
        <select name="ayah_pendidikan" class="form-control">
            <option value="">- Pilih -</option>
            <?php foreach(['Tidak Sekolah','SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $o): ?>
            <option value="<?= $o ?>" <?= ssel($s,'ayah_pendidikan',$o) ?>><?= $o ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group"><label>Pekerjaan Ayah</label><input type="text" name="ayah_pekerjaan" class="form-control" value="<?= sv($s,'ayah_pekerjaan') ?>"></div>

<h4 class="form-section-title">Data Ibu</h4>
<div class="form-row">
    <div class="form-group"><label>Nama Ibu</label><input type="text" name="ibu_nama" class="form-control" value="<?= sv($s,'ibu_nama') ?>"></div>
    <div class="form-group"><label>NIK Ibu</label><input type="text" name="ibu_nik" class="form-control" value="<?= sv($s,'ibu_nik') ?>"></div>
</div>
<div class="form-row">
    <div class="form-group"><label>Tahun Lahir</label><input type="number" name="ibu_tahun_lahir" class="form-control" min="1900" max="<?= date('Y') ?>" value="<?= sv($s,'ibu_tahun_lahir') ?>"></div>
    <div class="form-group"><label>Jenjang Pendidikan</label>
        <select name="ibu_pendidikan" class="form-control">
            <option value="">- Pilih -</option>
            <?php foreach(['Tidak Sekolah','SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $o): ?>
            <option value="<?= $o ?>" <?= ssel($s,'ibu_pendidikan',$o) ?>><?= $o ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group"><label>Pekerjaan Ibu</label><input type="text" name="ibu_pekerjaan" class="form-control" value="<?= sv($s,'ibu_pekerjaan') ?>"></div>

<script>
function toggleKip(){
    var v=document.getElementById('penerima_kip').value;
    document.getElementById('kip_wrap').style.display = (v==='ya')?'':'none';
}
document.addEventListener('DOMContentLoaded',toggleKip);
</script>
