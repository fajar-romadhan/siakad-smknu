<?php $pageTitle = 'Detail Guru'; ob_start();
function dg($guru,$k){ return ($guru[$k] ?? '') !== '' && ($guru[$k] ?? null) !== null ? htmlspecialchars($guru[$k]) : '-'; }
?>
<div class="card"><div class="card-header"><h3>Biodata Guru</h3>
    <a href="<?= base_url('index.php?page=kepsek&action=guru') ?>" class="btn btn-secondary">← Kembali</a></div>
<div class="card-body">
    <h4 class="form-section-title">Identitas</h4>
    <div class="detail-grid">
        <div class="detail-item"><label>Kode Guru</label><span><?= dg($guru,'kode_guru') ?></span></div>
        <div class="detail-item"><label>NUPTK</label><span><?= dg($guru,'nuptk') ?></span></div>
        <div class="detail-item"><label>NIK</label><span><?= dg($guru,'nik') ?></span></div>
        <div class="detail-item"><label>NIP</label><span><?= dg($guru,'nip') ?></span></div>
        <div class="detail-item"><label>Nama</label><span><?= dg($guru,'nama') ?></span></div>
        <div class="detail-item"><label>Gelar</label><span><?= dg($guru,'gelar') ?></span></div>
        <div class="detail-item"><label>Jenis Kelamin</label><span><?= dg($guru,'jenis_kelamin') ?></span></div>
        <div class="detail-item"><label>Tempat, Tgl Lahir</label><span><?= dg($guru,'tempat_lahir') ?><?= ($guru['tanggal_lahir']??null)?', '.date('d F Y',strtotime($guru['tanggal_lahir'])):'' ?></span></div>
        <div class="detail-item"><label>Agama</label><span><?= dg($guru,'agama') ?></span></div>
    </div>
    <h4 class="form-section-title">Kepegawaian</h4>
    <div class="detail-grid">
        <div class="detail-item"><label>Status Kepegawaian</label><span><?= dg($guru,'status_kepegawaian') ?></span></div>
        <div class="detail-item"><label>Jenis PTK</label><span><?= dg($guru,'jenis_ptk') ?></span></div>
        <div class="detail-item"><label>Jenjang Pendidikan</label><span><?= dg($guru,'jenjang_pendidikan') ?></span></div>
        <div class="detail-item"><label>Jurusan / Prodi</label><span><?= dg($guru,'jurusan_prodi') ?></span></div>
        <div class="detail-item"><label>TMT Kerja</label><span><?= ($guru['tmt_kerja']??null)?date('d F Y',strtotime($guru['tmt_kerja'])):'-' ?></span></div>
        <div class="detail-item"><label>Tugas Tambahan</label><span><?= dg($guru,'tugas_tambahan') ?></span></div>
    </div>
    <h4 class="form-section-title">Kontak</h4>
    <div class="detail-grid">
        <div class="detail-item"><label>No. HP</label><span><?= dg($guru,'no_hp') ?></span></div>
        <div class="detail-item"><label>Email</label><span><?= dg($guru,'email') ?></span></div>
        <div class="detail-item"><label>Alamat</label><span><?= dg($guru,'alamat') ?></span></div>
    </div>
</div></div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
