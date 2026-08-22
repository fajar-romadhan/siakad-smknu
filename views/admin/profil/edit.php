<?php $pageTitle='Edit Profil'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Edit Profil Admin</h3>
        <a href="<?= base_url('index.php?page=profil') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <?php if(!empty($error)): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
        <div style="display:flex;align-items:center;gap:16px;padding:14px;background:var(--primary-soft);border-radius:var(--radius-sm);margin-bottom:20px;">
            <div class="profile-avatar" style="width:64px;height:64px;font-size:26px;"><?= strtoupper(substr($profil['nama'],0,1)) ?></div>
            <div>
                <h4 style="font-size:16px;color:var(--gray-800);font-weight:600;">Edit informasi profil Anda</h4>
                <p style="font-size:12px;color:var(--gray-500);">Perubahan akan tersimpan ke akun <?= htmlspecialchars($profil['username']) ?></p>
            </div>
        </div>
        <form method="POST" action="<?= base_url('index.php?page=profil&action=update') ?>" style="max-width:640px;">
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($profil['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($profil['username']) ?>" disabled>
                <small style="color:var(--gray-400);font-size:11px;">Username tidak dapat diubah</small>
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
