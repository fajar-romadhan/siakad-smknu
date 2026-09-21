<?php $pageTitle='Profil Saya'; ob_start(); 
function ds($s, $k) { return ($s[$k] ?? '') !== '' && ($s[$k] ?? null) !== null ? htmlspecialchars($s[$k]) : '-'; }
$alamatLengkap = trim(($profil['alamat']??'').
    (($profil['rt']??'')?' RT '.$profil['rt']:'').
    (($profil['rw']??'')?'/RW '.$profil['rw']:'').
    (($profil['kelurahan']??'')?', Kel. '.$profil['kelurahan']:'').
    (($profil['kecamatan']??'')?', Kec. '.$profil['kecamatan']:'').
    (($profil['kode_pos']??'')?' '.$profil['kode_pos']:''), ', ');
?>

<div class="greeting" style="margin-bottom:16px;">
    <h3>Profil & Biodata Lengkap Saya</h3>
    <p>Informasi data pribadi dan akademis siswa</p>
</div>

<!-- HEADER PROFILE AVATAR CARD -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
        <div class="profile-avatar" style="width:64px; height:64px; font-size:28px; flex-shrink:0;">
            <?= strtoupper(substr($profil['nama'] ?? 'S', 0, 1)) ?>
        </div>
        <div style="flex:1; min-width:200px;">
            <h3 style="font-size:18px; font-weight:700; color:var(--gray-800); margin-bottom:4px;">
                <?= htmlspecialchars($profil['nama'] ?? '-') ?>
            </h3>
            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <span class="badge" style="background:#00923F; color:#fff; font-size:11px; font-weight:600; padding:3px 10px; border-radius:12px;">Siswa</span>
                <span style="color:var(--primary); font-size:13px; font-weight:600;">Kelas: <?= ds($profil, 'nama_kelas') ?></span>
                <span style="color:var(--gray-500); font-size:12px;">NISN: <?= ds($profil, 'nisn') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 1: IDENTITAS SISWA -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>Identitas Siswa</h3></div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><label>Kode Siswa</label><span><?= ds($profil,'kode_siswa') ?></span></div>
            <div class="detail-item"><label>NISN</label><span><?= ds($profil,'nisn') ?></span></div>
            <div class="detail-item"><label>NIPD</label><span><?= ds($profil,'nipd') ?></span></div>
            <div class="detail-item"><label>NIK</label><span><?= ds($profil,'nik') ?></span></div>
            <div class="detail-item"><label>Nama Lengkap</label><span><strong><?= ds($profil,'nama') ?></strong></span></div>
            <div class="detail-item"><label>Kelas</label><span><?= ds($profil,'nama_kelas') ?></span></div>
            <div class="detail-item"><label>Jenis Kelamin</label><span><?= ds($profil,'jenis_kelamin') ?></span></div>
            <div class="detail-item"><label>Tempat, Tgl Lahir</label><span><?= ds($profil,'tempat_lahir') ?><?= ($profil['tanggal_lahir']??null)?', '.date('d F Y',strtotime($profil['tanggal_lahir'])):'' ?></span></div>
            <div class="detail-item"><label>Agama</label><span><?= ds($profil,'agama') ?></span></div>
        </div>
    </div>
</div>

<!-- SECTION 2: ALAMAT & KONTAK -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>Alamat & Kontak</h3></div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><label>Alamat Lengkap</label><span><?= $alamatLengkap ?: '-' ?></span></div>
            <div class="detail-item"><label>No. HP / Whatsapp</label><span><?= ds($profil,'no_hp') ?></span></div>
        </div>
    </div>
</div>

<!-- SECTION 3: DATA TAMBAHAN -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>Data Tambahan</h3></div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><label>Penerima KIP</label><span><?= ($profil['penerima_kip']??'tidak')==='ya' ? 'Ya'.(($profil['nomor_kip']??'')?' ('.$profil['nomor_kip'].')':'') : 'Tidak' ?></span></div>
            <div class="detail-item"><label>Sekolah Asal</label><span><?= ds($profil,'sekolah_asal') ?></span></div>
            <div class="detail-item"><label>Jenis Tinggal</label><span><?= ds($profil,'jenis_tinggal') ?></span></div>
            <div class="detail-item"><label>Anak ke-</label><span><?= ds($profil,'anak_ke') ?></span></div>
            <div class="detail-item"><label>Jumlah Saudara</label><span><?= ds($profil,'jml_saudara') ?></span></div>
            <div class="detail-item"><label>Jarak ke Sekolah</label><span><?= ($profil['jarak_sekolah_km']??'')!=='' && ($profil['jarak_sekolah_km']??null)!==null ? $profil['jarak_sekolah_km'].' KM' : '-' ?></span></div>
            <div class="detail-item"><label>Tahun Masuk</label><span><?= ds($profil,'tahun_masuk') ?></span></div>
        </div>
    </div>
</div>

<!-- SECTION 4: DATA AYAH -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>Data Ayah Kandung</h3></div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><label>Nama Ayah</label><span><?= ds($profil,'ayah_nama') ?></span></div>
            <div class="detail-item"><label>NIK Ayah</label><span><?= ds($profil,'ayah_nik') ?></span></div>
            <div class="detail-item"><label>Tahun Lahir</label><span><?= ds($profil,'ayah_tahun_lahir') ?></span></div>
            <div class="detail-item"><label>Pendidikan</label><span><?= ds($profil,'ayah_pendidikan') ?></span></div>
            <div class="detail-item"><label>Pekerjaan</label><span><?= ds($profil,'ayah_pekerjaan') ?></span></div>
        </div>
    </div>
</div>

<!-- SECTION 5: DATA IBU -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>Data Ibu Kandung</h3></div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><label>Nama Ibu</label><span><?= ds($profil,'ibu_nama') ?></span></div>
            <div class="detail-item"><label>NIK Ibu</label><span><?= ds($profil,'ibu_nik') ?></span></div>
            <div class="detail-item"><label>Tahun Lahir</label><span><?= ds($profil,'ibu_tahun_lahir') ?></span></div>
            <div class="detail-item"><label>Pendidikan</label><span><?= ds($profil,'ibu_pendidikan') ?></span></div>
            <div class="detail-item"><label>Pekerjaan</label><span><?= ds($profil,'ibu_pekerjaan') ?></span></div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/mobile.php'; ?>
