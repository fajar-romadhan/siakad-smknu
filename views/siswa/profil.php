<?php $pageTitle='Profil'; ob_start(); ?>
<div class="greeting">
    <h3>Profil Saya</h3>
    <p>Data biodata siswa</p>
</div>
<div class="mobile-card profile-card">
    <div class="profile-avatar-lg"><?= strtoupper(substr($profil['nama'],0,1)) ?></div>
    <h3 style="text-align:center;color:var(--gray-800);font-size:17px;margin-bottom:4px;"><?= htmlspecialchars($profil['nama']) ?></h3>
    <p style="text-align:center;color:var(--gray-500);font-size:12px;margin-bottom:14px;">NISN: <?= htmlspecialchars($profil['nisn']) ?></p>
    <span class="role-badge" style="display:inline-block;margin-bottom:16px;">Siswa</span>
    <div class="detail-list">
        <div class="detail-item"><label>Jenis Kelamin</label><span><?= htmlspecialchars($profil['jenis_kelamin']) ?></span></div>
        <div class="detail-item"><label>Tempat, Tgl Lahir</label><span><?= htmlspecialchars($profil['tempat_lahir']??'-') ?>, <?= $profil['tanggal_lahir']?date('d/m/Y',strtotime($profil['tanggal_lahir'])):'-' ?></span></div>
        <div class="detail-item"><label>Agama</label><span><?= htmlspecialchars($profil['agama']?:'-') ?></span></div>
        <div class="detail-item"><label>Alamat</label><span><?= htmlspecialchars($profil['alamat']?:'-') ?></span></div>
        <div class="detail-item"><label>No. HP</label><span><?= htmlspecialchars($profil['no_hp']?:'-') ?></span></div>
        <div class="detail-item"><label>Wali/Orang Tua</label><span><?= htmlspecialchars($profil['wali_siswa']?:'-') ?></span></div>
        <div class="detail-item"><label>Tahun Masuk</label><span><?= htmlspecialchars($profil['tahun_masuk']?:'-') ?></span></div>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
