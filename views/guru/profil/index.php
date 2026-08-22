<?php $pageTitle='Profil Guru'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Profil Guru</h3>
        <a href="<?= base_url('index.php?page=profil&action=edit') ?>" class="btn btn-primary">✏️ Edit Biodata</a>
    </div>
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:20px;padding:24px;background:var(--primary-soft);border-radius:var(--radius);margin-bottom:24px;">
            <div class="profile-avatar" style="width:96px;height:96px;font-size:40px;"><?= strtoupper(substr($profil['nama'],0,1)) ?></div>
            <div>
                <h3 style="font-size:22px;color:var(--gray-800);font-weight:700;margin-bottom:4px;"><?= htmlspecialchars($profil['nama']) ?></h3>
                <div style="display:flex;gap:8px;align-items:center;margin-top:6px;">
                    <span class="role-badge">Guru</span>
                    <span style="color:var(--gray-500);font-size:13px;">Kode Guru: <?= htmlspecialchars($profil['kode_guru']) ?></span>
                </div>
            </div>
        </div>

        <?php $pf=function($k) use($profil){ return ($profil[$k]??'')!=='' && ($profil[$k]??null)!==null ? htmlspecialchars($profil[$k]) : '-'; }; ?>
        <h4 style="color:var(--primary);font-size:15px;font-weight:600;margin-bottom:12px;">Data Pribadi</h4>
        <div class="detail-grid" style="margin-bottom:24px;">
            <div class="detail-item"><label>Nama Lengkap</label><span><?= $pf('nama') ?></span></div>
            <div class="detail-item"><label>NUPTK</label><span><?= $pf('nuptk') ?></span></div>
            <div class="detail-item"><label>NIK</label><span><?= $pf('nik') ?></span></div>
            <div class="detail-item"><label>NIP</label><span><?= $pf('nip') ?></span></div>
            <div class="detail-item"><label>Gelar</label><span><?= $pf('gelar') ?></span></div>
            <div class="detail-item"><label>Jenis Kelamin</label><span><?= $pf('jenis_kelamin') ?></span></div>
            <div class="detail-item"><label>Agama</label><span><?= $pf('agama') ?></span></div>
            <div class="detail-item"><label>Tempat, Tanggal Lahir</label><span><?= $pf('tempat_lahir') ?><?= ($profil['tanggal_lahir']??null)?', '.date('d F Y',strtotime($profil['tanggal_lahir'])):'' ?></span></div>
            <div class="detail-item"><label>Alamat</label><span><?= $pf('alamat') ?></span></div>
        </div>

        <h4 style="color:var(--primary);font-size:15px;font-weight:600;margin-bottom:12px;">Kontak</h4>
        <div class="detail-grid" style="margin-bottom:24px;">
            <div class="detail-item"><label>Email</label><span><?= $pf('email') ?></span></div>
            <div class="detail-item"><label>No. HP</label><span><?= $pf('no_hp') ?></span></div>
        </div>

        <h4 style="color:var(--primary);font-size:15px;font-weight:600;margin-bottom:12px;">Data Kepegawaian</h4>
        <div class="detail-grid">
            <div class="detail-item"><label>Kode Guru</label><span><?= $pf('kode_guru') ?></span></div>
            <div class="detail-item"><label>Status Kepegawaian</label><span><?= $pf('status_kepegawaian') ?></span></div>
            <div class="detail-item"><label>Jenis PTK</label><span><?= $pf('jenis_ptk') ?></span></div>
            <div class="detail-item"><label>Jenjang Pendidikan</label><span><?= $pf('jenjang_pendidikan') ?></span></div>
            <div class="detail-item"><label>Jurusan / Prodi</label><span><?= $pf('jurusan_prodi') ?></span></div>
            <div class="detail-item"><label>TMT Kerja</label><span><?= ($profil['tmt_kerja']??null)?date('d F Y',strtotime($profil['tmt_kerja'])):'-' ?></span></div>
            <div class="detail-item"><label>Tugas Tambahan</label><span><?= $pf('tugas_tambahan') ?></span></div>
            <div class="detail-item"><label>Tahun Bergabung</label><span><?= $pf('tahun_masuk') ?></span></div>
        </div>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
