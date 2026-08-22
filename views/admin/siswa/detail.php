<?php $pageTitle = 'Detail Siswa'; ob_start();
function ds($siswa,$k){ return ($siswa[$k] ?? '') !== '' && ($siswa[$k] ?? null) !== null ? htmlspecialchars($siswa[$k]) : '-'; }
$alamatLengkap = trim(($siswa['alamat']??'').
    (($siswa['rt']??'')?' RT '.$siswa['rt']:'').
    (($siswa['rw']??'')?'/RW '.$siswa['rw']:'').
    (($siswa['kelurahan']??'')?', Kel. '.$siswa['kelurahan']:'').
    (($siswa['kecamatan']??'')?', Kec. '.$siswa['kecamatan']:'').
    (($siswa['kode_pos']??'')?' '.$siswa['kode_pos']:''), ', ');
?>
<div class="card"><div class="card-header"><h3>Biodata Siswa</h3></div><div class="card-body">

<h4 class="form-section-title">Identitas</h4>
<div class="detail-grid">
    <div class="detail-item"><label>Kode Siswa</label><span><?= ds($siswa,'kode_siswa') ?></span></div>
    <div class="detail-item"><label>NISN</label><span><?= ds($siswa,'nisn') ?></span></div>
    <div class="detail-item"><label>NIPD</label><span><?= ds($siswa,'nipd') ?></span></div>
    <div class="detail-item"><label>NIK</label><span><?= ds($siswa,'nik') ?></span></div>
    <div class="detail-item"><label>Nama</label><span><?= ds($siswa,'nama') ?></span></div>
    <div class="detail-item"><label>Jenis Kelamin</label><span><?= ds($siswa,'jenis_kelamin') ?></span></div>
    <div class="detail-item"><label>Tempat, Tgl Lahir</label><span><?= ds($siswa,'tempat_lahir') ?><?= ($siswa['tanggal_lahir']??null)?', '.date('d F Y',strtotime($siswa['tanggal_lahir'])):'' ?></span></div>
    <div class="detail-item"><label>Agama</label><span><?= ds($siswa,'agama') ?></span></div>
</div>

<h4 class="form-section-title">Alamat & Kontak</h4>
<div class="detail-grid">
    <div class="detail-item"><label>Alamat Lengkap</label><span><?= $alamatLengkap ?: '-' ?></span></div>
    <div class="detail-item"><label>No. HP</label><span><?= ds($siswa,'no_hp') ?></span></div>
</div>

<h4 class="form-section-title">Data Tambahan</h4>
<div class="detail-grid">
    <div class="detail-item"><label>Penerima KIP</label><span><?= ($siswa['penerima_kip']??'tidak')==='ya' ? 'Ya'.(($siswa['nomor_kip']??'')?' ('.$siswa['nomor_kip'].')':'') : 'Tidak' ?></span></div>
    <div class="detail-item"><label>Sekolah Asal</label><span><?= ds($siswa,'sekolah_asal') ?></span></div>
    <div class="detail-item"><label>Jenis Tinggal</label><span><?= ds($siswa,'jenis_tinggal') ?></span></div>
    <div class="detail-item"><label>Anak ke-</label><span><?= ds($siswa,'anak_ke') ?></span></div>
    <div class="detail-item"><label>Jumlah Saudara</label><span><?= ds($siswa,'jml_saudara') ?></span></div>
    <div class="detail-item"><label>Jarak ke Sekolah</label><span><?= ($siswa['jarak_sekolah_km']??null)!==null && ($siswa['jarak_sekolah_km']??'')!=='' ? $siswa['jarak_sekolah_km'].' KM' : '-' ?></span></div>
</div>

<h4 class="form-section-title">Data Ayah</h4>
<div class="detail-grid">
    <div class="detail-item"><label>Nama</label><span><?= ds($siswa,'ayah_nama') ?></span></div>
    <div class="detail-item"><label>NIK</label><span><?= ds($siswa,'ayah_nik') ?></span></div>
    <div class="detail-item"><label>Tahun Lahir</label><span><?= ds($siswa,'ayah_tahun_lahir') ?></span></div>
    <div class="detail-item"><label>Pendidikan</label><span><?= ds($siswa,'ayah_pendidikan') ?></span></div>
    <div class="detail-item"><label>Pekerjaan</label><span><?= ds($siswa,'ayah_pekerjaan') ?></span></div>
</div>

<h4 class="form-section-title">Data Ibu</h4>
<div class="detail-grid">
    <div class="detail-item"><label>Nama</label><span><?= ds($siswa,'ibu_nama') ?></span></div>
    <div class="detail-item"><label>NIK</label><span><?= ds($siswa,'ibu_nik') ?></span></div>
    <div class="detail-item"><label>Tahun Lahir</label><span><?= ds($siswa,'ibu_tahun_lahir') ?></span></div>
    <div class="detail-item"><label>Pendidikan</label><span><?= ds($siswa,'ibu_pendidikan') ?></span></div>
    <div class="detail-item"><label>Pekerjaan</label><span><?= ds($siswa,'ibu_pekerjaan') ?></span></div>
</div>

<div class="form-actions">
    <a href="<?= base_url('index.php?page=siswa&action=edit&id='.$siswa['id']) ?>" class="btn btn-success">Edit</a>
    <a href="<?= base_url('index.php?page=siswa') ?>" class="btn btn-secondary">Kembali</a>
</div></div></div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
