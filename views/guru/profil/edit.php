<?php $pageTitle='Edit Profil'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Edit Biodata Guru</h3>
        <a href="<?= base_url('index.php?page=profil') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <?php 
        $fotoUrl = (!empty($profil['foto']) && file_exists(BASE_PATH . '/public/uploads/guru/' . $profil['foto'])) 
                   ? base_url('uploads/guru/' . $profil['foto']) 
                   : null;
        ?>
        <div style="display:flex;align-items:center;gap:16px;padding:14px;background:var(--primary-soft);border-radius:var(--radius-sm);margin-bottom:20px;">
            <?php if ($fotoUrl): ?>
                <img src="<?= $fotoUrl ?>" alt="Foto Profil" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
            <?php else: ?>
                <div class="profile-avatar" style="width:64px;height:64px;font-size:26px;"><?= strtoupper(substr($profil['nama'],0,1)) ?></div>
            <?php endif; ?>
            <div>
                <h4 style="font-size:16px;color:var(--gray-800);font-weight:600;"><?= htmlspecialchars($profil['nama']) ?></h4>
                <p style="font-size:12px;color:var(--gray-500);">Edit biodata dan foto profil guru</p>
            </div>
        </div>
        <form method="POST" action="<?= base_url('index.php?page=profil&action=update') ?>" enctype="multipart/form-data">
            <h4 style="color:var(--primary);font-size:14px;font-weight:600;margin-bottom:12px;">Foto Profil Guru</h4>
            <div class="form-group" style="margin-bottom:20px;">
                <label>Unggah / Ubah Foto Profil <small>(JPG, PNG, WEBP, Maks. 3MB)</small></label>
                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>

            <h4 style="color:var(--primary);font-size:14px;font-weight:600;margin-bottom:12px;">Data Pribadi</h4>
            <div class="form-row">
                <div class="form-group"><label>Nama Lengkap *</label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($profil['nama']) ?>" required></div>
            </div>
            <div class="form-group"><label>Alamat</label><textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($profil['alamat']??'') ?></textarea></div>
            <h4 style="color:var(--primary);font-size:14px;font-weight:600;margin:20px 0 12px;">Kontak</h4>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($profil['email']??'') ?>" placeholder="email@example.com"></div>
                <div class="form-group"><label>No. HP</label><input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($profil['no_hp']??'') ?>" placeholder="08xxxxxxxxxx"></div>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=profil') ?>" class="btn btn-secondary">Batal</a>
                <button type="reset" class="btn btn-info">Reset</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
