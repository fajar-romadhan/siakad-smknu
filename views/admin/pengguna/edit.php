<?php $pageTitle='Edit Pengguna'; ob_start(); $roleLabel = str_replace('_',' ', ucwords($user['role'],'_')); ?>
<div class="card">
    <div class="card-header">
        <h3>Edit Pengguna</h3>
        <a href="<?= base_url('index.php?page=pengguna') ?>" class="btn btn-secondary">← Kembali</a>
    </div>
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:16px;padding:14px;background:var(--primary-soft);border-radius:var(--radius-sm);margin-bottom:20px;">
            <div class="profile-avatar" style="width:56px;height:56px;font-size:22px;"><?= strtoupper(substr($user['nama'],0,1)) ?></div>
            <div>
                <h4 style="font-size:16px;color:var(--gray-800);font-weight:600;"><?= htmlspecialchars($user['nama']) ?></h4>
                <p style="font-size:12px;color:var(--gray-500);"><?= $roleLabel ?><?= $user['role']!=='admin' ? ' • Akun dibuat otomatis dari Data '.($user['role']==='guru'?'Guru':'Siswa') : '' ?></p>
            </div>
        </div>
        <form method="POST" action="<?= base_url('index.php?page=pengguna&action=update&id='.$user['id']) ?>" onsubmit="return confirmSaveUser(event, this)">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                <small style="color:var(--gray-400);font-size:11px;">Username tidak dapat diubah karena dibuat otomatis oleh sistem</small>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" id="pwdBaru" class="form-control" placeholder="Kosongkan jika tidak diubah">
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" id="pwdKonfirmasi" class="form-control" placeholder="Ulangi password baru">
                </div>
            </div>
            <div class="form-group">
                <label>Status Akun</label>
                <div style="display:flex;gap:16px;padding:10px 0;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="radio" name="status" value="aktif" <?= $user['status']==='aktif'?'checked':'' ?>> <span class="badge aktif">Aktif</span></label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="radio" name="status" value="nonaktif" <?= $user['status']==='nonaktif'?'checked':'' ?>> <span class="badge nonaktif">Nonaktif</span></label>
                </div>
            </div>
            <div style="padding:12px;background:var(--gray-50);border-radius:var(--radius-sm);border-left:3px solid var(--info);margin-bottom:14px;font-size:12.5px;color:var(--gray-600);">
                <strong>Info:</strong> Data akun ini berasal dari menu Data <?= $user['role']==='guru'?'Guru':($user['role']==='siswa'?'Siswa':'Admin') ?>. Untuk mengubah nama/email/nomor HP atau data pribadi lainnya, lakukan perubahan melalui menu Data <?= $user['role']==='guru'?'Guru':($user['role']==='siswa'?'Siswa':'Admin') ?>. Perubahan akan otomatis tersinkronisasi ke akun pengguna.
            </div>
            <div class="form-actions">
                <a href="<?= base_url('index.php?page=pengguna') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
function confirmSaveUser(e, form){
    var pwd = document.getElementById('pwdBaru').value;
    var konf = document.getElementById('pwdKonfirmasi').value;
    if (pwd && pwd !== konf) {
        alert('Password baru dan konfirmasi tidak cocok!');
        e.preventDefault();
        return false;
    }
    return confirm('Anda yakin ingin menyimpan perubahan pada akun ini? Perubahan yang disimpan akan langsung diterapkan dan mempengaruhi proses login pengguna.');
}
</script>
<?php $content=ob_get_clean(); require VIEW_PATH.'/layouts/admin.php'; ?>
