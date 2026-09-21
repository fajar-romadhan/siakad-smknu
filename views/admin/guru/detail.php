<?php $pageTitle = 'Detail Guru'; ob_start();
function d($guru,$k){ return ($guru[$k] ?? '') !== '' && ($guru[$k] ?? null) !== null ? htmlspecialchars($guru[$k]) : '-'; }
?>
<div class="card">
    <div class="card-header"><h3>Biodata Guru</h3></div>
    <div class="card-body">
        <?php 
        $fotoUrl = (!empty($guru['foto']) && file_exists(BASE_PATH . '/public/uploads/guru/' . $guru['foto'])) 
                   ? base_url('uploads/guru/' . $guru['foto']) 
                   : null;
        ?>
        <div style="display:flex;align-items:center;gap:20px;padding:20px;background:var(--primary-soft);border-radius:var(--radius);margin-bottom:24px;">
            <?php if ($fotoUrl): ?>
                <img src="<?= $fotoUrl ?>" alt="Foto Guru" style="width:84px;height:84px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <?php else: ?>
                <div class="profile-avatar" style="width:84px;height:84px;font-size:36px;"><?= strtoupper(substr($guru['nama'],0,1)) ?></div>
            <?php endif; ?>
            <div>
                <h3 style="font-size:22px;color:var(--gray-800);font-weight:700;margin-bottom:4px;"><?= htmlspecialchars($guru['nama']) ?></h3>
                <div style="display:flex;gap:8px;align-items:center;margin-top:6px;">
                    <span class="role-badge">Guru</span>
                    <span style="color:var(--gray-500);font-size:13px;">Kode Guru: <?= htmlspecialchars($guru['kode_guru']) ?></span>
                </div>
            </div>
        </div>

        <h4 class="form-section-title">Identitas</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Kode Guru</label><span><?= d($guru,'kode_guru') ?></span></div>
            <div class="detail-item"><label>NUPTK</label><span><?= d($guru,'nuptk') ?></span></div>
            <div class="detail-item"><label>NIK</label><span><?= d($guru,'nik') ?></span></div>
            <div class="detail-item"><label>NIP</label><span><?= d($guru,'nip') ?></span></div>
            <div class="detail-item"><label>Nama</label><span><?= d($guru,'nama') ?></span></div>
            <div class="detail-item"><label>Gelar</label><span><?= d($guru,'gelar') ?></span></div>
            <div class="detail-item"><label>Jenis Kelamin</label><span><?= d($guru,'jenis_kelamin') ?></span></div>
            <div class="detail-item"><label>Tempat, Tgl Lahir</label><span><?= d($guru,'tempat_lahir') ?><?= ($guru['tanggal_lahir'] ?? null) ? ', '.date('d F Y', strtotime($guru['tanggal_lahir'])) : '' ?></span></div>
            <div class="detail-item"><label>Agama</label><span><?= d($guru,'agama') ?></span></div>
        </div>

        <h4 class="form-section-title">Kepegawaian</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Status Kepegawaian</label><span><?= d($guru,'status_kepegawaian') ?></span></div>
            <div class="detail-item"><label>Jenis PTK</label><span><?= d($guru,'jenis_ptk') ?></span></div>
            <div class="detail-item"><label>Jenjang Pendidikan</label><span><?= d($guru,'jenjang_pendidikan') ?></span></div>
            <div class="detail-item"><label>Jurusan / Prodi</label><span><?= d($guru,'jurusan_prodi') ?></span></div>
            <div class="detail-item"><label>TMT Kerja</label><span><?= ($guru['tmt_kerja'] ?? null) ? date('d F Y', strtotime($guru['tmt_kerja'])) : '-' ?></span></div>
            <div class="detail-item"><label>Tugas Tambahan</label><span><?= d($guru,'tugas_tambahan') ?></span></div>
            <div class="detail-item"><label>Tahun Masuk</label><span><?= d($guru,'tahun_masuk') ?></span></div>
        </div>

        <h4 class="form-section-title">Kontak</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>No. HP</label><span><?= d($guru,'no_hp') ?></span></div>
            <div class="detail-item"><label>Email</label><span><?= d($guru,'email') ?></span></div>
            <div class="detail-item"><label>Alamat</label><span><?= d($guru,'alamat') ?></span></div>
        </div>

        <div class="form-actions">
            <a href="<?= base_url('index.php?page=guru&action=edit&id='.$guru['id']) ?>" class="btn btn-success">Edit</a>
            <a href="<?= base_url('index.php?page=guru') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
