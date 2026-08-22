<?php $pageTitle='Ganti Password'; ob_start(); ?>
<div class="card">
    <div class="card-header">
        <h3>Ganti Password</h3>
        <a href="<?= base_url('index.php?page=profil') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <?php if(!empty($error)): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
        <div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-sm);border-left:3px solid var(--info);margin-bottom:18px;font-size:12.5px;color:var(--gray-600);">
            <strong>Info:</strong> Password baru akan langsung berlaku setelah disimpan. Anda perlu login ulang menggunakan password baru pada sesi berikutnya.
        </div>
        <form method="POST" action="<?= base_url('index.php?page=profil&action=updatePassword') ?>" style="max-width:520px;">
            <div class="form-group"><label>Password Lama *</label><input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama" required></div>
            <div class="form-group"><label>Password Baru *</label><input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required></div>
            <div class="form-group"><label>Konfirmasi Password Baru *</label><input type="password" name="konfirmasi" class="form-control" placeholder="Ulangi password baru" required></div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=profil') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Password</button>
            </div>
        </form>
    </div>
</div>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
